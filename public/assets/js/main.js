document.addEventListener('DOMContentLoaded', () => {

    // Marcar link activo en el nav según la URL
    const navLinks = document.querySelectorAll('.nav a');
    const rutaActual = window.location.pathname;
    navLinks.forEach(link => {
        link.classList.remove('activo');
        if (link.getAttribute('href') === rutaActual) {
            link.classList.add('activo');
        }
    });

    // ── MINI-CARRITO (header) ─────────────────────────────

    const btnToggle   = document.getElementById('btn-carrito-toggle');
    const miniCarrito = document.getElementById('mini-carrito');
    const wrapper     = document.getElementById('carrito-wrapper');

    if (btnToggle && miniCarrito) {
        // Abrir / cerrar al hacer clic en el botón
        btnToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const abierto = !miniCarrito.hidden;
            miniCarrito.hidden = abierto;
            btnToggle.setAttribute('aria-expanded', String(!abierto));
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                miniCarrito.hidden = true;
                btnToggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Botones eliminar dentro del mini-carrito
        miniCarrito.addEventListener('click', async (e) => {
            const btn = e.target.closest('.mini-item-eliminar');
            if (!btn) return;

            const id   = btn.dataset.id;
            const fila = miniCarrito.querySelector(`.mini-item[data-id="${id}"]`);
            fila.style.opacity = '0.4';

            const data = await postJSON('/carrito/eliminar', { id });
            if (data.ok) {
                await refrescarMiniCarrito();
                actualizarContador(data.total_items);
            } else {
                fila.style.opacity = '1';
            }
        });
    }

    async function refrescarMiniCarrito() {
        try {
            const res  = await fetch('/carrito/mini');
            const data = await res.json();
            if (!data.ok || !miniCarrito) return;

            const items    = data.items;
            const total    = data.total;
            const miniItems = miniCarrito.querySelector('#mini-items');
            const miniTotal = miniCarrito.querySelector('#mini-total');

            if (items.length === 0) {
                miniCarrito.innerHTML = `
                    <div class="mini-vacio">
                        <span>🎣</span>
                        <p>Tu carrito está vacío</p>
                        <a href="/productos" class="mini-ver-btn">Ver productos</a>
                    </div>`;
                return;
            }

            if (miniItems) {
                miniItems.innerHTML = items.map(item => `
                    <div class="mini-item" data-id="${item.id}">
                        <div class="mini-item-imagen">
                            ${item.imagen
                                ? `<img src="${item.imagen}" alt="${item.nombre}">`
                                : '<span>🎣</span>'}
                        </div>
                        <div class="mini-item-info">
                            <p class="mini-item-nombre">${item.nombre}</p>
                            <p class="mini-item-detalle">${item.cantidad} × $${parseInt(item.precio).toLocaleString('es-AR')}</p>
                        </div>
                        <p class="mini-item-subtotal">$${parseInt(item.subtotal).toLocaleString('es-AR')}</p>
                        <button class="mini-item-eliminar" data-id="${item.id}" title="Quitar">✕</button>
                    </div>`).join('');
                twemoji.parse(miniItems);
            }

            if (miniTotal) {
                miniTotal.textContent = '$' + parseInt(total).toLocaleString('es-AR');
            }
        } catch { /* silencioso */ }
    }

    // ── CARRITO — botones "Agregar al carrito" ────────────

    document.querySelectorAll('.btn-agregar').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const textoOriginal = btn.innerHTML;

            btn.disabled = true;
            btn.textContent = 'Agregando...';

            try {
                const data = await postJSON('/carrito/agregar', { id, cantidad: 1 });

                if (data.ok) {
                    actualizarContador(data.total_items);
                    await refrescarMiniCarrito();
                    mostrarToast('¡Agregado al carrito!');
                    btn.textContent = '✓ Agregado';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = textoOriginal;
                        twemoji.parse(btn);
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = textoOriginal;
                    mostrarToast(data.mensaje, 'error');
                }
            } catch {
                btn.disabled = false;
                btn.innerHTML = textoOriginal;
            }
        });
    });

    // ── PÁGINA DE DETALLE ────────────────────────────────

    const inputCantidad = document.getElementById('detalle-cantidad');
    if (inputCantidad) {
        const max = parseInt(inputCantidad.max);

        document.getElementById('btn-restar').addEventListener('click', () => {
            const val = parseInt(inputCantidad.value);
            if (val > 1) inputCantidad.value = val - 1;
        });

        document.getElementById('btn-sumar').addEventListener('click', () => {
            const val = parseInt(inputCantidad.value);
            if (val < max) inputCantidad.value = val + 1;
        });

        const btnDetalle = document.querySelector('.btn-agregar-detalle');
        if (btnDetalle) {
            btnDetalle.addEventListener('click', async () => {
                const id        = btnDetalle.dataset.id;
                const cantidad  = parseInt(inputCantidad.value);
                const textoOrig = btnDetalle.innerHTML;

                btnDetalle.disabled = true;
                btnDetalle.textContent = 'Agregando...';

                try {
                    const data = await postJSON('/carrito/agregar', { id, cantidad });

                    if (data.ok) {
                        actualizarContador(data.total_items);
                        await refrescarMiniCarrito();
                        mostrarToast('¡Agregado al carrito!');
                        btnDetalle.textContent = '✓ Agregado';
                        setTimeout(() => {
                            btnDetalle.disabled = false;
                            btnDetalle.innerHTML = textoOrig;
                            twemoji.parse(btnDetalle);
                        }, 2000);
                    } else {
                        mostrarToast(data.mensaje, 'error');
                        btnDetalle.disabled = false;
                        btnDetalle.innerHTML = textoOrig;
                    }
                } catch {
                    btnDetalle.disabled = false;
                    btnDetalle.innerHTML = textoOrig;
                }
            });
        }
    }

    // ── PÁGINA DEL CARRITO ───────────────────────────────

    document.querySelectorAll('.btn-cantidad').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id      = btn.dataset.id;
            const accion  = btn.dataset.accion;
            const fila    = document.querySelector(`.carrito-item[data-id="${id}"]`);
            const spanNum = fila.querySelector('.cantidad-numero');
            let cantidad  = parseInt(spanNum.textContent);

            cantidad = accion === 'sumar' ? cantidad + 1 : cantidad - 1;

            const data = await postJSON('/carrito/actualizar', { id, cantidad });

            if (data.ok) {
                if (cantidad <= 0) {
                    fila.remove();
                } else {
                    spanNum.textContent = cantidad;
                    actualizarSubtotalFila(fila, cantidad);
                }
                actualizarResumen(data.total_items, data.total_precio);
                actualizarContador(data.total_items);
                if (data.total_items === 0) location.reload();
            }
        });
    });

    document.querySelectorAll('.btn-eliminar-item').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id   = btn.dataset.id;
            const fila = document.querySelector(`.carrito-item[data-id="${id}"]`);

            fila.style.opacity = '0.4';
            const data = await postJSON('/carrito/eliminar', { id });

            if (data.ok) {
                fila.remove();
                actualizarResumen(data.total_items, data.total_precio);
                actualizarContador(data.total_items);
                if (data.total_items === 0) location.reload();
            } else {
                fila.style.opacity = '1';
            }
        });
    });

    const btnVaciar = document.getElementById('btn-vaciar');
    if (btnVaciar) {
        btnVaciar.addEventListener('click', async () => {
            if (!confirm('¿Vaciar el carrito?')) return;
            const data = await postJSON('/carrito/vaciar', {});
            if (data.ok) location.reload();
        });
    }

    // ── HELPERS ──────────────────────────────────────────

    async function postJSON(url, cuerpo) {
        const res = await fetch(url, {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    new URLSearchParams(cuerpo).toString(),
        });
        return res.json();
    }

    function actualizarContador(cantidad) {
        const el = document.querySelector('.carrito-cantidad');
        if (el) {
            el.textContent = cantidad;
            el.classList.remove('bounce');
            void el.offsetWidth;
            el.classList.add('bounce');
        }
    }

    function actualizarSubtotalFila(fila, cantidad) {
        const precioEl   = fila.querySelector('.carrito-item-precio');
        const precio     = parseInt(precioEl.textContent.replace(/[^0-9]/g, ''));
        const subtotalEl = fila.querySelector('.carrito-item-subtotal');
        subtotalEl.textContent = '$' + (precio * cantidad).toLocaleString('es-AR');
    }

    function actualizarResumen(totalItems, totalPrecio) {
        const elCantidad = document.getElementById('resumen-cantidad');
        const elTotal    = document.getElementById('resumen-total');
        if (elCantidad) elCantidad.textContent = totalItems;
        if (elTotal)    elTotal.textContent = '$' + parseInt(totalPrecio).toLocaleString('es-AR');
    }

    function mostrarToast(mensaje, tipo = 'ok') {
        const toast = document.createElement('div');
        toast.className = 'toast-carrito' + (tipo === 'error' ? ' toast-error' : '');
        toast.textContent = mensaje;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('visible'), 10);
        setTimeout(() => {
            toast.classList.remove('visible');
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }
});

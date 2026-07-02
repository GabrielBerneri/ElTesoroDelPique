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

    // ── CARRITO ──────────────────────────────────────────

    // Botones "Agregar al carrito" (página de productos)
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
                const id       = btnDetalle.dataset.id;
                const cantidad = parseInt(inputCantidad.value);
                const textoOrig = btnDetalle.innerHTML;

                btnDetalle.disabled = true;
                btnDetalle.textContent = 'Agregando...';

                try {
                    const data = await postJSON('/carrito/agregar', { id, cantidad });

                    if (data.ok) {
                        actualizarContador(data.total_items);
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

    // Botones +/- de cantidad
    document.querySelectorAll('.btn-cantidad').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id       = btn.dataset.id;
            const accion   = btn.dataset.accion;
            const fila     = document.querySelector(`.carrito-item[data-id="${id}"]`);
            const spanNum  = fila.querySelector('.cantidad-numero');
            let cantidad   = parseInt(spanNum.textContent);

            cantidad = accion === 'sumar' ? cantidad + 1 : cantidad - 1;

            const data = await postJSON('/carrito/actualizar', { id, cantidad });

            if (data.ok) {
                if (cantidad <= 0) {
                    fila.remove();
                } else {
                    spanNum.textContent = cantidad;
                    actualizarSubtotalFila(fila, id, cantidad);
                }
                actualizarResumen(data.total_items, data.total_precio);
                actualizarContador(data.total_items);
                if (data.total_items === 0) location.reload();
            }
        });
    });

    // Botón eliminar item
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

    // Botón vaciar carrito
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
        const params = new URLSearchParams(cuerpo).toString();
        const res = await fetch(url, {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    params,
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

    function actualizarSubtotalFila(fila, id, cantidad) {
        // precio por unidad está en el DOM como texto
        const precioEl = fila.querySelector('.carrito-item-precio');
        const precio = parseInt(
            precioEl.textContent.replace(/[^0-9]/g, '')
        );
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

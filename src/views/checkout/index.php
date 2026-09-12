<!-- PASOS -->
<div class="checkout-pasos">
    <div class="paso completado">
        <span class="paso-num">✓</span>
        <span class="paso-label">Carrito</span>
    </div>
    <div class="paso-linea completada"></div>
    <div class="paso activo">
        <span class="paso-num">2</span>
        <span class="paso-label">Tus datos</span>
    </div>
    <div class="paso-linea"></div>
    <div class="paso">
        <span class="paso-num">3</span>
        <span class="paso-label">Pago</span>
    </div>
</div>

<?php if ($error === 'datos'): ?>
<div class="contenedor checkout-alerta">
    ⚠️ Completá tu nombre y email para continuar.
</div>
<?php elseif ($error === 'mp'): ?>
<div class="contenedor checkout-alerta checkout-alerta-error">
    ❌ Hubo un problema al conectar con MercadoPago. Intentá de nuevo.
</div>
<?php endif; ?>

<div class="checkout-layout contenedor">

    <!-- FORMULARIO -->
    <div class="checkout-form-wrap">
        <h2 class="checkout-seccion-titulo">Tus datos</h2>

        <form action="/checkout/procesar" method="POST" class="checkout-form">

            <div class="campo-grupo">
                <div class="campo">
                    <label for="nombre">Nombre completo *</label>
                    <input type="text" id="nombre" name="nombre"
                           placeholder="Juan Pérez" required
                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                </div>
                <div class="campo">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email"
                           placeholder="juan@email.com" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
            </div>

            <div class="campo">
                <label for="telefono">Teléfono / WhatsApp</label>
                <input type="tel" id="telefono" name="telefono"
                       placeholder="+54 11 1234-5678"
                       value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
            </div>

            <h2 class="checkout-seccion-titulo" style="margin-top:28px">Dirección de envío</h2>

            <div class="campo">
                <label for="direccion">Calle y número</label>
                <input type="text" id="direccion" name="direccion"
                       placeholder="Av. Corrientes 1234"
                       value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
            </div>

            <div class="campo-grupo">
                <div class="campo">
                    <label for="provincia">Provincia</label>
                    <select id="provincia" name="provincia">
                        <option value="">Seleccioná...</option>
                        <?php
                        $provincias = ['Buenos Aires','CABA','Catamarca','Chaco','Chubut',
                            'Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa',
                            'La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta',
                            'San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero',
                            'Tierra del Fuego','Tucumán'];
                        $seleccionada = $_POST['provincia'] ?? '';
                        foreach ($provincias as $prov):
                        ?>
                        <option value="<?= $prov ?>" <?= $seleccionada === $prov ? 'selected' : '' ?>>
                            <?= $prov ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="campo">
                    <label for="localidad">Localidad</label>
                    <input type="text" id="localidad" name="localidad"
                           placeholder="Tigre"
                           value="<?= htmlspecialchars($_POST['localidad'] ?? '') ?>">
                </div>
            </div>

            <div class="campo">
                <label for="codigo_postal">Código postal</label>
                <input type="text" id="codigo_postal" name="codigo_postal"
                       placeholder="1648"
                       value="<?= htmlspecialchars($_POST['codigo_postal'] ?? '') ?>">
            </div>

            <h2 class="checkout-seccion-titulo" style="margin-top:28px">Método de pago</h2>

            <div class="metodos-pago">
                <label class="metodo-opcion">
                    <input type="radio" name="metodo_pago" value="mercadopago" checked>
                    <span class="metodo-contenido">
                        <span class="metodo-icono">💳</span>
                        <span class="metodo-texto">
                            <strong>MercadoPago</strong>
                            <small>Tarjeta de crédito, débito o dinero en cuenta</small>
                            <span class="metodo-precio-tag">Total: <strong>$<?= number_format($total, 0, ',', '.') ?></strong></span>
                        </span>
                    </span>
                </label>

                <label class="metodo-opcion">
                    <input type="radio" name="metodo_pago" value="transferencia">
                    <span class="metodo-contenido">
                        <span class="metodo-icono">🏦</span>
                        <span class="metodo-texto">
                            <strong>Transferencia bancaria</strong>
                            <small>Te mostramos los datos y enviás el comprobante por WhatsApp</small>
                            <span class="metodo-precio-tag metodo-precio-ahorro">Total: <strong>$<?= number_format($totalEfectivo, 0, ',', '.') ?></strong><?= $totalEfectivo < $total ? ' — <span class="metodo-badge-off">10% OFF</span>' : '' ?></span>
                        </span>
                    </span>
                </label>

                <label class="metodo-opcion">
                    <input type="radio" name="metodo_pago" value="efectivo">
                    <span class="metodo-contenido">
                        <span class="metodo-icono">💵</span>
                        <span class="metodo-texto">
                            <strong>Efectivo</strong>
                            <small>Coordinás el pago por WhatsApp</small>
                            <span class="metodo-precio-tag metodo-precio-ahorro">Total: <strong>$<?= number_format($totalEfectivo, 0, ',', '.') ?></strong><?= $totalEfectivo < $total ? ' — <span class="metodo-badge-off">10% OFF</span>' : '' ?></span>
                        </span>
                    </span>
                </label>
            </div>

            <button type="submit" class="checkout-btn-pagar" id="btn-checkout">
                Continuar
            </button>

            <p class="checkout-seguridad">
                🔒 El pago con MercadoPago se procesa de forma segura. No almacenamos datos de tarjetas.
            </p>

        </form>
    </div>

    <!-- RESUMEN DEL PEDIDO -->
    <aside class="checkout-resumen">
        <h2 class="checkout-seccion-titulo">Tu pedido</h2>

        <div class="checkout-items">
            <?php foreach ($items as $item): ?>
            <?php
                $subtotalMP = $item['precio'] * $item['cantidad'];
                $subtotalEf = $item['precio'] * 0.90 * $item['cantidad'];
            ?>
            <div class="checkout-item">
                <div class="checkout-item-imagen">
                    <?php if ($item['imagen']): ?>
                        <img src="<?= htmlspecialchars($item['imagen']) ?>"
                             alt="<?= htmlspecialchars($item['nombre']) ?>">
                    <?php else: ?>
                        <span>🎣</span>
                    <?php endif; ?>
                </div>
                <div class="checkout-item-info">
                    <p class="checkout-item-nombre"><?= htmlspecialchars($item['nombre']) ?></p>
                    <p class="checkout-item-cant">Cant: <?= $item['cantidad'] ?></p>
                </div>
                <p class="checkout-item-precio js-item-precio"
                   data-precio-mp="<?= $subtotalMP ?>"
                   data-precio-ef="<?= $subtotalEf ?>">
                    $<?= number_format($subtotalMP, 0, ',', '.') ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="checkout-totales">
            <div class="checkout-linea">
                <span>Subtotal</span>
                <span id="js-subtotal">$<?= number_format($total, 0, ',', '.') ?></span>
            </div>
            <div class="checkout-linea checkout-linea-envio">
                <span>Envío</span>
                <span class="texto-suave">A coordinar por WhatsApp</span>
            </div>
            <?php if ($totalEfectivo < $total): ?>
            <div id="js-descuento-badge" class="checkout-descuento-badge" style="display:none">
                ✅ Precio especial por efectivo / transferencia
            </div>
            <?php endif; ?>
            <div class="checkout-linea checkout-total">
                <span>Total</span>
                <span id="js-total">$<?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>

        <script>
        (function() {
            var totalMP = <?= (int) $total ?>;
            var totalEf = <?= (int) $totalEfectivo ?>;

            function fmt(n) {
                return '$' + n.toLocaleString('es-AR', { maximumFractionDigits: 0 });
            }

            function actualizar(metodo) {
                var esEfectivo = metodo === 'efectivo' || metodo === 'transferencia';

                document.querySelectorAll('.js-item-precio').forEach(function(el) {
                    var p = esEfectivo ? parseFloat(el.dataset.precioEf) : parseFloat(el.dataset.precioMp);
                    el.textContent = fmt(p);
                });

                var t = esEfectivo ? totalEf : totalMP;
                var fmtT = fmt(t);
                var sub = document.getElementById('js-subtotal');
                var tot = document.getElementById('js-total');
                var badge = document.getElementById('js-descuento-badge');
                if (sub) sub.textContent = fmtT;
                if (tot) tot.textContent = fmtT;
                if (badge) badge.style.display = esEfectivo ? 'block' : 'none';
            }

            document.querySelectorAll('input[name="metodo_pago"]').forEach(function(r) {
                r.addEventListener('change', function() { actualizar(this.value); });
            });

            var checked = document.querySelector('input[name="metodo_pago"]:checked');
            if (checked) actualizar(checked.value);
        })();
        </script>

        <p class="checkout-nota-envio">
            🚚 El costo del envío se coordina por WhatsApp después de la compra.
            El total de arriba es solo por los productos.
        </p>

        <a href="/carrito" class="checkout-editar">← Editar carrito</a>
    </aside>

</div>

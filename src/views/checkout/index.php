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
                    <label for="ciudad">Ciudad</label>
                    <input type="text" id="ciudad" name="ciudad"
                           placeholder="Buenos Aires"
                           value="<?= htmlspecialchars($_POST['ciudad'] ?? '') ?>">
                </div>
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
            </div>

            <button type="submit" class="checkout-btn-pagar">
                <img src="https://www.mercadopago.com/org-img/Manual/ManualMP/mp_icon.png"
                     alt="MP" class="mp-icon" onerror="this.style.display='none'">
                Pagar con MercadoPago
            </button>

            <p class="checkout-seguridad">
                🔒 Tu pago es procesado de forma segura por MercadoPago.
                No almacenamos datos de tarjetas.
            </p>

        </form>
    </div>

    <!-- RESUMEN DEL PEDIDO -->
    <aside class="checkout-resumen">
        <h2 class="checkout-seccion-titulo">Tu pedido</h2>

        <div class="checkout-items">
            <?php foreach ($items as $item): ?>
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
                <p class="checkout-item-precio">
                    $<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="checkout-totales">
            <div class="checkout-linea">
                <span>Subtotal</span>
                <span>$<?= number_format($total, 0, ',', '.') ?></span>
            </div>
            <div class="checkout-linea checkout-linea-envio">
                <span>Envío</span>
                <span class="texto-suave">A calcular</span>
            </div>
            <div class="checkout-linea checkout-total">
                <span>Total</span>
                <span>$<?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>

        <a href="/carrito" class="checkout-editar">← Editar carrito</a>
    </aside>

</div>

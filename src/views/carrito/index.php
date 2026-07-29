<section class="pagina-header">
    <div class="contenedor">
        <h1 class="pagina-titulo">Tu Carrito</h1>
        <p class="pagina-subtitulo">Revisá tus productos antes de comprar</p>
    </div>
</section>

<div class="carrito-layout contenedor">

    <?php if (empty($items)): ?>

        <div class="carrito-vacio">
            <span class="carrito-vacio-icono">🎣</span>
            <h2>Tu carrito está vacío</h2>
            <p>Todavía no agregaste ningún producto.</p>
            <a href="/productos" class="btn btn-primario">Ver productos</a>
        </div>

    <?php else: ?>

        <!-- LISTA DE PRODUCTOS -->
        <div class="carrito-items">
            <?php foreach ($items as $item): ?>
            <div class="carrito-item" data-id="<?= $item['id'] ?>">

                <a href="/producto/<?= $item['slug'] ?>" class="carrito-item-imagen">
                    <?php if ($item['imagen']): ?>
                        <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
                    <?php else: ?>
                        <span class="carrito-imagen-placeholder">🎣</span>
                    <?php endif; ?>
                </a>

                <div class="carrito-item-info">
                    <a href="/producto/<?= $item['slug'] ?>" class="carrito-item-nombre">
                        <?= htmlspecialchars($item['nombre']) ?>
                    </a>
                    <p class="carrito-item-precio">$<?= number_format($item['precio'], 0, ',', '.') ?> c/u</p>
                </div>

                <div class="carrito-item-cantidad">
                    <button class="btn-cantidad" data-accion="restar" data-id="<?= $item['id'] ?>">−</button>
                    <span class="cantidad-numero"><?= $item['cantidad'] ?></span>
                    <button class="btn-cantidad" data-accion="sumar" data-id="<?= $item['id'] ?>">+</button>
                </div>

                <p class="carrito-item-subtotal" data-id="<?= $item['id'] ?>">
                    $<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?>
                </p>

                <button class="btn-eliminar-item" data-id="<?= $item['id'] ?>" title="Eliminar">✕</button>

            </div>
            <?php endforeach; ?>
        </div>

        <!-- RESUMEN -->
        <aside class="carrito-resumen">
            <h2 class="resumen-titulo">Resumen</h2>

            <div class="resumen-linea">
                <span>Productos</span>
                <span id="resumen-cantidad"><?= array_sum(array_column($items, 'cantidad')) ?></span>
            </div>
            <div class="resumen-linea resumen-total">
                <span>Total</span>
                <span id="resumen-total">$<?= number_format($total, 0, ',', '.') ?></span>
            </div>
            <p class="resumen-envio">🚚 El envío se coordina por WhatsApp después de la compra</p>

            <a href="/checkout" class="btn btn-primario resumen-btn">
                Proceder al pago
            </a>
            <a href="/productos" class="resumen-seguir">← Seguir comprando</a>

            <button class="btn-vaciar" id="btn-vaciar">Vaciar carrito</button>
        </aside>

    <?php endif; ?>

</div>

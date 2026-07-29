<?php
// Estados en orden y sus etiquetas
$flujoEstados = [
    'pendiente' => ['Pendiente', '🕒'],
    'pagado'    => ['Pagado',    '💳'],
    'enviado'   => ['Enviado',   '📦'],
    'entregado' => ['Entregado', '✅'],
];
?>

<section class="pagina-header">
    <div class="contenedor">
        <h1 class="pagina-titulo">Seguimiento de pedido</h1>
        <p class="pagina-subtitulo">Consultá el estado de tu compra</p>
    </div>
</section>

<section class="seguimiento">
    <div class="contenedor">

        <!-- FORMULARIO -->
        <form method="POST" action="/seguimiento" class="seguimiento-form">
            <div class="campo">
                <label for="referencia">Número de orden</label>
                <input type="text" id="referencia" name="referencia" required
                       placeholder="Ej: ORD-1234567890-123"
                       value="<?= htmlspecialchars($refPrefill) ?>">
            </div>
            <div class="campo">
                <label for="email">Email de la compra</label>
                <input type="email" id="email" name="email" required
                       placeholder="tu@email.com">
            </div>
            <button type="submit" class="btn btn-primario">Consultar estado</button>
        </form>

        <?php if ($error): ?>
            <div class="seguimiento-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- RESULTADO -->
        <?php if ($pedido): ?>
        <div class="seguimiento-resultado">

            <div class="seguimiento-cabecera">
                <div>
                    <span class="seguimiento-etiqueta">Pedido</span>
                    <span class="seguimiento-ref"><?= htmlspecialchars($pedido['referencia_externa']) ?></span>
                </div>
                <div style="text-align:right">
                    <span class="seguimiento-etiqueta">Fecha de compra</span>
                    <span class="seguimiento-fecha"><?= date('d/m/Y H:i', strtotime($pedido['creado_en'])) ?> hs</span>
                </div>
            </div>

            <?php if ($pedido['estado'] === 'cancelado'): ?>
                <div class="seguimiento-cancelado">
                    ❌ Este pedido fue cancelado. Si creés que es un error, escribinos.
                </div>
            <?php else: ?>
                <?php
                $claves    = array_keys($flujoEstados);
                $actual    = array_search($pedido['estado'], $claves, true);
                if ($actual === false) $actual = 0;
                ?>
                <div class="timeline">
                    <?php foreach ($flujoEstados as $clave => $info): ?>
                    <?php
                    $indice     = array_search($clave, $claves, true);
                    $completado = $indice <= $actual;
                    $esActual   = $indice === $actual;
                    ?>
                    <div class="timeline-paso <?= $completado ? 'completado' : '' ?> <?= $esActual ? 'actual' : '' ?>">
                        <div class="timeline-icono"><?= $info[1] ?></div>
                        <span class="timeline-label"><?= $info[0] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- PRODUCTOS -->
            <div class="seguimiento-items">
                <?php foreach ($detalle as $d): ?>
                <div class="seguimiento-item">
                    <span class="seguimiento-item-nombre">
                        <?= htmlspecialchars($d['producto_nombre'] ?? 'Producto') ?>
                        <span class="seguimiento-item-cant">× <?= $d['cantidad'] ?></span>
                    </span>
                    <span class="seguimiento-item-precio">
                        $<?= number_format($d['precio_unit'] * $d['cantidad'], 0, ',', '.') ?>
                    </span>
                </div>
                <?php endforeach; ?>
                <div class="seguimiento-total">
                    <span>Total</span>
                    <span>$<?= number_format($pedido['total'], 0, ',', '.') ?></span>
                </div>
            </div>

        </div>
        <?php endif; ?>

    </div>
</section>

<!-- BREADCRUMB -->
<nav class="breadcrumb">
    <div class="contenedor breadcrumb-inner">
        <a href="/">Inicio</a>
        <span class="breadcrumb-sep">/</span>
        <a href="/productos">Productos</a>
        <span class="breadcrumb-sep">/</span>
        <a href="/categorias/<?= $producto['categoria_slug'] ?>"><?= htmlspecialchars($producto['categoria_nombre']) ?></a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-actual"><?= htmlspecialchars($producto['nombre']) ?></span>
    </div>
</nav>

<!-- DETALLE -->
<div class="detalle-layout contenedor">

    <!-- GALERÍA -->
    <div class="detalle-galeria">
        <div class="detalle-imagen-wrap">
            <?php if (!empty($imagenes)): ?>
                <img src="<?= htmlspecialchars($imagenes[0]['ruta']) ?>"
                     alt="<?= htmlspecialchars($producto['nombre']) ?>"
                     class="detalle-imagen" id="detalle-imagen-principal">
            <?php elseif ($producto['imagen_principal']): ?>
                <img src="<?= htmlspecialchars($producto['imagen_principal']) ?>"
                     alt="<?= htmlspecialchars($producto['nombre']) ?>"
                     class="detalle-imagen" id="detalle-imagen-principal">
            <?php else: ?>
                <div class="detalle-imagen-placeholder">🎣</div>
            <?php endif; ?>

            <?php if ($producto['stock'] == 0): ?>
                <span class="detalle-badge-agotado">Sin stock</span>
            <?php endif; ?>
        </div>

        <?php if (count($imagenes) > 1): ?>
        <div class="detalle-miniaturas">
            <?php foreach ($imagenes as $i => $img): ?>
            <button type="button"
                    class="detalle-miniatura <?= $i === 0 ? 'activa' : '' ?>"
                    data-ruta="<?= htmlspecialchars($img['ruta']) ?>">
                <img src="<?= htmlspecialchars($img['ruta']) ?>" alt="Vista <?= $i + 1 ?>">
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- INFO -->
    <div class="detalle-info">

        <span class="producto-categoria"><?= htmlspecialchars($producto['categoria_nombre']) ?></span>

        <h1 class="detalle-nombre"><?= htmlspecialchars($producto['nombre']) ?></h1>

        <p class="detalle-precio">$<?= number_format($producto['precio'], 0, ',', '.') ?></p>

        <?php if ($producto['descripcion']): ?>
        <div class="detalle-descripcion">
            <?= nl2br(htmlspecialchars($producto['descripcion'])) ?>
        </div>
        <?php endif; ?>

        <?php if ($producto['stock'] > 0): ?>

            <p class="detalle-stock stock-ok">
                ✓ En stock — <?= $producto['stock'] ?> disponible<?= $producto['stock'] !== 1 ? 's' : '' ?>
            </p>

            <div class="detalle-compra">
                <div class="detalle-cantidad-wrap">
                    <button class="btn-cantidad" id="btn-restar" type="button">−</button>
                    <input type="number" id="detalle-cantidad" value="1"
                           min="1" max="<?= $producto['stock'] ?>" readonly>
                    <button class="btn-cantidad" id="btn-sumar" type="button">+</button>
                </div>
                <button class="btn-agregar-detalle btn btn-primario"
                        data-id="<?= $producto['id'] ?>">
                    🛒 Agregar al carrito
                </button>
            </div>

        <?php else: ?>

            <p class="detalle-stock stock-agotado">✕ Sin stock disponible</p>
            <button class="btn btn-primario" disabled style="opacity:.4;cursor:not-allowed">
                Sin stock
            </button>

        <?php endif; ?>

    </div>
</div>

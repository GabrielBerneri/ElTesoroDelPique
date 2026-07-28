<?php
$iconosCategorias = [
    'canas'      => '🎣',
    'reels'      => '⚙️',
    'senuelos'   => '🦈',
    'lineas'     => '🪢',
    'anzuelos'   => '🪝',
    'accesorios' => '🧰',
];
?>

<!-- ENCABEZADO -->
<section class="pagina-header">
    <div class="contenedor">
        <h1 class="pagina-titulo"><?= htmlspecialchars($tituloPagina) ?></h1>
        <p class="pagina-subtitulo">
            <?= $slugCategoria ? 'Filtrando por categoría' : 'Todo el catálogo de El Tesoro del Pique' ?>
        </p>
    </div>
</section>

<!-- LAYOUT: SIDEBAR + PRODUCTOS -->
<div class="productos-layout">

    <!-- SIDEBAR CATEGORÍAS -->
    <aside class="sidebar-categorias">
        <p class="sidebar-titulo">Categorías</p>
        <nav class="sidebar-nav">
            <a href="/productos" class="sidebar-item <?= !$slugCategoria ? 'activo' : '' ?>">
                <span class="sidebar-icono">🗂️</span>
                <span>Todos</span>
            </a>
            <?php foreach ($categorias as $cat): ?>
            <a href="/categorias/<?= $cat['slug'] ?>"
               class="sidebar-item <?= $slugCategoria === $cat['slug'] ? 'activo' : '' ?>">
                <span class="sidebar-icono"><?= $iconosCategorias[$cat['slug']] ?? '📦' ?></span>
                <span><?= htmlspecialchars($cat['nombre']) ?></span>
            </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <!-- GRILLA DE PRODUCTOS -->
    <main class="productos-contenido">
        <?php if (empty($productos)): ?>
            <div class="sin-productos">
                <span>🎣</span>
                <p>No hay productos en esta categoría todavía.</p>
                <a href="/productos" class="btn btn-primario">Ver todos los productos</a>
            </div>
        <?php else: ?>
            <div class="productos-grid">
                <?php foreach ($productos as $producto): ?>
                <article class="producto-card">
                    <a href="/producto/<?= $producto['slug'] ?>" class="producto-imagen">
                        <?php if ($producto['imagen_principal']): ?>
                            <img src="<?= htmlspecialchars($producto['imagen_principal']) ?>"
                                 alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                 onerror="this.onerror=null;this.outerHTML='&#127907;'">
                        <?php else: ?>
                            🎣
                        <?php endif; ?>
                        <?php if ($producto['stock'] == 0): ?>
                            <span class="producto-badge badge-agotado">Sin stock</span>
                        <?php endif; ?>
                    </a>
                    <div class="producto-info">
                        <span class="producto-categoria"><?= htmlspecialchars($producto['categoria_nombre']) ?></span>
                        <h3 class="producto-nombre">
                            <a href="/producto/<?= $producto['slug'] ?>">
                                <?= htmlspecialchars($producto['nombre']) ?>
                            </a>
                        </h3>
                        <?php if ($producto['descripcion']): ?>
                            <p class="producto-descripcion">
                                <?= htmlspecialchars(mb_substr($producto['descripcion'], 0, 80)) ?>...
                            </p>
                        <?php endif; ?>
                        <p class="producto-precio">$<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                    </div>
                    <div class="producto-footer">
                        <?php if ($producto['stock'] > 0): ?>
                            <button class="btn-agregar" data-id="<?= $producto['id'] ?>">
                                🛒 Agregar al carrito
                            </button>
                        <?php else: ?>
                            <button class="btn-agregar btn-agotado" disabled>Sin stock</button>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</div>

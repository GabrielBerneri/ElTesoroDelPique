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

<section class="pagina-header">
    <div class="contenedor">
        <h1 class="pagina-titulo">Categorías</h1>
        <p class="pagina-subtitulo">Explorá el catálogo por tipo de producto</p>
    </div>
</section>

<section class="seccion-categorias">
    <div class="contenedor">
        <?php if (empty($categorias)): ?>
            <p class="sin-productos"><span>🗂️</span></p>
        <?php else: ?>
            <div class="categorias-grid">
                <?php foreach ($categorias as $cat): ?>
                <a href="/categorias/<?= $cat['slug'] ?>" class="categoria-card">
                    <span class="categoria-icono"><?= $iconosCategorias[$cat['slug']] ?? '📦' ?></span>
                    <span class="categoria-nombre"><?= htmlspecialchars($cat['nombre']) ?></span>
                    <span class="categoria-cantidad">
                        <?= $cat['cantidad_productos'] ?> producto<?= $cat['cantidad_productos'] != 1 ? 's' : '' ?>
                    </span>
                </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ENCABEZADO DE SECCIÓN -->
<section class="pagina-header">
    <div class="contenedor">
        <h1 class="pagina-titulo"><?= htmlspecialchars($tituloPagina) ?></h1>
        <p class="pagina-subtitulo">
            <?= $slugCategoria ? 'Mostrando productos de esta categoría' : 'Todos nuestros productos' ?>
        </p>
    </div>
</section>

<!-- CONTENIDO -->
<section class="pagina-productos">
    <div class="contenedor">
        <div class="productos-layout">

            <!-- SIDEBAR DE CATEGORÍAS -->
            <aside class="categorias-sidebar">
                <h3 class="sidebar-titulo">Categorías</h3>
                <ul class="categorias-lista">
                    <li>
                        <a href="/productos" class="<?= !$slugCategoria ? 'activo' : '' ?>">
                            Todos los productos
                        </a>
                    </li>
                    <?php foreach ($categorias as $cat): ?>
                    <li>
                        <a href="/categorias/<?= $cat['slug'] ?>"
                           class="<?= $slugCategoria === $cat['slug'] ? 'activo' : '' ?>">
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <!-- GRILLA DE PRODUCTOS -->
            <div class="productos-contenido">
                <?php if (empty($productos)): ?>
                    <div class="sin-productos">
                        <span>🎣</span>
                        <p>No hay productos en esta categoría todavía.</p>
                        <a href="/productos" class="btn btn-primario">Ver todos los productos</a>
                    </div>
                <?php else: ?>
                    <p class="cantidad-resultados"><?= count($productos) ?> producto<?= count($productos) !== 1 ? 's' : '' ?></p>
                    <div class="productos-grid">
                        <?php foreach ($productos as $producto): ?>
                        <article class="producto-card">
                            <a href="/producto/<?= $producto['slug'] ?>" class="producto-imagen">
                                <?php if ($producto['imagen_principal']): ?>
                                    <img src="<?= htmlspecialchars($producto['imagen_principal']) ?>"
                                         alt="<?= htmlspecialchars($producto['nombre']) ?>">
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
                                        Agregar al carrito
                                    </button>
                                <?php else: ?>
                                    <button class="btn-agregar btn-agotado" disabled>Sin stock</button>
                                <?php endif; ?>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

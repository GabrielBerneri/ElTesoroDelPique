<?php
/* Vista: Página de inicio
   Variables disponibles: $productosDestacados, $categorias
*/
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-contenido">
        <p class="hero-eyebrow">🎣 Tienda de pesca en Argentina</p>
        <h1 class="hero-titulo" data-text="Todo para tu
próxima salida">
            Todo para tu<br>
            <span>próxima salida</span>
        </h1>
        <p class="hero-descripcion">
            Cañas, reels, señuelos y accesorios para todos los estilos de pesca.
            Enviamos a todo el país.
        </p>
        <div class="hero-botones">
            <a href="/productos" class="btn btn-primario">Ver productos</a>
            <a href="/nosotros" class="btn btn-secundario">Quiénes somos</a>
        </div>
    </div>
</section>

<!-- CATEGORÍAS -->
<section class="seccion-categorias">
    <div class="contenedor">
        <h2 class="seccion-titulo">Explorá por categoría</h2>
        <p class="seccion-subtitulo">Encontrá lo que necesitás para cada tipo de pesca</p>

        <div class="categorias-grid">
            <a href="/categorias/canas" class="categoria-card">
                <span class="categoria-icono">🎣</span>
                <span class="categoria-nombre">Cañas</span>
                <span class="categoria-cantidad">Ver productos</span>
            </a>
            <a href="/categorias/reels" class="categoria-card">
                <span class="categoria-icono">⚙️</span>
                <span class="categoria-nombre">Reels</span>
                <span class="categoria-cantidad">Ver productos</span>
            </a>
            <a href="/categorias/senuelos" class="categoria-card">
                <span class="categoria-icono">🦈</span>
                <span class="categoria-nombre">Señuelos</span>
                <span class="categoria-cantidad">Ver productos</span>
            </a>
            <a href="/categorias/lineas" class="categoria-card">
                <span class="categoria-icono">🪢</span>
                <span class="categoria-nombre">Líneas</span>
                <span class="categoria-cantidad">Ver productos</span>
            </a>
            <a href="/categorias/anzuelos" class="categoria-card">
                <span class="categoria-icono">🪝</span>
                <span class="categoria-nombre">Anzuelos</span>
                <span class="categoria-cantidad">Ver productos</span>
            </a>
            <a href="/categorias/accesorios" class="categoria-card">
                <span class="categoria-icono">🧰</span>
                <span class="categoria-nombre">Accesorios</span>
                <span class="categoria-cantidad">Ver productos</span>
            </a>
        </div>
    </div>
</section>

<!-- PRODUCTOS DESTACADOS -->
<section class="seccion-productos">
    <div class="contenedor">
        <div class="seccion-header">
            <div>
                <h2 class="seccion-titulo">Productos destacados</h2>
                <p class="seccion-subtitulo">Los más vendidos de la semana</p>
            </div>
            <a href="/productos" class="ver-todos">Ver todos →</a>
        </div>

        <div class="productos-grid">
            <?php if (empty($productosDestacados)): ?>
                <p style="color: var(--color-texto-suave); grid-column: 1/-1; text-align: center; padding: 40px 0;">
                    Próximamente cargamos los productos. ¡Volvé pronto!
                </p>
            <?php else: ?>
                <?php foreach ($productosDestacados as $producto): ?>
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
                            <a href="/producto/<?= $producto['slug'] ?>"><?= htmlspecialchars($producto['nombre']) ?></a>
                        </h3>
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
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- BANNER ENVÍOS -->
<section class="banner-secundario">
    <div class="contenedor">
        <h2>📦 Enviamos a todo el país</h2>
        <p>Comprá desde cualquier provincia y recibí en la puerta de tu casa.</p>
        <a href="/envios" class="btn btn-primario">Ver información de envíos</a>
    </div>
</section>

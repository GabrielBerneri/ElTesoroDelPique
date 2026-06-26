<?php
/* Vista: Página de inicio
   Variables disponibles: $productosDestacados, $categorias
*/
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-contenido">
        <p class="hero-eyebrow">🎣 Tienda de pesca en Argentina</p>
        <h1 class="hero-titulo">
            Todo para tu<br>
            <span>próxima salida</span>
        </h1>
        <p class="hero-descripcion">
            Cañas, reels, señuelos y accesorios para todos los estilos de pesca.
            Enviamos a todo el país.
        </p>
        <div class="hero-botones">
            <a href="/productos" class="btn btn-primario">Ver productos</a>
            <a href="/ofertas" class="btn btn-secundario">Ver ofertas</a>
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
            <!-- Tarjetas de ejemplo — se reemplazarán con datos reales de la BD -->
            <?php
            $productosEjemplo = [
                ['nombre' => 'Caña Spinning 2.40m', 'categoria' => 'Cañas',    'precio' => 18500, 'icono' => '🎣', 'badge' => 'Nuevo'],
                ['nombre' => 'Reel Frontal 4000',   'categoria' => 'Reels',    'precio' => 32000, 'icono' => '🔄', 'badge' => null],
                ['nombre' => 'Señuelo Popper 9cm',  'categoria' => 'Señuelos', 'precio' => 4200,  'icono' => '🐟', 'badge' => 'Oferta'],
                ['nombre' => 'Tanza Monofilamento', 'categoria' => 'Líneas',   'precio' => 1800,  'icono' => '〰️', 'badge' => null],
            ];

            foreach ($productosEjemplo as $producto): ?>
            <article class="producto-card">
                <div class="producto-imagen">
                    <?= $producto['icono'] ?>
                    <?php if ($producto['badge']): ?>
                    <span class="producto-badge"><?= $producto['badge'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="producto-info">
                    <span class="producto-categoria"><?= $producto['categoria'] ?></span>
                    <h3 class="producto-nombre"><?= $producto['nombre'] ?></h3>
                    <p class="producto-precio">$<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                </div>
                <div class="producto-footer">
                    <button class="btn-agregar">Agregar al carrito</button>
                </div>
            </article>
            <?php endforeach; ?>
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

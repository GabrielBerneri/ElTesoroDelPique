<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina ?? 'El Tesoro del Pique') ?> | El Tesoro del Pique</title>
    <link rel="stylesheet" href="/assets/css/estilos.css">
</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="header-inner">

        <a href="/" class="logo">
            <span class="logo-nombre">El Tesoro del Pique</span>
            <span class="logo-tagline">Todo para la pesca</span>
        </a>

        <nav class="nav">
            <a href="/" class="activo">Inicio</a>
            <a href="/productos">Productos</a>
            <a href="/categorias">Categorías</a>
            <a href="/ofertas">Ofertas</a>
            <a href="/contacto">Contacto</a>
        </nav>

        <div class="header-acciones">
            <a href="/carrito" class="btn-carrito">
                🛒 Carrito
                <span class="carrito-cantidad">0</span>
            </a>
        </div>

    </div>
</header>

<!-- CONTENIDO DE LA PÁGINA -->
<main>
    <?= $contenido ?? '' ?>
</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <span class="logo-nombre">El Tesoro del Pique</span>
            <p>Tu tienda de pesca de confianza en Argentina. Encontrá todo lo que necesitás para tu próxima salida de pesca.</p>
        </div>
        <div class="footer-col">
            <h4>Productos</h4>
            <ul>
                <li><a href="/categorias/canas">Cañas</a></li>
                <li><a href="/categorias/reels">Reels</a></li>
                <li><a href="/categorias/senuelos">Señuelos</a></li>
                <li><a href="/categorias/accesorios">Accesorios</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Información</h4>
            <ul>
                <li><a href="/nosotros">Sobre nosotros</a></li>
                <li><a href="/envios">Envíos</a></li>
                <li><a href="/devoluciones">Devoluciones</a></li>
                <li><a href="/contacto">Contacto</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> El Tesoro del Pique — Argentina</p>
    </div>
</footer>

<script src="/assets/js/main.js"></script>
</body>
</html>

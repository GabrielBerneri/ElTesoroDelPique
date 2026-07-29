<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina ?? 'El Tesoro del Pique') ?> | El Tesoro del Pique</title>
    <link rel="stylesheet" href="/assets/css/estilos.css?v=17">
</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="header-inner">

        <a href="/" class="logo">
            <img src="/assets/image/logo.jpeg" alt="El Tesoro del Pique" class="logo-imagen">
            <div class="logo-texto">
                <span class="logo-nombre">El Tesoro del Pique</span>
            </div>
        </a>

        <nav class="nav">
            <a href="/">Inicio</a>
            <a href="/productos">Productos</a>
            <a href="/categorias">Categorías</a>
            <a href="/nosotros">Nosotros</a>
            <a href="/contacto">Contacto</a>
        </nav>

        <div class="header-acciones">
            <?php
            $itemsCarrito    = $_SESSION['carrito'] ?? [];
            $cantidadCarrito = array_sum(array_column($itemsCarrito, 'cantidad'));
            $totalCarrito    = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $itemsCarrito));
            ?>

            <div class="carrito-wrapper" id="carrito-wrapper">

                <button class="btn-carrito" id="btn-carrito-toggle" aria-expanded="false">
                    🛒 Carrito
                    <span class="carrito-cantidad"><?= $cantidadCarrito ?></span>
                </button>

                <!-- MINI CARRITO -->
                <div class="mini-carrito" id="mini-carrito" hidden>

                    <?php if (empty($itemsCarrito)): ?>
                        <div class="mini-vacio">
                            <span>🎣</span>
                            <p>Tu carrito está vacío</p>
                            <a href="/productos" class="mini-ver-btn">Ver productos</a>
                        </div>
                    <?php else: ?>

                        <div class="mini-items" id="mini-items">
                            <?php foreach ($itemsCarrito as $item): ?>
                            <div class="mini-item" data-id="<?= $item['id'] ?>">
                                <div class="mini-item-imagen">
                                    <?php if ($item['imagen']): ?>
                                        <img src="<?= htmlspecialchars($item['imagen']) ?>"
                                             alt="<?= htmlspecialchars($item['nombre']) ?>">
                                    <?php else: ?>
                                        <span>🎣</span>
                                    <?php endif; ?>
                                </div>
                                <div class="mini-item-info">
                                    <p class="mini-item-nombre"><?= htmlspecialchars($item['nombre']) ?></p>
                                    <p class="mini-item-detalle">
                                        <?= $item['cantidad'] ?> × $<?= number_format($item['precio'], 0, ',', '.') ?>
                                    </p>
                                </div>
                                <p class="mini-item-subtotal">
                                    $<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?>
                                </p>
                                <button class="mini-item-eliminar" data-id="<?= $item['id'] ?>" title="Quitar">✕</button>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mini-footer">
                            <div class="mini-total-linea">
                                <span>Total</span>
                                <span id="mini-total">$<?= number_format($totalCarrito, 0, ',', '.') ?></span>
                            </div>
                            <a href="/carrito" class="mini-btn-carrito">Ver carrito completo</a>
                            <a href="/checkout" class="mini-btn-pago">Ir al pago →</a>
                        </div>

                    <?php endif; ?>

                </div>
                <!-- /MINI CARRITO -->

            </div>
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
            <img src="/assets/image/logo.jpeg" alt="El Tesoro del Pique" class="footer-logo">
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
                <li><a href="/seguimiento">Seguí tu pedido</a></li>
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

<script src="/assets/js/main.js?v=4"></script>
<script src="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/js/twemoji.min.js" crossorigin="anonymous"></script>
<script>twemoji.parse(document.body, { folder: 'svg', ext: '.svg' });</script>
</body>
</html>

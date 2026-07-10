<?php
// Router simple: lee la URL y decide qué controlador ejecutar

function manejarRuta(PDO $bd): void {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/') ?: '/';
    $metodo = $_SERVER['REQUEST_METHOD'];

    // Rutas públicas
    if ($uri === '/') {
        require_once BASE_PATH . '/src/controllers/HomeController.php';
        HomeController::inicio($bd);
        return;
    }

    // Página de productos y categorías
    if ($uri === '/productos' || str_starts_with($uri, '/categorias/')) {
        require_once BASE_PATH . '/src/controllers/ProductoController.php';
        ProductoController::lista($bd, $uri);
        return;
    }

    // Páginas estáticas
    if ($uri === '/categorias') {
        require_once BASE_PATH . '/src/controllers/PaginaController.php';
        PaginaController::categorias($bd);
        return;
    }
    if ($uri === '/ofertas') {
        require_once BASE_PATH . '/src/controllers/PaginaController.php';
        PaginaController::ofertas();
        return;
    }
    if ($uri === '/contacto') {
        require_once BASE_PATH . '/src/controllers/PaginaController.php';
        PaginaController::contacto();
        return;
    }

    // Detalle de producto
    if (str_starts_with($uri, '/producto/')) {
        require_once BASE_PATH . '/src/controllers/ProductoController.php';
        ProductoController::detalle($bd, $uri);
        return;
    }

    // Rutas del carrito
    if (str_starts_with($uri, '/carrito')) {
        require_once BASE_PATH . '/src/controllers/CarritoController.php';
        match (true) {
            $uri === '/carrito'                                      => CarritoController::vista($bd),
            $uri === '/carrito/agregar'    && $metodo === 'POST'     => CarritoController::agregar($bd),
            $uri === '/carrito/actualizar' && $metodo === 'POST'     => CarritoController::actualizar(),
            $uri === '/carrito/eliminar'   && $metodo === 'POST'     => CarritoController::eliminar(),
            $uri === '/carrito/vaciar'     && $metodo === 'POST'     => CarritoController::vaciar(),
            $uri === '/carrito/mini'       && $metodo === 'GET'      => CarritoController::mini(),
            default => redirigir('/carrito'),
        };
        return;
    }

    // Checkout
    if (str_starts_with($uri, '/checkout')) {
        require_once BASE_PATH . '/src/controllers/CheckoutController.php';
        match (true) {
            $uri === '/checkout'           && $metodo === 'GET'  => CheckoutController::vista(),
            $uri === '/checkout/procesar'  && $metodo === 'POST' => CheckoutController::procesar($bd),
            $uri === '/checkout/exito'                           => CheckoutController::exito($bd),
            $uri === '/checkout/fallo'                           => CheckoutController::fallo(),
            $uri === '/checkout/pendiente'                       => CheckoutController::pendiente(),
            $uri === '/checkout/transferencia'                   => CheckoutController::transferencia(),
            $uri === '/checkout/efectivo'                        => CheckoutController::efectivo(),
            default => redirigir('/checkout'),
        };
        return;
    }

    // Rutas del panel admin
    if (str_starts_with($uri, '/admin')) {
        require_once BASE_PATH . '/src/controllers/AdminController.php';

        match (true) {
            $uri === '/admin/login'  && $metodo === 'GET'  => AdminController::loginVista(),
            $uri === '/admin/login'  && $metodo === 'POST' => AdminController::loginProcesar($bd),
            $uri === '/admin/logout'                        => AdminController::logout(),
            $uri === '/admin'        || $uri === '/admin/dashboard' => AdminController::dashboard($bd),
            $uri === '/admin/productos'                     => AdminController::productos($bd),
            $uri === '/admin/productos/nuevo' && $metodo === 'GET'  => AdminController::productoNuevoVista($bd),
            $uri === '/admin/productos/nuevo' && $metodo === 'POST' => AdminController::productoNuevoProcesar($bd),
            str_starts_with($uri, '/admin/productos/editar') && $metodo === 'GET'  => AdminController::productoEditarVista($bd, $uri),
            str_starts_with($uri, '/admin/productos/editar') && $metodo === 'POST' => AdminController::productoEditarProcesar($bd, $uri),
            str_starts_with($uri, '/admin/productos/eliminar')                     => AdminController::productoEliminar($bd, $uri),
            str_starts_with($uri, '/admin/imagenes/eliminar')                      => AdminController::imagenEliminar($bd, $uri),
            $uri === '/admin/categorias'         && $metodo === 'GET'               => AdminController::categorias($bd),
            $uri === '/admin/categorias/nueva'   && $metodo === 'POST'              => AdminController::categoriaNuevaProcesar($bd),
            str_starts_with($uri, '/admin/categorias/eliminar')                    => AdminController::categoriaEliminar($bd, $uri),
            $uri === '/admin/ventas'             && $metodo === 'GET'               => AdminController::ventas($bd),
            str_starts_with($uri, '/admin/ventas/estado/') && $metodo === 'POST'   => AdminController::ventaEstadoProcesar($bd, $uri),
            str_starts_with($uri, '/admin/ventas/')        && $metodo === 'GET'    => AdminController::ventaDetalle($bd, $uri),
            $uri === '/admin/perfil' && $metodo === 'GET'                           => AdminController::perfilVista(),
            $uri === '/admin/perfil' && $metodo === 'POST'                          => AdminController::perfilProcesar($bd),
            $uri === '/admin/administradores'       && $metodo === 'GET'            => AdminController::administradores($bd),
            $uri === '/admin/administradores/nuevo' && $metodo === 'POST'           => AdminController::administradorNuevoProcesar($bd),
            str_starts_with($uri, '/admin/administradores/eliminar')               => AdminController::administradorEliminar($bd, $uri),
            default => redirigir('/admin')
        };
        return;
    }

    // 404
    http_response_code(404);
    echo '<h1>Página no encontrada</h1>';
}

function redirigir(string $url): void {
    header("Location: $url");
    exit;
}

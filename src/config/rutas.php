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

    // Rutas del carrito
    if (str_starts_with($uri, '/carrito')) {
        require_once BASE_PATH . '/src/controllers/CarritoController.php';
        match (true) {
            $uri === '/carrito'                                      => CarritoController::vista($bd),
            $uri === '/carrito/agregar'    && $metodo === 'POST'     => CarritoController::agregar($bd),
            $uri === '/carrito/actualizar' && $metodo === 'POST'     => CarritoController::actualizar(),
            $uri === '/carrito/eliminar'   && $metodo === 'POST'     => CarritoController::eliminar(),
            $uri === '/carrito/vaciar'     && $metodo === 'POST'     => CarritoController::vaciar(),
            default => redirigir('/carrito'),
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
            $uri === '/admin/perfil' && $metodo === 'GET'                           => AdminController::perfilVista(),
            $uri === '/admin/perfil' && $metodo === 'POST'                          => AdminController::perfilProcesar($bd),
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

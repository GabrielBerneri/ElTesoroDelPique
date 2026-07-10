<?php

require_once BASE_PATH . '/src/models/Producto.php';
require_once BASE_PATH . '/src/models/Categoria.php';

class ProductoController {

    public static function lista(PDO $bd, string $uri): void {
        $modeloProducto  = new Producto($bd);
        $modeloCategoria = new Categoria($bd);

        $categorias = $modeloCategoria->obtenerTodas();

        // Si la URL es /categorias/slug filtramos por categoría
        $slugCategoria = null;
        if (str_starts_with($uri, '/categorias/')) {
            $slugCategoria = basename($uri);
            $productos     = $modeloProducto->obtenerPorCategoria($slugCategoria);
            $categoriaActual = array_filter($categorias, fn($c) => $c['slug'] === $slugCategoria);
            $categoriaActual = reset($categoriaActual);
            $tituloPagina  = $categoriaActual ? $categoriaActual['nombre'] : 'Productos';
        } else {
            $productos    = $modeloProducto->obtenerTodos();
            $tituloPagina = 'Productos';
        }

        ob_start();
        require_once BASE_PATH . '/src/views/productos/lista.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }

    public static function detalle(PDO $bd, string $uri): void {
        $slug           = basename($uri);
        $modeloProducto = new Producto($bd);
        $producto       = $modeloProducto->obtenerPorSlug($slug);

        if (!$producto) {
            http_response_code(404);
            $tituloPagina = 'Producto no encontrado';
            $contenido    = '<div style="text-align:center;padding:80px 20px"><h1>Producto no encontrado</h1><a href="/productos">Ver todos los productos</a></div>';
            require_once BASE_PATH . '/src/views/layouts/base.php';
            return;
        }

        $tituloPagina = $producto['nombre'];
        $imagenes     = $modeloProducto->obtenerImagenes((int) $producto['id']);

        ob_start();
        require_once BASE_PATH . '/src/views/productos/detalle.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }
}

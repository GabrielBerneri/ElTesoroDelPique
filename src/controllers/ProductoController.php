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
}

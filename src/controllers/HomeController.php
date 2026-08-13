<?php

require_once BASE_PATH . '/src/models/Categoria.php';
require_once BASE_PATH . '/src/models/Producto.php';
require_once BASE_PATH . '/src/models/Marca.php';

class HomeController {

    public static function inicio(PDO $bd): void {
        $modeloCategoria = new Categoria($bd);
        $modeloProducto  = new Producto($bd);
        $modeloMarca     = new Marca($bd);

        $categorias          = $modeloCategoria->obtenerTodas();
        $productosDestacados = $modeloProducto->obtenerDestacados(8);
        $marcas              = $modeloMarca->obtenerActivas();

        ob_start();
        require_once BASE_PATH . '/src/views/home/inicio.php';
        $contenido = ob_get_clean();

        $tituloPagina = 'Inicio';
        require_once BASE_PATH . '/src/views/layouts/base.php';
    }
}

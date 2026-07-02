<?php
// Punto de entrada único de la aplicación

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/src/config/env.php';
require_once BASE_PATH . '/src/config/database.php';
require_once BASE_PATH . '/src/helpers/sanitize.php';
require_once BASE_PATH . '/src/models/Categoria.php';
require_once BASE_PATH . '/src/models/Producto.php';

cargarEnv();

try {
    $bd = conectarBD();
    $modeloCategoria = new Categoria($bd);
    $modeloProducto  = new Producto($bd);

    $categorias          = $modeloCategoria->obtenerTodas();
    $productosDestacados = $modeloProducto->obtenerDestacados(8);

} catch (PDOException $e) {
    // En producción no mostramos el error real
    $categorias          = [];
    $productosDestacados = [];
}

ob_start();
require_once BASE_PATH . '/src/views/home/inicio.php';
$contenido = ob_get_clean();

$tituloPagina = 'Inicio';
require_once BASE_PATH . '/src/views/layouts/base.php';

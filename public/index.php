<?php
// Punto de entrada único de la aplicación

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/src/helpers/sanitize.php';

// Capturamos el contenido de la vista en un buffer
ob_start();
require_once BASE_PATH . '/src/views/home/inicio.php';
$contenido = ob_get_clean();

$tituloPagina = 'Inicio';

// Renderizamos el layout base con el contenido adentro
require_once BASE_PATH . '/src/views/layouts/base.php';

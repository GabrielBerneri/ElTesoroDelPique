<?php
// Punto de entrada único de la aplicación

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/src/config/env.php';
require_once BASE_PATH . '/src/config/database.php';
require_once BASE_PATH . '/src/helpers/sanitize.php';
require_once BASE_PATH . '/src/helpers/auth.php';
require_once BASE_PATH . '/src/config/rutas.php';

cargarEnv();
iniciarSesion();

try {
    $bd = conectarBD();
} catch (PDOException $e) {
    die('Error de conexión a la base de datos.');
}

manejarRuta($bd);

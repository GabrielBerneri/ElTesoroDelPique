<?php
// Punto de entrada único de la aplicación
// Todas las URLs del sitio pasan por acá

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/src/config/database.php';
require_once BASE_PATH . '/src/helpers/sanitize.php';

// Por ahora mostramos una página de inicio simple
echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Tesoro del Pique</title>
</head>
<body>
    <h1>El Tesoro del Pique</h1>
    <p>Sitio en construcción.</p>
</body>
</html>';

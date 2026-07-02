<?php
// Carga las variables del archivo .env en $_ENV
// PHP no lo hace automáticamente, así que lo hacemos nosotros

function cargarEnv(): void {
    $ruta = BASE_PATH . '/.env';

    if (!file_exists($ruta)) {
        return;
    }

    $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lineas as $linea) {
        // Ignorar comentarios
        if (str_starts_with(trim($linea), '#')) {
            continue;
        }

        // Separar clave=valor
        [$clave, $valor] = explode('=', $linea, 2);
        $_ENV[trim($clave)] = trim($valor);
    }
}

<?php
// Conexión a MySQL usando PDO
// Lee las credenciales del archivo .env (nunca hardcodeadas acá)

function conectarBD(): PDO {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $nombre = $_ENV['DB_NAME'] ?? '';
    $usuario = $_ENV['DB_USER'] ?? '';
    $password = $_ENV['DB_PASS'] ?? '';

    $dsn = "mysql:host={$host};dbname={$nombre};charset=utf8mb4";

    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, $usuario, $password, $opciones);
}

<?php
// Funciones de sesión y autenticación

function iniciarSesion(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function estaLogueado(): bool {
    iniciarSesion();
    return isset($_SESSION['admin_id']);
}

function requiereAdmin(): void {
    if (!estaLogueado()) {
        header('Location: /admin/login');
        exit;
    }
}

function guardarSesionAdmin(array $usuario): void {
    iniciarSesion();
    $_SESSION['admin_id']     = $usuario['id'];
    $_SESSION['admin_nombre'] = $usuario['nombre'];
}

function cerrarSesion(): void {
    iniciarSesion();
    session_destroy();
}

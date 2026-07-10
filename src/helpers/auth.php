<?php
// Funciones de sesión y autenticación

// Cuenta con permisos para gestionar otros administradores
const SUPER_ADMIN_EMAIL = 'gabyberneri.gb@gmail.com';

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
    $_SESSION['admin_email']  = $usuario['email'];
}

function esSuperAdmin(): bool {
    iniciarSesion();
    return ($_SESSION['admin_email'] ?? '') === SUPER_ADMIN_EMAIL;
}

function requiereSuperAdmin(): void {
    requiereAdmin();
    if (!esSuperAdmin()) {
        header('Location: /admin');
        exit;
    }
}

function cerrarSesion(): void {
    iniciarSesion();
    session_destroy();
}

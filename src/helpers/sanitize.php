<?php
// Funciones para limpiar datos que vienen del usuario
// Siempre sanitizar antes de usar datos de formularios o URLs

function limpiarTexto(string $texto): string {
    return htmlspecialchars(strip_tags(trim($texto)), ENT_QUOTES, 'UTF-8');
}

function limpiarEntero(mixed $valor): int {
    return (int) filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
}

function limpiarEmail(string $email): string|false {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

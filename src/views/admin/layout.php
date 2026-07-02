<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina ?? 'Admin') ?> — El Tesoro del Pique</title>
    <link rel="stylesheet" href="/assets/css/estilos.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">

<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <img src="/assets/image/logo.jpeg" alt="Logo">
        <span>Admin</span>
    </div>
    <nav class="sidebar-nav">
        <a href="/admin" class="<?= str_ends_with($_SERVER['REQUEST_URI'], '/admin') ? 'activo' : '' ?>">
            📊 Dashboard
        </a>
        <a href="/admin/productos" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/productos') ? 'activo' : '' ?>">
            📦 Productos
        </a>
        <a href="/" target="_blank">🌐 Ver sitio</a>
        <a href="/admin/logout" class="logout">🚪 Salir</a>
    </nav>
</aside>

<main class="admin-main">

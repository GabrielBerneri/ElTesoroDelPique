<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina ?? 'Admin') ?> — El Tesoro del Pique</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎣</text></svg>">
    <link rel="stylesheet" href="/assets/css/estilos.css?v=9">
    <link rel="stylesheet" href="/assets/css/admin.css?v=3">
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
        <a href="/admin/categorias" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/categorias') ? 'activo' : '' ?>">
            🗂️ Categorías
        </a>
        <a href="/admin/ventas" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/ventas') ? 'activo' : '' ?>">
            🛒 Ventas
        </a>
        <a href="/admin/perfil" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/perfil') ? 'activo' : '' ?>">
            👤 Mi perfil
        </a>
        <?php if (esSuperAdmin()): ?>
        <a href="/admin/administradores" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/administradores') ? 'activo' : '' ?>">
            🛡️ Administradores
        </a>
        <?php endif; ?>
        <a href="/" target="_blank">🌐 Ver sitio</a>
        <a href="/admin/logout" class="logout">🚪 Salir</a>
    </nav>
</aside>

<main class="admin-main">

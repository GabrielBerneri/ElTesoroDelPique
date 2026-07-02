<?php require_once BASE_PATH . '/src/views/admin/layout.php'; ?>

<div class="admin-header">
    <h1>Dashboard</h1>
    <p>Bienvenido, <?= htmlspecialchars($_SESSION['admin_nombre']) ?></p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-numero"><?= $totalProductos ?></span>
        <span class="stat-label">Productos</span>
        <a href="/admin/productos" class="stat-link">Ver todos →</a>
    </div>
    <div class="stat-card">
        <span class="stat-numero"><?= $totalPedidos ?></span>
        <span class="stat-label">Pedidos</span>
    </div>
    <div class="stat-card">
        <span class="stat-numero"><?= $totalUsuarios ?></span>
        <span class="stat-label">Usuarios</span>
    </div>
</div>

<div class="acciones-rapidas">
    <h2>Acciones rápidas</h2>
    <div class="acciones-grid">
        <a href="/admin/productos/nuevo" class="accion-card">
            <span class="accion-icono">➕</span>
            <span>Agregar producto</span>
        </a>
        <a href="/" target="_blank" class="accion-card">
            <span class="accion-icono">🌐</span>
            <span>Ver sitio</span>
        </a>
    </div>
</div>

<?php require_once BASE_PATH . '/src/views/admin/layout.php'; ?>

<div class="admin-header">
    <h1>Productos</h1>
    <a href="/admin/productos/nuevo" class="btn btn-primario">+ Agregar producto</a>
</div>

<?php if (!empty($mensaje)): ?>
    <div class="alerta alerta-ok"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="tabla-contenedor">
    <?php if (empty($productos)): ?>
        <p class="vacio">No hay productos cargados todavía.</p>
    <?php else: ?>
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= htmlspecialchars($p['categoria_nombre']) ?></td>
                    <td>$<?= number_format($p['precio'], 0, ',', '.') ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td><?= $p['activo'] ? '✅' : '❌' ?></td>
                    <td class="acciones">
                        <a href="/admin/productos/editar/<?= $p['id'] ?>" class="btn-editar">Editar</a>
                        <a href="/admin/productos/eliminar/<?= $p['id'] ?>"
                           class="btn-eliminar"
                           onclick="return confirm('¿Eliminás este producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

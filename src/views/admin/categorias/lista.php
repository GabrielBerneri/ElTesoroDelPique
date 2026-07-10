<?php require_once BASE_PATH . '/src/views/admin/layout.php'; ?>

<div class="admin-header">
    <h1>Categorías</h1>
</div>

<?php if ($exito): ?>
    <div class="alerta alerta-ok"><?= htmlspecialchars($exito) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="categorias-layout">

    <!-- FORMULARIO NUEVA CATEGORÍA -->
    <div class="formulario-admin categoria-form">
        <h2 class="form-subtitulo">Nueva categoría</h2>
        <form method="POST" action="/admin/categorias/nueva">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required
                       placeholder="Ej: Cañas">
            </div>
            <div class="campo">
                <label for="descripcion">Descripción (opcional)</label>
                <textarea id="descripcion" name="descripcion" rows="3"
                          placeholder="Breve descripción de la categoría"></textarea>
            </div>
            <button type="submit" class="btn btn-primario">+ Crear categoría</button>
        </form>
    </div>

    <!-- LISTADO -->
    <div class="tabla-contenedor categoria-tabla">
        <?php if (empty($categorias)): ?>
            <p class="vacio">No hay categorías todavía.</p>
        <?php else: ?>
            <table class="tabla-admin">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Productos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($cat['nombre']) ?></strong></td>
                        <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
                        <td><?= $cat['cantidad_productos'] ?></td>
                        <td class="acciones">
                            <?php if ($cat['cantidad_productos'] == 0): ?>
                            <a href="/admin/categorias/eliminar/<?= $cat['id'] ?>"
                               class="btn-eliminar"
                               onclick="return confirm('¿Eliminar la categoría <?= htmlspecialchars($cat['nombre']) ?>?')">
                                Eliminar
                            </a>
                            <?php else: ?>
                            <span class="badge-bloqueado" title="Tiene productos asociados">🔒 En uso</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>

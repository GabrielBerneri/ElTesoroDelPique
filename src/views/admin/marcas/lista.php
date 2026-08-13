<?php
/* Vista: Gestión de marcas (admin)
   Variables disponibles: $marcas, $siguienteOrden, $exito, $error
*/
require_once BASE_PATH . '/src/views/admin/layout.php';
?>

<div class="admin-header">
    <div>
        <h1>🏷️ Marcas</h1>
        <p>Logos que aparecen en el carrusel de la página de inicio</p>
    </div>
</div>

<?php if ($exito): ?>
    <div class="alerta alerta-ok"><?= htmlspecialchars($exito) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- Formulario para agregar marca -->
<div class="formulario-admin" style="max-width: 600px; margin-bottom: 32px;">
    <h2 class="form-subtitulo">Agregar marca</h2>
    <form method="POST" action="/admin/marcas/nueva" enctype="multipart/form-data">
        <div class="campo">
            <label for="nombre">Nombre de la marca</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Ej: Shimano">
        </div>
        <div class="campo">
            <label for="logo">Logo (JPG, PNG o WEBP — máx 5 MB)</label>
            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp" required>
        </div>
        <div class="campo">
            <label for="orden">Orden en el carrusel</label>
            <input type="number" id="orden" name="orden" value="<?= $siguienteOrden ?>" min="0" style="width: 120px;">
        </div>
        <button type="submit" class="btn btn-primario">+ Agregar marca</button>
    </form>
</div>

<!-- Listado de marcas -->
<div class="tabla-contenedor">
    <h2 class="form-subtitulo" style="margin-bottom: 16px;">Marcas cargadas (<?= count($marcas) ?>)</h2>

    <?php if (empty($marcas)): ?>
        <p class="vacio">Todavía no hay marcas. Agregá la primera arriba.</p>
    <?php else: ?>
        <div class="marcas-admin-grid">
            <?php foreach ($marcas as $marca): ?>
            <div class="marca-admin-card <?= $marca['activo'] ? '' : 'marca-inactiva' ?>">
                <div class="marca-admin-imagen">
                    <img src="<?= htmlspecialchars($marca['ruta']) ?>"
                         alt="<?= htmlspecialchars($marca['nombre']) ?>">
                </div>
                <div class="marca-admin-info">
                    <strong><?= htmlspecialchars($marca['nombre']) ?></strong>
                    <span><?= $marca['activo'] ? '✅ Visible' : '🔴 Oculta' ?></span>
                    <span style="font-size: 12px; color: #888;">Orden: <?= $marca['orden'] ?></span>
                </div>
                <div class="marca-admin-acciones">
                    <form method="POST" action="/admin/marcas/toggle/<?= $marca['id'] ?>">
                        <button type="submit" class="btn-tabla btn-tabla-secundario">
                            <?= $marca['activo'] ? 'Ocultar' : 'Mostrar' ?>
                        </button>
                    </form>
                    <form method="POST" action="/admin/marcas/eliminar/<?= $marca['id'] ?>"
                          onsubmit="return confirm('¿Eliminar la marca «<?= htmlspecialchars($marca['nombre'], ENT_QUOTES) ?>»?')">
                        <button type="submit" class="btn-tabla btn-tabla-peligro">Eliminar</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

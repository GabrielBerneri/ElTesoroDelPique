<?php require_once BASE_PATH . '/src/views/admin/layout.php'; ?>

<?php $editando = isset($producto); ?>

<div class="admin-header">
    <h1><?= $editando ? 'Editar producto' : 'Nuevo producto' ?></h1>
    <a href="/admin/productos" class="btn-volver">← Volver</a>
</div>

<form method="POST"
      action="<?= $editando ? '/admin/productos/editar/'.$producto['id'] : '/admin/productos/nuevo' ?>"
      class="formulario-admin">

    <div class="campo-grupo">
        <div class="campo">
            <label for="nombre">Nombre del producto</label>
            <input type="text" id="nombre" name="nombre" required
                   value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>">
        </div>
        <div class="campo">
            <label for="categoria_id">Categoría</label>
            <select id="categoria_id" name="categoria_id" required>
                <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                    <?= isset($producto) && $producto['categoria_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nombre']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="campo">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
    </div>

    <div class="campo-grupo">
        <div class="campo">
            <label for="precio">Precio ($)</label>
            <input type="number" id="precio" name="precio" step="0.01" min="0" required
                   value="<?= $producto['precio'] ?? '' ?>">
        </div>
        <div class="campo">
            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" min="0" required
                   value="<?= $producto['stock'] ?? 0 ?>">
        </div>
    </div>

    <div class="campo campo-check">
        <input type="checkbox" id="activo" name="activo"
               <?= !isset($producto) || $producto['activo'] ? 'checked' : '' ?>>
        <label for="activo">Producto visible en la tienda</label>
    </div>

    <div class="form-footer">
        <button type="submit" class="btn btn-primario">
            <?= $editando ? 'Guardar cambios' : 'Crear producto' ?>
        </button>
    </div>
</form>

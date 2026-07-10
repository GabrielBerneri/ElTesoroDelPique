<?php require_once BASE_PATH . '/src/views/admin/layout.php'; ?>

<?php $editando = isset($producto); ?>

<div class="admin-header">
    <h1><?= $editando ? 'Editar producto' : 'Nuevo producto' ?></h1>
    <a href="/admin/productos" class="btn-volver">← Volver</a>
</div>

<form method="POST"
      action="<?= $editando ? '/admin/productos/editar/'.$producto['id'] : '/admin/productos/nuevo' ?>"
      class="formulario-admin"
      enctype="multipart/form-data">

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

    <?php if ($editando && !empty($imagenes)): ?>
    <div class="campo">
        <label>Imágenes actuales</label>
        <div class="galeria-admin">
            <?php foreach ($imagenes as $i => $img): ?>
            <div class="galeria-admin-item">
                <img src="<?= htmlspecialchars($img['ruta']) ?>" alt="Imagen del producto">
                <?php if ($i === 0): ?><span class="galeria-badge-principal">Principal</span><?php endif; ?>
                <a href="/admin/imagenes/eliminar/<?= $img['id'] ?>"
                   class="galeria-admin-eliminar"
                   onclick="return confirm('¿Eliminar esta imagen?')">✕</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="campo">
        <label for="imagenes">
            <?= $editando ? 'Agregar más imágenes' : 'Imágenes del producto' ?>
        </label>
        <input type="file" id="imagenes" name="imagenes[]"
               accept="image/jpeg,image/png,image/webp" multiple>
        <p class="campo-ayuda">
            Podés seleccionar varias a la vez (JPG, PNG o WEBP, máx. 5 MB c/u).
            La primera imagen será la principal.
        </p>
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

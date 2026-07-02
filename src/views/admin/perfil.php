<?php require_once BASE_PATH . '/src/views/admin/layout.php'; ?>

<div class="admin-header">
    <h1>Mi perfil</h1>
</div>

<div class="formulario-admin">
    <?php if (!empty($exito)): ?>
        <div class="alerta alerta-ok">✅ <?= htmlspecialchars($exito) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alerta alerta-error">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/admin/perfil">
        <div class="campo">
            <label for="password_actual">Contraseña actual</label>
            <input type="password" id="password_actual" name="password_actual" required>
        </div>
        <div class="campo">
            <label for="password_nueva">Nueva contraseña</label>
            <input type="password" id="password_nueva" name="password_nueva" required minlength="6">
        </div>
        <div class="campo">
            <label for="password_confirmar">Confirmar nueva contraseña</label>
            <input type="password" id="password_confirmar" name="password_confirmar" required minlength="6">
        </div>
        <div class="form-footer">
            <button type="submit" class="btn btn-primario">Cambiar contraseña</button>
        </div>
    </form>
</div>

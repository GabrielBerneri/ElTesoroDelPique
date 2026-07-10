<?php require_once BASE_PATH . '/src/views/admin/layout.php'; ?>

<div class="admin-header">
    <h1>Administradores</h1>
    <p>Gestioná quién puede acceder al panel</p>
</div>

<?php if ($exito): ?>
    <div class="alerta alerta-ok"><?= htmlspecialchars($exito) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="categorias-layout">

    <!-- FORMULARIO NUEVO ADMIN -->
    <div class="formulario-admin categoria-form">
        <h2 class="form-subtitulo">Nuevo administrador</h2>
        <form method="POST" action="/admin/administradores/nuevo">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required
                       placeholder="Ej: Juan Pérez">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required
                       placeholder="juan@email.com">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required
                       minlength="6" placeholder="Mínimo 6 caracteres">
            </div>
            <button type="submit" class="btn btn-primario">+ Crear administrador</button>
        </form>
    </div>

    <!-- LISTADO -->
    <div class="tabla-contenedor categoria-tabla">
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Alta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $a): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($a['nombre']) ?></strong></td>
                    <td>
                        <?= htmlspecialchars($a['email']) ?>
                        <?php if ($a['email'] === SUPER_ADMIN_EMAIL): ?>
                            <span class="badge-principal">Principal</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="texto-mini"><?= date('d/m/Y', strtotime($a['creado_en'])) ?></span></td>
                    <td class="acciones">
                        <?php if ($a['email'] === SUPER_ADMIN_EMAIL): ?>
                            <span class="badge-bloqueado">🔒 Protegido</span>
                        <?php elseif ((int) $a['id'] === (int) $_SESSION['admin_id']): ?>
                            <span class="badge-bloqueado">Sos vos</span>
                        <?php else: ?>
                            <a href="/admin/administradores/eliminar/<?= $a['id'] ?>"
                               class="btn-eliminar"
                               onclick="return confirm('¿Eliminar a <?= htmlspecialchars($a['nombre']) ?>?')">
                                Eliminar
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

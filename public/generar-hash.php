<?php
// ARCHIVO TEMPORAL — BORRAR DESPUÉS DE USAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['p'])) {
    echo '<strong>Hash generado:</strong><br><code>' . password_hash($_POST['p'], PASSWORD_BCRYPT) . '</code>';
    exit;
}
?>
<form method="POST">
    <input type="password" name="p" placeholder="Ingresá tu contraseña">
    <button type="submit">Generar hash</button>
</form>

<?php
require_once BASE_PATH . '/src/views/admin/layout.php';

$estados = ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'];
?>

<div class="admin-header">
    <h1>Venta #<?= $pedido['id'] ?></h1>
    <a href="/admin/ventas" class="btn-volver">← Volver a ventas</a>
</div>

<div class="venta-detalle-grid">

    <!-- DATOS DEL CLIENTE -->
    <div class="formulario-admin">
        <h2 class="form-subtitulo">Datos del cliente</h2>
        <div class="dato-linea"><span>Nombre</span><strong><?= htmlspecialchars($pedido['nombre_contacto'] ?? '—') ?></strong></div>
        <div class="dato-linea"><span>Email</span><strong><?= htmlspecialchars($pedido['email_contacto']) ?></strong></div>
        <div class="dato-linea"><span>Teléfono</span><strong><?= htmlspecialchars($pedido['telefono_contacto'] ?: '—') ?></strong></div>
        <div class="dato-linea"><span>Dirección</span><strong><?= htmlspecialchars($pedido['direccion'] ?: '—') ?></strong></div>
        <div class="dato-linea"><span>Fecha</span><strong><?= date('d/m/Y H:i', strtotime($pedido['creado_en'])) ?></strong></div>
        <div class="dato-linea"><span>Referencia</span><strong><?= htmlspecialchars($pedido['referencia_externa'] ?? '—') ?></strong></div>

        <h2 class="form-subtitulo" style="margin-top:24px">Estado del pedido</h2>
        <form method="POST" action="/admin/ventas/estado/<?= $pedido['id'] ?>" class="estado-form">
            <select name="estado">
                <?php foreach ($estados as $e): ?>
                <option value="<?= $e ?>" <?= $pedido['estado'] === $e ? 'selected' : '' ?>>
                    <?= ucfirst($e) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primario">Actualizar</button>
        </form>
    </div>

    <!-- PRODUCTOS -->
    <div class="tabla-contenedor">
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalle as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['producto_nombre'] ?? 'Producto eliminado') ?></td>
                    <td><?= $d['cantidad'] ?></td>
                    <td>$<?= number_format($d['precio_unit'], 0, ',', '.') ?></td>
                    <td><strong>$<?= number_format($d['precio_unit'] * $d['cantidad'], 0, ',', '.') ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right"><strong>Total</strong></td>
                    <td><strong>$<?= number_format($pedido['total'], 0, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

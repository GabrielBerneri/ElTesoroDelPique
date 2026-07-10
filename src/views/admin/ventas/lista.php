<?php
require_once BASE_PATH . '/src/views/admin/layout.php';

$estadoLabels = [
    'pendiente' => ['Pendiente', 'estado-pendiente'],
    'pagado'    => ['Pagado',    'estado-pagado'],
    'enviado'   => ['Enviado',   'estado-enviado'],
    'entregado' => ['Entregado', 'estado-entregado'],
    'cancelado' => ['Cancelado', 'estado-cancelado'],
];
?>

<div class="admin-header">
    <h1>Ventas</h1>
    <p><?= count($pedidos) ?> pedido<?= count($pedidos) !== 1 ? 's' : '' ?> en total</p>
</div>

<?php if ($exito): ?>
    <div class="alerta alerta-ok"><?= htmlspecialchars($exito) ?></div>
<?php endif; ?>

<div class="tabla-contenedor">
    <?php if (empty($pedidos)): ?>
        <p class="vacio">Todavía no hay ventas registradas.</p>
    <?php else: ?>
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Contacto</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td>#<?= $p['id'] ?></td>
                    <td><strong><?= htmlspecialchars($p['nombre_contacto'] ?? '—') ?></strong></td>
                    <td>
                        <?= htmlspecialchars($p['email_contacto']) ?>
                        <?php if (!empty($p['telefono_contacto'])): ?>
                            <br><span class="texto-mini"><?= htmlspecialchars($p['telefono_contacto']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><strong>$<?= number_format($p['total'], 0, ',', '.') ?></strong></td>
                    <td>
                        <?php [$label, $clase] = $estadoLabels[$p['estado']] ?? [$p['estado'], '']; ?>
                        <span class="estado-badge <?= $clase ?>"><?= $label ?></span>
                    </td>
                    <td><span class="texto-mini"><?= date('d/m/Y H:i', strtotime($p['creado_en'])) ?></span></td>
                    <td class="acciones">
                        <a href="/admin/ventas/<?= $p['id'] ?>" class="btn-editar">Ver</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

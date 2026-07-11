<?php
// Helper de envío de emails usando la función mail() nativa de PHP (sin dependencias)

// A dónde llegan los avisos de nuevas órdenes (CAMBIAR por el mail del negocio si hace falta)
const EMAIL_ADMIN       = 'gabyberneri.gb@gmail.com';
const EMAIL_FROM        = 'no-reply@darksalmon-quail-593672.hostingersite.com';
const EMAIL_FROM_NOMBRE = 'El Tesoro del Pique';

/**
 * Envía un email en formato HTML. Devuelve true si mail() lo aceptó.
 */
function enviarEmail(string $para, string $asunto, string $html): bool {
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . EMAIL_FROM_NOMBRE . ' <' . EMAIL_FROM . '>',
        'Reply-To: ' . EMAIL_FROM,
    ];
    $asuntoUtf = '=?UTF-8?B?' . base64_encode($asunto) . '?=';
    return @mail($para, $asuntoUtf, $html, implode("\r\n", $headers));
}

/**
 * Devuelve el nombre legible de un método de pago.
 */
function nombreMetodoPago(string $metodo): string {
    return match ($metodo) {
        'mercadopago'   => 'MercadoPago',
        'transferencia' => 'Transferencia bancaria',
        'efectivo'      => 'Efectivo',
        default         => $metodo,
    };
}

/**
 * Arma el HTML del email de una orden.
 * $datos: referencia, nombre, email, telefono, direccion, total, metodo
 * $items: ítems del carrito (nombre, precio, cantidad)
 * $paraAdmin: true = aviso al negocio, false = confirmación al cliente
 */
function plantillaEmailPedido(array $datos, array $items, bool $paraAdmin): string {
    $dorado = '#C8960C';
    $oscuro = '#0d0d0d';

    $titulo = $paraAdmin
        ? '🛒 Nueva orden recibida'
        : '¡Gracias por tu compra!';

    $intro = $paraAdmin
        ? 'Ingresó una nueva orden en la tienda. Estos son los datos:'
        : 'Recibimos tu pedido. Te contactamos a la brevedad para coordinar el pago y el envío.';

    // Filas de productos
    $filas = '';
    foreach ($items as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $filas .= '<tr>'
            . '<td style="padding:8px 0;border-bottom:1px solid #eee;">' . htmlspecialchars($item['nombre']) . '</td>'
            . '<td style="padding:8px 0;border-bottom:1px solid #eee;text-align:center;">' . (int) $item['cantidad'] . '</td>'
            . '<td style="padding:8px 0;border-bottom:1px solid #eee;text-align:right;">$' . number_format($subtotal, 0, ',', '.') . '</td>'
            . '</tr>';
    }

    $totalFmt  = '$' . number_format($datos['total'], 0, ',', '.');
    $metodoTxt = nombreMetodoPago($datos['metodo']);

    // Botón de seguimiento (solo en el mail al cliente)
    $bloqueSeguimiento = '';
    if (!$paraAdmin) {
        $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
        if ($appUrl) {
            $urlSeguimiento = $appUrl . '/seguimiento?ref=' . urlencode($datos['referencia']);
            $bloqueSeguimiento =
                '<div style="text-align:center;margin:24px 0 4px;">'
                . '<a href="' . $urlSeguimiento . '" '
                . 'style="display:inline-block;background:' . $dorado . ';color:' . $oscuro . ';'
                . 'font-weight:bold;font-size:14px;text-decoration:none;padding:12px 24px;border-radius:8px;">'
                . 'Seguí el estado de tu pedido</a>'
                . '<p style="color:#999;font-size:12px;margin:8px 0 0;">Vas a necesitar esta referencia y tu email.</p>'
                . '</div>';
        }
    }

    // Bloque con datos del cliente (solo en el mail al negocio)
    $bloqueCliente = '';
    if ($paraAdmin) {
        $bloqueCliente = '<h3 style="color:' . $oscuro . ';font-size:15px;margin:24px 0 8px;">Datos del cliente</h3>'
            . '<p style="margin:2px 0;font-size:14px;color:#333;">Nombre: <strong>' . htmlspecialchars($datos['nombre']) . '</strong></p>'
            . '<p style="margin:2px 0;font-size:14px;color:#333;">Email: ' . htmlspecialchars($datos['email']) . '</p>'
            . '<p style="margin:2px 0;font-size:14px;color:#333;">Teléfono: ' . htmlspecialchars($datos['telefono'] ?: '—') . '</p>'
            . '<p style="margin:2px 0;font-size:14px;color:#333;">Dirección: ' . htmlspecialchars($datos['direccion'] ?: '—') . '</p>';
    }

    return '
    <div style="background:#f4f4f4;padding:24px 0;font-family:Arial,Helvetica,sans-serif;">
      <div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #e5e5e5;">
        <div style="background:' . $oscuro . ';padding:20px 28px;">
          <span style="color:' . $dorado . ';font-size:20px;font-weight:bold;">El Tesoro del Pique</span>
        </div>
        <div style="padding:28px;">
          <h2 style="color:' . $oscuro . ';font-size:20px;margin:0 0 8px;">' . $titulo . '</h2>
          <p style="color:#555;font-size:14px;line-height:1.6;margin:0 0 16px;">' . $intro . '</p>

          <p style="margin:2px 0;font-size:14px;color:#333;">Pedido: <strong>' . htmlspecialchars($datos['referencia']) . '</strong></p>
          <p style="margin:2px 0;font-size:14px;color:#333;">Método de pago: <strong>' . $metodoTxt . '</strong></p>

          ' . $bloqueCliente . '

          <h3 style="color:' . $oscuro . ';font-size:15px;margin:24px 0 8px;">Productos</h3>
          <table style="width:100%;border-collapse:collapse;font-size:14px;color:#333;">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px 0;border-bottom:2px solid ' . $dorado . ';">Producto</th>
                <th style="text-align:center;padding:8px 0;border-bottom:2px solid ' . $dorado . ';">Cant.</th>
                <th style="text-align:right;padding:8px 0;border-bottom:2px solid ' . $dorado . ';">Subtotal</th>
              </tr>
            </thead>
            <tbody>' . $filas . '</tbody>
          </table>

          <p style="text-align:right;font-size:18px;font-weight:bold;color:' . $oscuro . ';margin:20px 0 0;">
            Total: ' . $totalFmt . '
          </p>

          ' . $bloqueSeguimiento . '
        </div>
        <div style="background:#faf6ee;padding:16px 28px;text-align:center;">
          <span style="color:#999;font-size:12px;">El Tesoro del Pique — Tienda de pesca en Argentina</span>
        </div>
      </div>
    </div>';
}

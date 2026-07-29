<?php

require_once BASE_PATH . '/src/models/Carrito.php';
require_once BASE_PATH . '/src/models/Pedido.php';

class CheckoutController {

    // ── Datos de pago alternativos (CAMBIAR por los reales) ──
    const PAGO_ALIAS      = 'tesoro.pique.pesca';   // alias de la cuenta bancaria
    const PAGO_TITULAR    = 'Martín Gómez';          // nombre del titular
    const WHATSAPP_NUMERO = '5491100000000';          // formato: 54 9 + área + número, sin espacios ni +


    public static function vista(): void {
        $items = Carrito::obtener();

        if (empty($items)) {
            redirigir('/carrito');
        }

        $total        = Carrito::totalPrecio();
        $tituloPagina = 'Checkout';
        $error        = limpiarTexto($_GET['error'] ?? '');

        ob_start();
        require_once BASE_PATH . '/src/views/checkout/index.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }

    public static function procesar(PDO $bd): void {
        $items = Carrito::obtener();

        if (empty($items)) {
            redirigir('/carrito');
        }

        $nombre       = limpiarTexto($_POST['nombre']        ?? '');
        $email        = limpiarEmail($_POST['email']         ?? '');
        $telefono     = limpiarTexto($_POST['telefono']      ?? '');
        $direccion    = limpiarTexto($_POST['direccion']     ?? '');
        $localidad    = limpiarTexto($_POST['localidad']     ?? '');
        $provincia    = limpiarTexto($_POST['provincia']     ?? '');
        $codigoPostal = limpiarTexto($_POST['codigo_postal'] ?? '');
        $metodo       = limpiarTexto($_POST['metodo_pago']   ?? 'mercadopago');

        if (!$nombre || !$email) {
            redirigir('/checkout?error=datos');
        }

        // Armar ítems para MercadoPago
        $mpItems = [];
        foreach ($items as $item) {
            $mpItems[] = [
                'id'          => (string) $item['id'],
                'title'       => $item['nombre'],
                'quantity'    => (int)   $item['cantidad'],
                'unit_price'  => (float) $item['precio'],
                'currency_id' => 'ARS',
            ];
        }

        $pagador = [
            'name'  => $nombre,
            'email' => $email,
        ];
        if ($telefono) {
            $pagador['phone'] = ['number' => $telefono];
        }
        if ($direccion) {
            $pagador['address'] = [
                'street_name' => $direccion,
                'city'        => $localidad,
                'zip_code'    => $codigoPostal,
            ];
        }

        $referencia = 'ORD-' . time() . '-' . rand(100, 999);

        // Guardar el pedido como "pendiente" antes de ir a MercadoPago
        $localidadCP       = $localidad . ($codigoPostal ? ' (CP ' . $codigoPostal . ')' : '');
        $direccionCompleta = trim($direccion . ', ' . $localidadCP . ', ' . $provincia, ', ');
        try {
            $modeloPedido = new Pedido($bd);
            $modeloPedido->crear([
                'email'      => $email,
                'nombre'     => $nombre,
                'telefono'   => $telefono,
                'total'      => Carrito::totalPrecio(),
                'referencia' => $referencia,
                'direccion'  => $direccionCompleta,
            ], $items);
        } catch (Throwable $e) {
            error_log('Error al guardar pedido: ' . $e->getMessage());
            redirigir('/checkout?error=mp');
        }

        // Avisar por email (al negocio y al cliente). Si falla, no interrumpe la compra.
        try {
            require_once BASE_PATH . '/src/helpers/email.php';
            $datosEmail = [
                'referencia' => $referencia,
                'nombre'     => $nombre,
                'email'      => $email,
                'telefono'   => $telefono,
                'direccion'  => $direccionCompleta,
                'total'      => Carrito::totalPrecio(),
                'metodo'     => $metodo,
            ];
            enviarEmail(EMAIL_ADMIN, 'Nueva orden ' . $referencia . ' - El Tesoro del Pique',
                plantillaEmailPedido($datosEmail, $items, true));
            if ($email) {
                enviarEmail($email, 'Recibimos tu pedido ' . $referencia,
                    plantillaEmailPedido($datosEmail, $items, false));
            }
        } catch (Throwable $e) {
            error_log('Error al enviar email de orden: ' . $e->getMessage());
        }

        // Métodos de pago manuales: transferencia y efectivo
        if ($metodo === 'transferencia' || $metodo === 'efectivo') {
            $_SESSION['pedido_pendiente'] = [
                'referencia' => $referencia,
                'total'      => Carrito::totalPrecio(),
                'nombre'     => $nombre,
            ];
            redirigir('/checkout/' . $metodo);
        }

        try {
            require_once BASE_PATH . '/src/helpers/mercadopago.php';
            $preferencia = crearPreferenciaMercadoPago($mpItems, $pagador, $referencia);

            if (!empty($preferencia['init_point'])) {
                // Guardar referencia en sesión para verificar en el éxito
                $_SESSION['checkout_referencia'] = $referencia;
                redirigir($preferencia['init_point']);
            } else {
                $mpError = $preferencia['message'] ?? 'Sin respuesta';
                error_log('MercadoPago error: ' . $mpError);
                redirigir('/checkout?error=mp');
            }
        } catch (RuntimeException $e) {
            error_log('Checkout error: ' . $e->getMessage());
            redirigir('/checkout?error=mp');
        }
    }

    public static function exito(PDO $bd): void {
        $referencia = $_SESSION['checkout_referencia'] ?? '';

        if ($referencia) {
            $modeloPedido = new Pedido($bd);
            $modeloPedido->marcarPagadoPorReferencia($referencia);
        }

        Carrito::vaciar();
        $tituloPagina = '¡Pago aprobado!';

        ob_start();
        require_once BASE_PATH . '/src/views/checkout/exito.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }

    public static function fallo(): void {
        $tituloPagina = 'Pago rechazado';

        ob_start();
        require_once BASE_PATH . '/src/views/checkout/fallo.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }

    public static function pendiente(): void {
        $tituloPagina = 'Pago pendiente';

        ob_start();
        require_once BASE_PATH . '/src/views/checkout/pendiente.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }

    public static function transferencia(): void {
        $pendiente = $_SESSION['pedido_pendiente'] ?? null;
        if (!$pendiente) {
            redirigir('/');
        }
        Carrito::vaciar();

        $referencia   = $pendiente['referencia'];
        $total        = $pendiente['total'];
        $alias        = self::PAGO_ALIAS;
        $titular      = self::PAGO_TITULAR;

        $mensaje = "¡Hola! Hice el pedido {$referencia} por $"
                 . number_format($total, 0, ',', '.')
                 . " y voy a pagar por transferencia. Te envío el comprobante.";
        $whatsappUrl = 'https://wa.me/' . self::WHATSAPP_NUMERO . '?text=' . rawurlencode($mensaje);

        $tituloPagina = 'Transferencia';
        ob_start();
        require_once BASE_PATH . '/src/views/checkout/transferencia.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }

    public static function efectivo(): void {
        $pendiente = $_SESSION['pedido_pendiente'] ?? null;
        if (!$pendiente) {
            redirigir('/');
        }
        Carrito::vaciar();

        $referencia = $pendiente['referencia'];
        $total      = $pendiente['total'];

        $mensaje = "¡Hola! Hice el pedido {$referencia} por $"
                 . number_format($total, 0, ',', '.')
                 . " y quiero pagar en efectivo.";
        $whatsappUrl = 'https://wa.me/' . self::WHATSAPP_NUMERO . '?text=' . rawurlencode($mensaje);

        $tituloPagina = 'Pago en efectivo';
        ob_start();
        require_once BASE_PATH . '/src/views/checkout/efectivo.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }
}

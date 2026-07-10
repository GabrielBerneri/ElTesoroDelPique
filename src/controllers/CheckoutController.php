<?php

require_once BASE_PATH . '/src/models/Carrito.php';

class CheckoutController {

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

    public static function procesar(): void {
        $items = Carrito::obtener();

        if (empty($items)) {
            redirigir('/carrito');
        }

        $nombre    = limpiarTexto($_POST['nombre']    ?? '');
        $email     = limpiarEmail($_POST['email']     ?? '');
        $telefono  = limpiarTexto($_POST['telefono']  ?? '');
        $direccion = limpiarTexto($_POST['direccion'] ?? '');
        $ciudad    = limpiarTexto($_POST['ciudad']    ?? '');
        $provincia = limpiarTexto($_POST['provincia'] ?? '');

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
                'city'        => $ciudad,
            ];
        }

        $referencia = 'ORD-' . time() . '-' . rand(100, 999);

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

    public static function exito(): void {
        Carrito::vaciar();
        $referencia   = $_SESSION['checkout_referencia'] ?? '';
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
}

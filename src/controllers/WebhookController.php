<?php

require_once BASE_PATH . '/src/models/Pedido.php';

class WebhookController {

    /**
     * Recibe las notificaciones de MercadoPago cuando cambia el estado de un pago.
     * MercadoPago envía el ID del pago; nosotros re-consultamos el pago real
     * en la API para saber su estado verdadero (nunca confiamos en el cuerpo recibido).
     */
    public static function mercadopago(PDO $bd): void {
        // Detectar el ID del pago (soporta el formato nuevo "Webhooks" y el viejo "IPN")
        $tipo      = $_GET['type'] ?? $_GET['topic'] ?? null;
        $paymentId = null;

        $cuerpo = json_decode(file_get_contents('php://input'), true);
        if (is_array($cuerpo)) {
            $tipo      = $cuerpo['type'] ?? $cuerpo['topic'] ?? $tipo;
            $paymentId = $cuerpo['data']['id'] ?? null;
        }
        if (!$paymentId) {
            $paymentId = $_GET['data_id'] ?? $_GET['id'] ?? null;
        }

        // Solo procesamos notificaciones de pago
        if ($tipo !== 'payment' || !$paymentId) {
            http_response_code(200);
            echo 'ignored';
            return;
        }

        try {
            require_once BASE_PATH . '/src/helpers/mercadopago.php';
            $pago = obtenerPagoMercadoPago((string) $paymentId);
        } catch (Throwable $e) {
            error_log('Webhook MP - error al consultar pago: ' . $e->getMessage());
            http_response_code(500); // MP reintentará más tarde
            echo 'error';
            return;
        }

        $referencia = $pago['external_reference'] ?? '';
        $status     = $pago['status'] ?? '';

        if (!$referencia) {
            http_response_code(200);
            echo 'sin-referencia';
            return;
        }

        $estado = match ($status) {
            'approved'                                        => 'pagado',
            'rejected', 'cancelled', 'refunded', 'charged_back' => 'cancelado',
            default                                           => 'pendiente',
        };

        try {
            $modelo = new Pedido($bd);
            $modelo->registrarPagoPorReferencia($referencia, $estado, (string) $paymentId);
        } catch (Throwable $e) {
            error_log('Webhook MP - error al actualizar pedido: ' . $e->getMessage());
            http_response_code(500);
            echo 'error';
            return;
        }

        http_response_code(200);
        echo 'ok';
    }
}

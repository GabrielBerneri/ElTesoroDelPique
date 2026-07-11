<?php

/**
 * Crea una preferencia de pago en MercadoPago y devuelve la respuesta.
 * Usa cURL directamente, sin SDK ni Composer.
 */
function crearPreferenciaMercadoPago(array $items, array $pagador, string $referenciaExterna): array {
    $accessToken = $_ENV['MP_ACCESS_TOKEN'] ?? '';

    if (!$accessToken) {
        throw new RuntimeException('MP_ACCESS_TOKEN no está configurado en .env');
    }

    $appUrl = rtrim($_ENV['APP_URL'] ?? ('https://' . $_SERVER['HTTP_HOST']), '/');

    $payload = [
        'items'               => $items,
        'payer'               => $pagador,
        'back_urls'           => [
            'success' => $appUrl . '/checkout/exito',
            'failure' => $appUrl . '/checkout/fallo',
            'pending' => $appUrl . '/checkout/pendiente',
        ],
        'auto_return'         => 'approved',
        'external_reference'  => $referenciaExterna,
        'statement_descriptor'=> 'El Tesoro del Pique',
        'notification_url'    => $appUrl . '/webhook/mercadopago',
    ];

    $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
        ],
        CURLOPT_TIMEOUT        => 15,
    ]);

    $respuesta = curl_exec($ch);
    $errorCurl = curl_error($ch);
    curl_close($ch);

    if ($errorCurl) {
        throw new RuntimeException('Error de conexión con MercadoPago: ' . $errorCurl);
    }

    $datos = json_decode($respuesta, true);

    if (!is_array($datos)) {
        throw new RuntimeException('Respuesta inválida de MercadoPago');
    }

    return $datos;
}

/**
 * Consulta un pago en la API de MercadoPago por su ID.
 * Es la fuente de verdad: nunca confiamos en el cuerpo del webhook,
 * siempre re-consultamos el pago real con nuestro access token.
 */
function obtenerPagoMercadoPago(string $paymentId): array {
    $accessToken = $_ENV['MP_ACCESS_TOKEN'] ?? '';

    if (!$accessToken) {
        throw new RuntimeException('MP_ACCESS_TOKEN no está configurado en .env');
    }

    $ch = curl_init('https://api.mercadopago.com/v1/payments/' . urlencode($paymentId));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
        CURLOPT_TIMEOUT        => 15,
    ]);

    $respuesta = curl_exec($ch);
    $errorCurl = curl_error($ch);
    curl_close($ch);

    if ($errorCurl) {
        throw new RuntimeException('Error de conexión con MercadoPago: ' . $errorCurl);
    }

    $datos = json_decode($respuesta, true);

    if (!is_array($datos)) {
        throw new RuntimeException('Respuesta inválida de MercadoPago');
    }

    return $datos;
}

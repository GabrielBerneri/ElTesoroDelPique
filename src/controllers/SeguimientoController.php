<?php

require_once BASE_PATH . '/src/models/Pedido.php';

class SeguimientoController {

    public static function vista(PDO $bd): void {
        $pedido     = null;
        $detalle    = [];
        $error      = null;
        $buscado    = false;

        // El número de orden puede venir por link del mail (?ref=) para prellenar el campo
        $refPrefill = limpiarTexto($_POST['referencia'] ?? $_GET['ref'] ?? '');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $buscado    = true;
            $referencia = limpiarTexto($_POST['referencia'] ?? '');
            $email      = limpiarEmail($_POST['email'] ?? '');

            if ($referencia === '' || !$email) {
                $error = 'Completá el número de orden y el email de la compra.';
            } else {
                $modelo = new Pedido($bd);
                $pedido = $modelo->obtenerPorReferenciaYEmail($referencia, $email);

                if ($pedido) {
                    $detalle = $modelo->obtenerDetalle((int) $pedido['id']);
                } else {
                    $error = 'No encontramos un pedido con esos datos. Revisá el número de orden y el email.';
                }
            }
        }

        $tituloPagina = 'Seguimiento de pedido';

        ob_start();
        require_once BASE_PATH . '/src/views/paginas/seguimiento.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }
}

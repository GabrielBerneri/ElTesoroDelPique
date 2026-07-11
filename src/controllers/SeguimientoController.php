<?php

require_once BASE_PATH . '/src/models/Pedido.php';

class SeguimientoController {

    public static function vista(PDO $bd): void {
        $pedido     = null;
        $detalle    = [];
        $error      = null;
        $buscado    = false;
        $refPrefill = limpiarTexto($_GET['ref'] ?? '');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $buscado    = true;
            $referencia = limpiarTexto($_POST['referencia'] ?? '');
            $email      = limpiarEmail($_POST['email'] ?? '');
            $refPrefill = $referencia;

            if ($referencia === '' || !$email) {
                $error = 'Completá la referencia del pedido y tu email.';
            } else {
                $modelo = new Pedido($bd);
                $pedido = $modelo->obtenerPorReferenciaYEmail($referencia, $email);

                if ($pedido) {
                    $detalle = $modelo->obtenerDetalle((int) $pedido['id']);
                } else {
                    $error = 'No encontramos un pedido con esos datos. Revisá la referencia y el email.';
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

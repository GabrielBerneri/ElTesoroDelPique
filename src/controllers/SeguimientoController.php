<?php

require_once BASE_PATH . '/src/models/Pedido.php';

class SeguimientoController {

    public static function vista(PDO $bd): void {
        $pedido     = null;
        $detalle    = [];
        $error      = null;
        $buscado    = false;

        // Acepta el número de orden por el formulario (POST) o por link del mail (?ref=)
        $referencia = limpiarTexto($_POST['referencia'] ?? $_GET['ref'] ?? '');
        $refPrefill = $referencia;

        if ($referencia !== '') {
            $buscado = true;
            $modelo  = new Pedido($bd);
            $pedido  = $modelo->obtenerPorReferencia($referencia);

            if ($pedido) {
                $detalle = $modelo->obtenerDetalle((int) $pedido['id']);
            } else {
                $error = 'No encontramos un pedido con ese número. Revisá que esté bien escrito.';
            }
        }

        $tituloPagina = 'Seguimiento de pedido';

        ob_start();
        require_once BASE_PATH . '/src/views/paginas/seguimiento.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }
}

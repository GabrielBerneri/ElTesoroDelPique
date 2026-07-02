<?php

require_once BASE_PATH . '/src/models/Carrito.php';
require_once BASE_PATH . '/src/models/Producto.php';

class CarritoController {

    public static function vista(PDO $bd): void {
        $tituloPagina = 'Carrito';
        $items        = Carrito::obtener();
        $total        = Carrito::totalPrecio();

        ob_start();
        require_once BASE_PATH . '/src/views/carrito/index.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }

    public static function agregar(PDO $bd): void {
        header('Content-Type: application/json');

        $id       = limpiarEntero($_POST['id'] ?? 0);
        $cantidad = limpiarEntero($_POST['cantidad'] ?? 1);

        if ($id <= 0 || $cantidad <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Datos inválidos']);
            return;
        }

        $modeloProducto = new Producto($bd);
        $producto = $modeloProducto->obtenerPorId($id);

        if (!$producto) {
            echo json_encode(['ok' => false, 'mensaje' => 'Producto no encontrado']);
            return;
        }

        if ($producto['stock'] <= 0) {
            echo json_encode(['ok' => false, 'mensaje' => 'Sin stock disponible']);
            return;
        }

        Carrito::agregar(
            (int) $producto['id'],
            $producto['nombre'],
            (float) $producto['precio'],
            $producto['imagen_principal'],
            $producto['slug'],
            $cantidad
        );

        echo json_encode([
            'ok'          => true,
            'mensaje'     => '¡Agregado al carrito!',
            'total_items' => Carrito::totalItems(),
        ]);
    }

    public static function actualizar(): void {
        header('Content-Type: application/json');

        $id       = limpiarEntero($_POST['id'] ?? 0);
        $cantidad = limpiarEntero($_POST['cantidad'] ?? 0);

        Carrito::actualizar($id, $cantidad);

        echo json_encode([
            'ok'           => true,
            'total_items'  => Carrito::totalItems(),
            'total_precio' => Carrito::totalPrecio(),
        ]);
    }

    public static function eliminar(): void {
        header('Content-Type: application/json');

        $id = limpiarEntero($_POST['id'] ?? 0);
        Carrito::eliminar($id);

        echo json_encode([
            'ok'           => true,
            'total_items'  => Carrito::totalItems(),
            'total_precio' => Carrito::totalPrecio(),
        ]);
    }

    public static function vaciar(): void {
        header('Content-Type: application/json');
        Carrito::vaciar();
        echo json_encode(['ok' => true, 'total_items' => 0]);
    }
}

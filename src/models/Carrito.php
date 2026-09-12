<?php

class Carrito {

    public static function agregar(int $id, string $nombre, float $precio, ?float $precioEfectivo, ?string $imagen, string $slug, int $cantidad = 1): void {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$id] = [
                'id'              => $id,
                'nombre'          => $nombre,
                'precio'          => $precio,
                'precio_efectivo' => $precioEfectivo,
                'imagen'          => $imagen,
                'slug'            => $slug,
                'cantidad'        => $cantidad,
            ];
        }
    }

    public static function actualizar(int $id, int $cantidad): void {
        if ($cantidad <= 0) {
            self::eliminar($id);
            return;
        }
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] = $cantidad;
        }
    }

    public static function eliminar(int $id): void {
        unset($_SESSION['carrito'][$id]);
    }

    public static function vaciar(): void {
        $_SESSION['carrito'] = [];
    }

    public static function obtener(): array {
        return $_SESSION['carrito'] ?? [];
    }

    public static function totalItems(): int {
        $total = 0;
        foreach (self::obtener() as $item) {
            $total += $item['cantidad'];
        }
        return $total;
    }

    public static function totalPrecio(string $metodo = 'mercadopago'): float {
        $total = 0.0;
        $esEfectivo = in_array($metodo, ['efectivo', 'transferencia'], true);
        foreach (self::obtener() as $item) {
            if ($esEfectivo && isset($item['precio_efectivo']) && $item['precio_efectivo'] !== null) {
                $precio = (float) $item['precio_efectivo'];
            } else {
                $precio = (float) $item['precio'];
            }
            $total += $precio * $item['cantidad'];
        }
        return $total;
    }
}

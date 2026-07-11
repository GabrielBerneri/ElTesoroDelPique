<?php

class Pedido {

    private PDO $bd;

    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    /**
     * Crea un pedido con su detalle dentro de una transacción.
     * $items: array del carrito (id, nombre, precio, cantidad).
     * Devuelve el ID del pedido creado.
     */
    public function crear(array $datos, array $items): int {
        $this->bd->beginTransaction();
        try {
            $stmt = $this->bd->prepare(
                'INSERT INTO pedidos
                    (email_contacto, nombre_contacto, telefono_contacto, total, estado, referencia_externa, direccion)
                 VALUES
                    (:email, :nombre, :telefono, :total, :estado, :referencia, :direccion)'
            );
            $stmt->execute([
                ':email'      => $datos['email'],
                ':nombre'     => $datos['nombre'],
                ':telefono'   => $datos['telefono'],
                ':total'      => $datos['total'],
                ':estado'     => 'pendiente',
                ':referencia' => $datos['referencia'],
                ':direccion'  => $datos['direccion'],
            ]);

            $pedidoId = (int) $this->bd->lastInsertId();

            $stmtDetalle = $this->bd->prepare(
                'INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unit)
                 VALUES (?, ?, ?, ?)'
            );
            foreach ($items as $item) {
                $stmtDetalle->execute([
                    $pedidoId,
                    $item['id'],
                    $item['cantidad'],
                    $item['precio'],
                ]);
            }

            $this->bd->commit();
            return $pedidoId;
        } catch (Throwable $e) {
            $this->bd->rollBack();
            throw $e;
        }
    }

    public function obtenerTodos(): array {
        return $this->bd->query(
            'SELECT * FROM pedidos ORDER BY creado_en DESC'
        )->fetchAll();
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->bd->prepare('SELECT * FROM pedidos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function obtenerDetalle(int $pedidoId): array {
        $stmt = $this->bd->prepare(
            'SELECT d.*, p.nombre AS producto_nombre, p.slug AS producto_slug
             FROM detalle_pedidos d
             LEFT JOIN productos p ON d.producto_id = p.id
             WHERE d.pedido_id = :id'
        );
        $stmt->execute([':id' => $pedidoId]);
        return $stmt->fetchAll();
    }

    public function actualizarEstado(int $id, string $estado): void {
        $permitidos = ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'];
        if (!in_array($estado, $permitidos, true)) {
            return;
        }
        $this->bd->prepare('UPDATE pedidos SET estado = :estado WHERE id = :id')
            ->execute([':estado' => $estado, ':id' => $id]);
    }

    public function marcarPagadoPorReferencia(string $referencia): void {
        $this->bd->prepare(
            "UPDATE pedidos SET estado = 'pagado'
             WHERE referencia_externa = :ref AND estado = 'pendiente'"
        )->execute([':ref' => $referencia]);
    }

    /**
     * Actualiza el estado y el ID de pago de MercadoPago según la referencia.
     * Usado por el webhook. No pisa pedidos ya entregados/enviados.
     */
    public function registrarPagoPorReferencia(string $referencia, string $estado, ?string $mpPaymentId): void {
        $permitidos = ['pendiente', 'pagado', 'cancelado'];
        if (!in_array($estado, $permitidos, true)) {
            return;
        }
        $this->bd->prepare(
            "UPDATE pedidos
             SET estado = :estado, mp_payment_id = :mp
             WHERE referencia_externa = :ref
               AND estado NOT IN ('enviado', 'entregado')"
        )->execute([
            ':estado' => $estado,
            ':mp'     => $mpPaymentId,
            ':ref'    => $referencia,
        ]);
    }

    public function contarPorEstado(string $estado): int {
        $stmt = $this->bd->prepare('SELECT COUNT(*) FROM pedidos WHERE estado = :estado');
        $stmt->execute([':estado' => $estado]);
        return (int) $stmt->fetchColumn();
    }

    public function totalVendido(): float {
        return (float) $this->bd->query(
            "SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE estado IN ('pagado','enviado','entregado')"
        )->fetchColumn();
    }
}

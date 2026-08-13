<?php

class Marca {

    public function __construct(private PDO $bd) {}

    public function obtenerActivas(): array {
        return $this->bd->query(
            'SELECT * FROM marcas WHERE activo = 1 ORDER BY orden, id'
        )->fetchAll();
    }

    public function obtenerTodas(): array {
        return $this->bd->query(
            'SELECT * FROM marcas ORDER BY orden, id'
        )->fetchAll();
    }

    public function crear(string $nombre, string $ruta, int $orden): int {
        $stmt = $this->bd->prepare(
            'INSERT INTO marcas (nombre, ruta, orden) VALUES (:nombre, :ruta, :orden)'
        );
        $stmt->execute([':nombre' => $nombre, ':ruta' => $ruta, ':orden' => $orden]);
        return (int) $this->bd->lastInsertId();
    }

    public function eliminar(int $id): ?string {
        $stmt = $this->bd->prepare('SELECT ruta FROM marcas WHERE id = ?');
        $stmt->execute([$id]);
        $marca = $stmt->fetch();
        if (!$marca) {
            return null;
        }
        $this->bd->prepare('DELETE FROM marcas WHERE id = ?')->execute([$id]);
        return $marca['ruta'];
    }

    public function toggleActivo(int $id): void {
        $this->bd->prepare('UPDATE marcas SET activo = NOT activo WHERE id = ?')->execute([$id]);
    }

    public function siguienteOrden(): int {
        return (int) $this->bd->query('SELECT COALESCE(MAX(orden), -1) + 1 FROM marcas')->fetchColumn();
    }
}

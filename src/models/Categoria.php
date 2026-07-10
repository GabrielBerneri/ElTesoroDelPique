<?php

class Categoria {

    private PDO $bd;

    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    public function obtenerTodas(): array {
        $consulta = $this->bd->query('SELECT * FROM categorias ORDER BY nombre');
        return $consulta->fetchAll();
    }

    /** Categorías con la cantidad de productos que tiene cada una (para el admin). */
    public function obtenerTodasConConteo(): array {
        return $this->bd->query(
            'SELECT c.*, COUNT(p.id) AS cantidad_productos
             FROM categorias c
             LEFT JOIN productos p ON p.categoria_id = c.id
             GROUP BY c.id
             ORDER BY c.nombre'
        )->fetchAll();
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->bd->prepare('SELECT * FROM categorias WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear(string $nombre, string $slug, string $descripcion): void {
        $stmt = $this->bd->prepare(
            'INSERT INTO categorias (nombre, slug, descripcion) VALUES (:nombre, :slug, :descripcion)'
        );
        $stmt->execute([
            ':nombre'      => $nombre,
            ':slug'        => $slug,
            ':descripcion' => $descripcion,
        ]);
    }

    public function contarProductos(int $id): int {
        $stmt = $this->bd->prepare('SELECT COUNT(*) FROM productos WHERE categoria_id = :id');
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn();
    }

    public function eliminar(int $id): void {
        $this->bd->prepare('DELETE FROM categorias WHERE id = :id')->execute([':id' => $id]);
    }

    public function existeSlug(string $slug): bool {
        $stmt = $this->bd->prepare('SELECT 1 FROM categorias WHERE slug = :slug');
        $stmt->execute([':slug' => $slug]);
        return (bool) $stmt->fetchColumn();
    }
}

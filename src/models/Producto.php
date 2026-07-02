<?php

class Producto {

    private PDO $bd;

    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    public function obtenerDestacados(int $limite = 8): array {
        $consulta = $this->bd->prepare(
            'SELECT p.*, c.nombre AS categoria_nombre
             FROM productos p
             JOIN categorias c ON p.categoria_id = c.id
             WHERE p.activo = 1
             ORDER BY p.creado_en DESC
             LIMIT :limite'
        );
        $consulta->bindValue(':limite', $limite, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public function obtenerTodos(): array {
        return $this->bd->query(
            'SELECT p.*, c.nombre AS categoria_nombre
             FROM productos p
             JOIN categorias c ON p.categoria_id = c.id
             ORDER BY p.creado_en DESC'
        )->fetchAll();
    }

    public function obtenerPorId(int $id): array|false {
        $consulta = $this->bd->prepare(
            'SELECT * FROM productos WHERE id = :id'
        );
        $consulta->execute([':id' => $id]);
        return $consulta->fetch();
    }

    public function obtenerPorSlug(string $slug): array|false {
        $consulta = $this->bd->prepare(
            'SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug
             FROM productos p
             JOIN categorias c ON p.categoria_id = c.id
             WHERE p.slug = :slug AND p.activo = 1'
        );
        $consulta->execute([':slug' => $slug]);
        return $consulta->fetch();
    }

    public function obtenerPorCategoria(string $slug): array {
        $consulta = $this->bd->prepare(
            'SELECT p.*, c.nombre AS categoria_nombre
             FROM productos p
             JOIN categorias c ON p.categoria_id = c.id
             WHERE c.slug = :slug AND p.activo = 1
             ORDER BY p.nombre'
        );
        $consulta->bindValue(':slug', $slug);
        $consulta->execute();
        return $consulta->fetchAll();
    }
}

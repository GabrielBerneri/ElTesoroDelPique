<?php

class Usuario {

    private PDO $bd;

    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }

    public function obtenerAdmins(): array {
        return $this->bd->query(
            'SELECT id, nombre, email, creado_en FROM usuarios WHERE es_admin = 1 ORDER BY creado_en'
        )->fetchAll();
    }

    public function existeEmail(string $email): bool {
        $stmt = $this->bd->prepare('SELECT 1 FROM usuarios WHERE email = :email');
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetchColumn();
    }

    public function crearAdmin(string $nombre, string $email, string $password): void {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->bd->prepare(
            'INSERT INTO usuarios (nombre, email, password, es_admin)
             VALUES (:nombre, :email, :password, 1)'
        );
        $stmt->execute([
            ':nombre'   => $nombre,
            ':email'    => $email,
            ':password' => $hash,
        ]);
    }

    public function obtenerPorId(int $id): array|false {
        $stmt = $this->bd->prepare('SELECT id, nombre, email FROM usuarios WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function eliminar(int $id): void {
        $this->bd->prepare('DELETE FROM usuarios WHERE id = :id')->execute([':id' => $id]);
    }
}

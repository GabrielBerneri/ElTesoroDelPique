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
}

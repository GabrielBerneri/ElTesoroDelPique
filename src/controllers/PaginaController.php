<?php

require_once BASE_PATH . '/src/models/Categoria.php';

class PaginaController {

    public static function categorias(PDO $bd): void {
        $modeloCategoria = new Categoria($bd);
        $categorias      = $modeloCategoria->obtenerTodasConConteo();

        $tituloPagina = 'Categorías';

        ob_start();
        require_once BASE_PATH . '/src/views/paginas/categorias.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }

    public static function nosotros(): void {
        $tituloPagina = 'Nosotros';

        ob_start();
        require_once BASE_PATH . '/src/views/paginas/nosotros.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }

    public static function contacto(): void {
        $tituloPagina = 'Contacto';

        ob_start();
        require_once BASE_PATH . '/src/views/paginas/contacto.php';
        $contenido = ob_get_clean();

        require_once BASE_PATH . '/src/views/layouts/base.php';
    }
}

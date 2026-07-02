<?php

require_once BASE_PATH . '/src/helpers/auth.php';
require_once BASE_PATH . '/src/helpers/sanitize.php';
require_once BASE_PATH . '/src/models/Producto.php';
require_once BASE_PATH . '/src/models/Categoria.php';

class AdminController {

    public static function loginVista(): void {
        $tituloPagina = 'Ingresar';
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);
        require_once BASE_PATH . '/src/views/admin/login.php';
    }

    public static function loginProcesar(PDO $bd): void {
        iniciarSesion();
        $email    = limpiarEmail($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $consulta = $bd->prepare('SELECT * FROM usuarios WHERE email = :email AND es_admin = 1');
        $consulta->execute([':email' => $email]);
        $usuario = $consulta->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            guardarSesionAdmin($usuario);
            redirigir('/admin');
        } else {
            $_SESSION['login_error'] = 'Email o contraseña incorrectos';
            redirigir('/admin/login');
        }
    }

    public static function logout(): void {
        cerrarSesion();
        redirigir('/admin/login');
    }

    public static function dashboard(PDO $bd): void {
        requiereAdmin();

        $totalProductos  = $bd->query('SELECT COUNT(*) FROM productos')->fetchColumn();
        $totalPedidos    = $bd->query('SELECT COUNT(*) FROM pedidos')->fetchColumn();
        $totalUsuarios   = $bd->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();

        $tituloPagina = 'Panel de administración';
        require_once BASE_PATH . '/src/views/admin/dashboard.php';
    }

    public static function productos(PDO $bd): void {
        requiereAdmin();
        $modelo   = new Producto($bd);
        $productos = $modelo->obtenerTodos();

        $tituloPagina = 'Productos';
        require_once BASE_PATH . '/src/views/admin/productos/lista.php';
    }

    public static function productoNuevoVista(PDO $bd): void {
        requiereAdmin();
        $modeloCategoria = new Categoria($bd);
        $categorias      = $modeloCategoria->obtenerTodas();

        $tituloPagina = 'Nuevo producto';
        require_once BASE_PATH . '/src/views/admin/productos/formulario.php';
    }

    public static function productoNuevoProcesar(PDO $bd): void {
        requiereAdmin();
        $datos = self::extraerDatosFormulario();

        $consulta = $bd->prepare(
            'INSERT INTO productos (categoria_id, nombre, slug, descripcion, precio, stock, activo)
             VALUES (:categoria_id, :nombre, :slug, :descripcion, :precio, :stock, :activo)'
        );
        $consulta->execute($datos);
        redirigir('/admin/productos');
    }

    public static function productoEditarVista(PDO $bd, string $uri): void {
        requiereAdmin();
        $id      = (int) basename($uri);
        $modelo  = new Producto($bd);
        $producto = $modelo->obtenerPorId($id);

        if (!$producto) {
            redirigir('/admin/productos');
        }

        $modeloCategoria = new Categoria($bd);
        $categorias      = $modeloCategoria->obtenerTodas();

        $tituloPagina = 'Editar producto';
        require_once BASE_PATH . '/src/views/admin/productos/formulario.php';
    }

    public static function productoEditarProcesar(PDO $bd, string $uri): void {
        requiereAdmin();
        $id    = (int) basename($uri);
        $datos = self::extraerDatosFormulario();
        $datos[':id'] = $id;

        $consulta = $bd->prepare(
            'UPDATE productos
             SET categoria_id=:categoria_id, nombre=:nombre, slug=:slug,
                 descripcion=:descripcion, precio=:precio, stock=:stock, activo=:activo
             WHERE id=:id'
        );
        $consulta->execute($datos);
        redirigir('/admin/productos');
    }

    public static function productoEliminar(PDO $bd, string $uri): void {
        requiereAdmin();
        $id = (int) basename($uri);
        $bd->prepare('DELETE FROM productos WHERE id = :id')->execute([':id' => $id]);
        redirigir('/admin/productos');
    }

    private static function extraerDatosFormulario(): array {
        $nombre = limpiarTexto($_POST['nombre'] ?? '');
        return [
            ':categoria_id' => limpiarEntero($_POST['categoria_id'] ?? 0),
            ':nombre'       => $nombre,
            ':slug'         => crearSlug($nombre),
            ':descripcion'  => limpiarTexto($_POST['descripcion'] ?? ''),
            ':precio'       => (float) ($_POST['precio'] ?? 0),
            ':stock'        => limpiarEntero($_POST['stock'] ?? 0),
            ':activo'       => isset($_POST['activo']) ? 1 : 0,
        ];
    }
}

function crearSlug(string $texto): string {
    $texto = mb_strtolower($texto, 'UTF-8');
    $texto = str_replace(['á','é','í','ó','ú','ñ','ü'], ['a','e','i','o','u','n','u'], $texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    return trim($texto, '-');
}

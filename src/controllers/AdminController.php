<?php

require_once BASE_PATH . '/src/helpers/auth.php';
require_once BASE_PATH . '/src/helpers/sanitize.php';
require_once BASE_PATH . '/src/helpers/imagenes.php';
require_once BASE_PATH . '/src/models/Producto.php';
require_once BASE_PATH . '/src/models/Categoria.php';
require_once BASE_PATH . '/src/models/Pedido.php';
require_once BASE_PATH . '/src/models/Usuario.php';

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

    public static function probarEmail(): void {
        requiereSuperAdmin();
        require_once BASE_PATH . '/src/helpers/email.php';

        $destino = EMAIL_ADMIN;
        $html    = '<p>Este es un email de prueba de El Tesoro del Pique. Si lo recibís, el envío funciona.</p>';
        $ok      = enviarEmail($destino, 'Prueba de email - El Tesoro del Pique', $html);

        header('Content-Type: text/html; charset=UTF-8');
        echo '<div style="font-family:sans-serif;max-width:520px;margin:60px auto;padding:24px;border:1px solid #ddd;border-radius:12px;">';
        echo '<h2>Prueba de envío de email</h2>';
        echo '<p><strong>Destino:</strong> ' . htmlspecialchars($destino) . '</p>';
        echo '<p><strong>Remitente (From):</strong> ' . htmlspecialchars(EMAIL_FROM) . '</p>';
        echo '<p><strong>mail() devolvió:</strong> '
            . ($ok ? '<span style="color:green">TRUE ✅ (el servidor aceptó el mensaje)</span>'
                   : '<span style="color:red">FALSE ❌ (el servidor rechazó el envío)</span>')
            . '</p>';
        echo '<hr>';
        if ($ok) {
            echo '<p>El servidor aceptó el mensaje. Si no te llega, revisá la carpeta de <strong>spam</strong>. '
               . 'Si tampoco está ahí, el problema es de entrega (hace falta configurar SMTP o un dominio propio).</p>';
        } else {
            echo '<p>El servidor rechazó el envío: la función mail() está deshabilitada o restringida en el hosting. '
               . 'Hay que usar SMTP.</p>';
        }
        echo '<p><a href="/admin">← Volver al panel</a></p>';
        echo '</div>';
    }

    public static function dashboard(PDO $bd): void {
        requiereAdmin();

        $totalProductos  = $bd->query('SELECT COUNT(*) FROM productos')->fetchColumn();
        $totalPedidos    = $bd->query('SELECT COUNT(*) FROM pedidos')->fetchColumn();
        $totalUsuarios   = $bd->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();

        $modeloPedido    = new Pedido($bd);
        $totalVendido    = $modeloPedido->totalVendido();
        $pedidosPendientes = $modeloPedido->contarPorEstado('pendiente');

        $tituloPagina = 'Panel de administración';
        require_once BASE_PATH . '/src/views/admin/dashboard.php';
    }

    // ── CATEGORÍAS ───────────────────────────────────────

    public static function categorias(PDO $bd): void {
        requiereAdmin();
        $modelo     = new Categoria($bd);
        $categorias = $modelo->obtenerTodasConConteo();

        $exito = $_SESSION['categoria_exito'] ?? null;
        $error = $_SESSION['categoria_error'] ?? null;
        unset($_SESSION['categoria_exito'], $_SESSION['categoria_error']);

        $tituloPagina = 'Categorías';
        require_once BASE_PATH . '/src/views/admin/categorias/lista.php';
    }

    public static function categoriaNuevaProcesar(PDO $bd): void {
        requiereAdmin();
        $modelo = new Categoria($bd);

        $nombre      = limpiarTexto($_POST['nombre'] ?? '');
        $descripcion = limpiarTexto($_POST['descripcion'] ?? '');

        if ($nombre === '') {
            $_SESSION['categoria_error'] = 'El nombre no puede estar vacío.';
            redirigir('/admin/categorias');
        }

        $slug = crearSlug($nombre);
        if ($modelo->existeSlug($slug)) {
            $_SESSION['categoria_error'] = 'Ya existe una categoría con ese nombre.';
            redirigir('/admin/categorias');
        }

        $modelo->crear($nombre, $slug, $descripcion);
        $_SESSION['categoria_exito'] = 'Categoría creada correctamente.';
        redirigir('/admin/categorias');
    }

    public static function categoriaEliminar(PDO $bd, string $uri): void {
        requiereAdmin();
        $id     = (int) basename($uri);
        $modelo = new Categoria($bd);

        if ($modelo->contarProductos($id) > 0) {
            $_SESSION['categoria_error'] = 'No se puede borrar: la categoría tiene productos asociados.';
        } else {
            $modelo->eliminar($id);
            $_SESSION['categoria_exito'] = 'Categoría eliminada.';
        }
        redirigir('/admin/categorias');
    }

    // ── VENTAS ───────────────────────────────────────────

    public static function ventas(PDO $bd): void {
        requiereAdmin();
        $modelo  = new Pedido($bd);
        $pedidos = $modelo->obtenerTodos();

        $exito = $_SESSION['venta_exito'] ?? null;
        unset($_SESSION['venta_exito']);

        $tituloPagina = 'Ventas';
        require_once BASE_PATH . '/src/views/admin/ventas/lista.php';
    }

    public static function ventaDetalle(PDO $bd, string $uri): void {
        requiereAdmin();
        $id     = (int) basename($uri);
        $modelo = new Pedido($bd);
        $pedido = $modelo->obtenerPorId($id);

        if (!$pedido) {
            redirigir('/admin/ventas');
        }

        $detalle = $modelo->obtenerDetalle($id);

        $tituloPagina = 'Venta #' . $id;
        require_once BASE_PATH . '/src/views/admin/ventas/detalle.php';
    }

    public static function ventaEstadoProcesar(PDO $bd, string $uri): void {
        requiereAdmin();
        $id     = (int) basename($uri); // /admin/ventas/estado/{id}
        $estado = limpiarTexto($_POST['estado'] ?? '');

        $modelo = new Pedido($bd);
        $modelo->actualizarEstado($id, $estado);

        $_SESSION['venta_exito'] = 'Estado actualizado.';
        redirigir('/admin/ventas/' . $id);
    }

    // ── ADMINISTRADORES (solo super-admin) ───────────────

    public static function administradores(PDO $bd): void {
        requiereSuperAdmin();
        $modelo = new Usuario($bd);
        $admins = $modelo->obtenerAdmins();

        $exito = $_SESSION['admin_exito'] ?? null;
        $error = $_SESSION['admin_error'] ?? null;
        unset($_SESSION['admin_exito'], $_SESSION['admin_error']);

        $tituloPagina = 'Administradores';
        require_once BASE_PATH . '/src/views/admin/administradores/lista.php';
    }

    public static function administradorNuevoProcesar(PDO $bd): void {
        requiereSuperAdmin();
        $modelo = new Usuario($bd);

        $nombre   = limpiarTexto($_POST['nombre'] ?? '');
        $email    = limpiarEmail($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nombre === '' || !$email) {
            $_SESSION['admin_error'] = 'Completá nombre y un email válido.';
            redirigir('/admin/administradores');
        }
        if (strlen($password) < 6) {
            $_SESSION['admin_error'] = 'La contraseña debe tener al menos 6 caracteres.';
            redirigir('/admin/administradores');
        }
        if ($modelo->existeEmail($email)) {
            $_SESSION['admin_error'] = 'Ya existe un usuario con ese email.';
            redirigir('/admin/administradores');
        }

        $modelo->crearAdmin($nombre, $email, $password);
        $_SESSION['admin_exito'] = 'Administrador creado correctamente.';
        redirigir('/admin/administradores');
    }

    public static function administradorEliminar(PDO $bd, string $uri): void {
        requiereSuperAdmin();
        $id     = (int) basename($uri);
        $modelo = new Usuario($bd);
        $usuario = $modelo->obtenerPorId($id);

        if (!$usuario) {
            redirigir('/admin/administradores');
        }
        // No permitir borrar al super-admin ni a uno mismo
        if ($usuario['email'] === SUPER_ADMIN_EMAIL) {
            $_SESSION['admin_error'] = 'No se puede eliminar al administrador principal.';
        } elseif ((int) $usuario['id'] === (int) $_SESSION['admin_id']) {
            $_SESSION['admin_error'] = 'No podés eliminar tu propia cuenta.';
        } else {
            $modelo->eliminar($id);
            $_SESSION['admin_exito'] = 'Administrador eliminado.';
        }
        redirigir('/admin/administradores');
    }

    public static function productos(PDO $bd): void {
        requiereAdmin();
        $modelo   = new Producto($bd);
        $productos = $modelo->obtenerTodos();

        $mensaje = $_SESSION['producto_msg'] ?? null;
        unset($_SESSION['producto_msg']);

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
        $productoId = (int) $bd->lastInsertId();

        if (!empty($_FILES['imagenes']['name'][0])) {
            subirImagenesProducto($bd, $productoId, $_FILES['imagenes']);
        }

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
        $imagenes        = $modelo->obtenerImagenes($id);

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

        if (!empty($_FILES['imagenes']['name'][0])) {
            subirImagenesProducto($bd, $id, $_FILES['imagenes']);
        }
        // Volver a fijar la imagen principal por si se subieron nuevas
        sincronizarImagenPrincipal($bd, $id);

        redirigir('/admin/productos/editar/' . $id);
    }

    public static function imagenEliminar(PDO $bd, string $uri): void {
        requiereAdmin();
        $imagenId   = (int) basename($uri);
        $productoId = eliminarImagenProducto($bd, $imagenId);
        redirigir($productoId ? '/admin/productos/editar/' . $productoId : '/admin/productos');
    }

    public static function perfilVista(): void {
        requiereAdmin();
        $tituloPagina = 'Mi perfil';
        $exito = $_SESSION['perfil_exito'] ?? null;
        $error = $_SESSION['perfil_error'] ?? null;
        unset($_SESSION['perfil_exito'], $_SESSION['perfil_error']);
        require_once BASE_PATH . '/src/views/admin/perfil.php';
    }

    public static function perfilProcesar(PDO $bd): void {
        requiereAdmin();

        $actual     = $_POST['password_actual']    ?? '';
        $nueva      = $_POST['password_nueva']     ?? '';
        $confirmar  = $_POST['password_confirmar'] ?? '';

        $consulta = $bd->prepare('SELECT password FROM usuarios WHERE id = :id');
        $consulta->execute([':id' => $_SESSION['admin_id']]);
        $usuario = $consulta->fetch();

        if (!password_verify($actual, $usuario['password'])) {
            $_SESSION['perfil_error'] = 'La contraseña actual es incorrecta.';
        } elseif ($nueva !== $confirmar) {
            $_SESSION['perfil_error'] = 'Las contraseñas nuevas no coinciden.';
        } elseif (strlen($nueva) < 6) {
            $_SESSION['perfil_error'] = 'La contraseña debe tener al menos 6 caracteres.';
        } else {
            $hash = password_hash($nueva, PASSWORD_BCRYPT);
            $bd->prepare('UPDATE usuarios SET password = :password WHERE id = :id')
               ->execute([':password' => $hash, ':id' => $_SESSION['admin_id']]);
            $_SESSION['perfil_exito'] = 'Contraseña actualizada correctamente.';
        }

        redirigir('/admin/perfil');
    }

    public static function productoEliminar(PDO $bd, string $uri): void {
        requiereAdmin();
        $id     = (int) basename($uri);
        $modelo = new Producto($bd);

        // Guardar rutas de imágenes para borrar los archivos físicos si el borrado prospera
        $imagenes = $modelo->obtenerImagenes($id);

        try {
            $bd->prepare('DELETE FROM productos WHERE id = :id')->execute([':id' => $id]);

            foreach ($imagenes as $img) {
                $archivo = BASE_PATH . '/public' . $img['ruta'];
                if (is_file($archivo)) {
                    unlink($archivo);
                }
            }
            $_SESSION['producto_msg'] = 'Producto eliminado.';
        } catch (PDOException $e) {
            // Tiene ventas asociadas: lo ocultamos en vez de borrarlo (conserva el historial)
            $bd->prepare('UPDATE productos SET activo = 0 WHERE id = :id')->execute([':id' => $id]);
            $_SESSION['producto_msg'] = 'El producto tiene ventas asociadas, así que se ocultó de la tienda '
                . 'en lugar de borrarse (para conservar el historial de ventas).';
        }

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

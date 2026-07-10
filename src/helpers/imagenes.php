<?php
// Helper para subir y gestionar imágenes de productos

const IMG_DIR_RELATIVO = '/uploads/productos';
const IMG_TIPOS_PERMITIDOS = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];
const IMG_MAX_BYTES = 5 * 1024 * 1024; // 5 MB

/**
 * Sube las imágenes recibidas en $_FILES y las asocia al producto.
 * Devuelve la cantidad de imágenes subidas correctamente.
 */
function subirImagenesProducto(PDO $bd, int $productoId, array $archivos): int {
    $dirAbsoluto = BASE_PATH . '/public' . IMG_DIR_RELATIVO;
    if (!is_dir($dirAbsoluto)) {
        mkdir($dirAbsoluto, 0755, true);
    }

    // Orden inicial: continuar después de las imágenes existentes
    $stmtOrden = $bd->prepare('SELECT COALESCE(MAX(orden), -1) + 1 AS siguiente FROM producto_imagenes WHERE producto_id = ?');
    $stmtOrden->execute([$productoId]);
    $orden = (int) $stmtOrden->fetch()['siguiente'];

    $subidas = 0;
    $total   = count($archivos['name']);
    $stmtIns = $bd->prepare('INSERT INTO producto_imagenes (producto_id, ruta, orden) VALUES (?, ?, ?)');

    for ($i = 0; $i < $total; $i++) {
        if ($archivos['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $tmp  = $archivos['tmp_name'][$i];
        $tipo = mime_content_type($tmp);

        if (!isset(IMG_TIPOS_PERMITIDOS[$tipo])) {
            continue; // formato no permitido
        }
        if ($archivos['size'][$i] > IMG_MAX_BYTES) {
            continue; // demasiado grande
        }

        $extension = IMG_TIPOS_PERMITIDOS[$tipo];
        $nombre    = 'prod-' . $productoId . '-' . uniqid() . '.' . $extension;
        $destino   = $dirAbsoluto . '/' . $nombre;

        if (move_uploaded_file($tmp, $destino)) {
            $rutaPublica = IMG_DIR_RELATIVO . '/' . $nombre;
            $stmtIns->execute([$productoId, $rutaPublica, $orden]);
            $orden++;
            $subidas++;
        }
    }

    sincronizarImagenPrincipal($bd, $productoId);
    return $subidas;
}

/**
 * Elimina una imagen (registro + archivo físico) y devuelve el producto_id afectado.
 */
function eliminarImagenProducto(PDO $bd, int $imagenId): ?int {
    $stmt = $bd->prepare('SELECT producto_id, ruta FROM producto_imagenes WHERE id = ?');
    $stmt->execute([$imagenId]);
    $imagen = $stmt->fetch();

    if (!$imagen) {
        return null;
    }

    $bd->prepare('DELETE FROM producto_imagenes WHERE id = ?')->execute([$imagenId]);

    $archivo = BASE_PATH . '/public' . $imagen['ruta'];
    if (is_file($archivo)) {
        unlink($archivo);
    }

    sincronizarImagenPrincipal($bd, (int) $imagen['producto_id']);
    return (int) $imagen['producto_id'];
}

/**
 * Deja imagen_principal apuntando a la primera imagen del producto (o vacío si no hay).
 */
function sincronizarImagenPrincipal(PDO $bd, int $productoId): void {
    $stmt = $bd->prepare('SELECT ruta FROM producto_imagenes WHERE producto_id = ? ORDER BY orden, id LIMIT 1');
    $stmt->execute([$productoId]);
    $primera = $stmt->fetch();
    $ruta = $primera ? $primera['ruta'] : null;

    $bd->prepare('UPDATE productos SET imagen_principal = ? WHERE id = ?')
       ->execute([$ruta, $productoId]);
}

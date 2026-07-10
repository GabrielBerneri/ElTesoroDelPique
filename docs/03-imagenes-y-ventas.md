# Cambios: imágenes múltiples y listado de ventas

Este cambio agrega tres funcionalidades al panel de administración:

1. **Imágenes múltiples por producto** (subir 2-3 fotos)
2. **Gestión de categorías** (agregar / eliminar)
3. **Listado de ventas** (pedidos guardados en la base de datos)

---

## Paso 1 — Correr este SQL en phpMyAdmin

Entrá a **Hostinger → phpMyAdmin → tu base de datos → pestaña SQL** y pegá esto:

```sql
-- Tabla para guardar varias imágenes por producto
CREATE TABLE producto_imagenes (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    ruta        VARCHAR(500) NOT NULL,
    orden       INT DEFAULT 0,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

-- Columnas nuevas en la tabla de pedidos para guardar datos de contacto
ALTER TABLE pedidos
    ADD COLUMN nombre_contacto    VARCHAR(150) AFTER email_contacto,
    ADD COLUMN telefono_contacto  VARCHAR(30)  AFTER nombre_contacto,
    ADD COLUMN referencia_externa VARCHAR(100) AFTER mp_payment_id;
```

> **`ON DELETE CASCADE`**: si se borra un producto, sus imágenes se borran solas.

---

## Paso 2 — Carpeta de imágenes en el servidor

Las fotos subidas se guardan en `public/uploads/productos/`.
El código crea esa carpeta automáticamente la primera vez que subís una imagen.
Esta carpeta **no se sube a GitHub** (está en `.gitignore`), las fotos viven solo en Hostinger.

---

## Cómo funciona

### Imágenes
- En el formulario de producto ahora hay un campo para subir fotos (se pueden elegir varias a la vez).
- La **primera** imagen queda como imagen principal (la que se ve en las tarjetas y el carrito).
- En la página de detalle del producto se ven todas en una galería.
- Al editar un producto podés borrar fotos individuales.
- Formatos aceptados: JPG, PNG, WEBP. Máximo 5 MB por foto.

### Categorías
- Nueva sección **Categorías** en el menú del admin.
- Podés agregar una categoría (nombre + descripción) y se genera el slug solo.
- No se puede borrar una categoría que tenga productos asociados (para no dejar productos huérfanos).

### Ventas
- Cuando un cliente completa el checkout, el pedido se guarda como **pendiente** antes de ir a MercadoPago.
- Al volver de un pago aprobado, el pedido pasa a **pagado**.
- En la sección **Ventas** del admin se ve el listado con estado, total y datos del cliente.
- Desde el detalle de cada venta se puede cambiar el estado (pagado → enviado → entregado).

> **Nota técnica**: la confirmación del pago se hace cuando el cliente vuelve de MercadoPago
> a la página de éxito. Para producción conviene además configurar un **webhook** de MercadoPago
> que confirme el pago del lado del servidor (más seguro). Queda como mejora futura.

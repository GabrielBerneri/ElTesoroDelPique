# Base de datos

## Motor
MySQL — provisto por Hostinger en el panel de control (hPanel).

## ¿Cómo conectarse?
Las credenciales van en el archivo `.env` (nunca en GitHub).
La conexión se establece en `src/config/database.php` usando PDO.

### ¿Qué es PDO?
PDO (PHP Data Objects) es la forma moderna y segura de conectarse a MySQL desde PHP.
Permite usar *prepared statements*, que protegen contra ataques de SQL Injection.

---

## Esquema de tablas

### `categorias`
Agrupa los productos por tipo.

```sql
CREATE TABLE categorias (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    slug        VARCHAR(100) NOT NULL UNIQUE,  -- URL amigable: "ropa-deportiva"
    descripcion TEXT,
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### `productos`
Catálogo completo de productos.

```sql
CREATE TABLE productos (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id    INT NOT NULL,
    nombre          VARCHAR(200) NOT NULL,
    slug            VARCHAR(200) NOT NULL UNIQUE,
    descripcion     TEXT,
    precio          DECIMAL(10,2) NOT NULL,
    stock           INT DEFAULT 0,
    activo          TINYINT(1) DEFAULT 1,       -- 1=visible, 0=oculto
    imagen_principal VARCHAR(500),
    creado_en       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);
```

---

### `usuarios`
Clientes registrados en la tienda.

```sql
CREATE TABLE usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,          -- guardado con hash bcrypt
    telefono    VARCHAR(20),
    es_admin    TINYINT(1) DEFAULT 0,           -- 1=admin, 0=cliente
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

> **Nota de seguridad**: La contraseña NUNCA se guarda en texto plano.
> Se usa `password_hash()` de PHP al registrar y `password_verify()` al hacer login.

---

### `pedidos`
Cada compra que realiza un cliente.

```sql
CREATE TABLE pedidos (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id      INT,
    email_contacto  VARCHAR(150) NOT NULL,      -- por si compra como invitado
    total           DECIMAL(10,2) NOT NULL,
    estado          ENUM('pendiente','pagado','enviado','entregado','cancelado') DEFAULT 'pendiente',
    mp_payment_id   VARCHAR(100),               -- ID del pago en MercadoPago
    direccion       TEXT,
    creado_en       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
```

---

### `detalle_pedidos`
Los productos que forman parte de cada pedido.

```sql
CREATE TABLE detalle_pedidos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id   INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad    INT NOT NULL,
    precio_unit DECIMAL(10,2) NOT NULL,         -- precio al momento de la compra
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);
```

> **¿Por qué guardar `precio_unit`?** Porque el precio de un producto puede cambiar,
> pero el precio del pedido ya cerrado debe quedar fijo tal como fue comprado.

---

## Glosario

| Término | Significado |
|---------|-------------|
| `PRIMARY KEY` | Identificador único de cada fila |
| `AUTO_INCREMENT` | El número se asigna solo, de forma automática |
| `FOREIGN KEY` | Relación entre dos tablas |
| `slug` | Versión URL de un texto ("Ropa Deportiva" → "ropa-deportiva") |
| `DECIMAL(10,2)` | Número con hasta 10 dígitos y 2 decimales (ideal para precios) |
| `ENUM` | Campo que solo acepta uno de los valores listados |

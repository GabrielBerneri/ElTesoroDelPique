# Arquitectura del proyecto

## ¿Qué es la arquitectura?
La arquitectura es la forma en que organizamos el código del proyecto. Definir esto desde el inicio nos permite saber exactamente dónde va cada cosa y por qué.

---

## Patrón MVC (Modelo - Vista - Controlador)

Este proyecto usa el patrón **MVC**, que separa el código en tres responsabilidades:

| Capa | Carpeta | Responsabilidad | Ejemplo |
|------|---------|-----------------|---------|
| **Modelo** | `src/models/` | Habla con la base de datos | Obtener lista de productos |
| **Vista** | `src/views/` | Muestra el HTML al usuario | Página de inicio con los productos |
| **Controlador** | `src/controllers/` | Une el modelo y la vista, contiene la lógica | Recibe la petición del usuario, pide los datos al modelo y los manda a la vista |

### ¿Por qué MVC?
- El código queda ordenado y predecible
- Si hay un error en la base de datos, sabés que está en `models/`
- Si hay un error visual, sabés que está en `views/`
- Si hay un error de lógica, sabés que está en `controllers/`

---

## Flujo de una petición

Cuando un usuario entra a `tutienda.com/productos`:

```
Navegador
   ↓  (petición HTTP)
public/index.php        ← punto de entrada único
   ↓
Router                  ← decide qué controlador ejecutar
   ↓
ProductoController      ← pide datos al modelo
   ↓
ProductoModel           ← consulta MySQL y devuelve los datos
   ↓
ProductoController      ← recibe los datos
   ↓
Vista productos.php     ← arma el HTML con los datos
   ↓
Navegador               ← muestra la página al usuario
```

---

## Estructura de carpetas explicada

```
public/
│
│   ← Todo lo que está en public/ puede ser accedido desde el navegador.
│     En Hostinger, esta carpeta se configura como "document root".
│
├── index.php           ← ÚNICO punto de entrada. Todas las URLs pasan por acá.
└── assets/
    ├── css/            ← Estilos visuales
    ├── js/             ← JavaScript del frontend
    └── images/         ← Imágenes del sitio (logo, banners, etc.)

src/
│
│   ← Esta carpeta NO es accesible desde el navegador. Solo PHP puede usarla.
│     Esto protege la lógica y los datos sensibles.
│
├── config/
│   └── database.php    ← Conexión a MySQL (usa variables del .env)
│
├── controllers/        ← Un archivo por sección del sitio
│   ├── HomeController.php
│   ├── ProductoController.php
│   ├── CarritoController.php
│   └── PagoController.php
│
├── models/             ← Un archivo por tabla de la BD
│   ├── Producto.php
│   ├── Categoria.php
│   ├── Pedido.php
│   └── Usuario.php
│
├── views/              ← Plantillas HTML con PHP embebido
│   ├── layouts/
│   │   └── base.php    ← Estructura HTML común (header, footer)
│   ├── home/
│   ├── productos/
│   ├── carrito/
│   └── checkout/
│
└── helpers/
    ├── auth.php        ← Funciones de sesión y login
    └── sanitize.php    ← Limpieza de datos de entrada del usuario
```

---

## Base de datos

Motor: **MySQL** (provisto por Hostinger)

### Tablas principales

| Tabla | Descripción |
|-------|-------------|
| `categorias` | Categorías de productos |
| `productos` | Catálogo de productos con precio, stock, etc. |
| `usuarios` | Clientes registrados |
| `pedidos` | Órdenes de compra |
| `detalle_pedidos` | Productos dentro de cada pedido |
| `imagenes_productos` | Imágenes de cada producto |

El esquema SQL completo está en `docs/02-base-de-datos.md`.

---

## Seguridad básica

- **`.env`** nunca se sube a GitHub — contiene las credenciales reales
- **`src/`** no es accesible desde el navegador — solo `public/` lo es
- **Consultas SQL** se hacen con *prepared statements* para evitar SQL Injection
- **Datos de formularios** se sanitizan antes de usarlos

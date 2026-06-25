# El Tesoro del Pique — Contexto del proyecto

## Descripción
Ecommerce desarrollado desde cero en PHP + MySQL, alojado en Hostinger.
Opera en Argentina. Procesador de pagos: MercadoPago.

## Stack tecnológico
- **Backend**: PHP 8.x
- **Base de datos**: MySQL (provisto por Hostinger)
- **Frontend**: HTML + CSS + JavaScript vanilla
- **Pagos**: MercadoPago
- **Hosting**: Hostinger (deploy vía Git)
- **Repositorio**: https://github.com/GabrielBerneri/ElTesoroDelPique

## Estructura del proyecto
```
/
├── public/          # Document root en Hostinger (única carpeta expuesta al navegador)
│   ├── index.php
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
├── src/             # Lógica interna (no accesible desde el navegador)
│   ├── config/      # Configuración de BD y constantes
│   ├── controllers/ # Lógica de cada sección (productos, carrito, pagos)
│   ├── models/      # Interacción con la base de datos
│   ├── views/       # Plantillas HTML/PHP
│   └── helpers/     # Funciones utilitarias
├── docs/            # Documentación del proyecto
├── .env.example     # Variables de entorno de ejemplo (sin datos reales)
├── .gitignore
└── CLAUDE.md
```

## Convenciones
- Todo el código en español (variables, comentarios, docs)
- El archivo `.env` NUNCA se sube a GitHub (está en .gitignore)
- La carpeta `public/` es el document root que se configura en Hostinger

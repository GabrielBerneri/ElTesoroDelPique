-- Ejecutar una sola vez en la base de datos de producción
CREATE TABLE IF NOT EXISTS marcas (
    id         INT UNSIGNED     AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(100)     NOT NULL,
    ruta       VARCHAR(255)     NOT NULL,
    orden      INT UNSIGNED     NOT NULL DEFAULT 0,
    activo     TINYINT(1)       NOT NULL DEFAULT 1,
    creado_en  TIMESTAMP        DEFAULT CURRENT_TIMESTAMP
);

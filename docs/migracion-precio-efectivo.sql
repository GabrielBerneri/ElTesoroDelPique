-- Ejecutar una sola vez en la base de datos de producción
ALTER TABLE productos
    ADD COLUMN precio_efectivo DECIMAL(10,2) NULL DEFAULT NULL
    AFTER precio;

-- precio_efectivo NULL significa "sin precio diferenciado para efectivo"

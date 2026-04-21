-- Agregar campos es_correccion y motivo_correccion a la tabla novedades
-- es_correccion: Indica si es una corrección de una novedad ya reportada
-- motivo_correccion: Almacena el motivo cuando es_correccion = 'SI'

-- 1. Agregar campo es_correccion
ALTER TABLE novedades 
ADD COLUMN es_correccion ENUM('SI', 'NO') NOT NULL DEFAULT 'NO' 
AFTER justificacion;

-- 2. Agregar campo motivo_correccion
ALTER TABLE novedades 
ADD COLUMN motivo_correccion TEXT NULL 
AFTER es_correccion;

-- Verificar que se agregaron correctamente
DESCRIBE novedades;
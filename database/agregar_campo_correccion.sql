-- Agregar campo es_correccion a la tabla novedades

USE pollo_fiesta_novedades;

-- Agregar la columna es_correccion después de justificacion
ALTER TABLE novedades 
ADD COLUMN es_correccion ENUM('SI', 'NO') NOT NULL DEFAULT 'NO' 
AFTER justificacion;

-- Verificar que se agregó correctamente
DESCRIBE novedades;
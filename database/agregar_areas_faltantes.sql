-- Agregar áreas faltantes a la base de datos existente
-- Ejecutar este script para agregar las áreas que faltan

USE pollo_fiesta_novedades;

-- Insertar áreas de Granjas (si no existen)
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Granjas', NULL, (SELECT id FROM sedes WHERE nombre = 'Granjas')),
('Procesados', NULL, (SELECT id FROM sedes WHERE nombre = 'Granjas'));

-- Insertar áreas de Huevos (si no existe)
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Huevos', NULL, (SELECT id FROM sedes WHERE nombre = 'Huevos'));

-- Insertar áreas de Planta (si no existe)
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Planta de Beneficio', NULL, (SELECT id FROM sedes WHERE nombre = 'Planta'));

-- Insertar áreas de Producción - Sede 1 (si no existen)
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Posproceso', NULL, (SELECT id FROM sedes WHERE nombre = 'Sede 1')),
('Despachos', NULL, (SELECT id FROM sedes WHERE nombre = 'Sede 1'));

-- Insertar áreas de Producción - Sede 2 (si no existen)
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Posproceso', NULL, (SELECT id FROM sedes WHERE nombre = 'Sede 2')),
('Despachos', NULL, (SELECT id FROM sedes WHERE nombre = 'Sede 2'));

-- Insertar áreas de Producción - Sede 3 (si no existen)
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Posproceso', NULL, (SELECT id FROM sedes WHERE nombre = 'Sede 3')),
('Despachos', NULL, (SELECT id FROM sedes WHERE nombre = 'Sede 3'));

-- Insertar áreas de Toberin (si no existe)
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Toberin', NULL, (SELECT id FROM sedes WHERE nombre = 'Toberin'));

-- Verificar que se agregaron correctamente
SELECT 
    s.nombre AS sede,
    a.nombre AS area
FROM areas_trabajo a
JOIN sedes s ON a.sede_id = s.id
WHERE s.nombre IN ('Granjas', 'Huevos', 'Planta', 'Sede 1', 'Sede 2', 'Sede 3', 'Toberin')
ORDER BY s.nombre, a.nombre;
-- Configurar sedes y áreas correctamente
-- Sede 1: Asadero, Sede 2: (por definir), Sede 3: Posproceso

USE pollo_fiesta_novedades;

-- Primero, eliminar todas las sedes que no sean Sede 1, 2, 3
-- Esto también eliminará las áreas asociadas por CASCADE
DELETE FROM sedes WHERE nombre NOT IN ('Sede 1', 'Sede 2', 'Sede 3');

-- Asegurar que las 3 sedes existan
INSERT IGNORE INTO sedes (nombre) VALUES 
('Sede 1'),
('Sede 2'), 
('Sede 3');

-- Limpiar áreas existentes de estas sedes
DELETE FROM areas_trabajo WHERE sede_id IN (
    SELECT id FROM sedes WHERE nombre IN ('Sede 1', 'Sede 2', 'Sede 3')
);

-- Agregar las áreas correctas
INSERT INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
-- Sede 1: Asadero
('Asadero', NULL, (SELECT id FROM sedes WHERE nombre = 'Sede 1')),

-- Sede 2: Despachos (temporal, se puede cambiar)
('Despachos', NULL, (SELECT id FROM sedes WHERE nombre = 'Sede 2')),

-- Sede 3: Posproceso
('Posproceso', NULL, (SELECT id FROM sedes WHERE nombre = 'Sede 3'));

-- Verificar que solo queden las 3 sedes
SELECT * FROM sedes ORDER BY nombre;

-- Verificar las áreas que quedaron
SELECT 
    s.nombre AS sede,
    a.nombre AS area
FROM areas_trabajo a
JOIN sedes s ON a.sede_id = s.id
ORDER BY s.nombre, a.nombre;
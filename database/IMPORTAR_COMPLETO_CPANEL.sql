-- ============================================
-- SCRIPT COMPLETO PARA wwpoll_informe_novedades
-- Ejecutar DESPUÉS de importar schema.sql
-- ============================================

USE wwpoll_informe_novedades;

-- Eliminar "Producción" como sede (debe ser área de Granjas)
DELETE FROM sedes WHERE nombre = 'Producción';

-- Agregar áreas faltantes para Sede 1
SET @sede1_id = (SELECT id FROM sedes WHERE nombre = 'Sede 1');
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Despachos', NULL, @sede1_id),
('Posproceso', NULL, @sede1_id);

-- Agregar áreas faltantes para Sede 2
SET @sede2_id = (SELECT id FROM sedes WHERE nombre = 'Sede 2');
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Despachos', NULL, @sede2_id),
('Posproceso', NULL, @sede2_id);

-- Agregar áreas faltantes para Sede 3
SET @sede3_id = (SELECT id FROM sedes WHERE nombre = 'Sede 3');
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Despachos', NULL, @sede3_id),
('Posproceso', NULL, @sede3_id);

-- Agregar áreas faltantes para Planta
SET @planta_id = (SELECT id FROM sedes WHERE nombre = 'Planta');
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Planta de Beneficio', NULL, @planta_id);

-- Agregar áreas faltantes para Granjas (Producción y Procesados son ÁREAS, no sedes)
SET @granjas_id = (SELECT id FROM sedes WHERE nombre = 'Granjas');
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Granjas', NULL, @granjas_id),
('Producción', NULL, @granjas_id),
('Procesados', NULL, @granjas_id);

-- Agregar áreas faltantes para Huevos
SET @huevos_id = (SELECT id FROM sedes WHERE nombre = 'Huevos');
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Huevos', NULL, @huevos_id);

-- Agregar áreas faltantes para Toberin
SET @toberin_id = (SELECT id FROM sedes WHERE nombre = 'Toberin');
INSERT IGNORE INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Toberin', NULL, @toberin_id);

-- ============================================
-- VERIFICACIÓN
-- ============================================

SELECT '=== VERIFICACIÓN DE SEDES ===' AS '';
SELECT nombre, activo FROM sedes ORDER BY nombre;

SELECT '=== TOTAL DE ÁREAS POR SEDE ===' AS '';
SELECT s.nombre AS Sede, COUNT(a.id) AS Total_Areas
FROM sedes s
LEFT JOIN areas_trabajo a ON s.id = a.sede_id
GROUP BY s.id, s.nombre
ORDER BY s.nombre;

SELECT '=== ÁREAS DE GRANJAS (debe tener 3) ===' AS '';
SELECT a.nombre AS Area
FROM areas_trabajo a
JOIN sedes s ON a.sede_id = s.id
WHERE s.nombre = 'Granjas'
ORDER BY a.nombre;

SELECT '=== USUARIOS DEL SISTEMA ===' AS '';
SELECT id, usuario, nombre, rol, activo FROM usuarios ORDER BY nombre;

SELECT '=== TIPOS DE NOVEDAD ===' AS '';
SELECT nombre FROM tipos_novedad WHERE activo = 1 ORDER BY nombre;

-- ============================================
-- RESULTADO ESPERADO:
-- - 12 sedes (sin "Producción")
-- - 44 áreas en total
-- - Granjas con 3 áreas: Granjas, Procesados, Producción
-- - Al menos 1 usuario activo
-- ============================================

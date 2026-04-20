-- Insertar usuarios líderes en el sistema
-- Password por defecto: 123456
-- Hash: $2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.

USE pollo_fiesta_novedades;

-- Agregar sedes que faltan (si no existen)
INSERT IGNORE INTO sedes (nombre) VALUES 
('Sede 1'), ('Sede 2'), ('Sede 3'), ('Granjas'),
('Toberin'), ('Planta'), ('Huevos'), ('Producción'), 
('Administrativo'), ('Yopal');

-- ============================================================
-- USUARIOS
-- ============================================================

INSERT INTO usuarios (usuario, password, nombre, rol) VALUES

-- GERENTES (admin)
('hbenito',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'BENITO GUEVARA HERNAN MATEO',           'admin'),
('hfajardo',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'FAJARDO RODRIGUEZ HUGO EDUARDO',        'admin'),
('agarcia',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'GARCIA MOYA ALEX LEOPOLDO',             'admin'),
('jrestrepo',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RESTREPO MELO JOHN HENRY',              'admin'),
('mroa',         '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ROA BARRERA MARGARITA MARIA',           'admin'),
('croa',         '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ROA BARRERA MARIA CLEMENCIA',           'admin'),
('erodriguez',   '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ EDGAR GERMAN',                'admin'),
('drodriguez',   '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ CELY DIEGO ALEXANDER',        'admin'),
('ksanchez',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'SANCHEZ HERNANDEZ KELLY JOHANNA',       'admin'),
('osolano',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'SOLANO MARINO OSCAR HENRY',             'admin'),

-- DIRECTORES (admin)
('marias',       '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ARIAS MOLINA MICHAEL DARNYI',           'admin'),
('acardenas',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'CARDENAS BUITRAGO ANGELICA MARIA',      'admin'),
('ediaz',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'DIAZ FORY EDUAR YAMIL',                 'admin'),
('bferro',       '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'FERRO RODRIGUEZ BRIYITH NATALIA',       'admin'),
('egonzalez',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'GONZALEZ ZUBIETA ELMIRA',               'admin'),
('jibanez',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'IBANEZ LANDINEZ JUAN MANUEL',           'admin'),
('ymora',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MORA BARRETO YEISON JULIAN',            'admin'),
('lmurillo',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MURILLO BARRERO LUZ MERY',              'admin'),
('mnino',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'NINO GUERRERO MILTON ANDRES',           'admin'),
('jrios',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RIOS MUNEVAR JOHANNA ANDREA',           'admin'),
('rrodriguez',   '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ AGUIRRE RICARDO AUGUSTO',     'admin'),
('krodriguez',   '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ RODRIGUEZ KATHERINE GISELLE', 'admin'),
('jsanchez',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'SANCHEZ MELO JHON ILDEFONSO',           'admin'),
('gzubieta',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ZUBIETA CASTANEDA GINETH KATHERINE',    'admin'),
('msanchez',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'SANCHEZ IBARRA MIGUEL EDUARDO',         'admin'),
('amartinez',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MARTINEZ PAREDES ANGIE PAOLA',          'admin'),

-- JEFES (jefe)
('yalvarado',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ALVARADO DIAZ YENNY ANDREA',            'jefe'),
('calfonso',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ALFONSO ROMERO CATALINA',               'jefe'),
('langulo',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ANGULO SANTAMARIA LIZANDRO',            'jefe'),
('lardila',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ARDILA MARTINEZ LUCAS',                 'jefe'),
('sarevalo',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'AREVALO URREGO SEBASTIAN',              'jefe'),
('wbernate',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'BERNATE CASTRO WILSON ENRIQUE',         'jefe'),
('tcabana',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'CABANA LOZANO TIFFANY ARLETT',          'jefe'),
('hcampos',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'CAMPOS CAMPOS HENRY',                   'jefe'),
('jcastro',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'CASTRO PAEZ JEYLER MAURICIO',           'jefe'),
('cfontalvo',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'FONTALVO OCHOA CLARA MILENA',           'jefe'),
('gmarin',       '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MARIN RAMIREZ GUSTAVO',                 'jefe'),
('ymontenegro',  '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MONTENEGRO DE RODRIGUEZ YOLANDA',       'jefe'),
('aperez',       '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'PEREZ HERRERA ALEXIS',                  'jefe'),
('cpuentes',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'PUENTES BRINEZ CARLOS FERNANDO',        'jefe'),
('jrodriguez2',  '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ CASTILLO JOSE OBDULIO',       'jefe'),
('drodriguez2',  '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ MOYANO DIANA KATHERINE',      'jefe'),
('eromero',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ROMERO HUERTAS EDGAR ENRIQUE',          'jefe'),
('cruiz',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RUIZ MANCERA CESAR ALBERTO',            'jefe'),
('jurrego',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'URREGO MAHECHA JOHAN DAVID',            'jefe');

-- ============================================================
-- ASIGNAR SEDES A JEFES
-- ============================================================

-- Toberin
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'yalvarado'),   (SELECT id FROM sedes WHERE nombre = 'Toberin'));

-- Sede 2 - Jefe de Posproceso
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'calfonso'),    (SELECT id FROM sedes WHERE nombre = 'Sede 2'));

-- Sede 1 - Jefe de Posproceso
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'langulo'),     (SELECT id FROM sedes WHERE nombre = 'Sede 1'));

-- Yopal - Jefe de Despachos
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'lardila'),     (SELECT id FROM sedes WHERE nombre = 'Yopal'));

-- Administrativo - Arquitectura
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'sarevalo'),    (SELECT id FROM sedes WHERE nombre = 'Administrativo'));

-- Sede 2 - Despachos
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'wbernate'),    (SELECT id FROM sedes WHERE nombre = 'Sede 2')),
((SELECT id FROM usuarios WHERE usuario = 'tcabana'),     (SELECT id FROM sedes WHERE nombre = 'Sede 2')),
((SELECT id FROM usuarios WHERE usuario = 'hcampos'),     (SELECT id FROM sedes WHERE nombre = 'Sede 2')),
((SELECT id FROM usuarios WHERE usuario = 'cruiz'),       (SELECT id FROM sedes WHERE nombre = 'Sede 2'));

-- Sede 1 - Despachos
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'jcastro'),     (SELECT id FROM sedes WHERE nombre = 'Sede 1'));

-- Sede 1 - Posproceso
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'cfontalvo'),   (SELECT id FROM sedes WHERE nombre = 'Sede 1'));
 
-- Huevos
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'gmarin'),      (SELECT id FROM sedes WHERE nombre = 'Huevos'));

-- Administrativo - Tesorería
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'ymontenegro'), (SELECT id FROM sedes WHERE nombre = 'Administrativo'));

-- Sede 2 - Posproceso
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'aperez'),      (SELECT id FROM sedes WHERE nombre = 'Sede 2'));

-- Sede 3 - Despachos
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'cpuentes'),    (SELECT id FROM sedes WHERE nombre = 'Sede 3')),
((SELECT id FROM usuarios WHERE usuario = 'jurrego'),     (SELECT id FROM sedes WHERE nombre = 'Sede 3'));

-- Granjas - Transporte
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'jrodriguez2'), (SELECT id FROM sedes WHERE nombre = 'Granjas'));

-- Producción - Procesados
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'drodriguez2'), (SELECT id FROM sedes WHERE nombre = 'Producción'));

-- Planta de Beneficio
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'rrodriguez'),  (SELECT id FROM sedes WHERE nombre = 'Planta')),
((SELECT id FROM usuarios WHERE usuario = 'krodriguez'),  (SELECT id FROM sedes WHERE nombre = 'Planta'));

-- Administrativo - Mensajero de Gerencia
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES
((SELECT id FROM usuarios WHERE usuario = 'eromero'),  (SELECT id FROM sedes WHERE nombre = 'Administrativo'));

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
-- USUARIOS CON CARGOS
-- ============================================================

INSERT INTO usuarios (usuario, password, nombre, cargo, rol) VALUES

-- GERENTES (admin)
('hbenito',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'BENITO GUEVARA HERNAN MATEO',           'GERENTE COMERCIAL',                                    'admin'),
('hfajardo',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'FAJARDO RODRIGUEZ HUGO EDUARDO',        'GERENTE DE MANTENIMIENTO Y EFICIENCIA PRODUCTIVA',     'admin'),
('agarcia',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'GARCIA MOYA ALEX LEOPOLDO',             'GERENTE DE PRODUCCION',                                'admin'),
('jrestrepo',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RESTREPO MELO JOHN HENRY',              'GERENTE GENERAL',                                      'admin'),
('mroa',         '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ROA BARRERA MARGARITA MARIA',           'GERENTE DE MERCADEO',                                  'admin'),
('croa',         '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ROA BARRERA MARIA CLEMENCIA',           'GERENTE DE PRODUCCION',                                'admin'),
('erodriguez',   '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ EDGAR GERMAN',                'GERENTE COMERCIAL',                                    'admin'),
('drodriguez',   '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ CELY DIEGO ALEXANDER',        'GERENTE LOGISTICO',                                    'admin'),
('ksanchez',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'SANCHEZ HERNANDEZ KELLY JOHANNA',       'GERENTE ESTRATEGICO Y DE MEJORAMIENTO CONTINUO',       'admin'),
('osolano',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'SOLANO MARINO OSCAR HENRY',             'GERENTE CONTABLE Y FINANCIERO',                        'admin'),

-- DIRECTORES (admin)
('marias',       '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ARIAS MOLINA MICHAEL DARNYI',           'DIRECTOR PTOS DE VTA',                                 'admin'),
('acardenas',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'CARDENAS BUITRAGO ANGELICA MARIA',      'DIRECTOR DE POST PROCESO',                             'admin'),
('ediaz',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'DIAZ FORY EDUAR YAMIL',                 'DIRECTOR MTTO',                                        'admin'),
('bferro',       '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'FERRO RODRIGUEZ BRIYITH NATALIA',       'DIRECTOR DE COMPRAS',                                  'admin'),
('egonzalez',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'GONZALEZ ZUBIETA ELMIRA',               'DIRECTOR PTOS DE VTA',                                 'admin'),
('jibanez',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'IBANEZ LANDINEZ JUAN MANUEL',           'DIRECTOR TECNICO',                                     'admin'),
('ymora',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MORA BARRETO YEISON JULIAN',            'DIRECTOR PTOS DE VTA',                                 'admin'),
('lmurillo',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MURILLO BARRERO LUZ MERY',              'DIRECTOR DE CARTERA',                                  'admin'),
('mnino',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'NINO GUERRERO MILTON ANDRES',           'DIRECTOR DE MERCADEO',                                 'admin'),
('jrios',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RIOS MUNEVAR JOHANNA ANDREA',           'DIRECTOR DE GESTION HUMANA',                           'admin'),
('rrodriguez',   '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ AGUIRRE RICARDO AUGUSTO',     'DIRECTOR PLANTA BENEFICIO',                            'admin'),
('krodriguez',   '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ RODRIGUEZ KATHERINE GISELLE', 'DIRECTOR PLANTA BENEFICIO',                            'admin'),
('jsanchez',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'SANCHEZ MELO JHON ILDEFONSO',           'DIRECTOR CONTABLE Y FINANCIERO',                       'admin'),
('gzubieta',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ZUBIETA CASTANEDA GINETH KATHERINE',    'DIRECTOR AUDITORIA',                                   'admin'),
('msanchez',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'SANCHEZ IBARRA MIGUEL EDUARDO',         'INGENIERO SOPORTE TECNICO',                            'admin'),
('amartinez',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MARTINEZ PAREDES ANGIE PAOLA',          'OFICIAL DE CUMPLIMIENTO SAGRILAFT, PTEE',              'admin'),

-- JEFES (jefe)
('yalvarado',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ALVARADO DIAZ YENNY ANDREA',            'JEFE CENTRO DE DISTRIBUCION TOBERIN',                  'jefe'),
('calfonso',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ALFONSO ROMERO CATALINA',               'JEFE DE POSPROCESO',                                   'jefe'),
('langulo',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ANGULO SANTAMARIA LIZANDRO',            'JEFE DE POSPROCESO',                                   'jefe'),
('lardila',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ARDILA MARTINEZ LUCAS',                 'JEFE DE DESPACHOS',                                    'jefe'),
('sarevalo',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'AREVALO URREGO SEBASTIAN',              'JEFE DE ARQUITECTURA',                                 'jefe'),
('wbernate',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'BERNATE CASTRO WILSON ENRIQUE',         'JEFE DE DESPACHOS',                                    'jefe'),
('tcabana',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'CABANA LOZANO TIFFANY ARLETT',          'JEFE DE DESPACHOS',                                    'jefe'),
('hcampos',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'CAMPOS CAMPOS HENRY',                   'JEFE DE DESPACHOS',                                    'jefe'),
('jcastro',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'CASTRO PAEZ JEYLER MAURICIO',           'JEFE DE DESPACHOS',                                    'jefe'),
('cfontalvo',    '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'FONTALVO OCHOA CLARA MILENA',           'JEFE DE POSPROCESO',                                   'jefe'),
('gmarin',       '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MARIN RAMIREZ GUSTAVO',                 'JEFE DISTRIBUCION DE HUEVOS',                          'jefe'),
('ymontenegro',  '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'MONTENEGRO DE RODRIGUEZ YOLANDA',       'JEFE DE TESORERIA',                                    'jefe'),
('aperez',       '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'PEREZ HERRERA ALEXIS',                  'JEFE DE POSPROCESO',                                   'jefe'),
('cpuentes',     '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'PUENTES BRINEZ CARLOS FERNANDO',        'JEFE DE DESPACHOS',                                    'jefe'),
('jrodriguez2',  '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ CASTILLO JOSE OBDULIO',       'JEFE DE TRANSPORTE',                                   'jefe'),
('drodriguez2',  '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RODRIGUEZ MOYANO DIANA KATHERINE',      'JEFE DE PROCESADOS',                                   'jefe'),
('eromero',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'ROMERO HUERTAS EDGAR ENRIQUE',          'MENSAJERO DE GERENCIA',                                'jefe'),
('cruiz',        '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'RUIZ MANCERA CESAR ALBERTO',            'JEFE DE DESPACHOS',                                    'jefe'),
('jurrego',      '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'URREGO MAHECHA JOHAN DAVID',            'JEFE DE DESPACHOS',                                    'jefe');

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

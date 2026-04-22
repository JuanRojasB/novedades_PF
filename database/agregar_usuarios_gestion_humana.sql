-- Agregar 4 usuarios de Gestión Humana con acceso al dashboard
-- Estos usuarios recibirán correos de TODAS las novedades y tendrán acceso al dashboard
-- Patrón de contraseña: usuario2026*

-- 1. ELSA BECERRA - Auxiliar Gestión Humana
-- Usuario: ebecerra | Contraseña: ebecerra2026*
INSERT INTO usuarios (username, password, nombre, email, rol, activo)
VALUES ('ebecerra', '$2y$10$YourHashHere1', 'Elsa Becerra', 'r.humanos@pollo-fiesta.com', 'admin', 1)
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$YourHashHere1',
    email = 'r.humanos@pollo-fiesta.com',
    rol = 'admin',
    activo = 1;

-- 2. CATHERINE ORTIZ - Auxiliar Gestión Humana
-- Usuario: cortiz | Contraseña: cortiz2026*
INSERT INTO usuarios (username, password, nombre, email, rol, activo)
VALUES ('cortiz', '$2y$10$YourHashHere2', 'Catherine Ortiz', 'AuxiliarGH2@pollo-fiesta.com', 'admin', 1)
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$YourHashHere2',
    email = 'AuxiliarGH2@pollo-fiesta.com',
    rol = 'admin',
    activo = 1;

-- 3. CARMENZA MARTINEZ - Auxiliar Gestión Humana
-- Usuario: cmartinez | Contraseña: cmartinez2026*
INSERT INTO usuarios (username, password, nombre, email, rol, activo)
VALUES ('cmartinez', '$2y$10$YourHashHere3', 'Carmenza Martinez', 'AuxiliarGH1@pollo-fiesta.com', 'admin', 1)
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$YourHashHere3',
    email = 'AuxiliarGH1@pollo-fiesta.com',
    rol = 'admin',
    activo = 1;

-- 4. MICHELLE FERNANDA VELANDIA GIL - Profesional Nómina
-- Usuario: mvelandia | Contraseña: mvelandia2026*
INSERT INTO usuarios (username, password, nombre, email, rol, activo)
VALUES ('mvelandia', '$2y$10$YourHashHere4', 'Michelle Velandia', 'profesionalnomina@pollo-fiesta.com', 'admin', 1)
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$YourHashHere4',
    email = 'profesionalnomina@pollo-fiesta.com',
    rol = 'admin',
    activo = 1;

-- Verificar que se crearon correctamente
SELECT username, nombre, email, rol FROM usuarios WHERE username IN ('ebecerra', 'cortiz', 'cmartinez', 'mvelandia');

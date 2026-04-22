-- Agregar 4 usuarios de Gestión Humana con acceso al dashboard
-- Estos usuarios recibirán correos de TODAS las novedades y tendrán acceso al dashboard

-- 1. ELSA BECERRA - Auxiliar Gestión Humana
INSERT INTO usuarios (username, password, nombre, email, rol, activo)
VALUES ('ebecerra', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Elsa Becerra', 'r.humanos@pollo-fiesta.com', 'admin', 1);

-- 2. CATHERINE ORTIZ - Auxiliar Gestión Humana
INSERT INTO usuarios (username, password, nombre, email, rol, activo)
VALUES ('cortiz', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Catherine Ortiz', 'AuxiliarGH2@pollo-fiesta.com', 'admin', 1);

-- 3. CARMENZA MARTINEZ - Auxiliar Gestión Humana
INSERT INTO usuarios (username, password, nombre, email, rol, activo)
VALUES ('cmartinez', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carmenza Martinez', 'AuxiliarGH1@pollo-fiesta.com', 'admin', 1);

-- 4. MICHELLE FERNANDA VELANDIA GIL - Profesional Nómina
INSERT INTO usuarios (username, password, nombre, email, rol, activo)
VALUES ('mvelandia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Michelle Velandia', 'profesionalnomina@pollo-fiesta.com', 'admin', 1);

-- Verificar que se crearon correctamente
SELECT username, nombre, email, rol FROM usuarios WHERE username IN ('ebecerra', 'cortiz', 'cmartinez', 'mvelandia');

-- NOTA: La contraseña por defecto es "password" (hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
-- Cambiar las contraseñas después de la primera sesión

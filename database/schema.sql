-- Base de datos para Sistema de Novedades Pollo Fiesta

-- Eliminar la base de datos si existe
DROP DATABASE IF EXISTS pollo_fiesta_novedades;

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS pollo_fiesta_novedades CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE pollo_fiesta_novedades;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    cargo VARCHAR(150) NULL,
    email VARCHAR(100) NULL,
    rol ENUM('director', 'jefe') DEFAULT 'jefe',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuarios
INSERT INTO usuarios (usuario, password, nombre, cargo, rol) VALUES 
('admin', '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'Administrador', 'Administrador del Sistema', 'director'),
('usuario', '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'Usuario Sistema', 'Usuario de Prueba', 'jefe'),
('jefe_yopal', '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'Jefe de Yopal', 'Jefe de Despachos', 'jefe'),
('jefe_admin', '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'Jefe Administrativo', 'Jefe Administrativo', 'jefe'),
('jefe_pdv', '$2y$10$ILoO.088VKxrOY1Ix6CCbOKK2p1.2Kkl4V7lUmBwf7tgglKso1ul.', 'Jefe Puntos de Venta', 'Jefe Puntos de Venta', 'jefe');
-- Password: 123456

-- Tabla de sedes (nivel 1)
CREATE TABLE IF NOT EXISTS sedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar sedes principales
INSERT INTO sedes (nombre) VALUES 
('Puntos de Venta'),
('Yopal'),
('Guadalupe'),
('Huevos'),
('Planta'),
('Producción'),
('Administrativo'),
('Toberin'),
('Visión Colombia'),
('Sede 1'),
('Sede 2'),
('Sede 3'),
('Granjas');

-- Tabla de zonas geográficas (nivel 2)
CREATE TABLE IF NOT EXISTS zonas_geograficas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    sede_id INT NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar zonas geográficas (SOLO para Puntos de Venta)
INSERT INTO zonas_geograficas (nombre, sede_id) VALUES 
-- Puntos de Venta - Zona Sur
('Zona Sur', (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
-- Puntos de Venta - Zona Norte
('Zona Norte', (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta'));

-- Tabla de áreas de trabajo (nivel 3 - actualizada)
CREATE TABLE IF NOT EXISTS areas_trabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    zona_geografica_id INT NULL,
    sede_id INT NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (zona_geografica_id) REFERENCES zonas_geograficas(id) ON DELETE SET NULL,
    FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar áreas de Puntos de Venta - Zona Sur
INSERT INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('20 de Julio', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Sur'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Abastos', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Sur'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Kennedy', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Sur'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Santa Rita', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Sur'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Pradera', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Sur'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Soacha', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Sur'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Fusagasugá', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Sur'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta'));

-- Insertar áreas de Puntos de Venta - Zona Norte
INSERT INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Market Toberin', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Norte'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Engativá Centro', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Norte'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Floresta', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Norte'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Suba', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Norte'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta')),
('Cabaña', (SELECT id FROM zonas_geograficas WHERE nombre = 'Zona Norte'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta'));

-- Insertar áreas de Yopal (sin zonas geográficas)
INSERT INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Yopal PDV', NULL, (SELECT id FROM sedes WHERE nombre = 'Yopal')),
('Yopal Bodega', NULL, (SELECT id FROM sedes WHERE nombre = 'Yopal'));

-- Insertar áreas de Administrativo
INSERT INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Auditoría', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Contabilidad', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Sistemas', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Operaciones y Mantenimiento', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('HSEQ', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Vigías', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Calidad', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Compras', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Gestión Humana', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('SAGRILAFT', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Tesorería', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Cartera', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Asesores Comerciales S1', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Asesores Comerciales S3', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Publicidad', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
('Gerencia General', NULL, (SELECT id FROM sedes WHERE nombre = 'Administrativo'));

-- Insertar áreas de Guadalupe (sin zona geográfica)
INSERT INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Central de Carnes Guadalupe', NULL, (SELECT id FROM sedes WHERE nombre = 'Guadalupe'));

-- Insertar áreas de Visión Colombia (sin zona geográfica)
INSERT INTO areas_trabajo (nombre, zona_geografica_id, sede_id) VALUES 
('Visión Colombia', NULL, (SELECT id FROM sedes WHERE nombre = 'Visión Colombia'));

-- Tabla de relación usuario-sede (un usuario puede tener varias sedes)
CREATE TABLE IF NOT EXISTS usuario_sedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    sede_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_usuario_sede (usuario_id, sede_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de tipos de novedad
CREATE TABLE IF NOT EXISTS tipos_novedad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar tipos de novedad
INSERT INTO tipos_novedad (nombre) VALUES 
('PERMISO NO REMUNERADO'),
('PERMISO REMUNERADO'),
('INCAPACIDAD'),
('AUSENCIA'),
('VACACIONES'),
('REINTEGRO DE INCAPACIDAD'),
('REINTEGRO DE VACACIONES'),
('REINTEGRO DE AUSENCIA/SANCIÓN'),
('AISLAMIENTO'),
('NOTIFICACIÓN'),
('RENUNCIA');

-- Tabla de novedades
CREATE TABLE IF NOT EXISTS novedades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Información Personal
    nombres_apellidos VARCHAR(200) NOT NULL,
    numero_cedula BIGINT UNSIGNED NOT NULL,
    
    -- Información Laboral
    sede VARCHAR(100) NOT NULL,
    zona_geografica VARCHAR(100) NULL,
    area_trabajo VARCHAR(100) NOT NULL,
    
    -- Detalles de la Novedad
    fecha_novedad DATE NOT NULL,
    turno ENUM('DÍA', 'NOCHE') NOT NULL,
    novedad VARCHAR(100) NOT NULL,
    justificacion ENUM('SI', 'NO') NOT NULL,
    
    -- Información Adicional
    descontar_dominical ENUM('SI', 'NO') NOT NULL,
    observacion_novedad VARCHAR(200) NOT NULL,
    nota TEXT NULL,
    responsable VARCHAR(200) NOT NULL,

    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_fecha_novedad (fecha_novedad),
    INDEX idx_area_trabajo (area_trabajo),
    INDEX idx_sede (sede),
    INDEX idx_zona_geografica (zona_geografica),
    INDEX idx_cedula (numero_cedula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de archivos adjuntos (almacenados en filesystem)
CREATE TABLE IF NOT EXISTS archivos_adjuntos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    novedad_id INT NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    tipo_mime VARCHAR(100) NOT NULL,
    tamanio INT NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (novedad_id) REFERENCES novedades(id) ON DELETE CASCADE,
    INDEX idx_novedad (novedad_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Asignar sedes a usuarios (para pruebas)
-- Jefe de Yopal: solo tiene acceso a Yopal
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES 
((SELECT id FROM usuarios WHERE usuario = 'jefe_yopal'), (SELECT id FROM sedes WHERE nombre = 'Yopal'));

-- Jefe Administrativo: solo tiene acceso a Administrativo
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES 
((SELECT id FROM usuarios WHERE usuario = 'jefe_admin'), (SELECT id FROM sedes WHERE nombre = 'Administrativo'));

-- Jefe de Puntos de Venta: solo tiene acceso a Puntos de Venta
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES 
((SELECT id FROM usuarios WHERE usuario = 'jefe_pdv'), (SELECT id FROM sedes WHERE nombre = 'Puntos de Venta'));

-- Usuario genérico: tiene acceso a múltiples sedes (para probar selector)
INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES 
((SELECT id FROM usuarios WHERE usuario = 'usuario'), (SELECT id FROM sedes WHERE nombre = 'Yopal')),
((SELECT id FROM usuarios WHERE usuario = 'usuario'), (SELECT id FROM sedes WHERE nombre = 'Administrativo')),
((SELECT id FROM usuarios WHERE usuario = 'usuario'), (SELECT id FROM sedes WHERE nombre = 'Guadalupe'));

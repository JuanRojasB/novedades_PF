# Instalación de Base de Datos

## Pasos para configurar la base de datos MySQL

### 1. Abrir phpMyAdmin o MySQL Workbench

Accede a tu gestor de base de datos MySQL (phpMyAdmin viene con XAMPP).

### 2. Importar el esquema

Ejecuta el archivo `database/schema.sql` que contiene:
- Creación de la base de datos `pollo_fiesta_novedades`
- Tabla de usuarios
- Tabla de novedades con todos los campos
- Usuario por defecto: `usuario` / `123456`

### 3. Opciones de importación

#### Opción A: Desde phpMyAdmin
1. Abre phpMyAdmin (http://localhost/phpmyadmin)
2. Clic en "Importar" en el menú superior
3. Selecciona el archivo `database/schema.sql`
4. Clic en "Continuar"

#### Opción B: Desde línea de comandos
```bash
mysql -u root -p < database/schema.sql
```

#### Opción C: Copiar y pegar
1. Abre el archivo `database/schema.sql`
2. Copia todo el contenido
3. En phpMyAdmin, ve a la pestaña "SQL"
4. Pega el contenido y ejecuta

### 4. Verificar la instalación

Después de importar, deberías ver:
- Base de datos: `pollo_fiesta_novedades`
- Tabla `usuarios` con 1 registro
- Tabla `novedades` vacía

### 5. Configuración en config.php

El archivo `config/config.php` ya está configurado con:
```php
'database' => [
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'pollo_fiesta_novedades',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
]
```

Si tu MySQL tiene contraseña, actualiza el campo `password`.

### 6. Características de la BD

- **Filtrado por zonas**: Puedes filtrar novedades por área de trabajo
- **Filtrado por sede**: Filtra por sede específica
- **Filtrado por fechas**: Rango de fechas personalizado
- **Estadísticas**: Resumen de novedades por zona
- **Índices optimizados**: Para búsquedas rápidas

### 7. Credenciales de acceso

- Usuario: `usuario`
- Contraseña: `123456`

## Estructura de la tabla novedades

La tabla incluye todos los campos del formulario:
- Información personal (nombres, cédula)
- Información laboral (sede, área)
- Detalles de novedad (fecha, turno, tipo)
- Documentación (archivos adjuntos)
- Información adicional (observaciones, notas)
- Correcciones
- Timestamps automáticos

## Soporte

Si tienes problemas con la instalación:
1. Verifica que MySQL esté corriendo
2. Verifica las credenciales en config.php
3. Revisa los logs de error de PHP

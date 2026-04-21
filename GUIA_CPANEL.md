# Guía de Instalación en cPanel - Sistema de Novedades

## 📋 Requisitos Previos

- Acceso a cPanel
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Espacio en disco: mínimo 100MB

## 🚀 Pasos de Instalación

### 1. Preparar los Archivos

#### A. Desactivar errores de PHP para producción
Editar `public/index.php` y cambiar:

```php
// Cambiar esto:
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Por esto:
error_reporting(0);
ini_set('display_errors', 0);
```

#### B. Crear archivo .htaccess en la raíz del proyecto

Crear archivo `.htaccess` en la raíz (junto a `public/`, `app/`, etc.):

```apache
# Redirigir todo al directorio public
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### C. Verificar que existe `.htaccess` en `public/`

El archivo `public/.htaccess` ya existe, verificar que contenga:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

### 2. Crear Base de Datos en cPanel

1. **Ir a MySQL® Databases** en cPanel
2. **Crear nueva base de datos**:
   - Nombre: `pollo_fiesta_novedades` (o el que prefieras)
   - Click en "Create Database"

3. **Crear usuario de base de datos**:
   - Usuario: `pollo_user` (o el que prefieras)
   - Contraseña: Generar una segura
   - Click en "Create User"

4. **Asignar usuario a la base de datos**:
   - Seleccionar el usuario y la base de datos
   - Marcar "ALL PRIVILEGES"
   - Click en "Make Changes"

5. **Anotar los datos**:
   ```
   Host: localhost
   Database: cpanel_usuario_pollo_fiesta_novedades
   Username: cpanel_usuario_pollo_user
   Password: [la contraseña generada]
   ```
   
   **NOTA**: cPanel agrega un prefijo a los nombres (tu usuario de cPanel)

### 3. Importar la Base de Datos

1. **Ir a phpMyAdmin** en cPanel
2. **Seleccionar la base de datos** creada
3. **Click en "Import"**
4. **Subir el archivo** `database/schema.sql`
5. **Click en "Go"**
6. **Ejecutar script de áreas**:
   - Ir a la pestaña "SQL"
   - Copiar y pegar el contenido de `database/agregar_areas_directo.php` adaptado a SQL
   - O ejecutar el script PHP después de subir los archivos

### 4. Subir Archivos al Servidor

#### Opción A: File Manager de cPanel (Recomendado)

1. **Comprimir el proyecto** en tu PC:
   - Crear un archivo ZIP con todo el proyecto
   - Nombre: `sistema-novedades.zip`

2. **Ir a File Manager** en cPanel

3. **Navegar a `public_html`** (o el directorio de tu dominio)

4. **Subir el archivo ZIP**:
   - Click en "Upload"
   - Seleccionar el archivo ZIP
   - Esperar a que termine

5. **Extraer el archivo**:
   - Click derecho en el archivo ZIP
   - Seleccionar "Extract"
   - Extraer en el directorio actual

6. **Mover archivos** (si es necesario):
   - Si quieres que esté en la raíz del dominio, mover todo el contenido
   - O crear un subdirectorio como `novedades/`

#### Opción B: FTP (Alternativa)

1. **Usar FileZilla o similar**
2. **Conectar con credenciales FTP** de cPanel
3. **Subir todos los archivos** al directorio deseado

### 5. Configurar la Aplicación

#### A. Editar `config/config.php`

```php
return [
    'app' => [
        'name' => 'Sistema de Novedades - Pollo Fiesta',
        'version' => '1.0.0',
        'url' => 'https://tudominio.com', // CAMBIAR por tu dominio
    ],
    
    'database' => [
        'type' => 'mysql',
        'json_file' => STORAGE_PATH . '/novedades.json',
        
        // Configuración MySQL - ACTUALIZAR CON TUS DATOS
        'host' => 'localhost',
        'database' => 'cpanel_usuario_pollo_fiesta_novedades', // CAMBIAR
        'username' => 'cpanel_usuario_pollo_user', // CAMBIAR
        'password' => 'TU_CONTRASEÑA_AQUI', // CAMBIAR
        'charset' => 'utf8mb4'
    ],
    
    'upload' => [
        'path' => STORAGE_PATH . '/uploads',
        'max_files' => 3,
        'max_size' => 100 * 1024 * 1024,
        'allowed_types' => ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mp3', 'wav'],
    ],
    
    'auth' => [
        'username' => 'usuario',
        'password' => '123456'
    ],

    'mail' => [
        'host'       => 'mail.tudominio.com', // CAMBIAR
        'port'       => 465,
        'encryption' => 'ssl',
        'username'   => 'innovacion@tudominio.com', // CAMBIAR
        'password'   => 'TU_CONTRASEÑA_EMAIL', // CAMBIAR
        'from_email' => 'innovacion@tudominio.com', // CAMBIAR
        'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
    ]
];
```

### 6. Configurar Permisos

En File Manager de cPanel, configurar permisos:

1. **Directorio `storage/uploads/`**:
   - Click derecho → Permissions
   - Establecer: `755` o `777` (si 755 no funciona)
   - Marcar "Recurse into subdirectories"

2. **Archivo `.htaccess`**:
   - Permisos: `644`

### 7. Configurar el Dominio (si es necesario)

#### Si instalaste en un subdirectorio:

Ejemplo: `https://tudominio.com/novedades/`

Editar `config/config.php`:
```php
'url' => 'https://tudominio.com/novedades',
```

#### Si instalaste en la raíz:

Ejemplo: `https://novedades.tudominio.com/`

1. **Crear subdominio** en cPanel (si aplica)
2. **Apuntar el document root** a la carpeta `public/`

### 8. Probar la Instalación

1. **Acceder a la URL**:
   ```
   https://tudominio.com/
   o
   https://tudominio.com/novedades/
   ```

2. **Verificar que carga el login**

3. **Iniciar sesión**:
   - Usuario: `admin`
   - Contraseña: `admin123`
   
   O el usuario que hayas creado en la base de datos

4. **Probar funcionalidades**:
   - Ver novedades
   - Crear nueva novedad
   - Subir archivos
   - Ver estadísticas (si eres Johanna)

### 9. Seguridad Post-Instalación

#### A. Cambiar contraseñas por defecto

Ejecutar en phpMyAdmin:

```sql
-- Cambiar contraseña del usuario admin
UPDATE usuarios 
SET password = '$2y$10$TU_HASH_AQUI' 
WHERE usuario = 'admin';
```

Para generar el hash, usar:
```php
<?php echo password_hash('tu_nueva_contraseña', PASSWORD_DEFAULT); ?>
```

#### B. Proteger directorios sensibles

Crear archivo `.htaccess` en `/app/`, `/config/`, `/database/`:

```apache
Order deny,allow
Deny from all
```

#### C. Configurar SSL (HTTPS)

1. En cPanel, ir a **SSL/TLS Status**
2. Activar **AutoSSL** para tu dominio
3. O instalar certificado Let's Encrypt

#### D. Configurar copias de seguridad

1. En cPanel, ir a **Backup**
2. Configurar backups automáticos
3. O usar **JetBackup** si está disponible

### 10. Configuración de Email (Opcional)

Si los correos no se envían:

1. **Verificar configuración SMTP** en `config/config.php`
2. **Usar el servidor de correo de cPanel**:
   ```php
   'host' => 'localhost', // o mail.tudominio.com
   'port' => 587, // o 465 para SSL
   ```

3. **Crear cuenta de correo** en cPanel:
   - Email Accounts → Create
   - Usar esas credenciales en la config

### 11. Optimizaciones (Opcional)

#### A. Activar OPcache

En cPanel → Select PHP Version → Options:
- Activar `opcache`

#### B. Aumentar límites de PHP

En cPanel → Select PHP Version → Options:
- `upload_max_filesize`: 100M
- `post_max_size`: 100M
- `max_execution_time`: 300
- `memory_limit`: 256M

#### C. Configurar Cron Jobs (para tareas programadas)

Si necesitas ejecutar tareas automáticas:
1. cPanel → Cron Jobs
2. Agregar comando PHP

## 📝 Checklist Final

- [ ] Base de datos creada e importada
- [ ] Archivos subidos al servidor
- [ ] `config/config.php` actualizado con datos correctos
- [ ] Permisos de `storage/uploads/` configurados (755 o 777)
- [ ] `.htaccess` en raíz y en `public/`
- [ ] Errores de PHP desactivados en producción
- [ ] SSL/HTTPS configurado
- [ ] Login funciona correctamente
- [ ] Se pueden crear novedades
- [ ] Se pueden subir archivos
- [ ] Correos se envían correctamente (si aplica)
- [ ] Contraseñas por defecto cambiadas

## 🆘 Solución de Problemas

### Error 500 - Internal Server Error

1. Verificar permisos de archivos y directorios
2. Revisar `.htaccess` (comentar líneas si es necesario)
3. Verificar logs de error en cPanel → Error Log

### No se conecta a la base de datos

1. Verificar credenciales en `config/config.php`
2. Verificar que el usuario tiene permisos en la BD
3. Probar conexión desde phpMyAdmin

### No se suben archivos

1. Verificar permisos de `storage/uploads/` (755 o 777)
2. Verificar límites de PHP (upload_max_filesize)
3. Verificar que el directorio existe

### Rutas no funcionan (404)

1. Verificar que `.htaccess` existe en `public/`
2. Verificar que mod_rewrite está activado
3. Ajustar RewriteBase en `.htaccess`

### Los estilos no cargan

1. Verificar la URL base en `config/config.php`
2. Verificar rutas en el navegador (F12 → Network)
3. Ajustar rutas si instalaste en subdirectorio

## 📞 Soporte

Si tienes problemas:
1. Revisar logs de error en cPanel
2. Activar temporalmente errores de PHP
3. Verificar la consola del navegador (F12)

## 🎉 ¡Listo!

Tu sistema de novedades está ahora en producción en cPanel.

**URL de acceso**: https://tudominio.com/
**Usuario por defecto**: admin
**Contraseña por defecto**: admin123 (¡CAMBIAR!)

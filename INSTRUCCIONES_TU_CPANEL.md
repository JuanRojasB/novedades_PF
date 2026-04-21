# Instrucciones Específicas para Tu cPanel

## 📊 Base de Datos Detectada

**Nombre de la BD**: `wwpoll_informe_novedades` ✓

Esto significa que tu usuario de cPanel es: **`wwpoll`**

## 🔧 Configuración Específica

### 1. Usuario de Base de Datos

Tu usuario de BD probablemente es uno de estos:
- `wwpoll_admin`
- `wwpoll_user`
- `wwpoll_pollo`

**Para verificar**:
1. Ve a cPanel → **MySQL® Databases**
2. Busca en la sección **"Current Users"**
3. Anota el nombre completo del usuario

### 2. Configurar config.php

Después de subir los archivos a cPanel:

1. **Ir a File Manager** en cPanel
2. **Navegar a**: `public_html/tu-carpeta/config/`
3. **Renombrar**: `config.cpanel.php` → `config.php`
4. **Editar** `config.php` y cambiar:

```php
'database' => 'wwpoll_informe_novedades', // ✓ Ya está correcto
'username' => 'wwpoll_XXXXX', // CAMBIAR por tu usuario real
'password' => 'TU_CONTRASEÑA', // CAMBIAR por tu contraseña
```

### 3. Importar Base de Datos

1. **Ir a phpMyAdmin** en cPanel
2. **Seleccionar** la base de datos `wwpoll_informe_novedades`
3. **Click en "Import"**
4. **Subir** el archivo `database/schema.sql`
5. **Click en "Go"**
6. **Esperar** a que termine (puede tardar 1-2 minutos)
7. **Ir a la pestaña "SQL"**
8. **Copiar y pegar** el contenido de `database/EJECUTAR_EN_CPANEL.sql`
9. **Click en "Go"**

### 4. Verificar Importación

En phpMyAdmin, ejecutar esta consulta para verificar:

```sql
-- Verificar sedes
SELECT nombre FROM sedes ORDER BY nombre;

-- Verificar áreas por sede
SELECT s.nombre AS Sede, COUNT(a.id) AS Total_Areas
FROM sedes s
LEFT JOIN areas_trabajo a ON s.id = a.sede_id
GROUP BY s.id, s.nombre
ORDER BY s.nombre;

-- Verificar usuarios
SELECT usuario, nombre, rol FROM usuarios;
```

**Resultado esperado**:
- 12 sedes (sin "Producción")
- 44 áreas en total
- Granjas debe tener 3 áreas: Granjas, Producción, Procesados
- Al menos 1 usuario (admin)

### 5. Estructura de Directorios en cPanel

Tu estructura debería verse así:

```
public_html/
├── tu-carpeta-del-sistema/
│   ├── app/
│   ├── config/
│   │   └── config.php (renombrado de config.cpanel.php)
│   ├── database/
│   ├── public/
│   │   ├── assets/
│   │   ├── index.php
│   │   └── .htaccess
│   ├── storage/
│   │   └── uploads/ (permisos 755 o 777)
│   ├── .htaccess
│   └── ...
```

### 6. Configurar URL

Si instalaste en:

**Opción A: Raíz del dominio**
```
https://tudominio.com/
```
En `config.php`:
```php
'url' => 'https://tudominio.com',
```

**Opción B: Subdirectorio**
```
https://tudominio.com/novedades/
```
En `config.php`:
```php
'url' => 'https://tudominio.com/novedades',
```

**Opción C: Subdominio**
```
https://novedades.tudominio.com/
```
En `config.php`:
```php
'url' => 'https://novedades.tudominio.com',
```

### 7. Permisos Importantes

En File Manager, configurar:

1. **storage/uploads/**
   - Click derecho → Change Permissions
   - Establecer: `755` (primero intentar esto)
   - Si no funciona, cambiar a `777`
   - ✓ Marcar "Recurse into subdirectories"

2. **.htaccess** (raíz y public/)
   - Permisos: `644`

3. **config/config.php**
   - Permisos: `644`

### 8. Probar el Sistema

1. **Acceder a tu URL**:
   ```
   https://tudominio.com/tu-carpeta/
   ```

2. **Debe aparecer el login**

3. **Iniciar sesión**:
   - Usuario: `admin`
   - Contraseña: `admin123`

4. **Si funciona**: ¡Listo! 🎉

5. **Si no funciona**: Ver sección de errores abajo

## 🆘 Solución de Errores Comunes

### Error: "No se puede conectar a la base de datos"

**Solución**:
1. Verificar que el usuario de BD existe en cPanel
2. Verificar que el usuario tiene permisos en la BD
3. Verificar credenciales en `config/config.php`
4. Probar conexión desde phpMyAdmin

**Verificar en phpMyAdmin**:
```sql
SELECT USER(), DATABASE();
```

### Error 500 - Internal Server Error

**Solución**:
1. Verificar permisos de `storage/uploads/` (755 o 777)
2. Revisar `.htaccess` en raíz y en `public/`
3. Ver Error Log en cPanel → Metrics → Errors
4. Verificar que PHP 7.4+ está activo

### No cargan los estilos CSS

**Solución**:
1. Verificar la URL en `config/config.php`
2. Abrir navegador → F12 → Network
3. Ver qué archivos fallan
4. Ajustar rutas si es necesario

### No se suben archivos

**Solución**:
1. Permisos de `storage/uploads/` en `777`
2. En cPanel → Select PHP Version → Options:
   - `upload_max_filesize`: 100M
   - `post_max_size`: 100M
   - `max_execution_time`: 300

## 📝 Datos para Anotar

```
═══════════════════════════════════════════════════════════

DATOS DE TU INSTALACIÓN:

Base de Datos:
  Host:     localhost
  Database: wwpoll_informe_novedades ✓
  Username: wwpoll_______________ (completar)
  Password: _____________________ (completar)

URL del Sistema:
  URL: https://________________________

Usuario Admin:
  Usuario:  admin
  Password: admin123 (CAMBIAR después!)

Correo SMTP (opcional):
  Host:     mail.tudominio.com
  Port:     465 (SSL) o 587 (TLS)
  Email:    innovacion@tudominio.com
  Password: _____________________

═══════════════════════════════════════════════════════════
```

## ✅ Checklist Rápido

- [ ] Base de datos `wwpoll_informe_novedades` existe
- [ ] Usuario de BD creado y con permisos
- [ ] Archivos subidos a cPanel
- [ ] `config.cpanel.php` renombrado a `config.php`
- [ ] `config.php` editado con credenciales correctas
- [ ] `database/schema.sql` importado en phpMyAdmin
- [ ] `database/EJECUTAR_EN_CPANEL.sql` ejecutado
- [ ] Permisos de `storage/uploads/` configurados (755 o 777)
- [ ] Sistema probado y funciona
- [ ] Contraseña de admin cambiada

## 🎯 Siguiente Paso

1. **Comprimir el proyecto** en ZIP
2. **Subir a cPanel** vía File Manager
3. **Extraer** el ZIP
4. **Seguir esta guía** paso a paso

¡Éxito con la instalación! 🚀

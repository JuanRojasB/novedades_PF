# 🚀 Instalación Final en cPanel - Sistema de Novedades

## ✅ Pre-requisitos Completados
- ✅ Sistema completamente desarrollado y probado
- ✅ Base de datos configurada: `wwpoll_informe_novedades`
- ✅ 50+ correos corporativos configurados
- ✅ Archivos de prueba eliminados
- ✅ Configuración optimizada para producción

## 📋 Pasos de Instalación

### 1. 📁 Subir Archivos
Sube **TODOS** los archivos del proyecto a tu cPanel, manteniendo la estructura:
```
public_html/
├── app/
├── config/
├── database/
├── public/
├── storage/
├── .htaccess
├── README.md
└── *.md (archivos de documentación)
```

### 2. 🔧 Configurar Base de Datos
La base de datos ya está configurada, pero verifica:
- **Nombre:** `wwpoll_informe_novedades`
- **Usuario:** `wwpoll_admin_novedades`
- **Contraseña:** `^8znu9HDk[D2#)y-`

### 3. ⚙️ Configurar Archivo de Configuración
**IMPORTANTE:** Renombra el archivo de configuración:
```bash
# En cPanel File Manager:
config/config.cpanel.php → config/config.php
```

### 4. 📧 Verificar Configuración de Correo
En `config/config.php`, confirma:
```php
'mail' => [
    'host'       => 'pollo-fiesta.com',
    'port'       => 465,
    'encryption' => 'ssl',
    'username'   => 'innovacion@pollo-fiesta.com',
    'password'   => '^8znu9HDk[D2#)y-', // ⚠️ CAMBIAR por la contraseña real
    'from_email' => 'innovacion@pollo-fiesta.com',
    'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
]
```

### 5. 🔐 Configurar Permisos
Asegúrate de que la carpeta `storage/` tenga permisos de escritura:
```bash
chmod 755 storage/
chmod 755 storage/uploads/
```

### 6. 🌐 Configurar URL del Dominio
En `config/config.php`, actualiza:
```php
'app' => [
    'url' => 'https://pollo-fiesta.com', // ⚠️ CAMBIAR por tu dominio real
]
```

## 🧪 Verificación Post-Instalación

### 1. Acceso al Sistema
- **URL:** `https://tu-dominio.com`
- **Usuario:** `usuario`
- **Contraseña:** `123456`

### 2. Probar Funcionalidades
1. ✅ **Login:** Acceder con credenciales
2. ✅ **Formulario:** Crear nueva novedad
3. ✅ **Correos:** Verificar envío automático
4. ✅ **Dashboard:** Solo Johanna puede ver (usuarios normales van al formulario)
5. ✅ **Filtros:** Probar filtros con checkboxes
6. ✅ **Paginación:** Verificar navegación entre páginas

### 3. Verificar Correos
- Los correos se envían automáticamente a `innovacion@pollo-fiesta.com`
- Incluyen el ID de la novedad
- Formato HTML profesional

## 👥 Usuarios del Sistema

### Johanna (Administradora)
- **Acceso completo:** Dashboard, Estadísticas, Todas las sedes/áreas
- **Detección:** Sistema busca "johanna" en el nombre del usuario
- **Después de guardar:** Va al listado de novedades

### Usuarios Normales
- **Acceso limitado:** Solo formulario de creación
- **Sede/Área:** Restringida según su asignación
- **Después de guardar:** Vuelve al formulario

## 📊 Datos del Sistema
- **Total novedades:** 1,689+ registros reales
- **Usuarios configurados:** 50+ con correos corporativos
- **Sedes:** Sede 1, Sede 2, Sede 3
- **Áreas por sede:**
  - Sede 1: Asadero + Despachos
  - Sede 2: Despachos
  - Sede 3: Posproceso + Despachos

## 🔧 Configuraciones Importantes

### Archivos Clave
- `config/config.php` - Configuración principal
- `app/Controllers/NovedadController.php` - Lógica principal
- `app/Helpers/MailHelper.php` - Envío de correos
- `database/EJECUTAR_EN_CPANEL.sql` - Script de BD completo

### Características del Sistema
- ✅ **Responsive:** Funciona en móviles y tablets
- ✅ **Seguro:** Validación de archivos y datos
- ✅ **Optimizado:** Paginación y filtros eficientes
- ✅ **Profesional:** Diseño moderno y funcional

## 🆘 Solución de Problemas

### Si no llegan los correos:
1. Verificar credenciales SMTP en `config/config.php`
2. Confirmar que el puerto 465 esté abierto
3. Verificar que el dominio resuelva correctamente

### Si hay errores de BD:
1. Verificar credenciales en `config/config.php`
2. Ejecutar `database/EJECUTAR_EN_CPANEL.sql`
3. Verificar que la BD tenga todas las tablas

### Si no se pueden subir archivos:
1. Verificar permisos de `storage/uploads/`
2. Confirmar que el directorio existe
3. Verificar límites de PHP (upload_max_filesize)

## 📞 Contacto
Para soporte técnico, contactar al equipo de desarrollo con los detalles del error específico.

---
**✅ Sistema listo para producción - Versión 1.0.0**
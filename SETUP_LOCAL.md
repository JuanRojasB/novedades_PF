# 🚀 Configuración Local - Sistema de Novedades

## Requisitos Previos

- **XAMPP/WAMP/MAMP** o servidor local con PHP 7.4+ y MySQL
- **Composer** (opcional, ya incluido en vendor/)
- Navegador web moderno

## Pasos de Instalación

### 1. Clonar/Descargar el proyecto
```bash
# Colocar en la carpeta del servidor web
# Ejemplo: C:\xampp\htdocs\informe-novedades\
```

### 2. Iniciar servicios
- ✅ **Apache** (puerto 80)
- ✅ **MySQL** (puerto 3306)

### 3. Ejecutar configuración automática
Abrir en el navegador:
```
http://localhost/informe-novedades/setup_local.php
```

Este script:
- ✅ Crea la base de datos `wwpoll_informe_novedades`
- ✅ Crea todas las tablas necesarias
- ✅ Inserta usuarios de prueba
- ✅ Inserta datos básicos (sedes, áreas, tipos de novedad)
- ✅ Inserta algunas novedades de ejemplo

### 4. Acceder al sistema
```
http://localhost/informe-novedades/public/
```

## 👥 Usuarios de Prueba

| Usuario | Contraseña | Rol | Acceso |
|---------|------------|-----|--------|
| `jrios` | `jrios2026*` | Director | Dashboard completo |
| `ebecerra` | `ebecerra2026*` | Gestión Humana | Dashboard completo |
| `cortiz` | `cortiz2026*` | Gestión Humana | Dashboard completo |
| `cmartinez` | `cmartinez2026*` | Gestión Humana | Dashboard completo |
| `mvelandia` | `mvelandia2026*` | Gestión Humana | Dashboard completo |
| `usuario` | `123456` | Usuario normal | Solo formulario |

## 📧 Configuración de Correos

En **local**, los correos se guardan como archivos HTML en:
```
storage/correo_YYYY-MM-DD_HH-MM-SS_ID.html
```

Para cambiar a envío real, editar `config/config.php`:
```php
'mail' => [
    'mode' => 'smtp', // Cambiar de 'file' a 'smtp'
    // ... resto de configuración SMTP
]
```

## 🗂️ Estructura de Archivos

```
informe-novedades/
├── app/
│   ├── Controllers/     # Controladores
│   ├── Models/         # Modelos de datos
│   ├── Views/          # Vistas HTML
│   ├── Core/           # Clases base
│   └── Helpers/        # Utilidades
├── config/             # Configuración
├── database/           # Scripts SQL
├── public/             # Punto de entrada web
│   ├── assets/         # CSS, JS, imágenes
│   └── index.php       # Archivo principal
├── storage/            # Archivos subidos y correos
└── vendor/             # Dependencias PHP
```

## 🔧 Configuración Avanzada

### Base de Datos
Archivo: `config/config.php`
```php
'database' => [
    'host' => 'localhost',
    'database' => 'wwpoll_informe_novedades',
    'username' => 'root',
    'password' => '',
]
```

### URL Base
```php
'app' => [
    'url' => 'http://localhost/informe-novedades',
    'environment' => 'local',
]
```

## 🐛 Solución de Problemas

### Error de conexión a MySQL
- ✅ Verificar que MySQL esté ejecutándose
- ✅ Verificar usuario/contraseña en `config/config.php`
- ✅ Crear la base de datos manualmente si es necesario

### Error 404 - Página no encontrada
- ✅ Verificar que el proyecto esté en la carpeta correcta del servidor
- ✅ Acceder a `http://localhost/informe-novedades/public/`
- ✅ Verificar que Apache esté ejecutándose

### Problemas con archivos subidos
- ✅ Verificar permisos de escritura en carpeta `storage/`
- ✅ En Windows: dar permisos completos a la carpeta

### Correos no se envían
- ✅ En local, verificar que `'mode' => 'file'` en config
- ✅ Los correos se guardan en `storage/` como archivos HTML

## 📊 Funcionalidades Principales

- ✅ **Dashboard de estadísticas** (solo usuarios autorizados)
- ✅ **Registro de novedades** con archivos adjuntos
- ✅ **Filtros avanzados** por sede, área, tipo, fecha
- ✅ **Búsqueda en tiempo real**
- ✅ **Gráficos interactivos** con Chart.js
- ✅ **Sistema de correos** automático
- ✅ **Gestión de usuarios** y permisos
- ✅ **Responsive design** para móviles

## 🚀 Siguiente Paso

Una vez configurado localmente, puedes:
1. **Probar todas las funcionalidades**
2. **Verificar el diseño de la sección de usuarios**
3. **Hacer ajustes necesarios**
4. **Subir cambios a producción**

---

**¿Problemas?** Revisa los logs en:
- PHP: `error_log` del servidor
- Aplicación: Mensajes en pantalla
- Base de datos: phpMyAdmin en `http://localhost/phpmyadmin`
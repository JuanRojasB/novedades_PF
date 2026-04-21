# Sistema de Permisos de Usuarios

## Resumen

El sistema tiene dos niveles de acceso:

1. **Johanna** (administradora única) - Acceso completo a todo
2. **Usuarios normales** (todos los demás) - Solo pueden crear novedades

## Permisos por Usuario

### JOHANNA (Administradora Única)
✅ Ver listado completo de novedades  
✅ Crear nuevas novedades  
✅ Ver estadísticas y gráficos  
✅ Acceso a administración  
✅ Filtrar y buscar novedades  
✅ Ver todas las sedes y áreas  

**Menú visible:**
- Ver Novedades (dashboard con listado completo)
- Estadísticas (gráficos y análisis)
- Administración

**Dashboard incluye:**
- Listado completo de todas las novedades
- Filtros por sede, área, fecha
- Búsqueda por empleado, cédula
- Ordenamiento de columnas
- Estadísticas resumidas

**Estadísticas incluye:**
- Gráficos por sede
- Gráficos por tipo de novedad
- Gráficos por justificación
- Gráficos por área
- Gráficos por turno
- Análisis de descuento dominical
- Tendencias por mes
- Top responsables
- Conclusiones automáticas de Copilot

### USUARIOS NORMALES (Todos los demás)
✅ Crear nuevas novedades  
❌ NO pueden ver el listado de novedades  
❌ NO pueden ver estadísticas  
❌ NO pueden acceder a administración  

**Menú visible:**
- (Ningún enlace, solo info de usuario y cerrar sesión)

**Comportamiento:**
- Al iniciar sesión → Redirige automáticamente al formulario de crear novedad
- Después de guardar una novedad → Vuelve al formulario (puede crear otra)
- NO tienen acceso a `/novedades` (listado)

## Restricciones de Formulario

### Johanna
- Ve **todas las sedes** disponibles
- Ve **todas las áreas** disponibles
- Puede crear novedades para cualquier sede/área

### Usuarios Normales
- Ven **solo SU sede asignada**
- Ven **solo SU área asignada**
- Solo pueden crear novedades para su sede/área específica

## Notificaciones por Correo

Cuando un usuario crea una novedad:
- Se envía un correo al **correo corporativo del usuario que llenó el formulario**
- Si el usuario NO tiene correo configurado → Se envía a `innovacion@pollo-fiesta.com`

## Flujo de Usuarios Normales

```
1. Login → Autenticación exitosa
2. Redirección automática → /novedades/crear (formulario)
3. Usuario llena el formulario con:
   - Datos del empleado
   - Su sede (pre-seleccionada, solo 1 opción)
   - Su área (pre-seleccionada, solo 1 opción)
   - Tipo de novedad
   - Justificación
   - Archivos adjuntos (opcional)
4. Guardar novedad
5. Sistema envía correo al usuario
6. Redirección → /novedades/crear (puede crear otra)
```

## Flujo de Johanna

```
1. Login → Autenticación exitosa
2. Redirección → /novedades (listado completo)
3. Puede:
   - Ver todas las novedades
   - Filtrar por sede, área, fecha
   - Buscar por empleado, cédula
   - Ordenar columnas
   - Crear nuevas novedades
   - Ver estadísticas
   - Administrar el sistema
```

## Verificación de Permisos

El sistema verifica el nombre del usuario en cada acción:

```php
if (strtolower($user['nombre']) === 'johanna') {
    // Acceso completo
} else {
    // Solo crear novedades
}
```

**Nota:** La verificación es case-insensitive (no importa mayúsculas/minúsculas)

## Rutas Protegidas

| Ruta | Johanna | Usuarios Normales |
|------|---------|-------------------|
| `/novedades` | ✅ Dashboard completo con listado | ❌ Redirige a `/novedades/crear` |
| `/novedades/crear` | ✅ Formulario completo | ✅ Formulario restringido |
| `/estadisticas` | ✅ Gráficos y análisis completo | ❌ Redirige a `/novedades/crear` |
| `/admin` | ✅ Acceso completo | ❌ Acceso denegado |

## Resumen de Accesos

### Dashboard (Ver Novedades)
**Solo Johanna tiene acceso**

Incluye:
- Tabla con todas las novedades registradas
- Filtros: sede, área, fecha desde/hasta
- Búsqueda: por empleado o cédula
- Ordenamiento: click en columnas (ID, Fecha, Empleado, etc.)
- Botón "Nueva Novedad"
- Estadísticas resumidas en cards

### Estadísticas y Gráficos
**Solo Johanna tiene acceso**

Incluye:
- Gráfico de novedades por sede
- Gráfico de novedades por tipo
- Gráfico de justificación vs no justificación
- Gráfico de novedades por área
- Gráfico de novedades por turno
- Análisis de descuento dominical
- Tendencias mensuales
- Top 10 responsables
- Conclusiones automáticas de Copilot

### Formulario de Creación
**Johanna:** Acceso completo (todas las sedes y áreas)  
**Usuarios normales:** Acceso restringido (solo su sede y área)

## Cambios Implementados

### 1. Navbar (Menú)
- **Antes:** Todos veían "Ver Novedades"
- **Ahora:** Solo Johanna ve "Ver Novedades", "Estadísticas", "Administración"

### 2. Controlador NovedadController::index()
- **Antes:** Filtraba novedades por responsable para usuarios normales
- **Ahora:** Redirige directamente a `/novedades/crear` si NO es Johanna

### 3. Controlador NovedadController::guardar()
- **Antes:** Todos iban al listado después de guardar
- **Ahora:** 
  - Johanna → Va al listado (`/novedades`)
  - Usuarios normales → Vuelven al formulario (`/novedades/crear`)

### 4. Notificaciones
- **Antes:** Correo fijo a `innovacion@pollo-fiesta.com`
- **Ahora:** Correo al usuario que llena el formulario (según su correo corporativo)

## Agregar Nuevo Usuario con Acceso Completo

Si necesitas dar acceso completo a otro usuario (además de Johanna):

1. **Opción 1:** Cambiar el nombre del usuario en la BD a "Johanna"
2. **Opción 2:** Modificar el código en:
   - `app/Views/layouts/navbar.php`
   - `app/Controllers/NovedadController.php`

```php
// Cambiar de:
if (strtolower($user['nombre']) === 'johanna')

// A:
if (in_array(strtolower($user['nombre']), ['johanna', 'otro_usuario']))
```

## Seguridad

- Todas las rutas requieren autenticación (`$this->requireAuth()`)
- Las verificaciones se hacen en el servidor (PHP), no solo en el frontend
- Los usuarios normales NO pueden acceder al listado aunque intenten la URL directa
- Las redirecciones son automáticas y transparentes

## Testing

Para probar el sistema:

1. **Login como Johanna:**
   - Usuario: `admin` (si su nombre es "Johanna" en la BD)
   - Debe ver: Menú completo, listado de novedades

2. **Login como usuario normal:**
   - Usuario: cualquier otro (ej: `usuario`, `hbenito`, etc.)
   - Debe ver: Solo formulario, sin menú de navegación
   - Al guardar: Vuelve al formulario

3. **Intentar acceso directo:**
   - Usuario normal intenta ir a `/novedades`
   - Debe redirigir automáticamente a `/novedades/crear`

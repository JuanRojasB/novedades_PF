# Probar el Sistema como Johanna

## Usuario Johanna Configurado ✅

**Usuario:** `admin`  
**Contraseña:** `admin123`  
**Nombre:** `Johanna`  
**Rol:** `director`

## Acceso de Johanna

### 1. Iniciar Sesión

```
URL: http://localhost/informe-novedades/public/
Usuario: admin
Contraseña: admin123
```

### 2. Menú Visible

Después de iniciar sesión, Johanna debe ver:

```
┌─────────────────────────────────────────────────────┐
│  Ver Novedades  |  Estadísticas  |  Administración  │
└─────────────────────────────────────────────────────┘
```

### 3. Ver Novedades (Dashboard)

**Ruta:** `/novedades`

Johanna debe ver:
- ✅ Listado completo de TODAS las novedades
- ✅ Filtros por sede, área, fecha
- ✅ Búsqueda por empleado o cédula
- ✅ Ordenamiento de columnas (click en headers)
- ✅ Botón "Nueva Novedad"
- ✅ Estadísticas resumidas en cards

**Probar:**
1. Click en "Ver Novedades"
2. Verificar que aparece el dashboard completo
3. Probar filtros
4. Probar búsqueda
5. Probar ordenamiento (click en columnas)

### 4. Estadísticas y Gráficos

**Ruta:** `/estadisticas`

Johanna debe ver:
- ✅ Gráfico de novedades por sede
- ✅ Gráfico de novedades por tipo
- ✅ Gráfico de justificación vs no justificación
- ✅ Gráfico de novedades por área
- ✅ Gráfico de novedades por turno
- ✅ Análisis de descuento dominical
- ✅ Tendencias mensuales
- ✅ Top 10 responsables
- ✅ Conclusiones de Copilot

**Probar:**
1. Click en "Estadísticas"
2. Verificar que aparecen todos los gráficos
3. Verificar que los datos se muestran correctamente

### 5. Crear Nueva Novedad

**Ruta:** `/novedades/crear`

Johanna debe ver:
- ✅ TODAS las sedes disponibles (no solo una)
- ✅ TODAS las áreas disponibles (no solo una)
- ✅ Puede crear novedades para cualquier sede/área

**Probar:**
1. Click en "Nueva Novedad" o ir a `/novedades/crear`
2. Verificar que el dropdown de "Sede" tiene TODAS las opciones
3. Verificar que el dropdown de "Área" tiene TODAS las opciones
4. Crear una novedad de prueba
5. Verificar que después de guardar, va al listado (no al formulario)

### 6. Administración

**Ruta:** `/admin`

Johanna debe tener acceso completo.

**Probar:**
1. Click en "Administración"
2. Verificar acceso

## Comparación: Johanna vs Usuario Normal

### Johanna (admin)
```
Login → Dashboard con listado completo
Menú: Ver Novedades | Estadísticas | Administración
Sedes: TODAS
Áreas: TODAS
Después de guardar: Va al listado
```

### Usuario Normal (usuario)
```
Login → Formulario de creación (redirige automáticamente)
Menú: (vacío, solo info de usuario)
Sedes: Solo la suya (1 opción)
Áreas: Solo la suya (1 opción)
Después de guardar: Vuelve al formulario
```

## Casos de Prueba

### ✅ Caso 1: Johanna Ve Todas las Novedades
1. Login como `admin` / `admin123`
2. Debe ir automáticamente al dashboard
3. Debe ver TODAS las novedades de TODOS los usuarios
4. Probar filtros y búsqueda

### ✅ Caso 2: Johanna Ve Estadísticas
1. Login como Johanna
2. Click en "Estadísticas"
3. Debe ver todos los gráficos
4. Verificar que los datos son correctos

### ✅ Caso 3: Johanna Crea Novedad
1. Login como Johanna
2. Click en "Nueva Novedad"
3. Debe ver TODAS las sedes y áreas
4. Crear novedad
5. Debe volver al listado (no al formulario)

### ✅ Caso 4: Usuario Normal NO Ve Dashboard
1. Login como `usuario` / `123456`
2. Debe ir automáticamente al formulario
3. NO debe ver menú de navegación
4. Intentar ir a `/novedades` → Debe redirigir al formulario

### ✅ Caso 5: Usuario Normal NO Ve Estadísticas
1. Login como `usuario` / `123456`
2. Intentar ir a `/estadisticas` → Debe redirigir al formulario
3. Debe mostrar error de permisos

## Verificar Permisos en Código

El sistema verifica el nombre del usuario:

```php
// En NovedadController::index()
if (strtolower($user['nombre']) !== 'johanna') {
    $this->redirect('novedades/crear');
    return;
}

// En NovedadController::estadisticas()
if (strtolower($user['nombre']) !== 'johanna') {
    $_SESSION['error'] = 'No tienes permisos';
    $this->redirect('novedades/crear');
}

// En navbar.php
<?php if (strtolower($_SESSION['user']['nombre']) === 'johanna'): ?>
    <a href="...">Ver Novedades</a>
    <a href="...">Estadísticas</a>
    <a href="...">Administración</a>
<?php endif; ?>
```

## Crear Novedades de Prueba

Para probar el dashboard y estadísticas, crea algunas novedades:

1. **Como Johanna:**
   - Crear 2-3 novedades con diferentes sedes
   - Crear 2-3 novedades con diferentes tipos

2. **Como usuario normal:**
   - Login como `usuario` / `123456`
   - Crear 2-3 novedades (solo verá su sede/área)

3. **Verificar como Johanna:**
   - Login como `admin` / `admin123`
   - Ver que aparecen TODAS las novedades en el dashboard
   - Ver que las estadísticas reflejan todas las novedades

## Verificar en Base de Datos

```sql
-- Ver todas las novedades
SELECT id, nombres_apellidos, sede, area_trabajo, responsable, created_at
FROM novedades
ORDER BY created_at DESC;

-- Ver quién es Johanna
SELECT usuario, nombre, rol
FROM usuarios
WHERE nombre = 'Johanna';

-- Resultado esperado:
-- usuario: admin
-- nombre: Johanna
-- rol: director
```

## Checklist de Pruebas

- [ ] Login como Johanna funciona
- [ ] Johanna ve menú completo (Ver Novedades, Estadísticas, Administración)
- [ ] Johanna ve dashboard con listado completo
- [ ] Johanna puede filtrar y buscar novedades
- [ ] Johanna puede ordenar columnas
- [ ] Johanna ve estadísticas y gráficos
- [ ] Johanna puede crear novedades para cualquier sede/área
- [ ] Johanna vuelve al listado después de crear novedad
- [ ] Usuario normal NO ve menú de navegación
- [ ] Usuario normal NO puede acceder a dashboard
- [ ] Usuario normal NO puede acceder a estadísticas
- [ ] Usuario normal solo ve su sede y área
- [ ] Usuario normal vuelve al formulario después de crear novedad

## Resumen

**Johanna = Acceso Completo**
- Dashboard con todas las novedades ✅
- Estadísticas y gráficos ✅
- Administración ✅
- Todas las sedes y áreas ✅

**Usuarios Normales = Solo Formulario**
- Sin dashboard ❌
- Sin estadísticas ❌
- Sin administración ❌
- Solo su sede y área 🔒

¡Todo está configurado correctamente! 🚀

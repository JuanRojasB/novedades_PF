# ¿Quién Ve Qué en el Sistema?

## 👑 JOHANNA (Administradora Única)

### Menú de Navegación
```
┌─────────────────────────────────────────────────────┐
│  🏠 Ver Novedades  |  📊 Estadísticas  |  ⚙️ Admin  │
└─────────────────────────────────────────────────────┘
```

### 1. Ver Novedades (Dashboard)
**Ruta:** `/novedades`

```
┌────────────────────────────────────────────────────┐
│  📋 DASHBOARD DE NOVEDADES                         │
├────────────────────────────────────────────────────┤
│                                                    │
│  🔍 Búsqueda: [_________________]                 │
│                                                    │
│  📁 Filtros:                                       │
│     Sede: [Todas ▼]                               │
│     Área: [Todas ▼]                               │
│     Desde: [____] Hasta: [____]                   │
│                                                    │
│  📊 Estadísticas Rápidas:                         │
│     Total: 150  |  Este mes: 45  |  Hoy: 3       │
│                                                    │
│  📋 TABLA DE NOVEDADES:                           │
│  ┌──────────────────────────────────────────────┐ │
│  │ ID ↕ | Fecha ↕ | Empleado ↕ | Cédula ↕ | ... │ │
│  │ 150  | 2024... | Juan Pérez | 123456... | ... │ │
│  │ 149  | 2024... | Ana López  | 789012... | ... │ │
│  │ ...                                           │ │
│  └──────────────────────────────────────────────┘ │
│                                                    │
│  [+ Nueva Novedad]                                │
└────────────────────────────────────────────────────┘
```

**Funcionalidades:**
- ✅ Ve TODAS las novedades de TODOS los usuarios
- ✅ Puede filtrar por sede, área, fecha
- ✅ Puede buscar por empleado o cédula
- ✅ Puede ordenar columnas (click en headers)
- ✅ Puede crear nuevas novedades
- ✅ Ve estadísticas resumidas

### 2. Estadísticas (Gráficos)
**Ruta:** `/estadisticas`

```
┌────────────────────────────────────────────────────┐
│  📊 ESTADÍSTICAS Y GRÁFICOS                        │
├────────────────────────────────────────────────────┤
│                                                    │
│  📈 Novedades por Sede                            │
│  [Gráfico de barras]                              │
│                                                    │
│  📈 Novedades por Tipo                            │
│  [Gráfico de pastel]                              │
│                                                    │
│  📈 Justificación vs No Justificación             │
│  [Gráfico de dona]                                │
│                                                    │
│  📈 Novedades por Área                            │
│  [Gráfico de barras horizontales]                 │
│                                                    │
│  📈 Novedades por Turno                           │
│  [Gráfico de barras]                              │
│                                                    │
│  📈 Descuento Dominical                           │
│  [Gráfico de pastel]                              │
│                                                    │
│  📈 Tendencias Mensuales                          │
│  [Gráfico de líneas]                              │
│                                                    │
│  🏆 Top 10 Responsables                           │
│  [Tabla con ranking]                              │
│                                                    │
│  🤖 Conclusiones de Copilot                       │
│  [Análisis automático]                            │
└────────────────────────────────────────────────────┘
```

**Funcionalidades:**
- ✅ Ve gráficos de TODAS las novedades
- ✅ Análisis por sede, tipo, área, turno
- ✅ Tendencias temporales
- ✅ Rankings de responsables
- ✅ Conclusiones automáticas

### 3. Administración
**Ruta:** `/admin`

```
┌────────────────────────────────────────────────────┐
│  ⚙️ PANEL DE ADMINISTRACIÓN                        │
├────────────────────────────────────────────────────┤
│                                                    │
│  👥 Gestión de Usuarios                           │
│  📍 Gestión de Sedes                              │
│  🏢 Gestión de Áreas                              │
│  📝 Gestión de Tipos de Novedad                   │
│  ⚙️ Configuración del Sistema                     │
│                                                    │
└────────────────────────────────────────────────────┘
```

---

## 👤 USUARIOS NORMALES (Todos los demás)

### Menú de Navegación
```
┌─────────────────────────────────────────────────────┐
│  (Sin enlaces de navegación, solo info de usuario) │
└─────────────────────────────────────────────────────┘
```

### Única Vista: Formulario de Creación
**Ruta:** `/novedades/crear` (redirige automáticamente aquí al login)

```
┌────────────────────────────────────────────────────┐
│  📝 NUEVA NOVEDAD                                  │
├────────────────────────────────────────────────────┤
│                                                    │
│  ✅ Formulario enviado correctamente               │
│                                                    │
│  1. Nombres y Apellidos: [___________________]    │
│  2. Número de Cédula: [___________________]       │
│  3. Sede: [Su Sede] (solo 1 opción)              │
│  4. Área: [Su Área] (solo 1 opción)              │
│  5. Fecha de Novedad: [___________________]       │
│  6. Turno: [Seleccionar ▼]                        │
│  7. Novedad: [Seleccionar ▼]                      │
│  8. Justificación: ○ Sí  ○ No                    │
│  9. Archivos: [Subir archivos]                    │
│  10. Descontar Dominical: ○ Sí  ○ No             │
│  11. Observación: [___________________]           │
│  12. Observaciones: [___________________]         │
│                                                    │
│  [Guardar Novedad]                                │
└────────────────────────────────────────────────────┘
```

**Funcionalidades:**
- ✅ Puede crear nuevas novedades
- ✅ Solo ve SU sede asignada (1 opción)
- ✅ Solo ve SU área asignada (1 opción)
- ✅ Después de guardar, vuelve al formulario
- ✅ Ve mensaje de éxito
- ❌ NO puede ver el listado de novedades
- ❌ NO puede ver estadísticas
- ❌ NO puede ver administración

---

## 🔒 Restricciones de Acceso

### Si un usuario normal intenta acceder a:

#### `/novedades` (Dashboard)
```
❌ Redirige automáticamente a → /novedades/crear
```

#### `/estadisticas` (Gráficos)
```
❌ Muestra error: "No tienes permisos"
❌ Redirige a → /novedades/crear
```

#### `/admin` (Administración)
```
❌ Acceso denegado
```

---

## 📊 Comparación Visual

| Funcionalidad | Johanna | Usuarios Normales |
|--------------|---------|-------------------|
| **Ver Dashboard** | ✅ Sí | ❌ No |
| **Ver Listado de Novedades** | ✅ Todas | ❌ Ninguna |
| **Ver Estadísticas** | ✅ Sí | ❌ No |
| **Ver Gráficos** | ✅ Sí | ❌ No |
| **Crear Novedades** | ✅ Sí | ✅ Sí |
| **Sedes Disponibles** | ✅ Todas | 🔒 Solo la suya |
| **Áreas Disponibles** | ✅ Todas | 🔒 Solo la suya |
| **Administración** | ✅ Sí | ❌ No |
| **Filtros y Búsqueda** | ✅ Sí | ❌ No |
| **Ordenar Columnas** | ✅ Sí | ❌ No |

---

## 🎯 Resumen Ejecutivo

### JOHANNA = TODO
- Dashboard completo con listado
- Estadísticas y gráficos
- Administración
- Filtros, búsqueda, ordenamiento
- Crear novedades para cualquier sede/área

### USUARIOS NORMALES = SOLO FORMULARIO
- Solo pueden crear novedades
- Solo ven su sede y área
- No ven listados ni estadísticas
- No tienen menú de navegación

---

## 🔐 Cómo se Verifica

El sistema verifica el nombre del usuario:

```php
if (strtolower($user['nombre']) === 'johanna') {
    // Acceso completo a todo
} else {
    // Solo formulario de creación
}
```

**Nota:** La verificación es case-insensitive (no importa mayúsculas/minúsculas)

---

## 🚀 Para Probar

### Como Johanna:
1. Cambiar nombre en BD: `UPDATE usuarios SET nombre = 'Johanna' WHERE usuario = 'admin';`
2. Login: `admin` / `admin123`
3. Verás: Dashboard, Estadísticas, Administración

### Como Usuario Normal:
1. Login: `usuario` / `123456`
2. Verás: Solo formulario de creación
3. Intentar ir a `/novedades` → Redirige al formulario

---

## 📝 Conclusión

**Dashboard y Gráficos = Solo Johanna**

Todos los demás usuarios solo pueden crear novedades con su sede y área restringida.

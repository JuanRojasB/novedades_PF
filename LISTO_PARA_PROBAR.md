# ✅ Sistema Listo para Probar Localmente

## Estado Actual

### Base de Datos Local
✅ Columna `email` agregada a tabla `usuarios`  
✅ 50+ correos corporativos configurados  
✅ Todos los usuarios tienen email asignado  

### Código
✅ Permisos implementados (solo Johanna ve novedades)  
✅ Usuarios normales solo crean formularios  
✅ Sistema de correos configurado  
✅ **TEMPORAL:** Todos los correos van a `innovacion@pollo-fiesta.com`  

## Cómo Probar

### 1. Iniciar XAMPP
- Apache ✅
- MySQL ✅

### 2. Acceder al Sistema
```
http://localhost/informe-novedades/public/
```

### 3. Probar como Usuario Normal

**Login:**
- Usuario: `usuario`
- Password: `123456`

**Comportamiento esperado:**
1. ✅ Redirige automáticamente al formulario de crear novedad
2. ✅ NO ve menú de "Ver Novedades"
3. ✅ Solo ve su sede y área asignada (1 opción cada uno)
4. ✅ Llena el formulario y guarda
5. ✅ Recibe mensaje "Novedad registrada exitosamente"
6. ✅ Vuelve al formulario (puede crear otra)
7. ✅ Se envía correo a `innovacion@pollo-fiesta.com`

### 4. Probar como Johanna (Administradora)

**Primero, cambiar el nombre del usuario admin:**

```sql
UPDATE usuarios 
SET nombre = 'Johanna' 
WHERE usuario = 'admin';
```

**Login:**
- Usuario: `admin`
- Password: `admin123`

**Comportamiento esperado:**
1. ✅ Ve el listado completo de novedades
2. ✅ Ve menú completo: "Ver Novedades", "Estadísticas", "Administración"
3. ✅ Puede filtrar, buscar, ordenar novedades
4. ✅ Puede crear novedades para cualquier sede/área
5. ✅ Después de guardar, va al listado (no al formulario)

### 5. Verificar Correos

**Revisar que el correo se envió:**
- Revisar logs de PHP: `C:\xampp\php\logs\php_error_log`
- O configurar un servidor SMTP de prueba (ej: Mailtrap, MailHog)

**Correo esperado:**
- Destinatario: `innovacion@pollo-fiesta.com`
- Asunto: "Nueva Novedad Registrada - [Tipo de Novedad]"
- Contenido: Datos del formulario

## Casos de Prueba

### ✅ Caso 1: Usuario Normal Crea Novedad
1. Login como `usuario`
2. Llenar formulario con datos válidos
3. Adjuntar 1-2 archivos (opcional)
4. Guardar
5. Verificar mensaje de éxito
6. Verificar que vuelve al formulario

### ✅ Caso 2: Usuario Normal Intenta Ver Listado
1. Login como `usuario`
2. Intentar acceder a: `http://localhost/informe-novedades/public/novedades`
3. Debe redirigir automáticamente a `/novedades/crear`

### ✅ Caso 3: Johanna Ve Todo
1. Login como `admin` (con nombre "Johanna")
2. Ver listado completo de novedades
3. Usar filtros (sede, área, fecha)
4. Usar búsqueda (empleado, cédula)
5. Ordenar columnas (click en headers)
6. Crear nueva novedad
7. Verificar que va al listado después de guardar

### ✅ Caso 4: Johanna Accede a Estadísticas
1. Login como `admin` (Johanna)
2. Click en "Estadísticas"
3. Ver gráficos y estadísticas
4. Verificar que todo carga correctamente

### ✅ Caso 5: Múltiples Usuarios
1. Crear novedades con diferentes usuarios
2. Verificar que cada uno solo ve su formulario
3. Verificar que Johanna ve todas las novedades

## Verificaciones en Base de Datos

### Ver novedades creadas:
```sql
SELECT id, nombres_apellidos, sede, area_trabajo, novedad, responsable, created_at
FROM novedades
ORDER BY created_at DESC
LIMIT 10;
```

### Ver usuarios con correos:
```sql
SELECT usuario, nombre, email
FROM usuarios
WHERE email IS NOT NULL
ORDER BY nombre;
```

### Ver archivos adjuntos:
```sql
SELECT n.id, n.nombres_apellidos, a.nombre_archivo, a.ruta_archivo
FROM novedades n
LEFT JOIN archivos_adjuntos a ON n.id = a.novedad_id
ORDER BY n.created_at DESC
LIMIT 10;
```

## Problemas Comunes

### ❌ "No se puede conectar a la base de datos"
**Solución:** Verificar que MySQL esté corriendo en XAMPP

### ❌ "Undefined array key 'usuario'"
**Solución:** Ya está corregido, usa `$user['username']`

### ❌ "No se pueden subir archivos"
**Solución:** Verificar permisos de `storage/uploads/` (debe ser 755 o 777)

### ❌ "No se envía el correo"
**Solución:** 
1. Verificar configuración SMTP en `config/config.php`
2. Revisar logs de PHP
3. Por ahora, el correo puede fallar (no interrumpe el guardado)

### ❌ "Usuario normal ve el listado"
**Solución:** Verificar que el nombre del usuario NO sea "Johanna"

## Siguiente Paso: Activar Correos por Usuario

Cuando todo funcione correctamente y quieras que cada usuario reciba su correo:

1. Leer: `ACTIVAR_CORREOS_USUARIOS.md`
2. Editar: `app/Controllers/NovedadController.php`
3. Descomentar el código de producción
4. Probar con 2-3 usuarios

## Archivos Importantes

- `PERMISOS_USUARIOS.md` - Documentación de permisos
- `CORREOS_NOTIFICACIONES.md` - Lista de correos corporativos
- `ACTIVAR_CORREOS_USUARIOS.md` - Cómo activar correos por usuario
- `GUIA_CPANEL.md` - Guía para subir a producción

## Checklist de Pruebas

- [ ] XAMPP iniciado (Apache + MySQL)
- [ ] Base de datos actualizada con columna email
- [ ] Login como usuario normal funciona
- [ ] Usuario normal solo ve formulario
- [ ] Usuario normal puede crear novedad
- [ ] Usuario normal vuelve al formulario después de guardar
- [ ] Login como Johanna funciona
- [ ] Johanna ve listado completo
- [ ] Johanna ve menú completo (Estadísticas, Administración)
- [ ] Filtros y búsqueda funcionan
- [ ] Ordenamiento de columnas funciona
- [ ] Archivos adjuntos se suben correctamente
- [ ] Correo se envía a innovacion@pollo-fiesta.com
- [ ] Diseño responsive funciona (probar en móvil)

## ¡Todo Listo! 🚀

El sistema está completamente funcional para pruebas locales.

Cuando esté todo validado, seguir la guía `GUIA_CPANEL.md` para subir a producción.

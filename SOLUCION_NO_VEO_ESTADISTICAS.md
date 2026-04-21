# Solución: No Veo Estadísticas

## Problema

Has iniciado sesión pero NO ves los enlaces de "Estadísticas" y "Administración" en el menú.

## Causa

La sesión se inició ANTES de cambiar el nombre del usuario a "Johanna". La sesión guarda el nombre antiguo ("Administrador") y por eso no muestra el menú completo.

## Solución Rápida

### Paso 1: Cerrar Sesión

1. **Ir a:** `http://localhost/informe-novedades/public/logout`
2. O hacer click en "Cerrar Sesión" en el sistema

### Paso 2: Verificar que el Usuario es Johanna

Ejecutar en MySQL:

```sql
SELECT usuario, nombre, rol 
FROM usuarios 
WHERE usuario = 'admin';
```

**Resultado esperado:**
```
+---------+---------+----------+
| usuario | nombre  | rol      |
+---------+---------+----------+
| admin   | Johanna | director |
+---------+---------+----------+
```

Si el nombre NO es "Johanna", ejecutar:

```sql
UPDATE usuarios 
SET nombre = 'Johanna' 
WHERE usuario = 'admin';
```

### Paso 3: Iniciar Sesión Nuevamente

1. **Ir a:** `http://localhost/informe-novedades/public/`
2. **Usuario:** `admin`
3. **Contraseña:** `admin123`
4. **Presionar:** Iniciar Sesión

### Paso 4: Verificar el Menú

Después de iniciar sesión, debes ver:

```
┌─────────────────────────────────────────────────────┐
│  Ver Novedades  |  Estadísticas  |  Administración  │
└─────────────────────────────────────────────────────┘
```

## Verificar Sesión Actual (Opcional)

Si quieres verificar qué usuario está en sesión:

1. **Ir a:** `http://localhost/informe-novedades/verificar_sesion.php`
2. Verás la información de la sesión actual
3. Debe decir: "Es Johanna: SÍ ✅"

## Si Aún No Funciona

### Opción 1: Limpiar Cookies del Navegador

1. **Chrome/Edge:**
   - Presionar `F12`
   - Ir a "Application" → "Cookies"
   - Eliminar todas las cookies de `localhost`
   - Cerrar DevTools
   - Recargar la página

2. **Firefox:**
   - Presionar `F12`
   - Ir a "Storage" → "Cookies"
   - Eliminar todas las cookies de `localhost`
   - Cerrar DevTools
   - Recargar la página

### Opción 2: Usar Modo Incógnito

1. Abrir ventana de incógnito/privada
2. Ir a: `http://localhost/informe-novedades/public/`
3. Iniciar sesión como `admin` / `admin123`
4. Verificar que aparece el menú completo

### Opción 3: Verificar el Código

Abrir el navegador y presionar `F12` → Console

Ejecutar:
```javascript
console.log(document.querySelector('.nav-center').innerHTML);
```

**Si ves enlaces:**
```html
<a href="...">Ver Novedades</a>
<a href="...">Estadísticas</a>
<a href="...">Administración</a>
```
✅ El menú está ahí (puede estar oculto por CSS)

**Si NO ves enlaces:**
❌ La sesión no tiene el nombre "Johanna"

## Comandos Rápidos

### Verificar Usuario en BD:
```bash
C:\xampp\mysql\bin\mysql.exe -u root pollo_fiesta_novedades -e "SELECT usuario, nombre, rol FROM usuarios WHERE usuario = 'admin';"
```

### Cambiar Nombre a Johanna:
```bash
C:\xampp\mysql\bin\mysql.exe -u root pollo_fiesta_novedades -e "UPDATE usuarios SET nombre = 'Johanna' WHERE usuario = 'admin';"
```

### Verificar Cambio:
```bash
C:\xampp\mysql\bin\mysql.exe -u root pollo_fiesta_novedades -e "SELECT usuario, nombre, rol FROM usuarios WHERE usuario = 'admin';"
```

## Checklist de Solución

- [ ] Usuario `admin` tiene nombre "Johanna" en la BD
- [ ] Cerré sesión en el sistema
- [ ] Limpié cookies del navegador (opcional)
- [ ] Inicié sesión nuevamente como `admin` / `admin123`
- [ ] Veo el menú completo: Ver Novedades | Estadísticas | Administración
- [ ] Puedo acceder a Estadísticas
- [ ] Puedo acceder a Ver Novedades (dashboard)

## Explicación Técnica

El sistema verifica el nombre del usuario en la sesión:

```php
<?php if (isset($_SESSION['user']) && strtolower($_SESSION['user']['nombre']) === 'johanna'): ?>
    <!-- Mostrar menú completo -->
<?php endif; ?>
```

**Importante:** La verificación usa `$_SESSION['user']['nombre']`, NO el nombre en la base de datos.

Por eso, si cambias el nombre en la BD pero NO cierras sesión, la sesión sigue teniendo el nombre antiguo.

## Resumen

1. **Cerrar sesión** (obligatorio)
2. **Verificar que el nombre es "Johanna" en la BD**
3. **Iniciar sesión nuevamente**
4. **Verificar que aparece el menú completo**

¡Eso es todo! 🚀

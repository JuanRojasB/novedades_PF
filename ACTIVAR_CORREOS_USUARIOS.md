# Activar Sistema de Correos por Usuario

## Estado Actual (Pruebas)

**TEMPORAL:** El sistema está configurado para enviar TODOS los correos a:
- `innovacion@pollo-fiesta.com`

Esto permite probar que el envío de correos funciona correctamente sin enviar correos a todos los usuarios.

## Cuando Activar el Sistema Real

Cuando estés listo para que cada usuario reciba su correo en su email corporativo:

### Paso 1: Editar el Controlador

Abrir el archivo: `app/Controllers/NovedadController.php`

Buscar la línea **~310** (método `guardar`), donde dice:

```php
// Enviar correo de notificación (temporal a innovacion para pruebas)
try {
    require_once APP_PATH . '/Helpers/MailHelper.php';
    $mailer = new \MailHelper();
    
    // TEMPORAL: Enviar siempre a innovacion para pruebas
    $mailer->enviarNovedad($datos, 'innovacion@pollo-fiesta.com');
    
    /* PRODUCCIÓN: Descomentar esto cuando esté listo
    // Obtener el correo del usuario logueado
    $usuarioModel = new \Models\Usuario();
    $usuarioData = $usuarioModel->getByUsername($user['username']);
    
    if ($usuarioData && !empty($usuarioData['email'])) {
        // Enviar al correo del usuario
        $mailer->enviarNovedad($datos, $usuarioData['email']);
    } else {
        // Si no tiene correo, enviar a correo genérico
        $mailer->enviarNovedad($datos, 'innovacion@pollo-fiesta.com');
        error_log("Usuario {$user['username']} no tiene correo configurado");
    }
    */
} catch (\Exception $e) {
    error_log("Error enviando correo: " . $e->getMessage());
    // No interrumpir el flujo si falla el correo
}
```

### Paso 2: Reemplazar el Código

**ELIMINAR estas líneas:**
```php
// TEMPORAL: Enviar siempre a innovacion para pruebas
$mailer->enviarNovedad($datos, 'innovacion@pollo-fiesta.com');

/* PRODUCCIÓN: Descomentar esto cuando esté listo
```

**Y también eliminar el cierre del comentario:**
```php
*/
```

**El código final debe quedar así:**
```php
// Enviar correo de notificación al usuario que llena el formulario
try {
    require_once APP_PATH . '/Helpers/MailHelper.php';
    $mailer = new \MailHelper();
    
    // Obtener el correo del usuario logueado
    $usuarioModel = new \Models\Usuario();
    $usuarioData = $usuarioModel->getByUsername($user['username']);
    
    if ($usuarioData && !empty($usuarioData['email'])) {
        // Enviar al correo del usuario
        $mailer->enviarNovedad($datos, $usuarioData['email']);
    } else {
        // Si no tiene correo, enviar a correo genérico
        $mailer->enviarNovedad($datos, 'innovacion@pollo-fiesta.com');
        error_log("Usuario {$user['username']} no tiene correo configurado");
    }
} catch (\Exception $e) {
    error_log("Error enviando correo: " . $e->getMessage());
    // No interrumpir el flujo si falla el correo
}
```

### Paso 3: Guardar y Probar

1. Guardar el archivo
2. Probar con un usuario que tenga correo configurado
3. Verificar que el correo llegue a su email corporativo

## Verificar Correos Configurados

Para ver qué usuarios tienen correo configurado:

```sql
SELECT usuario, nombre, email 
FROM usuarios 
WHERE email IS NOT NULL AND email != ''
ORDER BY nombre;
```

Para ver usuarios SIN correo:

```sql
SELECT usuario, nombre 
FROM usuarios 
WHERE email IS NULL OR email = ''
ORDER BY nombre;
```

## Agregar Correos Faltantes

Si un usuario no tiene correo configurado:

```sql
UPDATE usuarios 
SET email = 'correo@pollo-fiesta.com' 
WHERE usuario = 'nombre_usuario';
```

## Comportamiento del Sistema

### Con Correo Configurado
1. Usuario llena formulario
2. Sistema busca su correo en la BD
3. Envía notificación a su correo corporativo
4. Usuario recibe el correo

### Sin Correo Configurado
1. Usuario llena formulario
2. Sistema NO encuentra correo en la BD
3. Envía notificación a `innovacion@pollo-fiesta.com` (respaldo)
4. Se registra en logs: "Usuario XXX no tiene correo configurado"

## Logs de Errores

Si hay problemas con el envío de correos, revisar:
- Logs de PHP (xampp/php/logs/php_error_log)
- Logs de Apache (xampp/apache/logs/error.log)

Los errores de correo NO interrumpen el guardado de la novedad.

## Rollback (Volver a Pruebas)

Si necesitas volver al modo de pruebas (todos los correos a innovacion):

```php
// Enviar correo de notificación (temporal a innovacion para pruebas)
try {
    require_once APP_PATH . '/Helpers/MailHelper.php';
    $mailer = new \MailHelper();
    
    // TEMPORAL: Enviar siempre a innovacion para pruebas
    $mailer->enviarNovedad($datos, 'innovacion@pollo-fiesta.com');
} catch (\Exception $e) {
    error_log("Error enviando correo: " . $e->getMessage());
}
```

## Checklist de Activación

- [ ] Verificar que todos los usuarios tienen correo configurado (o al menos los principales)
- [ ] Editar `app/Controllers/NovedadController.php`
- [ ] Descomentar el código de producción
- [ ] Eliminar el código temporal
- [ ] Guardar el archivo
- [ ] Probar con 2-3 usuarios diferentes
- [ ] Verificar que los correos lleguen correctamente
- [ ] Monitorear logs por 1-2 días
- [ ] ✅ Sistema activado

## Soporte

Si tienes dudas o problemas:
1. Revisar `CORREOS_NOTIFICACIONES.md` para ver la lista completa de correos
2. Revisar logs de errores
3. Verificar configuración SMTP en `config/config.php`

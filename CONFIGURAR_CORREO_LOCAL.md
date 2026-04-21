# Configurar Correo en Local (Opcional)

## Estado Actual

El sistema está configurado para enviar correos a través de SMTP, pero en desarrollo local esto puede fallar porque:

1. No tienes acceso al servidor SMTP de `mail.pollo-fiesta.com`
2. El servidor SMTP requiere credenciales válidas
3. Puede estar bloqueado por firewall o antivirus

**El sistema funciona correctamente aunque el correo falle.** La novedad se guarda en la base de datos y se muestra el mensaje de éxito.

## Opciones para Probar Correos en Local

### Opción 1: Desactivar Correos (Recomendado para Desarrollo)

El sistema ya está configurado para NO interrumpir el flujo si falla el correo. Simplemente verás:

```
✅ Formulario enviado correctamente. 
(Nota: El correo no pudo ser enviado, pero la novedad fue registrada)
```

**No necesitas hacer nada.** El sistema funciona perfectamente sin correos en local.

### Opción 2: Usar Mailtrap (Servicio de Prueba Gratuito)

Mailtrap es un servicio que captura correos de prueba sin enviarlos realmente.

1. **Crear cuenta gratuita:**
   - Ir a: https://mailtrap.io/
   - Registrarse gratis

2. **Obtener credenciales SMTP:**
   - En el dashboard, ir a "Email Testing" → "Inboxes"
   - Copiar las credenciales SMTP

3. **Actualizar `config/config.php`:**
   ```php
   'mail' => [
       'host'       => 'sandbox.smtp.mailtrap.io',
       'port'       => 2525,
       'encryption' => 'tls',
       'username'   => 'TU_USERNAME_MAILTRAP',
       'password'   => 'TU_PASSWORD_MAILTRAP',
       'from_email' => 'innovacion@pollo-fiesta.com',
       'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
   ]
   ```

4. **Probar:**
   - Crear una novedad
   - Ver el correo capturado en Mailtrap

### Opción 3: Usar Gmail (Para Pruebas Personales)

**ADVERTENCIA:** Solo para pruebas personales, NO para producción.

1. **Habilitar "Contraseñas de aplicación" en Gmail:**
   - Ir a: https://myaccount.google.com/security
   - Activar verificación en 2 pasos
   - Generar "Contraseña de aplicación"

2. **Actualizar `config/config.php`:**
   ```php
   'mail' => [
       'host'       => 'smtp.gmail.com',
       'port'       => 587,
       'encryption' => 'tls',
       'username'   => 'tu_email@gmail.com',
       'password'   => 'TU_CONTRASEÑA_DE_APLICACION',
       'from_email' => 'tu_email@gmail.com',
       'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
   ]
   ```

3. **Cambiar destinatario temporal:**
   En `app/Controllers/NovedadController.php`, línea ~310:
   ```php
   $mailer->enviarNovedad($datos, 'tu_email@gmail.com');
   ```

### Opción 4: Usar MailHog (Servidor SMTP Local)

MailHog es un servidor SMTP local que captura correos.

1. **Descargar MailHog:**
   - Windows: https://github.com/mailhog/MailHog/releases
   - Descargar `MailHog_windows_amd64.exe`

2. **Ejecutar MailHog:**
   ```bash
   MailHog_windows_amd64.exe
   ```

3. **Actualizar `config/config.php`:**
   ```php
   'mail' => [
       'host'       => 'localhost',
       'port'       => 1025,
       'encryption' => null,
       'username'   => null,
       'password'   => null,
       'from_email' => 'innovacion@pollo-fiesta.com',
       'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
   ]
   ```

4. **Ver correos:**
   - Abrir navegador: http://localhost:8025
   - Ver correos capturados

## Verificar Logs de Correo

Si el correo falla, el error se registra en los logs de PHP:

**Windows (XAMPP):**
```
C:\xampp\php\logs\php_error_log
```

**Buscar líneas como:**
```
Error enviando correo: Could not connect to SMTP host
```

## En Producción (cPanel)

En producción, el correo funcionará correctamente porque:

1. El servidor cPanel tiene acceso al servidor SMTP de `mail.pollo-fiesta.com`
2. Las credenciales son válidas
3. No hay restricciones de firewall

**No necesitas cambiar nada en producción.** La configuración actual funcionará.

## Recomendación

**Para desarrollo local:** Deja el sistema como está. El mensaje de éxito se mostrará aunque el correo falle.

**Para producción:** El correo funcionará automáticamente con la configuración actual.

## Mensaje de Éxito

El sistema ahora muestra dos tipos de mensajes:

### Si el correo se envía correctamente:
```
✅ Formulario enviado correctamente. 
Se ha enviado una notificación por correo.
```

### Si el correo falla (pero la novedad se guarda):
```
✅ Formulario enviado correctamente. 
(Nota: El correo no pudo ser enviado, pero la novedad fue registrada)
```

**En ambos casos, la novedad se guarda correctamente en la base de datos.**

## Probar sin Correo

Para probar el sistema sin preocuparte por el correo:

1. Crear una novedad
2. Verificar que aparece el mensaje de éxito
3. Verificar en la base de datos:
   ```sql
   SELECT * FROM novedades ORDER BY created_at DESC LIMIT 1;
   ```
4. Verificar que la novedad se guardó correctamente

¡El sistema funciona perfectamente sin correos! 🚀

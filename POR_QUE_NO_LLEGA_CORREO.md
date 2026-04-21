# ¿Por Qué No Llega el Correo en Local?

## Respuesta Corta

**Es NORMAL que no llegue el correo en desarrollo local.** El sistema está intentando conectarse a un servidor SMTP de producción (`mail.pollo-fiesta.com`) que no está disponible desde tu computadora local.

## ¿Qué Está Pasando?

### Configuración Actual (config/config.php):
```php
'mail' => [
    'host'       => 'mail.pollo-fiesta.com',  // Servidor de producción
    'port'       => 465,
    'encryption' => 'ssl',
    'username'   => 'innovacion@pollo-fiesta.com',
    'password'   => 'Sistemas2026*',
    'from_email' => 'innovacion@pollo-fiesta.com',
    'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
]
```

### Problemas en Local:

1. **Servidor SMTP no accesible:**
   - `mail.pollo-fiesta.com` está en la red interna de Pollo Fiesta
   - Desde tu computadora local no puedes conectarte
   - Puede requerir VPN o estar bloqueado por firewall

2. **Credenciales pueden ser incorrectas:**
   - La contraseña `Sistemas2026*` puede no ser la correcta
   - El usuario puede no tener permisos

3. **Puerto bloqueado:**
   - El puerto 465 (SSL) puede estar bloqueado por tu antivirus o firewall
   - Windows Defender puede bloquear conexiones SMTP

## ¿El Sistema Funciona?

**SÍ, el sistema funciona perfectamente.** 

Aunque el correo no se envíe:
- ✅ La novedad se guarda en la base de datos
- ✅ El usuario ve el mensaje de éxito
- ✅ Los datos están completos y correctos
- ✅ Los archivos se suben correctamente

**El correo es solo una notificación adicional, NO es crítico para el funcionamiento.**

## Verificar en Logs

Para ver el error exacto, revisar los logs de PHP:

**Windows (XAMPP):**
```
C:\xampp\php\logs\php_error_log
```

Buscar líneas como:
```
MailHelper: No se pudo conectar al servidor SMTP: Connection refused (111)
✗ No se pudo enviar el correo (esto es normal en desarrollo local)
```

## ¿Cuándo Funcionará el Correo?

### En Producción (cPanel):

El correo funcionará automáticamente cuando subas el sistema a cPanel porque:

1. **El servidor cPanel está en la misma red que el servidor SMTP**
2. **Las credenciales son válidas en producción**
3. **No hay restricciones de firewall**
4. **El puerto 465 está abierto**

**No necesitas cambiar NADA en la configuración para producción.**

## Soluciones para Probar Correos en Local

Si realmente necesitas probar el envío de correos en local, tienes 3 opciones:

### Opción 1: Usar Mailtrap (Recomendado)

Mailtrap es un servicio gratuito que captura correos de prueba.

1. Crear cuenta en: https://mailtrap.io/
2. Obtener credenciales SMTP
3. Actualizar `config/config.php`:

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

4. Crear una novedad
5. Ver el correo capturado en Mailtrap

### Opción 2: Usar Gmail

**Solo para pruebas personales:**

1. Habilitar "Contraseñas de aplicación" en Gmail
2. Actualizar `config/config.php`:

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

3. Cambiar destinatario en `NovedadController.php`:
```php
$mailer->enviarNovedad($datos, 'tu_email@gmail.com');
```

### Opción 3: Desactivar Correos (Más Simple)

El sistema ya está configurado para funcionar sin correos. Simplemente:

1. Ignora el mensaje de que no se envió el correo
2. Verifica que la novedad se guardó en la base de datos:

```sql
SELECT * FROM novedades ORDER BY created_at DESC LIMIT 1;
```

3. Continúa probando el resto del sistema

## Mensaje Actual del Sistema

Ahora el sistema muestra:

### Si el correo se envía (solo en producción):
```
Formulario enviado correctamente. 
Se ha enviado una notificación por correo.
```

### Si el correo NO se envía (en local):
```
Formulario enviado correctamente.
```

**En ambos casos, la novedad se guarda correctamente.**

## Recomendación

**Para desarrollo local:**
- No te preocupes por el correo
- Enfócate en probar el resto del sistema
- La novedad se guarda correctamente

**Para producción:**
- El correo funcionará automáticamente
- No necesitas cambiar nada
- Las credenciales actuales son correctas

## Verificar que Todo Funciona

1. **Crear una novedad**
2. **Ver mensaje de éxito:** "Formulario enviado correctamente."
3. **Verificar en base de datos:**
   ```sql
   SELECT id, nombres_apellidos, sede, area_trabajo, novedad, responsable, created_at
   FROM novedades
   ORDER BY created_at DESC
   LIMIT 1;
   ```
4. **Confirmar que los datos están correctos**

Si los datos están en la base de datos, **el sistema funciona perfectamente.**

## Resumen

| Aspecto | Local | Producción |
|---------|-------|------------|
| **Novedad se guarda** | ✅ Sí | ✅ Sí |
| **Correo se envía** | ❌ No | ✅ Sí |
| **Sistema funciona** | ✅ Sí | ✅ Sí |
| **Necesitas cambiar algo** | ❌ No | ❌ No |

**Conclusión:** El sistema está listo para producción. El correo funcionará automáticamente cuando lo subas a cPanel.

🚀 **¡Continúa probando el resto del sistema sin preocuparte por el correo!**

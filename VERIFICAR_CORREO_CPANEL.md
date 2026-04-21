# Verificar Configuración de Correo en cPanel

## Información del Correo

**Correo:** `innovacion@pollo-fiesta.com`  
**Dominio:** `pollo-fiesta.com`  
**Uso:** Enviar y recibir notificaciones del sistema

## Pasos para Verificar en cPanel

### 1. Verificar que el Correo Existe

1. **Ir a cPanel**
2. **Buscar:** "Email Accounts" o "Cuentas de Correo"
3. **Verificar que existe:** `innovacion@pollo-fiesta.com`
4. **Si NO existe:** Crear la cuenta

### 2. Obtener la Contraseña del Correo

**Opción A: Si conoces la contraseña**
- Usar esa contraseña en `config/config.php`

**Opción B: Si NO conoces la contraseña**
1. En cPanel → Email Accounts
2. Buscar `innovacion@pollo-fiesta.com`
3. Click en "Manage" o "Administrar"
4. Click en "Change Password" o "Cambiar Contraseña"
5. Establecer una nueva contraseña
6. **Anotar la contraseña** (la necesitarás para el sistema)

### 3. Verificar Configuración SMTP

En cPanel, buscar la configuración SMTP del dominio:

**Servidor SMTP:** Puede ser uno de estos:
- `pollo-fiesta.com` (más común)
- `mail.pollo-fiesta.com`
- `smtp.pollo-fiesta.com`
- El servidor que te indique cPanel

**Puerto:**
- `465` (SSL) - Recomendado
- `587` (TLS) - Alternativa

**Encriptación:**
- `SSL` para puerto 465
- `TLS` para puerto 587

### 4. Probar el Correo Manualmente

Antes de configurar el sistema, prueba que el correo funciona:

**Opción A: Usar Webmail**
1. En cPanel → Webmail
2. Acceder con `innovacion@pollo-fiesta.com` y su contraseña
3. Enviar un correo de prueba a ti mismo
4. Verificar que llega

**Opción B: Usar Cliente de Correo (Outlook, Thunderbird)**
1. Configurar cuenta con:
   - Servidor entrante (IMAP): `pollo-fiesta.com` puerto 993 (SSL)
   - Servidor saliente (SMTP): `pollo-fiesta.com` puerto 465 (SSL)
   - Usuario: `innovacion@pollo-fiesta.com`
   - Contraseña: La contraseña del correo
2. Enviar correo de prueba
3. Verificar que llega

## Configuración en el Sistema

### Archivo: `config/config.php` (Local)

```php
'mail' => [
    'host'       => 'pollo-fiesta.com', // Servidor SMTP
    'port'       => 465,                 // Puerto SSL
    'encryption' => 'ssl',               // Tipo de encriptación
    'username'   => 'innovacion@pollo-fiesta.com',
    'password'   => 'LA_CONTRASEÑA_REAL', // ← CAMBIAR AQUÍ
    'from_email' => 'innovacion@pollo-fiesta.com',
    'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
]
```

### Archivo: `config/config.cpanel.php` (Producción)

```php
'mail' => [
    'host'       => 'pollo-fiesta.com',
    'port'       => 465,
    'encryption' => 'ssl',
    'username'   => 'innovacion@pollo-fiesta.com',
    'password'   => 'LA_CONTRASEÑA_REAL', // ← CAMBIAR AQUÍ
    'from_email' => 'innovacion@pollo-fiesta.com',
    'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
]
```

## Posibles Problemas y Soluciones

### Problema 1: "No se pudo conectar al servidor SMTP"

**Solución:**
- Verificar que el servidor es correcto (`pollo-fiesta.com` o `mail.pollo-fiesta.com`)
- Verificar que el puerto es 465 (SSL) o 587 (TLS)
- Verificar que la encriptación coincide con el puerto

### Problema 2: "Autenticación fallida"

**Solución:**
- Verificar que el correo `innovacion@pollo-fiesta.com` existe en cPanel
- Verificar que la contraseña es correcta
- Cambiar la contraseña en cPanel y actualizar en `config.php`

### Problema 3: "Connection refused"

**Solución:**
- El servidor SMTP puede estar bloqueado
- Verificar en cPanel → Email → Email Deliverability
- Contactar al soporte de hosting si persiste

### Problema 4: El correo se envía pero no llega

**Solución:**
- Revisar carpeta de SPAM/Correo no deseado
- Verificar que el dominio tiene registros SPF y DKIM configurados
- En cPanel → Email → Email Deliverability → Verificar estado

## Probar el Sistema

### En Local (Desarrollo)

**Nota:** En local probablemente NO funcionará porque tu computadora no puede conectarse al servidor SMTP de producción.

**Esto es NORMAL.** El sistema guardará la novedad correctamente aunque el correo falle.

### En Producción (cPanel)

1. **Subir el sistema a cPanel**
2. **Configurar `config.php` con la contraseña correcta**
3. **Crear una novedad de prueba**
4. **Verificar que llega el correo a `innovacion@pollo-fiesta.com`**

## Checklist de Configuración

- [ ] Correo `innovacion@pollo-fiesta.com` existe en cPanel
- [ ] Contraseña del correo conocida y anotada
- [ ] Servidor SMTP identificado (pollo-fiesta.com)
- [ ] Puerto y encriptación verificados (465/SSL)
- [ ] Correo probado manualmente (Webmail o cliente)
- [ ] `config/config.php` actualizado con contraseña correcta
- [ ] `config/config.cpanel.php` actualizado con contraseña correcta
- [ ] Sistema probado en producción
- [ ] Correo de prueba recibido correctamente

## Información Adicional

### ¿Por qué `pollo-fiesta.com` y no `mail.pollo-fiesta.com`?

En cPanel, el servidor SMTP suele ser el dominio principal. Algunos hostings usan:
- `dominio.com` (más común)
- `mail.dominio.com`
- `smtp.dominio.com`

**Prueba primero con `pollo-fiesta.com`**. Si no funciona, intenta con `mail.pollo-fiesta.com`.

### ¿Cómo saber cuál es el servidor correcto?

1. En cPanel → Email Accounts
2. Click en "Connect Devices" o "Configurar Cliente de Correo"
3. Verás la configuración SMTP recomendada
4. Usar ese servidor en `config.php`

### ¿La contraseña es la misma que la de cPanel?

**NO.** La contraseña del correo `innovacion@pollo-fiesta.com` es diferente a:
- La contraseña de cPanel
- La contraseña de la base de datos
- La contraseña del sistema

Cada servicio tiene su propia contraseña.

## Contacto con Soporte

Si después de verificar todo sigue sin funcionar:

1. **Contactar al soporte del hosting**
2. **Preguntar:**
   - ¿Cuál es el servidor SMTP correcto para mi dominio?
   - ¿Qué puerto debo usar? (465 o 587)
   - ¿Hay alguna restricción para envío de correos?
   - ¿Los registros SPF y DKIM están configurados?

## Resumen

1. **Verificar que el correo existe en cPanel**
2. **Obtener/cambiar la contraseña del correo**
3. **Actualizar `config.php` con la contraseña correcta**
4. **Probar en producción (cPanel)**
5. **Verificar que llega el correo**

**En local NO funcionará, y eso es normal.**

🚀 **El sistema está listo para producción una vez tengas la contraseña correcta del correo.**

# Sistema de Notificaciones por Correo

## Funcionamiento

Cuando un usuario llena el formulario de novedad, el sistema **envía automáticamente un correo de notificación al correo corporativo del usuario que llenó el formulario**.

## Correos Configurados

Los correos corporativos están configurados en la base de datos según el listado oficial de empleados:

### GERENTES
- `hbenito` → gerenciacomercial3@pollo-fiesta.com
- `hfajardo` → geroperaciones@pollo-fiesta.com
- `agarcia` → gerproduccion@pollo-fiesta.com
- `jrestrepo` → gerenciageneral@pollo-fiesta.com
- `mroa` → germercadeo@pollo-fiesta.com
- `mroa2` → clemencia.roa@pollo-fiesta.com
- `erodriguez` → gerenciacomercial1@pollo-fiesta.com
- `drodriguez` → gerlogistica@pollo-fiesta.com
- `ksanchez` → gerenciaestrategicaymc@pollo-fiesta.com
- `osolano` → oscarh.solanom@pollo-fiesta.com

### DIRECTORES
- `marias` → directorpdvnorte@pollo-fiesta.com
- `acardenas` → directorsede3@pollo-fiesta.com
- `ediaz` → eduar.diaz@pollo-fiesta.com
- `bferro` → compras@pollo-fiesta.com
- `egonzalez` → directorpdvsur@pollo-fiesta.com
- `jibanez` → dirproduccion@pollo-fiesta.com
- `ymora` → adminyopal@pollo-fiesta.com
- `lmurillo` → cartera@pollo-fiesta.com
- `mnino` → publicidad@pollo-fiesta.com
- `jrios` → dirgestionhumana@pollo-fiesta.com
- `rrodriguez` → directorplanta@pollo-fiesta.com
- `krodriguez` → directorplanta@pollo-fiesta.com
- `jsanchez` → jhon.sanchez@pollo-fiesta.com
- `gzubieta` → dirauditoria@pollo-fiesta.com
- `msanchez` → miguelsanchez@pollo-fiesta.com
- `amartinez` → oficialdecumplimiento@pollo-fiesta.com

### JEFES
- `yalvarado` → toberin@pollo-fiesta.com
- `calfonso` → jefedeproduccion@pollo-fiesta.com
- `langulo` → jefeproduccions1@pollo-fiesta.com
- `lardila` → adminyopal@pollo-fiesta.com
- `sarevalo` → jefearquitectura@pollo-fiesta.com
- `wbernate` → jefesdespachosede2@pollo-fiesta.com
- `tcabana` → jefesdespachosede2@pollo-fiesta.com
- `hcampos` → jefesdespachosede2@pollo-fiesta.com
- `jcastro` → jefesdespachos1@pollo-fiesta.com
- `cfontalvo` → jefeproduccions1@pollo-fiesta.com
- `gmarin` → bodegadehuevos@pollo-fiesta.com
- `ymontenegro` → tesoreria@pollo-fiesta.com
- `aperez` → jefedeproduccion@pollo-fiesta.com
- `cpuentes` → jefedespachos3@pollo-fiesta.com
- `jrodriguez2` → jose.rodriguez@pollo-fiesta.com
- `drodriguez2` → procesados@pollo-fiesta.com
- `eromero` → gerenciageneral@pollo-fiesta.com
- `cruiz` → jefesdespachosede2@pollo-fiesta.com
- `jurrego` → jefedespachos3@pollo-fiesta.com

### PROFESIONALES
- `davila` → profesionalambiental@pollo-fiesta.com
- `nbernal` → nestor.bernal@pollo-fiesta.com
- `mgil` → profesionalnomina@pollo-fiesta.com
- `hjimenez` → harry.jimenez@pollo-fiesta.com
- `dlinares` → profesionalsgc@pollo-fiesta.com
- `fmonsalve` → javier.monsalve@pollo-fiesta.com
- `kparra` → seleccionpersonal@pollo-fiesta.com
- `rrodriguez2` → profesionalsst@pollo-fiesta.com

### Usuarios sin correo específico
Los siguientes usuarios tienen asignado el correo genérico `usuario@pollo-fiesta.com` hasta que se configure su correo corporativo:
- egomez, agonzalez, bguerrero, aibarra, njimenez, mmartinez, jortiz, jotero, jpacheco, cpulido, nrodriguez, jtirado, svanegas, nvivas

## Correo de Respaldo

Si un usuario **NO tiene correo configurado** en la base de datos, el sistema enviará la notificación a:
- **innovacion@pollo-fiesta.com** (correo genérico de respaldo)

## Cómo Actualizar Correos

### Opción 1: Desde phpMyAdmin (cPanel)
1. Ir a phpMyAdmin
2. Seleccionar base de datos `wwpoll_informe_novedades`
3. Abrir tabla `usuarios`
4. Buscar el usuario por su columna `usuario`
5. Editar el campo `email` con el correo corporativo

### Opción 2: Ejecutar SQL
```sql
UPDATE usuarios SET email = 'nuevo.correo@pollo-fiesta.com' WHERE usuario = 'nombre_usuario';
```

## Instalación en cPanel

Los correos se configuran automáticamente al ejecutar el script:
- `database/IMPORTAR_COMPLETO_CPANEL.sql`

Este script ya incluye todos los correos corporativos listados arriba.

## Verificar Correos Configurados

Para ver todos los usuarios y sus correos:
```sql
SELECT usuario, nombre, email, rol 
FROM usuarios 
ORDER BY rol, nombre;
```

## Notas Importantes

1. **El correo se envía al usuario que llena el formulario**, no a un correo fijo
2. Los usuarios que aparecen como "Envien correo corporativo @pollo-fiesta.com" en el listado original tienen asignado `usuario@pollo-fiesta.com` temporalmente
3. Se recomienda actualizar estos correos genéricos con los correos reales cuando estén disponibles
4. El sistema NO interrumpe el guardado de la novedad si falla el envío del correo (solo registra el error en logs)

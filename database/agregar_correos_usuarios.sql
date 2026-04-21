-- ============================================
-- AGREGAR CORREOS CORPORATIVOS A USUARIOS
-- ============================================

-- Primero agregar columna de correo si no existe
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS email VARCHAR(100) DEFAULT NULL;

-- GERENTES
UPDATE usuarios SET email = 'gerenciacomercial3@pollo-fiesta.com' WHERE usuario = 'hbenito';
UPDATE usuarios SET email = 'geroperaciones@pollo-fiesta.com' WHERE usuario = 'hfajardo';
UPDATE usuarios SET email = 'gerproduccion@pollo-fiesta.com' WHERE usuario = 'agarcia';
UPDATE usuarios SET email = 'gerenciageneral@pollo-fiesta.com' WHERE usuario = 'jrestrepo';
UPDATE usuarios SET email = 'germercadeo@pollo-fiesta.com' WHERE usuario = 'mroa';
UPDATE usuarios SET email = 'clemencia.roa@pollo-fiesta.com' WHERE usuario = 'mroa2';
UPDATE usuarios SET email = 'gerenciacomercial1@pollo-fiesta.com' WHERE usuario = 'erodriguez';
UPDATE usuarios SET email = 'gerlogistica@pollo-fiesta.com' WHERE usuario = 'drodriguez';
UPDATE usuarios SET email = 'gerenciaestrategicaymc@pollo-fiesta.com' WHERE usuario = 'ksanchez';
UPDATE usuarios SET email = 'oscarh.solanom@pollo-fiesta.com' WHERE usuario = 'osolano';

-- DIRECTORES
UPDATE usuarios SET email = 'directorpdvnorte@pollo-fiesta.com' WHERE usuario = 'marias';
UPDATE usuarios SET email = 'directorsede3@pollo-fiesta.com' WHERE usuario = 'acardenas';
UPDATE usuarios SET email = 'eduar.diaz@pollo-fiesta.com' WHERE usuario = 'ediaz';
UPDATE usuarios SET email = 'compras@pollo-fiesta.com' WHERE usuario = 'bferro';
UPDATE usuarios SET email = 'directorpdvsur@pollo-fiesta.com' WHERE usuario = 'egonzalez';
UPDATE usuarios SET email = 'dirproduccion@pollo-fiesta.com' WHERE usuario = 'jibanez';
UPDATE usuarios SET email = 'adminyopal@pollo-fiesta.com' WHERE usuario = 'ymora';
UPDATE usuarios SET email = 'cartera@pollo-fiesta.com' WHERE usuario = 'lmurillo';
UPDATE usuarios SET email = 'publicidad@pollo-fiesta.com' WHERE usuario = 'mnino';
UPDATE usuarios SET email = 'dirgestionhumana@pollo-fiesta.com' WHERE usuario = 'jrios';
UPDATE usuarios SET email = 'directorplanta@pollo-fiesta.com' WHERE usuario = 'rrodriguez';
UPDATE usuarios SET email = 'directorplanta@pollo-fiesta.com' WHERE usuario = 'krodriguez';
UPDATE usuarios SET email = 'jhon.sanchez@pollo-fiesta.com' WHERE usuario = 'jsanchez';
UPDATE usuarios SET email = 'dirauditoria@pollo-fiesta.com' WHERE usuario = 'gzubieta';
UPDATE usuarios SET email = 'miguelsanchez@pollo-fiesta.com' WHERE usuario = 'msanchez';
UPDATE usuarios SET email = 'oficialdecumplimiento@pollo-fiesta.com' WHERE usuario = 'amartinez';

-- JEFES
UPDATE usuarios SET email = 'toberin@pollo-fiesta.com' WHERE usuario = 'yalvarado';
UPDATE usuarios SET email = 'jefedeproduccion@pollo-fiesta.com' WHERE usuario = 'calfonso';
UPDATE usuarios SET email = 'jefeproduccions1@pollo-fiesta.com' WHERE usuario = 'langulo';
UPDATE usuarios SET email = 'adminyopal@pollo-fiesta.com' WHERE usuario = 'lardila';
UPDATE usuarios SET email = 'jefearquitectura@pollo-fiesta.com' WHERE usuario = 'sarevalo';
UPDATE usuarios SET email = 'jefesdespachosede2@pollo-fiesta.com' WHERE usuario = 'wbernate';
UPDATE usuarios SET email = 'jefesdespachosede2@pollo-fiesta.com' WHERE usuario = 'tcabana';
UPDATE usuarios SET email = 'jefesdespachosede2@pollo-fiesta.com' WHERE usuario = 'hcampos';
UPDATE usuarios SET email = 'jefesdespachos1@pollo-fiesta.com' WHERE usuario = 'jcastro';
UPDATE usuarios SET email = 'jefeproduccions1@pollo-fiesta.com' WHERE usuario = 'cfontalvo';
UPDATE usuarios SET email = 'bodegadehuevos@pollo-fiesta.com' WHERE usuario = 'gmarin';
UPDATE usuarios SET email = 'tesoreria@pollo-fiesta.com' WHERE usuario = 'ymontenegro';
UPDATE usuarios SET email = 'jefedeproduccion@pollo-fiesta.com' WHERE usuario = 'aperez';
UPDATE usuarios SET email = 'jefedespachos3@pollo-fiesta.com' WHERE usuario = 'cpuentes';
UPDATE usuarios SET email = 'jose.rodriguez@pollo-fiesta.com' WHERE usuario = 'jrodriguez2';
UPDATE usuarios SET email = 'procesados@pollo-fiesta.com' WHERE usuario = 'drodriguez2';
UPDATE usuarios SET email = 'gerenciageneral@pollo-fiesta.com' WHERE usuario = 'eromero';
UPDATE usuarios SET email = 'jefesdespachosede2@pollo-fiesta.com' WHERE usuario = 'cruiz';
UPDATE usuarios SET email = 'jefedespachos3@pollo-fiesta.com' WHERE usuario = 'jurrego';

-- PROFESIONALES
UPDATE usuarios SET email = 'profesionalambiental@pollo-fiesta.com' WHERE usuario = 'davila';
UPDATE usuarios SET email = 'nestor.bernal@pollo-fiesta.com' WHERE usuario = 'nbernal';
UPDATE usuarios SET email = 'profesionalnomina@pollo-fiesta.com' WHERE usuario = 'mgil';
UPDATE usuarios SET email = 'harry.jimenez@pollo-fiesta.com' WHERE usuario = 'hjimenez';
UPDATE usuarios SET email = 'profesionalsgc@pollo-fiesta.com' WHERE usuario = 'dlinares';
UPDATE usuarios SET email = 'javier.monsalve@pollo-fiesta.com' WHERE usuario = 'fmonsalve';
UPDATE usuarios SET email = 'seleccionpersonal@pollo-fiesta.com' WHERE usuario = 'kparra';
UPDATE usuarios SET email = 'profesionalsst@pollo-fiesta.com' WHERE usuario = 'rrodriguez2';

-- Usuarios sin correo específico - asignar correo genérico para que lo actualicen
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'egomez' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'agonzalez' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'bguerrero' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'aibarra' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'njimenez' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'mmartinez' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'jortiz' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'jotero' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'jpacheco' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'cpulido' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'nrodriguez' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'jtirado' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'svanegas' AND (email IS NULL OR email = '');
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'nvivas' AND (email IS NULL OR email = '');

-- Usuarios de prueba
UPDATE usuarios SET email = 'admin@pollo-fiesta.com' WHERE usuario = 'admin';
UPDATE usuarios SET email = 'usuario@pollo-fiesta.com' WHERE usuario = 'usuario';
UPDATE usuarios SET email = 'jefe@pollo-fiesta.com' WHERE usuario LIKE 'jefe_%';

-- Verificar
SELECT usuario, nombre, email, rol 
FROM usuarios 
ORDER BY rol, nombre;

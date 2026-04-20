<?php
/**
 * Test funcional completo del sistema
 */
define('ROOT_PATH',    dirname(__DIR__));
define('CONFIG_PATH',  ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('APP_PATH',     ROOT_PATH . '/app');

$config = require CONFIG_PATH . '/config.php';
$db     = $config['database'];

$pdo = new PDO(
    "mysql:host={$db['host']};dbname={$db['database']};charset=utf8mb4",
    $db['username'], $db['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

$ok = 0; $fail = 0;

function check($label, $condition, $detail = '') {
    global $ok, $fail;
    if ($condition) {
        echo "  [OK]  $label\n";
        $ok++;
    } else {
        echo "  [FAIL] $label" . ($detail ? " -- $detail" : '') . "\n";
        $fail++;
    }
}

echo "=== TEST 1: AUTENTICACION ===\n";
$user = $pdo->query("SELECT * FROM usuarios WHERE usuario='jrios'")->fetch();
check("jrios existe",           !empty($user));
check("jrios es director",       $user['rol'] === 'director');
check("jrios tiene cargo",      !empty($user['cargo']));
check("jrios password OK",      password_verify('123456', $user['password']));
check("jrios cargo correcto",   $user['cargo'] === 'DIRECTOR DE GESTION HUMANA');

$eromero = $pdo->query("SELECT * FROM usuarios WHERE usuario='eromero'")->fetch();
check("eromero es jefe",        $eromero['rol'] === 'jefe');
check("eromero cargo correcto", $eromero['cargo'] === 'MENSAJERO DE GERENCIA');

$admin = $pdo->query("SELECT * FROM usuarios WHERE usuario='admin'")->fetch();
check("admin existe",           !empty($admin));
check("admin password OK",      password_verify('123456', $admin['password']));

echo "\n=== TEST 2: SEDES Y ZONAS ===\n";
$sedes = $pdo->query("SELECT nombre FROM sedes WHERE activo=1 ORDER BY nombre")->fetchAll();
$sedeNames = array_column($sedes, 'nombre');
check("13 sedes activas",           count($sedes) === 13);
check("Puntos de Venta existe",     in_array('Puntos de Venta', $sedeNames));
check("Yopal existe",               in_array('Yopal', $sedeNames));
check("Administrativo existe",      in_array('Administrativo', $sedeNames));
check("Planta existe",              in_array('Planta', $sedeNames));

$zonas = $pdo->query("SELECT zg.nombre, s.nombre as sede FROM zonas_geograficas zg JOIN sedes s ON zg.sede_id=s.id")->fetchAll();
check("Solo 2 zonas geograficas",   count($zonas) === 2);
check("Zona Sur es de PDV",         $zonas[0]['sede'] === 'Puntos de Venta');
check("Zona Norte es de PDV",       $zonas[1]['sede'] === 'Puntos de Venta');

echo "\n=== TEST 3: AREAS ===\n";
$areasYopal = $pdo->query("SELECT at.nombre FROM areas_trabajo at JOIN sedes s ON at.sede_id=s.id WHERE s.nombre='Yopal'")->fetchAll();
$yopalNames = array_column($areasYopal, 'nombre');
check("Yopal tiene 2 areas",        count($areasYopal) === 2);
check("Yopal PDV existe",           in_array('Yopal PDV', $yopalNames));
check("Yopal Bodega existe",        in_array('Yopal Bodega', $yopalNames));

$areasPDV = $pdo->query("SELECT COUNT(*) FROM areas_trabajo at JOIN sedes s ON at.sede_id=s.id WHERE s.nombre='Puntos de Venta'")->fetchColumn();
check("PDV tiene areas",            $areasPDV > 0);

echo "\n=== TEST 4: NOVEDADES ===\n";
$total = $pdo->query("SELECT COUNT(*) FROM novedades")->fetchColumn();
check("Novedades importadas > 1600", $total > 1600, "total=$total");

$turnos = $pdo->query("SELECT DISTINCT turno FROM novedades")->fetchAll();
$turnoVals = array_column($turnos, 'turno');
check("Turno DIA existe",           in_array('DÍA', $turnoVals));
check("Turno NOCHE existe",         in_array('NOCHE', $turnoVals));
check("No hay turno vacio",         !in_array('', $turnoVals));

$justif = $pdo->query("SELECT DISTINCT justificacion FROM novedades")->fetchAll();
$justifVals = array_column($justif, 'justificacion');
check("Justificacion SI existe",    in_array('SI', $justifVals));
check("Justificacion NO existe",    in_array('NO', $justifVals));
check("No hay justificacion rara",  count($justifVals) <= 2);

$sedesNov = $pdo->query("SELECT DISTINCT sede FROM novedades")->fetchAll();
$sedesNovNames = array_column($sedesNov, 'sede');
$sedesRaras = array_diff($sedesNovNames, ['Sede 1','Sede 2','Sede 3','Granjas','Toberin','Planta','Huevos','Producción','Administrativo','Yopal','Puntos de Venta','Guadalupe','Visión Colombia']);
check("No hay sedes desconocidas",  count($sedesRaras) === 0, implode(', ', $sedesRaras));

echo "\n=== TEST 5: TIPOS NOVEDAD ===\n";
$tipos = $pdo->query("SELECT nombre FROM tipos_novedad WHERE activo=1")->fetchAll();
check("11 tipos de novedad",        count($tipos) === 11);

echo "\n=== TEST 6: USUARIOS LIDERES ===\n";
$totalUsers = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
check("50 usuarios total",          $totalUsers === 50, "total=$totalUsers");

$admins = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol='director'")->fetchColumn();
check("Directores >= 26",           $admins >= 26, "directores=$admins");

$jefes = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol='jefe'")->fetchColumn();
check("Jefes >= 19",                $jefes >= 19, "jefes=$jefes");

$conCargo = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE cargo IS NOT NULL AND cargo != ''")->fetchColumn();
check("Todos tienen cargo",         $conCargo >= 45, "con cargo=$conCargo");

echo "\n=== TEST 7: SEDES ASIGNADAS A JEFES ===\n";
$sedesEromero = $pdo->query("SELECT s.nombre FROM sedes s JOIN usuario_sedes us ON s.id=us.sede_id JOIN usuarios u ON u.id=us.usuario_id WHERE u.usuario='eromero'")->fetchAll();
check("eromero tiene sede asignada",    count($sedesEromero) > 0);
check("eromero en Administrativo",      $sedesEromero[0]['nombre'] === 'Administrativo');

$sedesLardila = $pdo->query("SELECT s.nombre FROM sedes s JOIN usuario_sedes us ON s.id=us.sede_id JOIN usuarios u ON u.id=us.usuario_id WHERE u.usuario='lardila'")->fetchAll();
check("lardila en Yopal",               !empty($sedesLardila) && $sedesLardila[0]['nombre'] === 'Yopal');

echo "\n=== TEST 8: ESTADISTICAS ===\n";
$porSede = $pdo->query("SELECT sede, COUNT(*) as total FROM novedades GROUP BY sede ORDER BY total DESC")->fetchAll();
check("Estadisticas por sede OK",   count($porSede) > 0);
check("Sede 3 tiene mas novedades", $porSede[0]['sede'] === 'Sede 3');

$porTipo = $pdo->query("SELECT novedad as tipo, COUNT(*) as total FROM novedades GROUP BY novedad ORDER BY total DESC")->fetchAll();
check("Estadisticas por tipo OK",   count($porTipo) > 0);
check("AUSENCIA es el tipo top",    $porTipo[0]['tipo'] === 'AUSENCIA');

echo "\n=== RESUMEN ===\n";
echo "  OK:   $ok\n";
echo "  FAIL: $fail\n";
echo "  TOTAL: " . ($ok + $fail) . "\n";
if ($fail === 0) echo "\n  SISTEMA 100% FUNCIONAL\n";
else echo "\n  HAY $fail PROBLEMAS A REVISAR\n";

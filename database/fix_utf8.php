<?php
/**
 * Corrige encoding UTF-8 en areas_trabajo, sedes, zonas_geograficas y tipos_novedad
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
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// ============================================================
// AREAS DE TRABAJO - nombres con caracteres especiales
// ============================================================
$areas = [
    // Buscar por LIKE y corregir
    ['like' => 'Vig%as',              'correcto' => 'Vigías'],
    ['like' => 'Caba%a',              'correcto' => 'Cabaña'],
    ['like' => 'Engativ%',            'correcto' => 'Engativá Centro'],
    ['like' => 'Fusagasug%',          'correcto' => 'Fusagasugá'],
    ['like' => 'Gesti%n Humana',      'correcto' => 'Gestión Humana'],
    ['like' => 'Auditor%a',           'correcto' => 'Auditoría'],
    ['like' => 'Tesorer%a',           'correcto' => 'Tesorería'],
    ['like' => 'Distribuci%n de Huevos', 'correcto' => 'Distribución de Huevos'],
    ['like' => 'Centro de Distribuci%n Toberin', 'correcto' => 'Centro de Distribución Toberin'],
    ['like' => 'Producci%n',          'correcto' => 'Producción'],
    ['like' => 'SAGRILAF%',           'correcto' => 'SAGRILAFT'],
    ['like' => 'Arquitectura',        'correcto' => 'Arquitectura'],
    ['like' => 'Procesados',          'correcto' => 'Procesados'],
];

echo "=== AREAS DE TRABAJO ===\n";
$stmt = $pdo->prepare("UPDATE areas_trabajo SET nombre = :correcto WHERE nombre LIKE :like AND nombre != :correcto");
foreach ($areas as $a) {
    $stmt->execute([':correcto' => $a['correcto'], ':like' => $a['like']]);
    if ($stmt->rowCount() > 0)
        echo "  Corregido: '{$a['like']}' -> '{$a['correcto']}'\n";
}

// Mostrar todas las areas actuales
echo "\nAreas actuales:\n";
foreach ($pdo->query("SELECT nombre FROM areas_trabajo ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC) as $a)
    echo "  - {$a['nombre']}\n";

// ============================================================
// SEDES
// ============================================================
$sedes = [
    ['like' => 'Producci%n',      'correcto' => 'Producción'],
    ['like' => 'Visi%n Colombia', 'correcto' => 'Visión Colombia'],
    ['like' => 'Toberin',         'correcto' => 'Toberin'],
];

echo "\n=== SEDES ===\n";
$stmt = $pdo->prepare("UPDATE sedes SET nombre = :correcto WHERE nombre LIKE :like AND nombre != :correcto");
foreach ($sedes as $s) {
    $stmt->execute([':correcto' => $s['correcto'], ':like' => $s['like']]);
    if ($stmt->rowCount() > 0)
        echo "  Corregido: '{$s['like']}' -> '{$s['correcto']}'\n";
}

echo "\nSedes actuales:\n";
foreach ($pdo->query("SELECT nombre FROM sedes ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC) as $s)
    echo "  - {$s['nombre']}\n";

// ============================================================
// TIPOS NOVEDAD
// ============================================================
$tipos = [
    ['like' => 'NOTIFICACI%N',                    'correcto' => 'NOTIFICACIÓN'],
    ['like' => 'REINTEGRO DE AUSENCIA/SANCI%N',   'correcto' => 'REINTEGRO DE AUSENCIA/SANCIÓN'],
    ['like' => 'PERMISO NO REMUNERADO',            'correcto' => 'PERMISO NO REMUNERADO'],
    ['like' => 'PERMISO REMUNERADO',               'correcto' => 'PERMISO REMUNERADO'],
    ['like' => 'VACACIONES',                       'correcto' => 'VACACIONES'],
    ['like' => 'INCAPACIDAD',                      'correcto' => 'INCAPACIDAD'],
    ['like' => 'AUSENCIA',                         'correcto' => 'AUSENCIA'],
    ['like' => 'RENUNCIA',                         'correcto' => 'RENUNCIA'],
    ['like' => 'AISLAMIENTO',                      'correcto' => 'AISLAMIENTO'],
    ['like' => 'REINTEGRO DE INCAPACIDAD',         'correcto' => 'REINTEGRO DE INCAPACIDAD'],
    ['like' => 'REINTEGRO DE VACACIONES',          'correcto' => 'REINTEGRO DE VACACIONES'],
];

echo "\n=== TIPOS NOVEDAD ===\n";
$stmt = $pdo->prepare("UPDATE tipos_novedad SET nombre = :correcto WHERE nombre LIKE :like AND nombre != :correcto");
foreach ($tipos as $t) {
    $stmt->execute([':correcto' => $t['correcto'], ':like' => $t['like']]);
    if ($stmt->rowCount() > 0)
        echo "  Corregido: '{$t['like']}' -> '{$t['correcto']}'\n";
}

echo "\nTipos novedad actuales:\n";
foreach ($pdo->query("SELECT nombre FROM tipos_novedad ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC) as $t)
    echo "  - {$t['nombre']}\n";

// ============================================================
// NOVEDADES - corregir novedad y sede con encoding roto
// ============================================================
echo "\n=== NOVEDADES (tipos) ===\n";
$pdo->exec("UPDATE novedades SET novedad = 'NOTIFICACIÓN' WHERE novedad LIKE 'NOTIFICACI%N'");
$pdo->exec("UPDATE novedades SET novedad = 'REINTEGRO DE AUSENCIA/SANCIÓN' WHERE novedad LIKE 'REINTEGRO DE AUSENCIA/SANCI%N'");
echo "Tipos en novedades corregidos\n";

// Corregir sede Producción en novedades
$pdo->exec("UPDATE novedades SET sede = 'Producción' WHERE sede LIKE 'Producci%n'");
$pdo->exec("UPDATE novedades SET sede = 'Visión Colombia' WHERE sede LIKE 'Visi%n Colombia'");
echo "Sedes en novedades corregidas\n";

// ============================================================
// VERIFICAR TABLA COLLATION
// ============================================================
echo "\n=== COLLATION TABLAS ===\n";
foreach (['areas_trabajo','sedes','zonas_geograficas','tipos_novedad','novedades','usuarios'] as $tabla) {
    $col = $pdo->query("SELECT CCSA.character_set_name, CCSA.collation_name 
        FROM information_schema.`TABLES` T
        JOIN information_schema.`COLLATION_CHARACTER_SET_APPLICABILITY` CCSA 
            ON CCSA.collation_name = T.table_collation
        WHERE T.table_schema = DATABASE() AND T.table_name = '$tabla'")->fetch(PDO::FETCH_ASSOC);
    echo "  $tabla: {$col['character_set_name']} / {$col['collation_name']}\n";
}

echo "\nDONE\n";

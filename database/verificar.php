<?php
define('ROOT_PATH',    dirname(__DIR__));
define('CONFIG_PATH',  ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('APP_PATH',     ROOT_PATH . '/app');

$config = require CONFIG_PATH . '/config.php';
$db     = $config['database'];

try {
    $pdo = new PDO(
        "mysql:host={$db['host']};dbname={$db['database']};charset=utf8mb4",
        $db['username'], $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    die("ERROR conexion: " . $e->getMessage() . "\n");
}

echo "=== CONTEOS ===\n";
foreach (['usuarios','sedes','zonas_geograficas','areas_trabajo','tipos_novedad','novedades','archivos_adjuntos'] as $t) {
    echo str_pad($t, 25) . ": " . $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn() . "\n";
}

echo "\n=== JRIOS ===\n";
$r = $pdo->query("SELECT usuario, nombre, cargo, rol FROM usuarios WHERE usuario='jrios'")->fetch();
echo "  usuario={$r['usuario']} | rol={$r['rol']} | cargo={$r['cargo']}\n";

echo "\n=== EROMERO ===\n";
$r = $pdo->query("SELECT usuario, nombre, cargo, rol FROM usuarios WHERE usuario='eromero'")->fetch();
echo "  usuario={$r['usuario']} | rol={$r['rol']} | cargo={$r['cargo']}\n";

echo "\n=== SEDES ===\n";
foreach ($pdo->query("SELECT nombre FROM sedes ORDER BY nombre")->fetchAll() as $s)
    echo "  - {$s['nombre']}\n";

echo "\n=== ZONAS GEOGRAFICAS ===\n";
foreach ($pdo->query("SELECT zg.nombre, s.nombre as sede FROM zonas_geograficas zg JOIN sedes s ON zg.sede_id=s.id")->fetchAll() as $z)
    echo "  - {$z['nombre']} -> sede: {$z['sede']}\n";

echo "\n=== AREAS YOPAL ===\n";
foreach ($pdo->query("SELECT at.nombre FROM areas_trabajo at JOIN sedes s ON at.sede_id=s.id WHERE s.nombre='Yopal'")->fetchAll() as $a)
    echo "  - {$a['nombre']}\n";

echo "\n=== TIPOS NOVEDAD ===\n";
foreach ($pdo->query("SELECT nombre FROM tipos_novedad ORDER BY nombre")->fetchAll() as $t)
    echo "  - {$t['nombre']}\n";

echo "\n=== MUESTRA NOVEDADES (5) ===\n";
foreach ($pdo->query("SELECT nombres_apellidos, sede, area_trabajo, novedad, responsable FROM novedades LIMIT 5")->fetchAll() as $n)
    echo "  {$n['nombres_apellidos']} | {$n['sede']} | {$n['area_trabajo']} | {$n['novedad']} | resp: {$n['responsable']}\n";

echo "\n=== NOVEDADES POR SEDE ===\n";
foreach ($pdo->query("SELECT sede, COUNT(*) as total FROM novedades GROUP BY sede ORDER BY total DESC")->fetchAll() as $s)
    echo "  {$s['sede']}: {$s['total']}\n";

echo "\n=== RESPONSABLE CAMPO (muestra) ===\n";
foreach ($pdo->query("SELECT DISTINCT responsable FROM novedades LIMIT 5")->fetchAll() as $r)
    echo "  '{$r['responsable']}'\n";

echo "\n=== VERIFICACION HASH PASSWORD (123456) ===\n";
$hash = $pdo->query("SELECT password FROM usuarios WHERE usuario='jrios'")->fetchColumn();
echo "  password_verify: " . (password_verify('123456', $hash) ? 'OK' : 'FALLO') . "\n";

echo "\n=== SEDES ASIGNADAS A JRIOS ===\n";
foreach ($pdo->query("SELECT s.nombre FROM sedes s JOIN usuario_sedes us ON s.id=us.sede_id JOIN usuarios u ON u.id=us.usuario_id WHERE u.usuario='jrios'")->fetchAll() as $s)
    echo "  - {$s['nombre']}\n";
if (empty($pdo->query("SELECT s.nombre FROM sedes s JOIN usuario_sedes us ON s.id=us.sede_id JOIN usuarios u ON u.id=us.usuario_id WHERE u.usuario='jrios'")->fetchAll()))
    echo "  (ninguna - es admin, ve todo)\n";

echo "\nDONE\n";

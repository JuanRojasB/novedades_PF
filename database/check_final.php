<?php
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

// Check for any remaining ?? broken chars in ALL text columns
$checks = [
    'areas_trabajo'    => 'nombre',
    'sedes'            => 'nombre',
    'zonas_geograficas'=> 'nombre',
    'tipos_novedad'    => 'nombre',
    'usuarios'         => 'nombre',
    'usuarios'         => 'cargo',
];

echo "=== BUSCAR CARACTERES ROTOS EN BD ===\n";
$found = false;
foreach ($checks as $tabla => $col) {
    $rows = $pdo->query("SELECT $col FROM $tabla WHERE $col LIKE '%??%' OR $col LIKE '%??' OR $col LIKE '??%'")->fetchAll();
    if (!empty($rows)) {
        echo "  ROTO en $tabla.$col:\n";
        foreach ($rows as $r) echo "    '{$r[$col]}'\n";
        $found = true;
    }
}
if (!$found) echo "  Ninguno - todo limpio\n";

echo "\n=== AREAS COMPLETAS ===\n";
foreach ($pdo->query("SELECT at.nombre as area, s.nombre as sede, zg.nombre as zona FROM areas_trabajo at JOIN sedes s ON at.sede_id=s.id LEFT JOIN zonas_geograficas zg ON at.zona_geografica_id=zg.id ORDER BY s.nombre, zg.nombre, at.nombre")->fetchAll() as $a)
    echo "  [{$a['sede']}]" . ($a['zona'] ? " [{$a['zona']}]" : "") . " {$a['area']}\n";

echo "\n=== SEDES ===\n";
foreach ($pdo->query("SELECT nombre FROM sedes ORDER BY nombre")->fetchAll() as $s)
    echo "  {$s['nombre']}\n";

echo "\n=== TIPOS NOVEDAD ===\n";
foreach ($pdo->query("SELECT nombre FROM tipos_novedad ORDER BY nombre")->fetchAll() as $t)
    echo "  {$t['nombre']}\n";

echo "\n=== ROLES ===\n";
foreach ($pdo->query("SELECT rol, COUNT(*) as total FROM usuarios GROUP BY rol")->fetchAll() as $r)
    echo "  {$r['rol']}: {$r['total']}\n";

echo "\nTODO OK\n";

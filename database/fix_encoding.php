<?php
/**
 * Fix encoding issues and remaining dirty data
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

// Fix remaining sede issues
$pdo->exec("UPDATE novedades SET sede = 'Administrativo' WHERE sede LIKE '%ig%as de riesgos%' OR sede LIKE 'Vig%'");
echo "Sede Vigias fixed: " . $pdo->query("SELECT ROW_COUNT()")->fetchColumn() . "\n";

// Fix NOTIFICACIÓN encoding
$pdo->exec("UPDATE novedades SET novedad = 'NOTIFICACIÓN' WHERE novedad LIKE 'NOTIFICACI%N'");
echo "NOTIFICACION fixed\n";

// Fix REINTEGRO DE AUSENCIA/SANCIÓN encoding  
$pdo->exec("UPDATE novedades SET novedad = 'REINTEGRO DE AUSENCIA/SANCIÓN' WHERE novedad LIKE 'REINTEGRO DE AUSENCIA/SANCI%N'");
echo "REINTEGRO SANCION fixed\n";

// Fix Producción encoding in sedes
$pdo->exec("UPDATE novedades SET sede = 'Producción' WHERE sede LIKE 'Producci%n'");
echo "Produccion fixed\n";

// Fix Visión Colombia encoding
$pdo->exec("UPDATE novedades SET sede = 'Visión Colombia' WHERE sede LIKE 'Visi%n Colombia'");
echo "Vision Colombia fixed\n";

// Fix turno encoding
$pdo->exec("UPDATE novedades SET turno = 'DÍA' WHERE turno LIKE 'D%A' AND turno != 'DÍA'");
echo "Turno DIA fixed\n";

// Normalize any remaining uppercase sedes that match known sedes
$knownSedes = ['Sede 1','Sede 2','Sede 3','Granjas','Toberin','Planta','Huevos','Producción','Administrativo','Yopal','Puntos de Venta','Guadalupe','Visión Colombia'];
foreach ($knownSedes as $sede) {
    $upper = strtoupper($sede);
    $pdo->prepare("UPDATE novedades SET sede = :correct WHERE UPPER(sede) = :upper AND sede != :correct")
        ->execute([':correct' => $sede, ':upper' => $upper]);
}

// Final report
echo "\n=== SEDES FINALES ===\n";
foreach ($pdo->query("SELECT sede, COUNT(*) as total FROM novedades GROUP BY sede ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC) as $s)
    echo "  {$s['sede']}: {$s['total']}\n";

echo "\n=== TIPOS NOVEDAD FINALES ===\n";
foreach ($pdo->query("SELECT novedad, COUNT(*) as total FROM novedades GROUP BY novedad ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC) as $n)
    echo "  {$n['novedad']}: {$n['total']}\n";

echo "\n=== TURNOS ===\n";
foreach ($pdo->query("SELECT turno, COUNT(*) as total FROM novedades GROUP BY turno")->fetchAll(PDO::FETCH_ASSOC) as $t)
    echo "  '{$t['turno']}': {$t['total']}\n";

echo "\n=== JUSTIFICACION ===\n";
foreach ($pdo->query("SELECT justificacion, COUNT(*) as total FROM novedades GROUP BY justificacion")->fetchAll(PDO::FETCH_ASSOC) as $j)
    echo "  '{$j['justificacion']}': {$j['total']}\n";

echo "\nDONE\n";

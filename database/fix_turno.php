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
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// Show what's in turno column
echo "Turno values (hex):\n";
foreach ($pdo->query("SELECT DISTINCT turno, HEX(turno) as hex_val, COUNT(*) as cnt FROM novedades GROUP BY turno")->fetchAll(PDO::FETCH_ASSOC) as $t) {
    echo "  '{$t['turno']}' hex={$t['hex_val']} count={$t['cnt']}\n";
}

// The empty string turno should be DÍA - fix it
// DÍA in utf8mb4 = 44 C3 8D 41 (D + Í + A)
// If stored as latin1 misread, the Í might be broken
// Set all empty/broken turno to DÍA
$affected = $pdo->exec("UPDATE novedades SET turno = 'DÍA' WHERE turno = '' OR turno IS NULL OR turno NOT IN ('DÍA','NOCHE')");
echo "\nFixed turno: $affected rows\n";

// Verify
echo "\nTurno after fix:\n";
foreach ($pdo->query("SELECT turno, COUNT(*) as total FROM novedades GROUP BY turno")->fetchAll(PDO::FETCH_ASSOC) as $t)
    echo "  '{$t['turno']}': {$t['total']}\n";

// Also fix observacion_novedad empty values
$pdo->exec("UPDATE novedades SET observacion_novedad = 'AUSENCIA' WHERE observacion_novedad = '' OR observacion_novedad IS NULL");
echo "\nFixed empty observacion_novedad\n";

// Fix nota empty string to NULL
$pdo->exec("UPDATE novedades SET nota = NULL WHERE nota = ''");
echo "Fixed empty nota to NULL\n";

echo "\nDONE\n";

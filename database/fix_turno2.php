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

// The ENUM('DÍA','NOCHE') is blocking empty string update
// First alter column to VARCHAR, fix, then restore ENUM
echo "Altering turno to VARCHAR...\n";
$pdo->exec("ALTER TABLE novedades MODIFY COLUMN turno VARCHAR(10) NOT NULL DEFAULT 'DÍA'");

$affected = $pdo->exec("UPDATE novedades SET turno = 'DÍA' WHERE turno = '' OR turno IS NULL");
echo "Fixed: $affected rows\n";

// Restore ENUM
echo "Restoring ENUM...\n";
$pdo->exec("ALTER TABLE novedades MODIFY COLUMN turno ENUM('DÍA','NOCHE') NOT NULL");

echo "\nTurno final:\n";
foreach ($pdo->query("SELECT turno, COUNT(*) as total FROM novedades GROUP BY turno")->fetchAll(PDO::FETCH_ASSOC) as $t)
    echo "  '{$t['turno']}': {$t['total']}\n";

// Also fix justificacion - make sure only SI/NO
echo "\nJustificacion:\n";
foreach ($pdo->query("SELECT justificacion, COUNT(*) as total FROM novedades GROUP BY justificacion")->fetchAll(PDO::FETCH_ASSOC) as $j)
    echo "  '{$j['justificacion']}': {$j['total']}\n";

// Fix descontar_dominical
echo "\nDescontar dominical:\n";
foreach ($pdo->query("SELECT descontar_dominical, COUNT(*) as total FROM novedades GROUP BY descontar_dominical")->fetchAll(PDO::FETCH_ASSOC) as $d)
    echo "  '{$d['descontar_dominical']}': {$d['total']}\n";

echo "\nDONE\n";

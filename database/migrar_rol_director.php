<?php
/**
 * Migra el rol 'admin' a 'director' en la base de datos activa
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

echo "Antes:\n";
foreach ($pdo->query("SELECT rol, COUNT(*) as total FROM usuarios GROUP BY rol")->fetchAll(PDO::FETCH_ASSOC) as $r)
    echo "  rol={$r['rol']}: {$r['total']}\n";

// 1. Cambiar ENUM para aceptar ambos temporalmente
$pdo->exec("ALTER TABLE usuarios MODIFY COLUMN rol ENUM('admin','director','jefe') DEFAULT 'jefe'");
echo "\nENUM ampliado\n";

// 2. Actualizar todos los 'admin' a 'director'
$affected = $pdo->exec("UPDATE usuarios SET rol = 'director' WHERE rol = 'admin'");
echo "Filas actualizadas: $affected\n";

// 3. Restaurar ENUM sin 'admin'
$pdo->exec("ALTER TABLE usuarios MODIFY COLUMN rol ENUM('director','jefe') DEFAULT 'jefe'");
echo "ENUM restaurado a (director, jefe)\n";

echo "\nDespués:\n";
foreach ($pdo->query("SELECT rol, COUNT(*) as total FROM usuarios GROUP BY rol")->fetchAll(PDO::FETCH_ASSOC) as $r)
    echo "  rol={$r['rol']}: {$r['total']}\n";

echo "\nMuestra de directores:\n";
foreach ($pdo->query("SELECT usuario, nombre, cargo, rol FROM usuarios WHERE rol='director' LIMIT 5")->fetchAll(PDO::FETCH_ASSOC) as $u)
    echo "  {$u['usuario']} | {$u['nombre']} | {$u['cargo']}\n";

echo "\nDONE\n";

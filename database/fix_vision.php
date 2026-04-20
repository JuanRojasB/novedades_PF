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

// Ver el valor exacto en hex
$row = $pdo->query("SELECT nombre, HEX(nombre) as hex FROM areas_trabajo WHERE nombre LIKE 'Visi%n Colombia'")->fetch(PDO::FETCH_ASSOC);
echo "Valor actual: '{$row['nombre']}'\n";
echo "HEX: {$row['hex']}\n";

// Forzar update por ID para evitar problemas de encoding en WHERE
$id = $pdo->query("SELECT id FROM areas_trabajo WHERE nombre LIKE 'Visi%n Colombia'")->fetchColumn();
if ($id) {
    $stmt = $pdo->prepare("UPDATE areas_trabajo SET nombre = 'Visión Colombia' WHERE id = :id");
    $stmt->execute([':id' => $id]);
    echo "Corregido ID $id -> 'Visión Colombia'\n";
}

// También corregir en schema.sql el INSERT
echo "\nAreas con 'ó' o 'ó' rota:\n";
foreach ($pdo->query("SELECT id, nombre FROM areas_trabajo ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC) as $a)
    echo "  [{$a['id']}] {$a['nombre']}\n";

echo "\nDONE\n";

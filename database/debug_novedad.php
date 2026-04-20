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

echo "=== ULTIMAS 5 NOVEDADES (por created_at) ===\n";
foreach ($pdo->query("SELECT id, nombres_apellidos, sede, fecha_novedad, created_at,
    (SELECT COUNT(*) FROM archivos_adjuntos WHERE novedad_id = n.id) as total_archivos
    FROM novedades n ORDER BY created_at DESC LIMIT 5")->fetchAll() as $n) {
    echo "  ID={$n['id']} | {$n['nombres_apellidos']} | {$n['sede']} | fecha={$n['fecha_novedad']} | creado={$n['created_at']} | archivos={$n['total_archivos']}\n";
}

echo "\n=== ARCHIVOS EN BD ===\n";
foreach ($pdo->query("SELECT id, novedad_id, nombre_archivo, tipo_mime, tamanio, ruta_archivo FROM archivos_adjuntos ORDER BY id DESC LIMIT 10")->fetchAll() as $a) {
    $existe = file_exists(STORAGE_PATH . '/uploads/' . $a['ruta_archivo']) ? 'EXISTE' : 'NO EXISTE';
    echo "  ID={$a['id']} | novedad={$a['novedad_id']} | {$a['nombre_archivo']} | ruta={$a['ruta_archivo']} | disco=$existe\n";
}

echo "\n=== COLUMNAS archivos_adjuntos ===\n";
foreach ($pdo->query("SHOW COLUMNS FROM archivos_adjuntos")->fetchAll() as $c)
    echo "  {$c['Field']} - {$c['Type']}\n";

echo "\n=== TOTAL NOVEDADES ===\n";
echo "  " . $pdo->query("SELECT COUNT(*) FROM novedades")->fetchColumn() . "\n";

echo "\nDONE\n";

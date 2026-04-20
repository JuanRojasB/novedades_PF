<?php
/**
 * Migra la tabla archivos_adjuntos de MEDIUMBLOB a ruta en filesystem
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

$uploadPath = STORAGE_PATH . '/uploads';
if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

// Verificar columnas actuales
$cols = $pdo->query("SHOW COLUMNS FROM archivos_adjuntos")->fetchAll(PDO::FETCH_ASSOC);
$colNames = array_column($cols, 'Field');
echo "Columnas actuales: " . implode(', ', $colNames) . "\n";

$tieneContenido    = in_array('contenido', $colNames);
$tieneRutaArchivo  = in_array('ruta_archivo', $colNames);

// 1. Si hay archivos con BLOB, extraerlos al disco primero
if ($tieneContenido && !$tieneRutaArchivo) {
    echo "\nMigrando archivos de BD a disco...\n";

    // Agregar columna ruta_archivo temporalmente
    $pdo->exec("ALTER TABLE archivos_adjuntos ADD COLUMN ruta_archivo VARCHAR(255) NULL AFTER tamanio");

    $archivos = $pdo->query("SELECT id, nombre_archivo, tipo_mime, contenido FROM archivos_adjuntos")->fetchAll(PDO::FETCH_ASSOC);
    echo "Archivos en BD: " . count($archivos) . "\n";

    $stmt = $pdo->prepare("UPDATE archivos_adjuntos SET ruta_archivo = :ruta WHERE id = :id");
    foreach ($archivos as $arch) {
        $ext          = strtolower(pathinfo($arch['nombre_archivo'], PATHINFO_EXTENSION));
        $nombre_unico = uniqid('arch_', true) . '.' . $ext;
        $ruta_disco   = $uploadPath . '/' . $nombre_unico;

        file_put_contents($ruta_disco, $arch['contenido']);
        $stmt->execute([':ruta' => $nombre_unico, ':id' => $arch['id']]);
        echo "  Migrado: {$arch['nombre_archivo']} -> $nombre_unico\n";
    }

    // Hacer ruta_archivo NOT NULL y eliminar contenido
    $pdo->exec("UPDATE archivos_adjuntos SET ruta_archivo = 'sin_archivo' WHERE ruta_archivo IS NULL");
    $pdo->exec("ALTER TABLE archivos_adjuntos MODIFY COLUMN ruta_archivo VARCHAR(255) NOT NULL");
    $pdo->exec("ALTER TABLE archivos_adjuntos DROP COLUMN contenido");
    echo "Columna 'contenido' eliminada\n";

} elseif (!$tieneRutaArchivo) {
    // No tiene ninguna de las dos — crear desde cero
    echo "\nAgregando columna ruta_archivo...\n";
    $pdo->exec("ALTER TABLE archivos_adjuntos ADD COLUMN ruta_archivo VARCHAR(255) NOT NULL DEFAULT '' AFTER tamanio");
    echo "Columna ruta_archivo agregada\n";

} else {
    echo "\nTabla ya tiene columna ruta_archivo - nada que migrar\n";

    // Si aún tiene contenido, eliminarlo
    if ($tieneContenido) {
        $pdo->exec("ALTER TABLE archivos_adjuntos DROP COLUMN contenido");
        echo "Columna 'contenido' eliminada\n";
    }
}

// Verificar estado final
echo "\nEstructura final:\n";
foreach ($pdo->query("SHOW COLUMNS FROM archivos_adjuntos")->fetchAll(PDO::FETCH_ASSOC) as $col)
    echo "  {$col['Field']} - {$col['Type']}\n";

echo "\nTotal archivos: " . $pdo->query("SELECT COUNT(*) FROM archivos_adjuntos")->fetchColumn() . "\n";
echo "\nDONE\n";

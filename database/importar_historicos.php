<?php
/**
 * Script de importación de novedades históricas
 * Ejecutar desde la raíz del proyecto: php database/importar_historicos.php
 */

define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');

$config = require CONFIG_PATH . '/config.php';
$db_cfg = $config['database'];

try {
    $pdo = new PDO(
        "mysql:host={$db_cfg['host']};dbname={$db_cfg['database']};charset=utf8mb4",
        $db_cfg['username'],
        $db_cfg['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage() . "\n");
}

$csvFile = __DIR__ . '/novedades_historicas.csv';
if (!file_exists($csvFile)) {
    die("Archivo CSV no encontrado: $csvFile\n");
}

// Leer CSV con separador ";"
$handle = fopen($csvFile, 'r');
if (!$handle) {
    die("No se pudo abrir el archivo CSV\n");
}

// Leer encabezados (primera línea)
$headers = fgetcsv($handle, 0, ';');

// Mapeo de columnas CSV → BD
// Columnas: ID;Correo;Nombre;Nombres y Apellidos;Número Cedula;Sede;ÁREA;FECHA;TURNO;NOVEDAD;JUSTIFICACIÓN;ADJUNTAR;DOMINICAL;Observación;NOTA;RESPONSABLE;¿Corrección?;¿A qué se debe?
// Índices:   0  1      2      3                  4             5    6     7     8     9       10            11       12        13          14   15           16            17

$stmt = $pdo->prepare("
    INSERT INTO novedades 
        (nombres_apellidos, numero_cedula, sede, zona_geografica, area_trabajo,
         fecha_novedad, turno, novedad, justificacion,
         descontar_dominical, observacion_novedad, nota, responsable)
    VALUES 
        (:nombres_apellidos, :numero_cedula, :sede, NULL, :area_trabajo,
         :fecha_novedad, :turno, :novedad, :justificacion,
         :descontar_dominical, :observacion_novedad, :nota, :responsable)
");

$importados = 0;
$errores    = 0;
$linea      = 1;

// Mapeo de justificación
function mapJustificacion(string $val): string {
    $val = strtoupper(trim($val));
    if (str_contains($val, 'CON') || $val === 'SI' || $val === 'SÍ') return 'SI';
    if (str_contains($val, 'SIN') || $val === 'NO')                   return 'NO';
    return 'NO'; // default
}

// Mapeo de turno
function mapTurno(string $val): string {
    $val = strtoupper(trim($val));
    if (str_contains($val, 'NOCHE')) return 'NOCHE';
    return 'DÍA';
}

// Mapeo de dominical
function mapDominical(string $val): string {
    $val = strtoupper(trim($val));
    if ($val === 'SI' || $val === 'SÍ') return 'SI';
    return 'NO';
}

// Parsear fecha (puede venir como M/D/YYYY o D/M/YYYY)
function parseFecha(string $val): ?string {
    $val = trim($val);
    if (empty($val)) return null;

    // Intentar M/D/YYYY (formato americano del CSV)
    if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $val, $m)) {
        // Asumir M/D/YYYY
        $month = (int)$m[1];
        $day   = (int)$m[2];
        $year  = (int)$m[3];
        if ($month > 12) { // swap si mes > 12 (es D/M)
            [$month, $day] = [$day, $month];
        }
        if (checkdate($month, $day, $year)) {
            return sprintf('%04d-%02d-%02d', $year, $month, $day);
        }
    }
    // Intentar YYYY-MM-DD
    if (preg_match('#^\d{4}-\d{2}-\d{2}$#', $val)) {
        return $val;
    }
    return null;
}

while (($row = fgetcsv($handle, 0, ';')) !== false) {
    $linea++;
    
    // Saltar filas vacías
    if (empty(array_filter($row))) continue;
    
    // Asegurar que hay suficientes columnas
    while (count($row) < 18) $row[] = '';

    $nombres   = trim($row[3] ?? '');
    $cedula    = preg_replace('/\D/', '', trim($row[4] ?? ''));
    $sede      = trim($row[5] ?? '');
    $area      = trim($row[6] ?? '');
    $fechaRaw  = trim($row[7] ?? '');
    $turno     = mapTurno($row[8] ?? '');
    $novedad   = trim($row[9] ?? '');
    $justif    = mapJustificacion($row[10] ?? '');
    $dominical = mapDominical($row[12] ?? '');
    $observ    = trim($row[13] ?? '');
    $nota      = trim($row[14] ?? '') ?: null;
    $responsable = trim($row[15] ?? '') ?: 'Importación histórica';

    // Validaciones mínimas
    if (empty($nombres) || empty($cedula) || empty($sede) || empty($area)) {
        echo "Línea $linea: Datos incompletos, omitida\n";
        $errores++;
        continue;
    }

    $fecha = parseFecha($fechaRaw);
    if (!$fecha) {
        echo "Línea $linea: Fecha inválida '$fechaRaw', omitida\n";
        $errores++;
        continue;
    }

    // Normalizar novedad
    if (empty($novedad)) $novedad = 'AUSENCIA';

    // Normalizar observación
    if (empty($observ)) $observ = 'AUSENCIA';

    try {
        $stmt->execute([
            ':nombres_apellidos'   => mb_strtoupper($nombres),
            ':numero_cedula'       => $cedula,
            ':sede'                => $sede,
            ':area_trabajo'        => $area,
            ':fecha_novedad'       => $fecha,
            ':turno'               => $turno,
            ':novedad'             => $novedad,
            ':justificacion'       => $justif,
            ':descontar_dominical' => $dominical,
            ':observacion_novedad' => $observ,
            ':nota'                => $nota,
            ':responsable'         => $responsable,
        ]);
        $importados++;
    } catch (PDOException $e) {
        echo "Línea $linea: Error BD - " . $e->getMessage() . "\n";
        $errores++;
    }
}

fclose($handle);

echo "\n=== IMPORTACIÓN COMPLETADA ===\n";
echo "Importados: $importados\n";
echo "Errores:    $errores\n";
echo "Total filas procesadas: " . ($linea - 1) . "\n";

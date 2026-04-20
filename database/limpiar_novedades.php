<?php
/**
 * Limpia y normaliza los datos históricos importados:
 * - Normaliza nombres de sedes inconsistentes
 * - Normaliza nombres de áreas inconsistentes
 * - Normaliza tipos de novedad
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

// ============================================================
// 1. NORMALIZAR SEDES
// ============================================================
$mapSedes = [
    // Planta de Beneficio
    'PB'                    => 'Planta',
    'P.B'                   => 'Planta',
    'P.B.'                  => 'Planta',
    'planta b'              => 'Planta',
    'PLANTA BENEFICIO'      => 'Planta',
    'PLANTA DE BENEFICIO'   => 'Planta',
    'Planta de Beneficio'   => 'Planta',
    'PLANTA BENENFICIO'     => 'Planta',
    'PLANTA BENEIFICO'      => 'Planta',
    'PLANTA BENENFICIO'     => 'Planta',
    'PLANAT DE BENEFICIO'   => 'Planta',
    'PLANTA DE BENEFICO'    => 'Planta',
    'PLANTA BENFICIO'       => 'Planta',
    // Yopal
    'REGIONAL YOPAL'        => 'Yopal',
    'YOPAL'                 => 'Yopal',
    'CEDIS YOPAL'           => 'Yopal',
    // Administrativo
    'HSEQ'                  => 'Administrativo',
    'ADMINISTRATIVO'        => 'Administrativo',
    // Granjas
    'GRANJAS'               => 'Granjas',
    // Toberin
    'TOBERIN'               => 'Toberin',
    'TOBERÍN'               => 'Toberin',
    // Sede 1
    'SEDE 1'                => 'Sede 1',
    'sede 1'                => 'Sede 1',
    // Sede 2
    'SEDE 2'                => 'Sede 2',
    'sede 2'                => 'Sede 2',
    // Sede 3
    'SEDE 3'                => 'Sede 3',
    'sede 3'                => 'Sede 3',
    // Puntos de Venta
    'PUNTOS DE VENTA'       => 'Puntos de Venta',
    'PDV'                   => 'Puntos de Venta',
    // Guadalupe
    'GUADALUPE'             => 'Guadalupe',
    // Huevos
    'HUEVOS'                => 'Huevos',
    // Producción
    'PRODUCCION'            => 'Producción',
    'PRODUCCIÓN'            => 'Producción',
    // Visión Colombia
    'VISION COLOMBIA'       => 'Visión Colombia',
    'VISIÓN COLOMBIA'       => 'Visión Colombia',
    // Casos especiales del CSV
    'Principal'             => 'Administrativo',
    'Vigías de riesgos'     => 'Administrativo',
    'AGUAZUL'               => 'Yopal',
];

$stmtSede = $pdo->prepare("UPDATE novedades SET sede = :nueva WHERE sede = :vieja");
$totalSedes = 0;
foreach ($mapSedes as $vieja => $nueva) {
    $stmtSede->execute([':nueva' => $nueva, ':vieja' => $vieja]);
    $rows = $stmtSede->rowCount();
    if ($rows > 0) {
        echo "Sede '$vieja' -> '$nueva': $rows registros\n";
        $totalSedes += $rows;
    }
}
echo "Total sedes normalizadas: $totalSedes\n\n";

// ============================================================
// 2. NORMALIZAR ÁREAS DE TRABAJO
// ============================================================
$mapAreas = [
    // Planta de Beneficio
    'PLANTA DE BENEFICIO'       => 'Planta de Beneficio',
    'PLANTA BENEFICIO'          => 'Planta de Beneficio',
    'PLANTA'                    => 'Planta de Beneficio',
    'POST-PROCESO'              => 'Posproceso',
    'POSPROCESO'                => 'Posproceso',
    'POST PROCESO'              => 'Posproceso',
    'DESPACHOS'                 => 'Despachos',
    'PROCESADOS'                => 'Procesados',
    // Administrativo
    'ADMINISTRATIVO'            => 'Gestión Humana',
    'GESTION HUMANA'            => 'Gestión Humana',
    'GESTIÓN HUMANA'            => 'Gestión Humana',
    'RECURSOS HUMANOS'          => 'Gestión Humana',
    'HSEQ'                      => 'HSEQ',
    'SISTEMAS'                  => 'Sistemas',
    'CONTABILIDAD'              => 'Contabilidad',
    'AUDITORIA'                 => 'Auditoría',
    'AUDITORÍA'                 => 'Auditoría',
    'COMPRAS'                   => 'Compras',
    'TESORERIA'                 => 'Tesorería',
    'TESORERÍA'                 => 'Tesorería',
    'CARTERA'                   => 'Cartera',
    'GERENCIA'                  => 'Gerencia General',
    'GERENCIA GENERAL'          => 'Gerencia General',
    // Granjas
    'GRANJAS'                   => 'Granjas',
    'CAMPO'                     => 'Granjas',
    // Yopal
    'YOPAL PDV'                 => 'Yopal PDV',
    'YOPAL BODEGA'              => 'Yopal Bodega',
    'YOPAL'                     => 'Yopal PDV',
    // Puntos de Venta
    'PUNTOS DE VENTA'           => 'Puntos de Venta',
    'PDV'                       => 'Puntos de Venta',
    // Huevos
    'HUEVOS'                    => 'Distribución de Huevos',
    'DISTRIBUCION DE HUEVOS'    => 'Distribución de Huevos',
    'DISTRIBUCIÓN DE HUEVOS'    => 'Distribución de Huevos',
    // Toberin
    'TOBERIN'                   => 'Centro de Distribución Toberin',
    'TOBERÍN'                   => 'Centro de Distribución Toberin',
    'CENTRO DE DISTRIBUCION'    => 'Centro de Distribución Toberin',
];

$stmtArea = $pdo->prepare("UPDATE novedades SET area_trabajo = :nueva WHERE area_trabajo = :vieja");
$totalAreas = 0;
foreach ($mapAreas as $vieja => $nueva) {
    $stmtArea->execute([':nueva' => $nueva, ':vieja' => $vieja]);
    $rows = $stmtArea->rowCount();
    if ($rows > 0) {
        echo "Area '$vieja' -> '$nueva': $rows registros\n";
        $totalAreas += $rows;
    }
}
echo "Total areas normalizadas: $totalAreas\n\n";

// ============================================================
// 3. NORMALIZAR TIPOS DE NOVEDAD
// ============================================================
$mapNovedad = [
    'AUSENCIA'                      => 'AUSENCIA',
    'INCAPACIDAD'                   => 'INCAPACIDAD',
    'PERMISO NO REMUNERADO'         => 'PERMISO NO REMUNERADO',
    'PERMISO REMUNERADO'            => 'PERMISO REMUNERADO',
    'VACACIONES'                    => 'VACACIONES',
    'REINTEGRO DE INCAPACIDAD'      => 'REINTEGRO DE INCAPACIDAD',
    'REINTEGRO DE VACACIONES'       => 'REINTEGRO DE VACACIONES',
    'REINTEGRO DE AUSENCIA/SANCION' => 'REINTEGRO DE AUSENCIA/SANCIÓN',
    'REINTEGRO DE AUSENCIA/SANCIÓN' => 'REINTEGRO DE AUSENCIA/SANCIÓN',
    'REINTEGRO DE AUSENCIA'         => 'REINTEGRO DE AUSENCIA/SANCIÓN',
    'AISLAMIENTO'                   => 'AISLAMIENTO',
    'NOTIFICACION'                  => 'NOTIFICACIÓN',
    'NOTIFICACIÓN'                  => 'NOTIFICACIÓN',
    'RENUNCIA'                      => 'RENUNCIA',
    // Variantes del CSV
    'Ausencia'                      => 'AUSENCIA',
    'Incapacidad'                   => 'INCAPACIDAD',
    'Vacaciones'                    => 'VACACIONES',
    'Renuncia'                      => 'RENUNCIA',
    'PERMISO'                       => 'PERMISO NO REMUNERADO',
    'LICENCIA'                      => 'PERMISO REMUNERADO',
    'SANCION'                       => 'REINTEGRO DE AUSENCIA/SANCIÓN',
    'SANCIÓN'                       => 'REINTEGRO DE AUSENCIA/SANCIÓN',
];

$stmtNov = $pdo->prepare("UPDATE novedades SET novedad = :nueva WHERE novedad = :vieja");
$totalNov = 0;
foreach ($mapNovedad as $vieja => $nueva) {
    $stmtNov->execute([':nueva' => $nueva, ':vieja' => $vieja]);
    $rows = $stmtNov->rowCount();
    if ($rows > 0) {
        echo "Novedad '$vieja' -> '$nueva': $rows registros\n";
        $totalNov += $rows;
    }
}
echo "Total novedades normalizadas: $totalNov\n\n";

// ============================================================
// 4. NORMALIZAR JUSTIFICACION
// ============================================================
$pdo->exec("UPDATE novedades SET justificacion = 'SI' WHERE justificacion IN ('CON JUSTIFICACIÓN','CON JUSTIFICACION','SI','SÍ','Con Justificación','Con Justificacion')");
$pdo->exec("UPDATE novedades SET justificacion = 'NO' WHERE justificacion IN ('SIN JUSTIFICACIÓN','SIN JUSTIFICACION','NO','Sin Justificación','Sin Justificacion')");
echo "Justificaciones normalizadas\n\n";

// ============================================================
// 5. REPORTE FINAL
// ============================================================
echo "=== ESTADO FINAL ===\n";
echo "Total novedades: " . $pdo->query("SELECT COUNT(*) FROM novedades")->fetchColumn() . "\n\n";

echo "Sedes en novedades:\n";
foreach ($pdo->query("SELECT sede, COUNT(*) as total FROM novedades GROUP BY sede ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC) as $s)
    echo "  {$s['sede']}: {$s['total']}\n";

echo "\nAreas (top 10):\n";
foreach ($pdo->query("SELECT area_trabajo, COUNT(*) as total FROM novedades GROUP BY area_trabajo ORDER BY total DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC) as $a)
    echo "  {$a['area_trabajo']}: {$a['total']}\n";

echo "\nTipos novedad:\n";
foreach ($pdo->query("SELECT novedad, COUNT(*) as total FROM novedades GROUP BY novedad ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC) as $n)
    echo "  {$n['novedad']}: {$n['total']}\n";

echo "\nDONE\n";

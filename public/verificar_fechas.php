<?php
// verificar_fechas.php - Verificar las fechas reales en la base de datos

$host = 'localhost';
$dbname = 'pollo_fiesta_novedades';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>📅 Verificar Fechas de Novedades</h1>";
    echo "<hr>";
    
    // Obtener rango de fechas
    $stmt = $pdo->query("SELECT MIN(fecha_novedad) as min_fecha, MAX(fecha_novedad) as max_fecha, COUNT(*) as total FROM novedades");
    $rango = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h2>📊 Rango de Fechas</h2>";
    echo "<p><strong>Fecha más antigua:</strong> {$rango['min_fecha']}</p>";
    echo "<p><strong>Fecha más reciente:</strong> {$rango['max_fecha']}</p>";
    echo "<p><strong>Total de novedades:</strong> {$rango['total']}</p>";
    
    // Obtener distribución por mes
    echo "<hr>";
    echo "<h2>📈 Distribución por Mes</h2>";
    
    $stmt = $pdo->query("
        SELECT 
            DATE_FORMAT(fecha_novedad, '%Y-%m') as mes,
            COUNT(*) as total
        FROM novedades
        GROUP BY mes
        ORDER BY mes DESC
        LIMIT 24
    ");
    $meses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
    echo "<tr style='background:#3b82f6;color:white;'>";
    echo "<th>Mes</th><th>Total Novedades</th>";
    echo "</tr>";
    
    foreach ($meses as $mes) {
        echo "<tr>";
        echo "<td><strong>{$mes['mes']}</strong></td>";
        echo "<td>{$mes['total']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<hr>";
    echo "<p><a href='index.php' style='background:#3b82f6;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>🏠 Volver al Inicio</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<?php
// corregir_nombres_usuarios.php - Corregir nombres mal escritos en la BD

$host = 'localhost';
$dbname = 'pollo_fiesta_novedades';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Corregir Nombres de Usuarios</h1>";
    echo "<hr>";
    
    // Correcciones de nombres mal escritos
    $correcciones = [
        'HURTADO SALAZAR JAMES' => 'James Hurtado Salazar',
        'YATE CLAVIJO JOS? VICENTE' => 'José Vicente Yate Clavijo',
        'PE?A AGUILERA JEFERSON STIVEN' => 'Jeferson Stiven Peña Aguilera',
        'MARTIN MARTIN OLGA LUCIA' => 'Olga Lucia Martin Martin'
    ];
    
    $corregidos = 0;
    
    foreach ($correcciones as $nombreIncorrecto => $nombreCorrecto) {
        $stmt = $pdo->prepare("UPDATE novedades SET nombres_apellidos = ? WHERE nombres_apellidos = ?");
        $stmt->execute([$nombreCorrecto, $nombreIncorrecto]);
        $affected = $stmt->rowCount();
        
        if ($affected > 0) {
            echo "<p>✅ Corregido: <strong>{$nombreIncorrecto}</strong> → <strong>{$nombreCorrecto}</strong> ({$affected} registros)</p>";
            $corregidos += $affected;
        }
    }
    
    echo "<hr>";
    echo "<h2>📊 Resumen</h2>";
    echo "<p>Total de registros corregidos: <strong>{$corregidos}</strong></p>";
    
    echo "<hr>";
    echo "<p><a href='index.php' style='background:#3b82f6;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>🏠 Volver al Inicio</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

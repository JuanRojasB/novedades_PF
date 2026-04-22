<?php
// corregir_todos_nombres.php - Corregir TODOS los nombres con caracteres mal codificados

$host = 'localhost';
$dbname = 'pollo_fiesta_novedades';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Corregir TODOS los Nombres con Caracteres Especiales</h1>";
    echo "<hr>";
    
    // Obtener todos los nombres únicos
    $stmt = $pdo->query("SELECT DISTINCT nombres_apellidos FROM novedades ORDER BY nombres_apellidos");
    $nombres = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>📋 Nombres a Revisar: " . count($nombres) . "</h2>";
    
    $corregidos = 0;
    $totalRegistros = 0;
    
    foreach ($nombres as $nombreOriginal) {
        // Detectar si tiene caracteres mal codificados
        if (strpos($nombreOriginal, '?') !== false || 
            strpos($nombreOriginal, '�') !== false ||
            preg_match('/[^\x20-\x7E\xC0-\xFF]/', $nombreOriginal)) {
            
            // Corregir caracteres especiales comunes
            $nombreCorregido = $nombreOriginal;
            
            // Reemplazos comunes de caracteres mal codificados
            $reemplazos = [
                'JOS?' => 'JOSÉ',
                'Jos?' => 'José',
                'PE?A' => 'PEÑA',
                'Pe?a' => 'Peña',
                'ANDR?S' => 'ANDRÉS',
                'Andr?s' => 'Andrés',
                'MAR?A' => 'MARÍA',
                'Mar?a' => 'María',
                'JES?S' => 'JESÚS',
                'Jes?s' => 'Jesús',
                'V?CTOR' => 'VÍCTOR',
                'V?ctor' => 'Víctor',
                'H?CTOR' => 'HÉCTOR',
                'H?ctor' => 'Héctor',
                'F?BIO' => 'FABIO',
                'F?bio' => 'Fabio',
                '?' => 'Ñ',
                '�' => 'Ñ'
            ];
            
            foreach ($reemplazos as $buscar => $reemplazar) {
                $nombreCorregido = str_replace($buscar, $reemplazar, $nombreCorregido);
            }
            
            // Convertir a formato Title Case (Primera Letra Mayúscula)
            $nombreCorregido = mb_convert_case(mb_strtolower($nombreCorregido, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            
            // Actualizar en la BD
            $stmt = $pdo->prepare("UPDATE novedades SET nombres_apellidos = ? WHERE nombres_apellidos = ?");
            $stmt->execute([$nombreCorregido, $nombreOriginal]);
            $affected = $stmt->rowCount();
            
            if ($affected > 0) {
                echo "<p>✅ <strong>{$nombreOriginal}</strong> → <strong>{$nombreCorregido}</strong> ({$affected} registros)</p>";
                $corregidos++;
                $totalRegistros += $affected;
            }
        }
    }
    
    echo "<hr>";
    echo "<h2>📊 Resumen</h2>";
    echo "<p>Nombres corregidos: <strong>{$corregidos}</strong></p>";
    echo "<p>Total de registros actualizados: <strong>{$totalRegistros}</strong></p>";
    
    // Mostrar nombres actualizados
    echo "<hr>";
    echo "<h2>✅ Nombres Actualizados</h2>";
    
    $stmt = $pdo->query("SELECT DISTINCT nombres_apellidos FROM novedades ORDER BY nombres_apellidos LIMIT 50");
    $nombresActualizados = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<div style='columns:2;column-gap:2rem;'>";
    foreach ($nombresActualizados as $nombre) {
        echo "<p style='margin:0.25rem 0;'>• {$nombre}</p>";
    }
    echo "</div>";
    
    echo "<hr>";
    echo "<p><a href='index.php' style='background:#3b82f6;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>🏠 Volver al Inicio</a></p>";
    echo "<p><a href='estadisticas' style='background:#10b981;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;margin-top:10px;'>📊 Ver Estadísticas</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<?php
// actualizar_todas_contrasenas.php - Actualizar contraseñas de TODOS los usuarios con patrón usuario2026*

$host = 'localhost';
$dbname = 'pollo_fiesta_novedades';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔐 Actualizar Contraseñas de TODOS los Usuarios</h1>";
    echo "<p><strong>Patrón:</strong> <code>usuario2026*</code></p>";
    echo "<hr>";
    
    // Obtener todos los usuarios
    $stmt = $pdo->query("SELECT id, usuario, nombre FROM usuarios ORDER BY usuario");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>📋 Usuarios a Actualizar: " . count($usuarios) . "</h2>";
    
    $actualizados = 0;
    $errores = 0;
    
    foreach ($usuarios as $user) {
        $usuario = $user['usuario'];
        $nuevaPassword = $usuario . '2026*';
        $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $stmt->execute([$hash, $user['id']]);
            
            echo "<p>✅ <strong>{$usuario}</strong> → <code>{$nuevaPassword}</code></p>";
            $actualizados++;
        } catch (PDOException $e) {
            echo "<p>❌ Error en <strong>{$usuario}</strong>: " . $e->getMessage() . "</p>";
            $errores++;
        }
    }
    
    echo "<hr>";
    echo "<h2>📊 Resumen</h2>";
    echo "<p>Contraseñas actualizadas: <strong>{$actualizados}</strong></p>";
    echo "<p>Errores: <strong>{$errores}</strong></p>";
    
    // Mostrar tabla de credenciales
    echo "<hr>";
    echo "<h2>🔑 Credenciales Actualizadas</h2>";
    echo "<table border='1' cellpadding='10' style='border-collapse:collapse;width:100%;'>";
    echo "<tr style='background:#3b82f6;color:white;'>";
    echo "<th>Usuario</th><th>Nombre</th><th>Contraseña</th>";
    echo "</tr>";
    
    $stmt = $pdo->query("SELECT usuario, nombre FROM usuarios ORDER BY usuario");
    $todosUsuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($todosUsuarios as $user) {
        $pass = $user['usuario'] . '2026*';
        echo "<tr>";
        echo "<td><strong>{$user['usuario']}</strong></td>";
        echo "<td>{$user['nombre']}</td>";
        echo "<td><code>{$pass}</code></td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>✅ ¡Todas las contraseñas actualizadas!</h3>";
    echo "<p><strong>Patrón aplicado:</strong> <code>usuario2026*</code></p>";
    echo "<p>Ejemplo: Usuario <code>fperez</code> → Contraseña <code>fperez2026*</code></p>";
    
    echo "<hr>";
    echo "<p><a href='index.php' style='background:#3b82f6;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>🔐 Ir al Login</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error de Conexión</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

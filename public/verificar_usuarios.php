<?php
// verificar_usuarios.php - Verificar si los usuarios de GH existen

$host = 'localhost';
$dbname = 'pollo_fiesta_novedades';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔍 Verificar Usuarios de Gestión Humana</h1>";
    echo "<hr>";
    
    // Verificar usuarios
    $stmt = $pdo->query("SELECT username, nombre, email, rol FROM usuarios WHERE username IN ('johanna', 'ebecerra', 'cortiz', 'cmartinez', 'mvelandia')");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($usuarios)) {
        echo "<h2>✅ Usuarios Encontrados: " . count($usuarios) . "/5</h2>";
        echo "<table border='1' cellpadding='10' style='border-collapse:collapse;width:100%;'>";
        echo "<tr style='background:#3b82f6;color:white;'>";
        echo "<th>Usuario</th><th>Nombre</th><th>Email</th><th>Rol</th>";
        echo "</tr>";
        
        foreach ($usuarios as $user) {
            echo "<tr>";
            echo "<td><strong>{$user['username']}</strong></td>";
            echo "<td>{$user['nombre']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['rol']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<h2>❌ No se encontraron usuarios</h2>";
        echo "<p>Necesitas ejecutar el script de creación.</p>";
    }
    
    echo "<hr>";
    echo "<h2>🔧 Acciones</h2>";
    echo "<p><a href='setup_usuarios_gh.php' style='background:#10b981;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>➕ Crear/Actualizar Usuarios</a></p>";
    echo "<p><a href='public/index.php' style='background:#3b82f6;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;margin-top:10px;'>🔐 Ir al Login</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Error de Conexión</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

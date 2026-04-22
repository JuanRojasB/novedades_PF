<?php
// ejecutar_sql_gestion_humana.php - Script para agregar usuarios de Gestión Humana

define('STORAGE_PATH', __DIR__ . '/storage');
define('CONFIG_PATH', __DIR__ . '/config');
$config = require CONFIG_PATH . '/config.php';

// Conectar a la base de datos
$host = $config['database']['host'];
$dbname = $config['database']['database'];
$username = $config['database']['username'];
$password = $config['database']['password'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Agregar Usuarios de Gestión Humana</h1>";
    echo "<hr>";
    
    // Leer el archivo SQL
    $sql = file_get_contents(__DIR__ . '/database/agregar_usuarios_gestion_humana.sql');
    
    // Separar por punto y coma para ejecutar cada statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $ejecutados = 0;
    $errores = 0;
    
    foreach ($statements as $statement) {
        // Ignorar comentarios y líneas vacías
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $ejecutados++;
            
            // Mostrar qué se ejecutó
            if (stripos($statement, 'INSERT') !== false) {
                preg_match("/VALUES \('([^']+)'/", $statement, $matches);
                if (isset($matches[1])) {
                    echo "<p>✅ Usuario creado: <strong>{$matches[1]}</strong></p>";
                }
            }
        } catch (PDOException $e) {
            // Si el error es "Duplicate entry", el usuario ya existe
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                preg_match("/VALUES \('([^']+)'/", $statement, $matches);
                if (isset($matches[1])) {
                    echo "<p>⚠️ Usuario ya existe: <strong>{$matches[1]}</strong></p>";
                }
            } else {
                echo "<p>❌ Error: " . $e->getMessage() . "</p>";
                $errores++;
            }
        }
    }
    
    echo "<hr>";
    echo "<h2>📊 Resumen</h2>";
    echo "<p>Statements ejecutados: <strong>{$ejecutados}</strong></p>";
    echo "<p>Errores: <strong>{$errores}</strong></p>";
    
    // Verificar que los usuarios existen
    echo "<hr>";
    echo "<h2>👥 Usuarios de Gestión Humana</h2>";
    
    $stmt = $pdo->query("SELECT username, nombre, email, rol FROM usuarios WHERE username IN ('ebecerra', 'cortiz', 'cmartinez', 'mvelandia')");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($usuarios)) {
        echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
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
        
        echo "<hr>";
        echo "<h3>✅ ¡Usuarios creados exitosamente!</h3>";
        echo "<p><strong>Contraseña por defecto:</strong> <code>password</code></p>";
        echo "<p>Los usuarios pueden iniciar sesión con:</p>";
        echo "<ul>";
        echo "<li><strong>ebecerra</strong> / password</li>";
        echo "<li><strong>cortiz</strong> / password</li>";
        echo "<li><strong>cmartinez</strong> / password</li>";
        echo "<li><strong>mvelandia</strong> / password</li>";
        echo "</ul>";
        
        echo "<hr>";
        echo "<p><a href='public/index.php' style='background:#3b82f6;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>🔐 Ir al Login</a></p>";
        echo "<p><a href='test_correo.php' style='background:#10b981;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;margin-left:10px;'>📧 Probar Correos</a></p>";
    } else {
        echo "<p>❌ No se encontraron los usuarios. Revisa los errores arriba.</p>";
    }
    
} catch (PDOException $e) {
    echo "<h2>❌ Error de Conexión</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

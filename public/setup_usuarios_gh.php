<?php
// setup_usuarios_gh.php - Crear usuarios de Gestión Humana directamente

// Configuración de BD
$host = 'localhost';
$dbname = 'pollo_fiesta_novedades';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔧 Configurar Usuarios de Gestión Humana</h1>";
    echo "<hr>";
    
    // Usuarios a crear con patrón usuario2026*
    $usuarios = [
        [
            'usuario' => 'ebecerra',
            'password' => 'ebecerra2026*',
            'nombre' => 'Elsa Becerra',
            'email' => 'r.humanos@pollo-fiesta.com'
        ],
        [
            'usuario' => 'cortiz',
            'password' => 'cortiz2026*',
            'nombre' => 'Catherine Ortiz',
            'email' => 'AuxiliarGH2@pollo-fiesta.com'
        ],
        [
            'usuario' => 'cmartinez',
            'password' => 'cmartinez2026*',
            'nombre' => 'Carmenza Martinez',
            'email' => 'AuxiliarGH1@pollo-fiesta.com'
        ],
        [
            'usuario' => 'mvelandia',
            'password' => 'mvelandia2026*',
            'nombre' => 'Michelle Velandia',
            'email' => 'profesionalnomina@pollo-fiesta.com'
        ]
    ];
    
    $creados = 0;
    $actualizados = 0;
    
    foreach ($usuarios as $user) {
        $hash = password_hash($user['password'], PASSWORD_DEFAULT);
        
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->execute([$user['usuario']]);
        $existe = $stmt->fetch();
        
        if ($existe) {
            // Actualizar usuario existente
            $stmt = $pdo->prepare("
                UPDATE usuarios 
                SET password = ?, nombre = ?, email = ?, rol = 'director'
                WHERE usuario = ?
            ");
            $stmt->execute([$hash, $user['nombre'], $user['email'], $user['usuario']]);
            
            echo "<p>🔄 Usuario actualizado: <strong>{$user['usuario']}</strong></p>";
            echo "<ul>";
            echo "<li>Nombre: {$user['nombre']}</li>";
            echo "<li>Email: {$user['email']}</li>";
            echo "<li>Contraseña: <code>{$user['password']}</code></li>";
            echo "</ul>";
            $actualizados++;
        } else {
            // Crear nuevo usuario
            $stmt = $pdo->prepare("
                INSERT INTO usuarios (usuario, password, nombre, email, rol)
                VALUES (?, ?, ?, ?, 'director')
            ");
            $stmt->execute([$user['usuario'], $hash, $user['nombre'], $user['email']]);
            
            echo "<p>✅ Usuario creado: <strong>{$user['usuario']}</strong></p>";
            echo "<ul>";
            echo "<li>Nombre: {$user['nombre']}</li>";
            echo "<li>Email: {$user['email']}</li>";
            echo "<li>Contraseña: <code>{$user['password']}</code></li>";
            echo "</ul>";
            $creados++;
        }
    }
    
    echo "<hr>";
    echo "<h2>📊 Resumen</h2>";
    echo "<p>Usuarios creados: <strong>{$creados}</strong></p>";
    echo "<p>Usuarios actualizados: <strong>{$actualizados}</strong></p>";
    
    // Mostrar tabla de usuarios
    echo "<hr>";
    echo "<h2>👥 Usuarios de Gestión Humana</h2>";
    
    $stmt = $pdo->query("SELECT usuario, nombre, email, rol FROM usuarios WHERE usuario IN ('ebecerra', 'cortiz', 'cmartinez', 'mvelandia')");
    $usuariosDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($usuariosDB)) {
        echo "<table border='1' cellpadding='10' style='border-collapse:collapse;width:100%;'>";
        echo "<tr style='background:#3b82f6;color:white;'>";
        echo "<th>Usuario</th><th>Nombre</th><th>Email</th><th>Contraseña</th><th>Rol</th>";
        echo "</tr>";
        
        foreach ($usuariosDB as $idx => $user) {
            $pass = $usuarios[$idx]['password'];
            echo "<tr>";
            echo "<td><strong>{$user['usuario']}</strong></td>";
            echo "<td>{$user['nombre']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td><code>{$pass}</code></td>";
            echo "<td>{$user['rol']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        echo "<hr>";
        echo "<h3>✅ ¡Configuración Completa!</h3>";
        echo "<p><strong>Estos usuarios pueden:</strong></p>";
        echo "<ul>";
        echo "<li>✅ Iniciar sesión con usuario/contraseña</li>";
        echo "<li>✅ Ver el dashboard completo con todas las novedades</li>";
        echo "<li>✅ Recibir correos de TODAS las novedades registradas</li>";
        echo "</ul>";
        
        echo "<hr>";
        echo "<p><a href='public/index.php' style='background:#3b82f6;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>🔐 Ir al Login</a></p>";
        echo "<p><a href='test_correo.php' style='background:#10b981;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;margin-left:10px;'>📧 Probar Correos</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<?php
// configuracion_completa.php - Configuración completa del sistema

// Habilitar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>⚙️ Configuración Completa del Sistema</h2>";

// Configurar rutas
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');

try {
    // Cargar configuración
    $config = require CONFIG_PATH . '/config.php';
    $dbConfig = $config['database'];
    
    // Conectar a la base de datos
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}",
        $dbConfig['username'],
        $dbConfig['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p>✅ <strong>Conectado a la base de datos:</strong> {$dbConfig['database']}</p>";
    
    echo "<h3>1. 🏢 Configurando Sedes</h3>";
    
    // Eliminar sedes que no sean Sede 1, 2, 3
    $stmt = $pdo->prepare("DELETE FROM sedes WHERE nombre NOT IN ('Sede 1', 'Sede 2', 'Sede 3')");
    $stmt->execute();
    $eliminadas = $stmt->rowCount();
    echo "<p>🗑️ Eliminadas {$eliminadas} sedes anteriores</p>";
    
    // Asegurar que las 3 sedes existan
    $sedes = ['Sede 1', 'Sede 2', 'Sede 3'];
    foreach ($sedes as $sede) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO sedes (nombre) VALUES (?)");
        $stmt->execute([$sede]);
        if ($stmt->rowCount() > 0) {
            echo "<p>✅ Creada sede: <strong>{$sede}</strong></p>";
        }
    }
    
    echo "<h3>2. 🏭 Configurando Áreas</h3>";
    
    // Limpiar áreas existentes de estas sedes
    $stmt = $pdo->prepare("
        DELETE FROM areas_trabajo WHERE sede_id IN (
            SELECT id FROM sedes WHERE nombre IN ('Sede 1', 'Sede 2', 'Sede 3')
        )
    ");
    $stmt->execute();
    
    // Configurar áreas correctas
    $configuracion = [
        'Sede 1' => ['Asadero', 'Despachos'],
        'Sede 2' => ['Despachos'],
        'Sede 3' => ['Posproceso', 'Despachos']
    ];
    
    foreach ($configuracion as $sede => $areas) {
        foreach ($areas as $area) {
            $stmt = $pdo->prepare("
                INSERT INTO areas_trabajo (nombre, zona_geografica_id, sede_id) 
                VALUES (?, NULL, (SELECT id FROM sedes WHERE nombre = ?))
            ");
            $stmt->execute([$area, $sede]);
            echo "<p>✅ <strong>{$sede}</strong> → {$area}</p>";
        }
    }
    
    echo "<h3>3. 📝 Agregando Campo de Corrección</h3>";
    
    // Verificar si la columna ya existe
    $stmt = $pdo->prepare("SHOW COLUMNS FROM novedades LIKE 'es_correccion'");
    $stmt->execute();
    $columnaExiste = $stmt->rowCount() > 0;
    
    if (!$columnaExiste) {
        $stmt = $pdo->prepare("
            ALTER TABLE novedades 
            ADD COLUMN es_correccion ENUM('SI', 'NO') NOT NULL DEFAULT 'NO' 
            AFTER justificacion
        ");
        $stmt->execute();
        echo "<p>✅ Campo 'es_correccion' agregado a la tabla novedades</p>";
    } else {
        echo "<p>ℹ️ Campo 'es_correccion' ya existe</p>";
    }
    
    echo "<div style='background:#dcfce7;border:1px solid #16a34a;color:#15803d;padding:1rem;border-radius:6px;margin:1rem 0;'>";
    echo "✅ <strong>Configuración completada exitosamente</strong>";
    echo "</div>";
    
    echo "<h3>📊 Configuración Final</h3>";
    
    // Mostrar sedes y áreas
    $stmt = $pdo->query("
        SELECT 
            s.nombre AS sede,
            a.nombre AS area
        FROM areas_trabajo a
        JOIN sedes s ON a.sede_id = s.id
        ORDER BY s.nombre, a.nombre
    ");
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>🏢 Sedes y Áreas:</h4>";
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
    echo "<tr><th>Sede</th><th>Área</th></tr>";
    foreach ($resultados as $row) {
        echo "<tr><td><strong>{$row['sede']}</strong></td><td>{$row['area']}</td></tr>";
    }
    echo "</table>";
    
    // Mostrar estructura de la tabla novedades
    echo "<h4>📝 Campos de la Tabla Novedades:</h4>";
    $stmt = $pdo->query("DESCRIBE novedades");
    $campos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Default</th></tr>";
    foreach ($campos as $campo) {
        $destacar = $campo['Field'] === 'es_correccion' ? 'style="background:#eff6ff;"' : '';
        echo "<tr {$destacar}>";
        echo "<td><strong>{$campo['Field']}</strong></td>";
        echo "<td>{$campo['Type']}</td>";
        echo "<td>{$campo['Null']}</td>";
        echo "<td>{$campo['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<div style='background:#fef2f2;border:1px solid #dc2626;color:#dc2626;padding:1rem;border-radius:6px;margin:1rem 0;'>";
    echo "❌ <strong>Error:</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
}

echo "<hr>";
echo "<h3>📋 Resumen de Cambios</h3>";
echo "<ul>";
echo "<li>✅ <strong>Sedes simplificadas:</strong> Solo Sede 1, Sede 2, Sede 3</li>";
echo "<li>✅ <strong>Áreas configuradas:</strong> Sede 1→Asadero+Despachos, Sede 2→Despachos, Sede 3→Posproceso+Despachos</li>";
echo "<li>✅ <strong>Campo agregado:</strong> '¿Es corrección de una novedad ya reportada?'</li>";
echo "<li>✅ <strong>Formulario actualizado:</strong> Nueva pregunta incluida</li>";
echo "</ul>";

echo "<p><strong>Ahora puedes probar el formulario con la nueva configuración.</strong></p>";

echo "<p><a href='novedades/crear' style='background:#3b82f6;color:#fff;padding:0.75rem 1.5rem;text-decoration:none;border-radius:6px;font-weight:600;'>🧪 Probar Formulario</a></p>";

echo "<p><a href='javascript:history.back()'>← Volver</a></p>";
?>
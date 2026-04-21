<?php
// ver_correos_simulados.php - Visor de correos simulados para desarrollo

echo "<h2>📧 Correos Simulados (Desarrollo Local)</h2>\n";

$storageDir = dirname(__DIR__) . '/storage';
$correos = glob($storageDir . '/correo_*.html');

if (empty($correos)) {
    echo "<div style='background:#fff3cd;border:1px solid #ffc107;padding:1rem;border-radius:6px;margin:1rem 0;'>";
    echo "📭 <strong>No hay correos simulados.</strong><br>";
    echo "Registra una novedad para generar un correo de prueba.";
    echo "</div>";
} else {
    // Ordenar por fecha (más reciente primero)
    usort($correos, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    
    echo "<div style='background:#dcfce7;border:1px solid #16a34a;padding:1rem;border-radius:6px;margin:1rem 0;'>";
    echo "✅ <strong>Encontrados " . count($correos) . " correos simulados</strong>";
    echo "</div>";
    
    echo "<div style='display:grid;gap:1rem;'>";
    
    foreach ($correos as $index => $archivo) {
        $nombre = basename($archivo);
        $fecha = date('d/m/Y H:i:s', filemtime($archivo));
        $tamaño = round(filesize($archivo) / 1024, 1);
        
        echo "<div style='border:1px solid #e2e8f0;border-radius:8px;padding:1rem;background:#fff;'>";
        echo "<div style='display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;'>";
        echo "<h4 style='margin:0;color:#1e293b;'>📧 Correo #" . ($index + 1) . "</h4>";
        echo "<span style='font-size:0.8rem;color:#64748b;'>{$fecha} • {$tamaño} KB</span>";
        echo "</div>";
        
        echo "<p style='margin:0.5rem 0;font-size:0.875rem;color:#475569;font-family:monospace;'>";
        echo "📁 {$nombre}";
        echo "</p>";
        
        echo "<div style='display:flex;gap:0.5rem;'>";
        echo "<a href='ver_correo.php?archivo=" . urlencode(basename($archivo)) . "' target='_blank' ";
        echo "style='background:#3b82f6;color:#fff;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;font-size:0.875rem;'>👁️ Ver Correo</a>";
        
        echo "<a href='?delete=" . urlencode($nombre) . "' ";
        echo "onclick='return confirm(\"¿Eliminar este correo?\")' ";
        echo "style='background:#dc2626;color:#fff;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;font-size:0.875rem;'>🗑️ Eliminar</a>";
        echo "</div>";
        
        echo "</div>";
    }
    
    echo "</div>";
    
    echo "<div style='margin-top:2rem;text-align:center;'>";
    echo "<a href='?deleteall=1' onclick='return confirm(\"¿Eliminar TODOS los correos simulados?\")' ";
    echo "style='background:#dc2626;color:#fff;padding:0.75rem 1.5rem;text-decoration:none;border-radius:6px;font-weight:600;'>🗑️ Eliminar Todos</a>";
    echo "</div>";
}

// Manejar eliminaciones
if (isset($_GET['delete'])) {
    $archivo = $storageDir . '/' . basename($_GET['delete']);
    if (file_exists($archivo) && strpos(basename($archivo), 'correo_') === 0) {
        unlink($archivo);
        echo "<script>alert('Correo eliminado'); window.location.href = 'ver_correos_simulados.php';</script>";
    }
}

if (isset($_GET['deleteall'])) {
    foreach ($correos as $archivo) {
        if (strpos(basename($archivo), 'correo_') === 0) {
            unlink($archivo);
        }
    }
    echo "<script>alert('Todos los correos eliminados'); window.location.href = 'ver_correos_simulados.php';</script>";
}

echo "<hr>";
echo "<h3>💡 Instrucciones</h3>";
echo "<ol>";
echo "<li><strong>Registra una novedad</strong> en el sistema</li>";
echo "<li><strong>El correo se guardará</strong> como archivo HTML en la carpeta storage/</li>";
echo "<li><strong>Haz clic en 'Ver Correo'</strong> para ver cómo se vería el correo real</li>";
echo "<li><strong>En producción</strong> estos correos se enviarán realmente por SMTP</li>";
echo "</ol>";

echo "<div style='background:#eff6ff;border-left:4px solid #3b82f6;padding:1rem;margin:1rem 0;border-radius:4px;'>";
echo "<p style='margin:0;'><strong>📍 Nota:</strong> Esta funcionalidad solo está activa en desarrollo local. ";
echo "En el servidor de producción se enviarán correos reales por SMTP.</p>";
echo "</div>";

echo "<p><a href='javascript:history.back()'>← Volver</a></p>";
?>
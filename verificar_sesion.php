<?php
// verificar_sesion.php - Script para verificar la sesión actual
session_start();

echo "<h1>Verificación de Sesión</h1>";

if (isset($_SESSION['user'])) {
    echo "<h2>Usuario Logueado:</h2>";
    echo "<pre>";
    print_r($_SESSION['user']);
    echo "</pre>";
    
    echo "<h2>Verificación de Johanna:</h2>";
    $nombre = $_SESSION['user']['nombre'];
    $esJohanna = strtolower($nombre) === 'johanna';
    
    echo "<p><strong>Nombre:</strong> {$nombre}</p>";
    echo "<p><strong>Es Johanna:</strong> " . ($esJohanna ? 'SÍ ✅' : 'NO ❌') . "</p>";
    
    if ($esJohanna) {
        echo "<p style='color: green; font-weight: bold;'>✅ Este usuario DEBE ver el menú completo (Ver Novedades, Estadísticas, Administración)</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Este usuario NO debe ver el menú de navegación</p>";
    }
    
    echo "<hr>";
    echo "<h2>Acciones:</h2>";
    echo "<p><a href='public/logout'>Cerrar Sesión</a></p>";
    echo "<p><a href='public/'>Ir al Sistema</a></p>";
    
} else {
    echo "<p style='color: red;'>No hay sesión activa. <a href='public/'>Iniciar Sesión</a></p>";
}
?>

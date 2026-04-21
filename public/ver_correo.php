<?php
// ver_correo.php - Mostrar correo simulado específico

if (!isset($_GET['archivo'])) {
    die('Archivo no especificado');
}

$archivo = basename($_GET['archivo']); // Seguridad: solo el nombre del archivo
$rutaCompleta = dirname(__DIR__) . '/storage/' . $archivo;

// Verificar que el archivo existe y es un correo simulado
if (!file_exists($rutaCompleta) || strpos($archivo, 'correo_') !== 0 || pathinfo($archivo, PATHINFO_EXTENSION) !== 'html') {
    die('Archivo no encontrado o no válido');
}

// Leer y mostrar el contenido
$contenido = file_get_contents($rutaCompleta);
echo $contenido;
?>
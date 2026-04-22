<?php
// test_correo.php - Script de prueba rápida para ver el correo

define('APP_PATH', __DIR__ . '/app');
require_once APP_PATH . '/Helpers/MailHelperLocal.php';

// Datos de prueba de una novedad
$datosPrueba = [
    'id' => 999,
    'nombres_apellidos' => 'JUAN PEREZ PRUEBA',
    'numero_cedula' => '12345678',
    'sede' => 'Sede 1',
    'area_trabajo' => 'Posproceso',
    'fecha_novedad' => date('Y-m-d'),
    'turno' => 'DÍA',
    'novedad' => 'INCAPACIDAD',
    'justificacion' => 'SI',
    'es_correccion' => 'SI',
    'motivo_correccion' => 'Se corrigió la fecha porque estaba mal ingresada inicialmente',
    'observacion_novedad' => 'ENFERMEDAD GENERAL',
    'nota' => 'Adjunto soporte médico',
    'responsable' => 'Fredy Perez'
];

echo "<h1>🧪 Test de Correo - Sistema de Novedades</h1>";
echo "<p>Generando correo de prueba...</p>";

$mailer = new MailHelperLocal();

// Enviar a los 4 de Gestión Humana
$correosGH = [
    'r.humanos@pollo-fiesta.com',
    'AuxiliarGH2@pollo-fiesta.com',
    'AuxiliarGH1@pollo-fiesta.com',
    'profesionalnomina@pollo-fiesta.com'
];

$enviados = 0;
foreach ($correosGH as $correo) {
    if ($mailer->enviarNovedad($datosPrueba, $correo)) {
        echo "<p>✅ Correo simulado enviado a: <strong>{$correo}</strong></p>";
        $enviados++;
    } else {
        echo "<p>❌ Error al enviar correo a: <strong>{$correo}</strong></p>";
    }
}

echo "<hr>";
echo "<h2>📊 Resumen</h2>";
echo "<p>Total de correos enviados: <strong>{$enviados}/4</strong></p>";
echo "<p>Los correos se guardaron en la carpeta <code>storage/</code></p>";

// Listar archivos de correo generados
$archivos = glob(__DIR__ . '/storage/correo_*.html');
if (!empty($archivos)) {
    echo "<h3>📧 Correos Generados:</h3>";
    echo "<ul>";
    foreach ($archivos as $archivo) {
        $nombre = basename($archivo);
        echo "<li><a href='storage/{$nombre}' target='_blank'>{$nombre}</a></li>";
    }
    echo "</ul>";
}

echo "<hr>";
echo "<p><a href='storage/" . basename(end($archivos)) . "' target='_blank' style='background:#3b82f6;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>📧 Ver Último Correo Generado</a></p>";
?>

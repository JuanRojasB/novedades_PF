<?php
/**
 * Test de visualización del correo de novedades
 * Abrir en navegador: http://localhost/informe-novedades/test_correo.php
 */

// Datos de prueba
$novedad = [
    'nombres_apellidos' => 'RODRIGUEZ MOYANO DIANA KATHERINE',
    'numero_cedula' => '1056931222',
    'sede' => 'Producción',
    'area_trabajo' => 'Procesados',
    'fecha_novedad' => '2026-04-20',
    'turno' => 'DÍA',
    'novedad' => 'INCAPACIDAD',
    'justificacion' => 'SI',
    'observacion_novedad' => 'ENFERMEDAD GENERAL',
    'nota' => 'Incapacidad médica por 3 días',
    'responsable' => 'RIOS MUNEVAR JOHANNA ANDREA'
];

$fecha = date('d/m/Y', strtotime($novedad['fecha_novedad']));
$just  = $novedad['justificacion'] === 'SI'
    ? '<span style="color:#15803d;font-weight:600;">SI</span>'
    : '<span style="color:#dc2626;font-weight:600;">NO</span>';

$urlSistema = 'http://localhost/informe-novedades';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa - Correo de Novedad</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .preview-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .preview-header h1 {
            margin: 0 0 10px 0;
            color: #1e293b;
        }
        .preview-header p {
            margin: 0;
            color: #64748b;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="preview-header">
        <h1>📧 Vista Previa del Correo</h1>
        <p>Así es como se verá el correo que recibe Gestión Humana cuando se registra una novedad.</p>
        <p style="margin-top: 10px;"><strong>Destinatario:</strong> innovacion@pollo-fiesta.com</p>
    </div>

    <div class="email-container">
        <!-- INICIO DEL CORREO -->
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:2rem 0;">
            <tr><td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);padding:1.5rem 2rem;">
                            <h1 style="margin:0;color:#ffffff;font-size:1.25rem;">Nueva Novedad Registrada</h1>
                            <p style="margin:0.25rem 0 0;color:#bfdbfe;font-size:0.875rem;">Sistema de Novedades - Pollo Fiesta</p>
                        </td>
                    </tr>

                    <!-- Cuerpo -->
                    <tr>
                        <td style="padding:2rem;">
                            <p style="margin:0 0 1.5rem;color:#475569;font-size:0.95rem;">Se ha registrado una nueva novedad en el sistema:</p>

                            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;font-size:0.875rem;">
                                <tr style="background:#f8fafc;">
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;width:40%;">Empleado</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $novedad['nombres_apellidos']; ?></td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Cédula</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $novedad['numero_cedula']; ?></td>
                                </tr>
                                <tr style="background:#f8fafc;">
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Sede</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $novedad['sede']; ?></td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Área</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $novedad['area_trabajo']; ?></td>
                                </tr>
                                <tr style="background:#f8fafc;">
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Fecha Novedad</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $fecha; ?></td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Turno</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $novedad['turno']; ?></td>
                                </tr>
                                <tr style="background:#f8fafc;">
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Novedad</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $novedad['novedad']; ?></td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Justificación</td>
                                    <td style="border:1px solid #e2e8f0;"><?php echo $just; ?></td>
                                </tr>
                                <tr style="background:#f8fafc;">
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Observación</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $novedad['observacion_novedad']; ?></td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Nota</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $novedad['nota']; ?></td>
                                </tr>
                                <tr style="background:#f8fafc;">
                                    <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Responsable</td>
                                    <td style="border:1px solid #e2e8f0;color:#1e293b;"><?php echo $novedad['responsable']; ?></td>
                                </tr>
                            </table>

                            <!-- Botón de acceso -->
                            <div style="margin:2rem 0 1rem;text-align:center;">
                                <a href="<?php echo $urlSistema; ?>" 
                                   style="display:inline-block;background:#3b82f6;color:#ffffff;padding:0.875rem 2rem;text-decoration:none;border-radius:8px;font-weight:600;font-size:0.95rem;box-shadow:0 4px 12px rgba(59,130,246,0.3);">
                                    Ingresar al Sistema
                                </a>
                            </div>

                            <!-- Credenciales -->
                            <div style="background:#eff6ff;border-left:4px solid #3b82f6;padding:1rem;margin-top:1.5rem;border-radius:4px;">
                                <p style="margin:0 0 0.5rem;color:#1e40af;font-weight:600;font-size:0.875rem;">Acceso al Sistema:</p>
                                <p style="margin:0;color:#475569;font-size:0.85rem;">
                                    <strong>URL:</strong> <a href="<?php echo $urlSistema; ?>" style="color:#3b82f6;text-decoration:none;"><?php echo $urlSistema; ?></a><br>
                                    <strong>Usuario:</strong> Su usuario asignado<br>
                                    <strong>Contraseña:</strong> Su contraseña asignada
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8fafc;padding:1rem 2rem;border-top:1px solid #e2e8f0;text-align:center;">
                            <p style="margin:0;font-size:0.75rem;color:#94a3b8;">
                                Este correo fue generado automáticamente por el Sistema de Novedades de Pollo Fiesta.<br>
                                Por favor no responda a este mensaje.
                            </p>
                        </td>
                    </tr>

                </table>
            </td></tr>
        </table>
        <!-- FIN DEL CORREO -->
    </div>

    <div class="preview-header" style="margin-top: 20px;">
        <h2 style="margin: 0 0 10px 0; color: #1e293b; font-size: 1.1rem;">✅ Características del Correo:</h2>
        <ul style="margin: 0; padding-left: 20px; color: #475569;">
            <li>Diseño profesional y responsive</li>
            <li>Botón de acceso directo al sistema</li>
            <li>Todos los datos de la novedad</li>
            <li>Instrucciones de acceso claras</li>
            <li>Compatible con todos los clientes de correo</li>
        </ul>
    </div>
</body>
</html>

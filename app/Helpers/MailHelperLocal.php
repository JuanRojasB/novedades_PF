<?php
// app/Helpers/MailHelperLocal.php
// Simulador de correos para desarrollo local

class MailHelperLocal {

    /**
     * Simular envío de correo (solo para desarrollo)
     */
    public function enviarNovedad(array $novedad, string $destinatario): bool {
        try {
            $asunto = "Nueva Novedad Registrada - {$novedad['nombres_apellidos']}";
            $cuerpo = $this->plantillaNovedad($novedad);
            
            // Asegurar que el directorio storage existe
            $storageDir = dirname(dirname(__DIR__)) . '/storage';
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0755, true);
            }
            
            // Guardar el correo en un archivo HTML para visualizar
            $filename = $storageDir . '/correo_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.html';
            
            $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>{$asunto}</title></head><body>";
            $html .= "<div style='background:#f0f0f0;padding:20px;'>";
            $html .= "<h2>📧 Correo Simulado (Desarrollo Local)</h2>";
            $html .= "<p><strong>Para:</strong> {$destinatario}</p>";
            $html .= "<p><strong>Asunto:</strong> {$asunto}</p>";
            $html .= "<hr>";
            $html .= $cuerpo;
            $html .= "</div></body></html>";
            
            $result = file_put_contents($filename, $html);
            
            if ($result === false) {
                error_log("❌ ERROR: No se pudo guardar el correo simulado en {$filename}");
                return false;
            }
            
            // Log para desarrollo
            error_log("📧 CORREO SIMULADO: Guardado en {$filename} para {$destinatario}");
            
            return true; // Simular éxito
            
        } catch (\Exception $e) {
            error_log("❌ ERROR en MailHelperLocal: " . $e->getMessage());
            return false;
        }
    }

    private function plantillaNovedad(array $n): string {
        // Usar la misma plantilla que el MailHelper real
        $configPath = dirname(dirname(__DIR__)) . '/config/config.php';
        $config = require $configPath;
        
        // En desarrollo, usar URL de producción para el correo
        $urlSistema = 'https://pollo-fiesta.com'; // URL real de producción
        
        $fecha = date('d/m/Y', strtotime($n['fecha_novedad']));
        $just  = $n['justificacion'] === 'SI'
            ? '<span style="color:#15803d;font-weight:600;">SI</span>'
            : '<span style="color:#dc2626;font-weight:600;">NO</span>';

        // Agregar fila del ID si existe
        $filaId = '';
        if (isset($n['id']) && !empty($n['id'])) {
            $filaId = '<tr style="background:#f8fafc;">
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;width:40%;">ID Novedad</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">#' . htmlspecialchars($n['id']) . '</td>
              </tr>';
        }

        // Agregar fila de motivo de corrección si es corrección
        $filaMotivo = '';
        if (isset($n['es_correccion']) && $n['es_correccion'] === 'SI' && !empty($n['motivo_correccion'])) {
            $filaMotivo = '<tr>
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Motivo de Corrección</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">' . htmlspecialchars($n['motivo_correccion']) . '</td>
              </tr>';
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:sans-serif;">
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
              {$filaId}
              <tr>
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Empleado</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$n['nombres_apellidos']}</td>
              </tr>
              <tr>
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Cédula</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$n['numero_cedula']}</td>
              </tr>
              <tr style="background:#f8fafc;">
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Sede</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$n['sede']}</td>
              </tr>
              <tr>
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Área</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$n['area_trabajo']}</td>
              </tr>
              <tr style="background:#f8fafc;">
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Fecha Novedad</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$fecha}</td>
              </tr>
              <tr>
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Turno</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$n['turno']}</td>
              </tr>
              <tr style="background:#f8fafc;">
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Novedad</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$n['novedad']}</td>
              </tr>
              <tr>
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Justificación</td>
                <td style="border:1px solid #e2e8f0;">{$just}</td>
              </tr>
              {$filaMotivo}
              <tr style="background:#f8fafc;">
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Observación</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$n['observacion_novedad']}</td>
              </tr>
              <tr>
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Nota</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$n['nota']}</td>
              </tr>
              <tr style="background:#f8fafc;">
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;">Responsable</td>
                <td style="border:1px solid #e2e8f0;color:#1e293b;">{$n['responsable']}</td>
              </tr>
            </table>

            <!-- Botón de acceso -->
            <div style="margin:2rem 0 1rem;text-align:center;">
              <a href="{$urlSistema}" 
                 style="display:inline-block;background:#3b82f6;color:#ffffff;padding:0.875rem 2rem;text-decoration:none;border-radius:8px;font-weight:600;font-size:0.95rem;box-shadow:0 4px 12px rgba(59,130,246,0.3);">
                Ingresar al Sistema
              </a>
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
</body>
</html>
HTML;
    }
}
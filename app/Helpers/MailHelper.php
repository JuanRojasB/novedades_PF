<?php
// app/Helpers/MailHelper.php
// Envío de correos via SMTP usando PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {

    private $config;
    private $lastError = '';
    private $mailer;

    public function __construct() {
        $this->config = require CONFIG_PATH . '/config.php';
        $this->config = $this->config['mail'];
        
        // Solo inicializar PHPMailer si el modo es SMTP
        if ($this->config['mode'] === 'smtp') {
            $this->mailer = new PHPMailer(true);
            $this->configurarSMTP();
        }
    }

    /**
     * Configurar PHPMailer con los datos SMTP
     */
    private function configurarSMTP(): void {
        try {
            // Configuración del servidor
            $this->mailer->isSMTP();
            $this->mailer->Host       = $this->config['host'];
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $this->config['username'];
            $this->mailer->Password   = $this->config['password'];
            $this->mailer->Port       = $this->config['port'];
            
            // Configurar HELO/EHLO con el nombre del servidor (FIX para error 550)
            $this->mailer->Hostname   = $this->config['host'];
            
            // Configurar encriptación
            if ($this->config['encryption'] === 'ssl') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($this->config['encryption'] === 'tls') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            // Configuración adicional
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
            
            // Debug (desactivado en producción)
            $this->mailer->SMTPDebug = 0;
            
        } catch (Exception $e) {
            $this->lastError = "Error al configurar SMTP: " . $e->getMessage();
            error_log("MailHelper: " . $this->lastError);
        }
    }

    /**
     * Obtener el último error
     */
    public function getError(): string {
        return $this->lastError;
    }

    /**
     * Enviar correo de nueva novedad registrada
     */
    public function enviarNovedad(array $novedad, string $destinatario): bool {
        // Modo FILE: Guardar como HTML
        if ($this->config['mode'] === 'file') {
            return $this->guardarComoArchivo($novedad, $destinatario);
        }
        
        // Modo SMTP: Enviar por correo
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            $this->mailer->addAddress($destinatario);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = "Nueva Novedad Registrada - {$novedad['nombres_apellidos']}";
            $this->mailer->Body    = $this->plantillaNovedad($novedad);
            
            if ($this->mailer->send()) {
                return true;
            }
            
            $this->lastError = "Error al enviar: " . $this->mailer->ErrorInfo;
            return false;
            
        } catch (Exception $e) {
            $this->lastError = "Excepción al enviar: " . $e->getMessage();
            error_log("MailHelper Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Guardar correo como archivo HTML (modo desarrollo)
     */
    private function guardarComoArchivo(array $novedad, string $destinatario): bool {
        try {
            $html = $this->plantillaNovedad($novedad);
            $fecha = date('Y-m-d_H-i-s');
            $id = uniqid();
            $filename = "correo_{$fecha}_{$id}.html";
            $filepath = STORAGE_PATH . '/' . $filename;
            
            if (file_put_contents($filepath, $html)) {
                error_log("MailHelper: Correo guardado en {$filename}");
                return true;
            }
            
            $this->lastError = "No se pudo guardar el archivo";
            return false;
            
        } catch (Exception $e) {
            $this->lastError = "Error al guardar: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Enviar correo genérico
     */
    public function enviar(string $para, string $asunto, string $cuerpoHtml): bool {
        try {
            // Limpiar destinatarios previos
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Configurar destinatario
            $this->mailer->addAddress($para);
            
            // Configurar asunto y cuerpo
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $asunto;
            $this->mailer->Body    = $cuerpoHtml;
            
            // Enviar
            if ($this->mailer->send()) {
                return true;
            }
            
            $this->lastError = "Error al enviar: " . $this->mailer->ErrorInfo;
            return false;
            
        } catch (Exception $e) {
            $this->lastError = "Excepción al enviar: " . $e->getMessage();
            error_log("MailHelper Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Plantilla HTML del correo de novedad
     */
    private function plantillaNovedad(array $n): string {
        $config = require CONFIG_PATH . '/config.php';
        
        // Usar URL de producción real
        $urlSistema = 'https://pollo-fiesta.com';
        
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

<?php
// app/Helpers/MailHelper.php
// Envío de correos via SMTP sin dependencias externas

class MailHelper {

    private $config;

    public function __construct() {
        $this->config = require CONFIG_PATH . '/config.php';
        $this->config = $this->config['mail'];
    }

    /**
     * Enviar correo de nueva novedad registrada
     */
    public function enviarNovedad(array $novedad, string $destinatario): bool {
        $asunto = "Nueva Novedad Registrada - {$novedad['nombres_apellidos']}";
        $cuerpo  = $this->plantillaNovedad($novedad);
        return $this->enviar($destinatario, $asunto, $cuerpo);
    }

    /**
     * Enviar correo genérico
     */
    public function enviar(string $para, string $asunto, string $cuerpoHtml): bool {
        $host       = $this->config['host'];
        $port       = $this->config['port'];
        $encryption = $this->config['encryption'];
        $user       = $this->config['username'];
        $pass       = $this->config['password'];
        $fromEmail  = $this->config['from_email'];
        $fromName   = $this->config['from_name'];

        try {
            // Conectar al servidor SMTP
            if ($encryption === 'ssl') {
                $socket = fsockopen("ssl://{$host}", $port, $errno, $errstr, 30);
            } else {
                $socket = fsockopen($host, $port, $errno, $errstr, 30);
            }

            if (!$socket) {
                error_log("MailHelper: No se pudo conectar al servidor SMTP: {$errstr} ({$errno})");
                return false;
            }

            // Leer bienvenida
            $this->leer($socket);

            // EHLO
            $this->escribir($socket, "EHLO {$host}");
            $this->leer($socket);

            // STARTTLS si es tls
            if ($encryption === 'tls') {
                $this->escribir($socket, "STARTTLS");
                $this->leer($socket);
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                $this->escribir($socket, "EHLO {$host}");
                $this->leer($socket);
            }

            // Autenticación
            $this->escribir($socket, "AUTH LOGIN");
            $this->leer($socket);
            $this->escribir($socket, base64_encode($user));
            $this->leer($socket);
            $this->escribir($socket, base64_encode($pass));
            $respAuth = $this->leer($socket);

            if (strpos($respAuth, '235') === false) {
                error_log("MailHelper: Autenticación fallida: {$respAuth}");
                fclose($socket);
                return false;
            }

            // MAIL FROM
            $this->escribir($socket, "MAIL FROM:<{$fromEmail}>");
            $this->leer($socket);

            // RCPT TO
            $this->escribir($socket, "RCPT TO:<{$para}>");
            $this->leer($socket);

            // DATA
            $this->escribir($socket, "DATA");
            $this->leer($socket);

            // Cabeceras y cuerpo
            $fecha    = date('r');
            $boundary = md5(uniqid());
            $mensaje  = "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>\r\n";
            $mensaje .= "To: {$para}\r\n";
            $mensaje .= "Subject: =?UTF-8?B?" . base64_encode($asunto) . "?=\r\n";
            $mensaje .= "Date: {$fecha}\r\n";
            $mensaje .= "MIME-Version: 1.0\r\n";
            $mensaje .= "Content-Type: text/html; charset=UTF-8\r\n";
            $mensaje .= "Content-Transfer-Encoding: base64\r\n";
            $mensaje .= "\r\n";
            $mensaje .= chunk_split(base64_encode($cuerpoHtml));
            $mensaje .= "\r\n.\r\n";

            fwrite($socket, $mensaje);
            $respData = $this->leer($socket);

            // QUIT
            $this->escribir($socket, "QUIT");
            fclose($socket);

            if (strpos($respData, '250') !== false) {
                return true;
            }

            error_log("MailHelper: Error al enviar: {$respData}");
            return false;

        } catch (\Exception $e) {
            error_log("MailHelper Exception: " . $e->getMessage());
            return false;
        }
    }

    private function escribir($socket, string $cmd): void {
        fwrite($socket, $cmd . "\r\n");
    }

    private function leer($socket): string {
        $respuesta = '';
        while ($linea = fgets($socket, 515)) {
            $respuesta .= $linea;
            if (substr($linea, 3, 1) === ' ') break;
        }
        return $respuesta;
    }

    /**
     * Plantilla HTML del correo de novedad
     */
    private function plantillaNovedad(array $n): string {
        $fecha = date('d/m/Y', strtotime($n['fecha_novedad']));
        $just  = $n['justificacion'] === 'SI'
            ? '<span style="color:#15803d;font-weight:600;">SI</span>'
            : '<span style="color:#dc2626;font-weight:600;">NO</span>';

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
          <td style="background:#3b82f6;padding:1.5rem 2rem;">
            <h1 style="margin:0;color:#ffffff;font-size:1.25rem;">Sistema de Novedades</h1>
            <p style="margin:0.25rem 0 0;color:#bfdbfe;font-size:0.875rem;">Pollo Fiesta</p>
          </td>
        </tr>

        <!-- Cuerpo -->
        <tr>
          <td style="padding:2rem;">
            <p style="margin:0 0 1.5rem;color:#475569;font-size:0.95rem;">Se ha registrado una nueva novedad en el sistema:</p>

            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;font-size:0.875rem;">
              <tr style="background:#f8fafc;">
                <td style="border:1px solid #e2e8f0;font-weight:600;color:#334155;width:40%;">Empleado</td>
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

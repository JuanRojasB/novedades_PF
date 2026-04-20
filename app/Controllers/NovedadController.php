<?php
// app/Controllers/NovedadController.php

namespace Controllers;

use Core\Controller;
use Models\Novedad;

class NovedadController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $novedadModel = new Novedad();
        $user = $this->getUser();
        $usuarioModel = new \Models\Usuario();
        
        // Obtener filtros de la URL
        $filters = [
            'area_trabajo' => $_GET['area_trabajo'] ?? '',
            'sede' => $_GET['sede'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        // Si es jefe, filtrar solo por sus sedes
        if ($user['rol'] === 'jefe') {
            $sedesAsignadas = $usuarioModel->getSedesAsignadas($user['id']);
            $sedesNombres = array_column($sedesAsignadas, 'nombre');
            $filters['sedes_permitidas'] = $sedesNombres;
        }
        
        $novedades = $novedadModel->getAll($filters);
        $estadisticas = $novedadModel->getEstadisticasPorZona();
        
        // Cargar catálogos para los filtros
        if ($user['rol'] === 'jefe') {
            $sedesDisponibles = $usuarioModel->getSedesAsignadas($user['id']);
        } else {
            $sedeModel = new \Models\Sede();
            $sedesDisponibles = $sedeModel->getAll();
        }
        
        $areaModel = new \Models\AreaTrabajo();
        
        $data = [
            'title' => 'Novedades',
            'user' => $user,
            'novedades' => $novedades,
            'estadisticas' => $estadisticas,
            'filters' => $filters,
            'sedes' => $sedesDisponibles,
            'areas' => $areaModel->getAll()
        ];
        
        $this->view('novedades/index', $data);
    }
    
    public function crear() {
        $this->requireAuth();
        
        $user = $this->getUser();
        $usuarioModel = new \Models\Usuario();
        
        // Si es jefe, solo mostrar sus sedes asignadas
        if ($user['rol'] === 'jefe') {
            $sedesDisponibles = $usuarioModel->getSedesAsignadas($user['id']);
            
            if (empty($sedesDisponibles)) {
                $_SESSION['error'] = 'No tienes sedes asignadas. Contacta al administrador.';
                $this->redirect('novedades');
            }
        } else {
            // Si es director, mostrar todas las sedes
            $sedeModel = new \Models\Sede();
            $sedesDisponibles = $sedeModel->getAll();
        }
        
        // Cargar catálogos desde la base de datos
        $areaModel = new \Models\AreaTrabajo();
        $tipoNovedadModel = new \Models\TipoNovedad();
        
        $data = [
            'title' => 'Nueva Novedad',
            'user' => $user,
            'sedes' => $sedesDisponibles,
            'areas' => $areaModel->getAll(),
            'tipos_novedad' => $tipoNovedadModel->getAll()
        ];
        
        $this->view('novedades/crear', $data);
    }
    
    public function guardar() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('novedades');
        }
        
        $errores = [];
        $datos = [];
        
        // Validar campos requeridos
        $campos_requeridos = [
            'nombres_apellidos', 'numero_cedula', 'sede', 'area_trabajo',
            'fecha_novedad', 'turno', 'novedad', 'justificacion',
            'descontar_dominical', 'observacion_novedad'
        ];
        
        foreach ($campos_requeridos as $campo) {
            if (empty($_POST[$campo])) {
                $errores[] = "El campo '$campo' es obligatorio.";
            } else {
                $datos[$campo] = htmlspecialchars($_POST[$campo]);
            }
        }
        
        // Campo opcional: zona_geografica (solo si la sede tiene zonas)
        if (!empty($_POST['zona_geografica'])) {
            $datos['zona_geografica'] = htmlspecialchars($_POST['zona_geografica']);
        } else {
            $datos['zona_geografica'] = null;
        }
        
        // Campo opcional: nota (el form usa name="observaciones")
        $datos['nota'] = !empty($_POST['observaciones']) ? htmlspecialchars($_POST['observaciones']) : null;
        
        // Agregar el responsable automáticamente (nombre del usuario logueado)
        $user = $this->getUser();
        $datos['responsable'] = $user['nombre'];
        
        // Procesar archivos — guardar en filesystem
        $archivos_temp = [];
        if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
            $max_files    = 3;
            $max_size     = 10 * 1024 * 1024; // 10MB
            $allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'];

            $total_archivos = count($_FILES['archivos']['name']);

            if ($total_archivos > $max_files) {
                $errores[] = "Solo se permiten hasta $max_files archivos.";
            } else {
                for ($i = 0; $i < $total_archivos; $i++) {
                    $nombre_archivo = $_FILES['archivos']['name'][$i];
                    $tamaño_archivo = $_FILES['archivos']['size'][$i];
                    $tmp_archivo    = $_FILES['archivos']['tmp_name'][$i];
                    $tipo_mime      = $_FILES['archivos']['type'][$i];
                    $extension      = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));

                    if (empty($nombre_archivo) || empty($tmp_archivo)) continue;

                    if ($tamaño_archivo > $max_size) {
                        $errores[] = "El archivo '$nombre_archivo' excede el tamaño máximo de 10MB.";
                        continue;
                    }

                    if (!in_array($extension, $allowed_types)) {
                        $errores[] = "El archivo '$nombre_archivo' no es válido. Solo se permiten: PDF, Word, Imágenes.";
                        continue;
                    }

                    $archivos_temp[] = [
                        'nombre'    => $nombre_archivo,
                        'tipo_mime' => $tipo_mime,
                        'tmp_path'  => $tmp_archivo,   // ruta temporal, no contenido
                    ];
                }
            }
        }
        
        if (empty($errores)) {
            $novedadModel = new Novedad();
            $novedad_id = $novedadModel->create($datos);
            
            if ($novedad_id) {
                // Guardar archivos en filesystem
                if (!empty($archivos_temp)) {
                    $archivoModel = new \Models\ArchivoAdjunto();
                    foreach ($archivos_temp as $archivo) {
                        $archivoModel->create(
                            $novedad_id,
                            $archivo['nombre'],
                            $archivo['tipo_mime'],
                            $archivo['tmp_path'],
                            true   // es ruta temporal
                        );
                    }
                }
                
                // Enviar correo de notificación
                try {
                    require_once APP_PATH . '/Helpers/MailHelper.php';
                    $mailer = new \MailHelper();
                    // Correo al área de Gestión Humana
                    $mailer->enviarNovedad($datos, 'innovacion@pollo-fiesta.com');
                } catch (\Exception $e) {
                    error_log("Error enviando correo: " . $e->getMessage());
                    // No interrumpir el flujo si falla el correo
                }
                
                $_SESSION['success'] = 'Novedad registrada exitosamente';
                $this->redirect('novedades');
            } else {
                $errores[] = "Error al guardar la novedad en la base de datos.";
                $_SESSION['errors'] = $errores;
                $this->redirect('novedades/crear');
            }
        } else {
            $_SESSION['errors'] = $errores;
            $this->redirect('novedades/crear');
        }
    }
    
    public function estadisticas() {
        $this->requireAuth();
        $user = $this->getUser();
        
        // Solo director puede ver estadísticas
        if ($user['rol'] !== 'director') {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            $this->redirect('novedades');
        }
        
        $novedadModel = new Novedad();
        
        // Obtener estadísticas generales
        $stats = [
            'total_novedades' => $novedadModel->getTotalNovedades(),
            'por_sede' => $novedadModel->getNovedadesPorSede(),
            'por_tipo' => $novedadModel->getNovedadesPorTipo(),
            'por_justificacion' => $novedadModel->getNovedadesPorJustificacion(),
            'por_area' => $novedadModel->getEstadisticasPorZona(),
            'por_turno' => $novedadModel->getNovedadesPorTurno(),
            'descontar_dominical' => $novedadModel->getNovedadesDescontarDominical(),
            'por_mes' => $novedadModel->getNovedadesPorMes(),
            'top_responsables' => $novedadModel->getTopResponsables()
        ];
        
        $data = [
            'title' => 'Estadísticas y Gráficos',
            'user' => $user,
            'stats' => $stats
        ];
        
        $this->view('novedades/estadisticas', $data);
    }
}

<?php
// app/Controllers/NovedadController.php

namespace Controllers;

use Core\Controller;
use Models\Novedad;

class NovedadController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $user = $this->getUser();
        
        // Solo Johanna puede ver el listado de novedades
        if (strtolower($user['nombre']) !== 'johanna') {
            // Usuarios normales van directo al formulario
            $this->redirect('novedades/crear');
            return;
        }
        
        $novedadModel = new Novedad();
        $usuarioModel = new \Models\Usuario();
        
        // Obtener filtros de la URL
        $filters = [
            'area_trabajo' => $_GET['area_trabajo'] ?? '',
            'sede' => $_GET['sede'] ?? '',
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
        ];
        
        // Johanna ve todas las novedades
        $novedades = $novedadModel->getAll($filters);
        $estadisticas = $novedadModel->getEstadisticasPorZona();
        
        // Cargar catálogos para los filtros
        $sedeModel = new \Models\Sede();
        $sedesDisponibles = $sedeModel->getAll();
        
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
        
        try {
            $user = $this->getUser();
            
            // Inicializar variables
            $sedesDisponibles = [];
            $areasDisponibles = [];
            
            // Si NO es Johanna, restringir a una sola sede y área
            if (strtolower($user['nombre']) !== 'johanna') {
                // Mapeo completo de usuarios a sedes y áreas (usando nombres exactos de la BD)
                $asignaciones = [
                    // GERENTES
                    'hbenito' => ['sede' => 'Administrativo', 'area' => 'Asesores Comerciales S3'],
                    'hfajardo' => ['sede' => 'Administrativo', 'area' => 'Gerencia General'],
                    'agarcia' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'jrestrepo' => ['sede' => 'Administrativo', 'area' => 'Gerencia General'],
                    'mroa' => ['sede' => 'Administrativo', 'area' => 'Publicidad'],
                    'mroa2' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'erodriguez' => ['sede' => 'Administrativo', 'area' => 'Asesores Comerciales S1'],
                    'drodriguez' => ['sede' => 'Administrativo', 'area' => 'Operaciones y Mantenimiento'],
                    'ksanchez' => ['sede' => 'Administrativo', 'area' => 'HSEQ'],
                    'osolano' => ['sede' => 'Administrativo', 'area' => 'Contabilidad'],
                    
                    // DIRECTORES
                    'marias' => ['sede' => 'Puntos de Venta', 'area' => '20 de Julio'],
                    'acardenas' => ['sede' => 'Sede 3', 'area' => 'Posproceso'],
                    'ediaz' => ['sede' => 'Administrativo', 'area' => 'Operaciones y Mantenimiento'],
                    'bferro' => ['sede' => 'Administrativo', 'area' => 'Compras'],
                    'egonzalez' => ['sede' => 'Puntos de Venta', 'area' => 'Abastos'],
                    'jibanez' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'ymora' => ['sede' => 'Yopal', 'area' => 'Yopal PDV'],
                    'lmurillo' => ['sede' => 'Administrativo', 'area' => 'Cartera'],
                    'mnino' => ['sede' => 'Administrativo', 'area' => 'Publicidad'],
                    'jrios' => ['sede' => 'Administrativo', 'area' => 'Gestión Humana'],
                    'rrodriguez' => ['sede' => 'Planta', 'area' => 'Planta de Beneficio'],
                    'krodriguez' => ['sede' => 'Planta', 'area' => 'Planta de Beneficio'],
                    'jsanchez' => ['sede' => 'Administrativo', 'area' => 'Contabilidad'],
                    'gzubieta' => ['sede' => 'Administrativo', 'area' => 'Auditoría'],
                    'msanchez' => ['sede' => 'Administrativo', 'area' => 'Sistemas'],
                    'amartinez' => ['sede' => 'Administrativo', 'area' => 'SAGRILAFT'],
                    
                    // JEFES
                    'yalvarado' => ['sede' => 'Toberin', 'area' => 'Toberin'],
                    'calfonso' => ['sede' => 'Sede 2', 'area' => 'Posproceso'],
                    'langulo' => ['sede' => 'Sede 1', 'area' => 'Posproceso'],
                    'lardila' => ['sede' => 'Yopal', 'area' => 'Yopal Bodega'],
                    'sarevalo' => ['sede' => 'Administrativo', 'area' => 'Operaciones y Mantenimiento'],
                    'wbernate' => ['sede' => 'Sede 2', 'area' => 'Despachos'],
                    'tcabana' => ['sede' => 'Sede 2', 'area' => 'Despachos'],
                    'hcampos' => ['sede' => 'Sede 2', 'area' => 'Despachos'],
                    'jcastro' => ['sede' => 'Sede 1', 'area' => 'Despachos'],
                    'cfontalvo' => ['sede' => 'Sede 1', 'area' => 'Posproceso'],
                    'gmarin' => ['sede' => 'Huevos', 'area' => 'Huevos'],
                    'ymontenegro' => ['sede' => 'Administrativo', 'area' => 'Tesorería'],
                    'aperez' => ['sede' => 'Sede 2', 'area' => 'Posproceso'],
                    'cpuentes' => ['sede' => 'Sede 3', 'area' => 'Despachos'],
                    'jrodriguez2' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'drodriguez2' => ['sede' => 'Granjas', 'area' => 'Procesados'],
                    'eromero' => ['sede' => 'Administrativo', 'area' => 'Gerencia General'],
                    'cruiz' => ['sede' => 'Sede 2', 'area' => 'Despachos'],
                    'jurrego' => ['sede' => 'Sede 3', 'area' => 'Despachos'],
                    
                    // PROFESIONALES
                    'davila' => ['sede' => 'Administrativo', 'area' => 'HSEQ'],
                    'nbernal' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'mgil' => ['sede' => 'Administrativo', 'area' => 'Gestión Humana'],
                    'egomez' => ['sede' => 'Administrativo', 'area' => 'Calidad'],
                    'agonzalez' => ['sede' => 'Administrativo', 'area' => 'Calidad'],
                    'bguerrero' => ['sede' => 'Administrativo', 'area' => 'Calidad'],
                    'aibarra' => ['sede' => 'Administrativo', 'area' => 'Calidad'],
                    'hjimenez' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'njimenez' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'dlinares' => ['sede' => 'Administrativo', 'area' => 'HSEQ'],
                    'mmartinez' => ['sede' => 'Administrativo', 'area' => 'Calidad'],
                    'fmonsalve' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'jortiz' => ['sede' => 'Administrativo', 'area' => 'Calidad'],
                    'jotero' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'jpacheco' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'kparra' => ['sede' => 'Administrativo', 'area' => 'Gestión Humana'],
                    'cpulido' => ['sede' => 'Administrativo', 'area' => 'Calidad'],
                    'nrodriguez' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    'rrodriguez2' => ['sede' => 'Administrativo', 'area' => 'HSEQ'],
                    'jtirado' => ['sede' => 'Administrativo', 'area' => 'Calidad'],
                    'svanegas' => ['sede' => 'Administrativo', 'area' => 'Calidad'],
                    'nvivas' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                    
                    // Usuarios de prueba
                    'jefe_admin' => ['sede' => 'Administrativo', 'area' => 'Gerencia General'],
                    'jefe_yopal' => ['sede' => 'Yopal', 'area' => 'Yopal Bodega'],
                    'jefe_pdv' => ['sede' => 'Puntos de Venta', 'area' => '20 de Julio'],
                    'usuario' => ['sede' => 'Administrativo', 'area' => 'Sistemas'],
                    'admin' => ['sede' => 'Administrativo', 'area' => 'Gerencia General'],
                ];
                
                // Obtener asignación del usuario actual
                $asignacion = $asignaciones[$user['username']] ?? null;
                
                if ($asignacion) {
                    $sedeAsignada = $asignacion['sede'];
                    $areaAsignada = $asignacion['area'];
                    
                    // Crear array con solo su sede y área
                    $sedesDisponibles = [['id' => 0, 'nombre' => $sedeAsignada, 'activo' => 1]];
                    $areasDisponibles = [['id' => 0, 'nombre' => $areaAsignada, 'activo' => 1]];
                } else {
                    // Si no está en el mapeo, dar acceso completo temporalmente
                    $sedeModel = new \Models\Sede();
                    $areaModel = new \Models\AreaTrabajo();
                    $sedesDisponibles = $sedeModel->getAll();
                    $areasDisponibles = $areaModel->getAll();
                }
            } else {
                // Johanna ve todas las sedes y áreas
                $sedeModel = new \Models\Sede();
                $areaModel = new \Models\AreaTrabajo();
                $sedesDisponibles = $sedeModel->getAll();
                $areasDisponibles = $areaModel->getAll();
            }
            
            // Cargar tipos de novedad (todos los usuarios ven todos)
            $tipoNovedadModel = new \Models\TipoNovedad();
            $tipos_novedad = $tipoNovedadModel->getAll();
            
            $data = [
                'title' => 'Nueva Novedad',
                'user' => $user,
                'sedes' => $sedesDisponibles,
                'areas' => $areasDisponibles,
                'tipos_novedad' => $tipos_novedad
            ];
            
            $this->view('novedades/crear', $data);
            
        } catch (\Exception $e) {
            // Capturar cualquier error y mostrarlo
            error_log("Error en NovedadController::crear - " . $e->getMessage());
            die("Error al cargar el formulario: " . $e->getMessage() . "<br><br>Trace: " . $e->getTraceAsString());
        }
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
                
                // Enviar correo de notificación (temporal a innovacion para pruebas)
                $correoEnviado = false;
                try {
                    require_once APP_PATH . '/Helpers/MailHelper.php';
                    $mailer = new \MailHelper();
                    
                    // TEMPORAL: Enviar siempre a innovacion para pruebas
                    $mailer->enviarNovedad($datos, 'innovacion@pollo-fiesta.com');
                    $correoEnviado = true;
                    
                    /* PRODUCCIÓN: Descomentar esto cuando esté listo
                    // Obtener el correo del usuario logueado
                    $usuarioModel = new \Models\Usuario();
                    $usuarioData = $usuarioModel->getByUsername($user['username']);
                    
                    if ($usuarioData && !empty($usuarioData['email'])) {
                        // Enviar al correo del usuario
                        $mailer->enviarNovedad($datos, $usuarioData['email']);
                    } else {
                        // Si no tiene correo, enviar a correo genérico
                        $mailer->enviarNovedad($datos, 'innovacion@pollo-fiesta.com');
                        error_log("Usuario {$user['username']} no tiene correo configurado");
                    }
                    */
                } catch (\Exception $e) {
                    error_log("Error enviando correo: " . $e->getMessage());
                    // No interrumpir el flujo si falla el correo
                }
                
                // Mensaje de éxito (siempre se muestra, aunque falle el correo)
                if ($correoEnviado) {
                    $_SESSION['success'] = '✅ Formulario enviado correctamente. Se ha enviado una notificación por correo.';
                } else {
                    $_SESSION['success'] = '✅ Formulario enviado correctamente. (Nota: El correo no pudo ser enviado, pero la novedad fue registrada)';
                }
                
                // Johanna va al listado, usuarios normales vuelven al formulario
                if (strtolower($user['nombre']) === 'johanna') {
                    $this->redirect('novedades');
                } else {
                    $this->redirect('novedades/crear');
                }
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

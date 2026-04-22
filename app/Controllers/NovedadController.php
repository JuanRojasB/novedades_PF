<?php
// app/Controllers/NovedadController.php

namespace Controllers;

use Core\Controller;
use Models\Novedad;

class NovedadController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $user = $this->getUser();
        
        // Usuarios con acceso al dashboard: jrios (Johanna) + 4 de Gestión Humana
        $usuariosConDashboard = ['jrios', 'ebecerra', 'cortiz', 'cmartinez', 'mvelandia'];
        $tieneAccesoDashboard = false;
        
        foreach ($usuariosConDashboard as $userPermitido) {
            if (stripos($user['nombre'], $userPermitido) !== false || $user['usuario'] === $userPermitido) {
                $tieneAccesoDashboard = true;
                break;
            }
        }
        
        if (!$tieneAccesoDashboard) {
            // Usuarios normales van directo al formulario
            $this->redirect('novedades/crear');
            return;
        }
        
        $novedadModel = new Novedad();
        $usuarioModel = new \Models\Usuario();
        
        // Paginación
        $porPagina = 50; // Mostrar 50 novedades por página
        $paginaActual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $offset = ($paginaActual - 1) * $porPagina;
        
        // Obtener filtros de la URL
        $filters = [
            'area_trabajo' => isset($_GET['area_trabajo']) && is_array($_GET['area_trabajo']) ? $_GET['area_trabajo'] : (isset($_GET['area_trabajo']) ? [$_GET['area_trabajo']] : []),
            'sede' => isset($_GET['sede']) && is_array($_GET['sede']) ? $_GET['sede'] : (isset($_GET['sede']) ? [$_GET['sede']] : []),
            'novedad' => isset($_GET['novedad']) && is_array($_GET['novedad']) ? $_GET['novedad'] : (isset($_GET['novedad']) ? [$_GET['novedad']] : []),
            'justificacion' => isset($_GET['justificacion']) && is_array($_GET['justificacion']) ? $_GET['justificacion'] : (isset($_GET['justificacion']) ? [$_GET['justificacion']] : []),
            'fecha_desde' => $_GET['fecha_desde'] ?? '',
            'fecha_hasta' => $_GET['fecha_hasta'] ?? '',
            'limit' => $porPagina,
            'offset' => $offset
        ];
        
        // jrios (Johanna) ve todas las novedades (con paginación)
        $novedades = $novedadModel->getAll($filters);
        $totalNovedades = $novedadModel->getTotalNovedades($filters); // Total sin paginación
        $totalPaginas = ceil($totalNovedades / $porPagina);
        $estadisticas = $novedadModel->getEstadisticasPorZona();
        
        // Cargar catálogos para los filtros
        $sedeModel = new \Models\Sede();
        $sedesDisponibles = $sedeModel->getAll();
        
        $areaModel = new \Models\AreaTrabajo();
        
        $data = [
            'title' => 'NOVEDADES',
            'user' => $user,
            'novedades' => $novedades,
            'totalNovedades' => $totalNovedades,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'porPagina' => $porPagina,
            'estadisticas' => $estadisticas,
            'filters' => $filters,
            'sedes' => $sedesDisponibles,
            'areas' => $areaModel->getAllUnique() // Usar método que trae áreas únicas
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
            
            // Si NO es jrios (Johanna), restringir según configuración
            if (stripos($user['nombre'], 'johanna') === false && $user['usuario'] !== 'jrios') {
                
                // Jefes con acceso a múltiples sedes/áreas (basado en datos históricos)
                $jefesMultiples = [
                    'fperez' => ['sedes' => ['Sede 1', 'Sede 2', 'Sede 3'], 'areas' => ['Posproceso']],
                    'aforero' => ['sedes' => ['Sede 2', 'Sede 3'], 'areas' => ['Planta de Beneficio']],
                    'eparra' => ['sedes' => ['Sede 1', 'Sede 2', 'Sede 3'], 'areas' => ['Posproceso']],
                    'wortega' => ['sedes' => ['Sede 3'], 'areas' => ['Posproceso', 'Procesados']],
                    'kposada' => ['sedes' => ['Sede 2', 'Sede 3'], 'areas' => ['Posproceso']],
                    'ahortua' => ['sedes' => ['Sede 1', 'Sede 3'], 'areas' => ['Posproceso']],
                    'lsantamaria' => ['sedes' => ['Sede 1', 'Sede 2', 'Sede 3'], 'areas' => ['Posproceso']],
                    'jtegue' => ['sedes' => ['Sede 1', 'Sede 3'], 'areas' => ['Posproceso', 'Procesados']],
                    'wbernate' => ['sedes' => ['Sede 1', 'Sede 2'], 'areas' => ['Procesados']],
                    'ycuaran' => ['sedes' => ['Sede 2'], 'areas' => ['Calidad', 'Limpieza y Desinfección']],
                    'hmontealegre' => ['sedes' => ['Sede 1', 'Sede 3'], 'areas' => ['Posproceso']],
                    'jdiaz' => ['sedes' => ['Sede 2'], 'areas' => ['Posproceso', 'Procesados']]
                ];
                
                // Verificar si el usuario tiene acceso múltiple
                if (isset($jefesMultiples[$user['usuario']])) {
                    $config = $jefesMultiples[$user['usuario']];
                    
                    // Cargar sedes asignadas
                    $sedeModel = new \Models\Sede();
                    $todasLasSedes = $sedeModel->getAll();
                    $sedesDisponibles = array_filter($todasLasSedes, function($sede) use ($config) {
                        return in_array($sede['nombre'], $config['sedes']);
                    });
                    
                    // Cargar áreas asignadas
                    $areaModel = new \Models\AreaTrabajo();
                    $todasLasAreas = $areaModel->getAll();
                    $areasDisponibles = array_filter($todasLasAreas, function($area) use ($config) {
                        return in_array($area['nombre'], $config['areas']);
                    });
                    
                } else {
                    // Mapeo simple de usuarios a una sola sede y área
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
                        
                        // JEFES (con una sola sede/área)
                        'yalvarado' => ['sede' => 'Toberin', 'area' => 'Toberin'],
                        'calfonso' => ['sede' => 'Sede 2', 'area' => 'Posproceso'],
                        'langulo' => ['sede' => 'Sede 1', 'area' => 'Posproceso'],
                        'lardila' => ['sede' => 'Yopal', 'area' => 'Yopal Bodega'],
                        'sarevalo' => ['sede' => 'Administrativo', 'area' => 'Operaciones y Mantenimiento'],
                        'tcabana' => ['sede' => 'Sede 2', 'area' => 'Posproceso'],
                        'hcampos' => ['sede' => 'Sede 2', 'area' => 'Posproceso'],
                        'jcastro' => ['sede' => 'Sede 1', 'area' => 'Posproceso'],
                        'cfontalvo' => ['sede' => 'Sede 1', 'area' => 'Posproceso'],
                        'gmarin' => ['sede' => 'Huevos', 'area' => 'Huevos'],
                        'ymontenegro' => ['sede' => 'Administrativo', 'area' => 'Tesorería'],
                        'aperez' => ['sede' => 'Sede 2', 'area' => 'Posproceso'],
                        'cpuentes' => ['sede' => 'Sede 3', 'area' => 'Posproceso'],
                        'jrodriguez2' => ['sede' => 'Granjas', 'area' => 'Granjas'],
                        'drodriguez2' => ['sede' => 'Granjas', 'area' => 'Procesados'],
                        'eromero' => ['sede' => 'Administrativo', 'area' => 'Gerencia General'],
                        'cruiz' => ['sede' => 'Sede 2', 'area' => 'Posproceso'],
                        'jurrego' => ['sede' => 'Sede 3', 'area' => 'Posproceso'],
                        
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
                    $asignacion = $asignaciones[$user['usuario']] ?? null;
                    
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
                }
            } else {
                // jrios (Johanna) ve todas las sedes y áreas
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
        try {
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
                'es_correccion', 'descontar_dominical', 'observacion_novedad'
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
            
            // Campo condicional: motivo_correccion (requerido si es_correccion = 'SI')
            if (!empty($datos['es_correccion']) && $datos['es_correccion'] === 'SI') {
                if (empty($_POST['motivo_correccion'])) {
                    $errores[] = "El motivo de la corrección es obligatorio cuando se marca como corrección.";
                } else {
                    $datos['motivo_correccion'] = htmlspecialchars($_POST['motivo_correccion']);
                }
            } else {
                $datos['motivo_correccion'] = null;
            }
            
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
                
                // Enviar correos de notificación
                $correosEnviados = 0;
                try {
                    // Agregar el ID a los datos para el correo
                    $datos['id'] = $novedad_id;
                    
                    // Correos que deben recibir TODAS las novedades (5 usuarios)
                    $correosDestino = [
                        //'r.humanos@pollo-fiesta.com',           // ELSA BECERRA
                        //'AuxiliarGH2@pollo-fiesta.com',         // CATHERINE ORTIZ
                        //'AuxiliarGH1@pollo-fiesta.com',         // CARMENZA MARTINEZ
                        //'profesionalnomina@pollo-fiesta.com',   // MICHELLE VELANDIA
                        //'dirgestionhumana@pollo-fiesta.com'     // JOHANNA (jrios)
                        'pasantesistemas1@pollo-fiesta.com'       // ENTORNO DE PRUEBA
                    ];
                    
                    // MODO PRUEBA: Enviar todos los correos a pasantesistemas1@pollo-fiesta.com
                    $modoPrueba = true; // Cambiar a false en producción
                    $correoPrueba = 'pasantesistemas1@pollo-fiesta.com';
                    
                    // DESARROLLO LOCAL: Usar simulador de correo
                    if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
                        require_once APP_PATH . '/Helpers/MailHelperLocal.php';
                        $mailer = new \MailHelperLocal();
                        
                        if ($modoPrueba) {
                            // En modo prueba, enviar solo a pasantesistemas1@pollo-fiesta.com
                            if ($mailer->enviarNovedad($datos, $correoPrueba)) {
                                $correosEnviados++;
                                error_log("📧 DESARROLLO (PRUEBA): Correo simulado enviado a {$correoPrueba}");
                            }
                        } else {
                            // Enviar a cada usuario
                            foreach ($correosDestino as $correo) {
                                if ($mailer->enviarNovedad($datos, $correo)) {
                                    $correosEnviados++;
                                    error_log("📧 DESARROLLO: Correo simulado enviado a {$correo}");
                                }
                            }
                        }
                    } else {
                        // PRODUCCIÓN: Usar correo real
                        require_once APP_PATH . '/Helpers/MailHelper.php';
                        $mailer = new \MailHelper();
                        
                        if ($modoPrueba) {
                            // En modo prueba, enviar solo a pasantesistemas1@pollo-fiesta.com
                            if ($mailer->enviarNovedad($datos, $correoPrueba)) {
                                $correosEnviados++;
                                error_log("✓ PRUEBA: Correo enviado a {$correoPrueba}");
                            } else {
                                error_log("✗ PRUEBA: No se pudo enviar el correo a {$correoPrueba}");
                            }
                        } else {
                            // Enviar a cada usuario (5 correos)
                            foreach ($correosDestino as $correo) {
                                if ($mailer->enviarNovedad($datos, $correo)) {
                                    $correosEnviados++;
                                    error_log("✓ Correo enviado exitosamente a {$correo}");
                                } else {
                                    error_log("✗ No se pudo enviar el correo a {$correo}");
                                }
                            }
                        }
                    }
                    
                } catch (\Exception $e) {
                    error_log("Error enviando correos: " . $e->getMessage());
                    // No interrumpir el flujo si falla el correo
                }
                
                // Mensaje de éxito
                if ($correosEnviados > 0) {
                    $_SESSION['success'] = 'Formulario enviado correctamente.';
                } else {
                    $_SESSION['success'] = 'Formulario enviado correctamente.';
                }
                
                // jrios (Johanna) va al listado, usuarios normales vuelven al formulario
                if (stripos($user['nombre'], 'johanna') !== false || $user['usuario'] === 'jrios') {
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
        
        } catch (\Exception $e) {
            // Capturar cualquier error y mostrarlo para debugging
            error_log("❌ ERROR CRÍTICO en NovedadController::guardar - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            $_SESSION['errors'] = ["Error del sistema: " . $e->getMessage()];
            $this->redirect('novedades/crear');
        }
    }
    
    public function estadisticas() {
        $this->requireAuth();
        $user = $this->getUser();
        
        // Usuarios con acceso a estadísticas: jrios (Johanna) + 4 de Gestión Humana
        $usuariosConDashboard = ['jrios', 'ebecerra', 'cortiz', 'cmartinez', 'mvelandia'];
        $tieneAcceso = false;
        
        foreach ($usuariosConDashboard as $userPermitido) {
            if (stripos($user['nombre'], $userPermitido) !== false || $user['usuario'] === $userPermitido) {
                $tieneAcceso = true;
                break;
            }
        }
        
        if (!$tieneAcceso) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            $this->redirect('novedades/crear');
        }
        
        $novedadModel = new Novedad();
        
        // Obtener filtros individuales para cada gráfico (todos inician en "todos")
        $filtroSede = $_GET['filtro_sede'] ?? 'todos';
        $filtroTipo = $_GET['filtro_tipo'] ?? 'todos';
        $filtroJustificacion = $_GET['filtro_justificacion'] ?? 'todos';
        $filtroArea = $_GET['filtro_area'] ?? 'todos';
        $filtroTurno = $_GET['filtro_turno'] ?? 'todos';
        $filtroDominical = $_GET['filtro_dominical'] ?? 'todos';
        $filtroMensual = $_GET['filtro_mensual'] ?? 'todos';
        
        // Obtener estadísticas con filtros individuales
        $stats = [
            'total_novedades' => $novedadModel->getTotalNovedades(),
            'por_sede' => $novedadModel->getNovedadesPorSede($filtroSede),
            'por_tipo' => $novedadModel->getNovedadesPorTipo($filtroTipo),
            'por_justificacion' => $novedadModel->getNovedadesPorJustificacion($filtroJustificacion),
            'por_area' => $novedadModel->getEstadisticasPorZona($filtroArea),
            'por_turno' => $novedadModel->getNovedadesPorTurno($filtroTurno),
            'descontar_dominical' => $novedadModel->getNovedadesDescontarDominical($filtroDominical),
            'por_mes' => $novedadModel->getNovedadesPorMes($filtroMensual),
            'comparativa' => $novedadModel->getComparativa2025vs2026(),
            'top_responsables' => $novedadModel->getTopResponsables(),
            // DATOS SIN FILTROS PARA CONCLUSIONES
            'conclusiones' => [
                'por_tipo' => $novedadModel->getNovedadesPorTipo('todos'),
                'por_justificacion' => $novedadModel->getNovedadesPorJustificacion('todos'),
                'descontar_dominical' => $novedadModel->getNovedadesDescontarDominical('todos')
            ]
        ];
        
        // Obtener total de empleados con novedades
        $totalEmpleadosConNovedades = $novedadModel->getTotalEmpleadosConNovedades();
        
        $data = [
            'title' => 'Estadísticas y Gráficos',
            'user' => $user,
            'stats' => $stats,
            'totalEmpleadosConNovedades' => $totalEmpleadosConNovedades,
            'filtros' => [
                'sede' => $filtroSede,
                'tipo' => $filtroTipo,
                'justificacion' => $filtroJustificacion,
                'area' => $filtroArea,
                'turno' => $filtroTurno,
                'dominical' => $filtroDominical,
                'mensual' => $filtroMensual
            ]
        ];
        
        $this->view('novedades/estadisticas', $data);
    }
    
    // API para actualizar gráficos sin recargar la página
    public function apiEstadisticas() {
        $this->requireAuth();
        
        header('Content-Type: application/json');
        
        $grafico = $_GET['grafico'] ?? '';
        $filtro = $_GET['filtro'] ?? 'todos';
        
        $novedadModel = new Novedad();
        $data = [];
        
        switch($grafico) {
            case 'sede':
                $data = $novedadModel->getNovedadesPorSede($filtro);
                break;
            case 'tipo':
                $data = $novedadModel->getNovedadesPorTipo($filtro);
                break;
            case 'justificacion':
                $data = $novedadModel->getNovedadesPorJustificacion($filtro);
                break;
            case 'turno':
                $data = $novedadModel->getNovedadesPorTurno($filtro);
                break;
            case 'area':
                $data = $novedadModel->getEstadisticasPorZona($filtro);
                break;
            case 'mensual':
                $data = $novedadModel->getNovedadesPorMes($filtro);
                break;
        }
        
        echo json_encode($data);
        exit;
    }
    
    public function listarUsuarios() {
        $this->requireAuth();
        $user = $this->getUser();
        
        // Forzar UTF-8 en la respuesta
        header('Content-Type: text/html; charset=utf-8');
        mb_internal_encoding('UTF-8');
        
        // Solo usuarios con acceso al dashboard pueden ver estadísticas de empleados
        $usuariosConDashboard = ['jrios', 'ebecerra', 'cortiz', 'cmartinez', 'mvelandia'];
        $tieneAcceso = false;
        
        foreach ($usuariosConDashboard as $userPermitido) {
            if (stripos($user['nombre'], $userPermitido) !== false || $user['usuario'] === $userPermitido) {
                $tieneAcceso = true;
                break;
            }
        }
        
        if (!$tieneAcceso) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            $this->redirect('novedades');
            return;
        }
        
        $novedadModel = new Novedad();
        
        // Obtener estadísticas por empleado
        $estadisticasEmpleados = $novedadModel->getEstadisticasPorEmpleado();
        $totalEmpleados = count($estadisticasEmpleados);
        
        // Obtener estadísticas de tipos de novedad para el gráfico de torta
        $tiposNovedad = $novedadModel->getEstadisticasTiposNovedad();
        
        // Mostrar TODOS los empleados (no solo top 20)
        $todosEmpleados = $estadisticasEmpleados;
        
        $data = [
            'title' => 'Estadísticas por Empleado',
            'user' => $user,
            'empleados' => $todosEmpleados,
            'totalEmpleados' => $totalEmpleados,
            'estadisticasCompletas' => $estadisticasEmpleados,
            'tiposNovedad' => $tiposNovedad
        ];
        
        $this->view('usuarios/lista', $data);
    }
    
    public function apiEmpleadoDetalle() {
        $this->requireAuth();
        
        $cedula = $_GET['cedula'] ?? '';
        
        if (empty($cedula)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Cédula requerida'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $novedadModel = new Novedad();
        
        // Obtener todas las novedades del empleado por cédula
        $novedades = $novedadModel->getNovedadesPorEmpleado('', $cedula);
        
        if (empty($novedades)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'No se encontraron novedades para este empleado'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // Obtener estadísticas de tipos de novedad para este empleado
        $tiposNovedadEmpleado = $novedadModel->getEstadisticasTiposNovedadPorEmpleado($cedula);
        
        // Calcular resumen
        $resumen = [
            'total_novedades' => count($novedades),
            'ausencias' => 0,
            'incapacidades' => 0,
            'vacaciones' => 0,
            'permisos' => 0,
            'justificadas' => 0,
            'no_justificadas' => 0
        ];
        
        foreach ($novedades as $novedad) {
            if ($novedad['novedad'] === 'AUSENCIA') $resumen['ausencias']++;
            if ($novedad['novedad'] === 'INCAPACIDAD') $resumen['incapacidades']++;
            if ($novedad['novedad'] === 'VACACIONES') $resumen['vacaciones']++;
            if (strpos($novedad['novedad'], 'PERMISO') !== false) $resumen['permisos']++;
            if ($novedad['justificacion'] === 'SI') $resumen['justificadas']++;
            else $resumen['no_justificadas']++;
        }
        
        // Tomar información del empleado de la novedad más reciente
        $empleado = [
            'nombres_apellidos' => $novedades[0]['nombres_apellidos'],
            'numero_cedula' => $novedades[0]['numero_cedula'],
            'sede' => $novedades[0]['sede'],
            'area_trabajo' => $novedades[0]['area_trabajo']
        ];
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'empleado' => $empleado,
            'resumen' => $resumen,
            'novedades' => $novedades,
            'tiposNovedad' => $tiposNovedadEmpleado
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public function apiNovedadDetalle() {
        $this->requireAuth();
        
        $id = $_GET['id'] ?? '';
        
        if (empty($id)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'ID de novedad requerido'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $novedadModel = new Novedad();
        $novedad = $novedadModel->getById($id);
        
        if (!$novedad) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Novedad no encontrada'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($novedad, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public function apiNovedadAdjuntos() {
        $this->requireAuth();
        
        $id = $_GET['id'] ?? '';
        
        if (empty($id)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'ID de novedad requerido'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $archivoModel = new \Models\ArchivoAdjunto();
        $adjuntos = $archivoModel->getByNovedadId($id);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['adjuntos' => $adjuntos], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

<?php
// app/Controllers/ApiController.php

namespace Controllers;

use Core\Controller;
use Models\ZonaGeografica;
use Models\AreaTrabajo;

class ApiController extends Controller {
    
    // Obtener zonas por sede
    public function zonas($sedeId) {
        header('Content-Type: application/json');
        
        $zonaModel = new ZonaGeografica();
        $zonas = $zonaModel->getBySede($sedeId);
        
        echo json_encode($zonas);
        exit;
    }
    
    // Obtener áreas por zona
    public function areasPorZona($zonaId) {
        header('Content-Type: application/json');
        
        $areaModel = new AreaTrabajo();
        $areas = $areaModel->getByZona($zonaId);
        
        echo json_encode($areas);
        exit;
    }
    
    // Obtener áreas por sede (cuando no hay zonas)
    public function areasPorSede($sedeId) {
        header('Content-Type: application/json');
        
        $areaModel = new AreaTrabajo();
        $areas = $areaModel->getBySede($sedeId);
        
        echo json_encode($areas);
        exit;
    }
    
    // Servir archivo desde BD (para visualizar en navegador)
    public function servirArchivo($archivo_id) {
        $archivoModel = new \Models\ArchivoAdjunto();
        $archivo = $archivoModel->getById($archivo_id);
        
        if (!$archivo) {
            http_response_code(404);
            echo "Archivo no encontrado";
            exit;
        }
        
        // Limpiar buffer de salida
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Configurar headers (inline para visualizar en navegador)
        header('Content-Type: ' . $archivo['tipo_mime']);
        header('Content-Disposition: inline; filename="' . $archivo['nombre_archivo'] . '"');
        header('Content-Length: ' . $archivo['tamanio']);
        header('Cache-Control: public, max-age=3600');
        
        // Enviar archivo
        echo $archivo['contenido'];
        exit;
    }
    
    // Descargar archivo desde BD
    public function descargarArchivo($archivo_id) {
        $archivoModel = new \Models\ArchivoAdjunto();
        $archivo = $archivoModel->getById($archivo_id);
        
        if (!$archivo) {
            http_response_code(404);
            echo "Archivo no encontrado";
            exit;
        }
        
        // Limpiar buffer de salida
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Configurar headers (attachment para forzar descarga)
        header('Content-Type: ' . $archivo['tipo_mime']);
        header('Content-Disposition: attachment; filename="' . $archivo['nombre_archivo'] . '"');
        header('Content-Length: ' . $archivo['tamanio']);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Expires: 0');
        
        // Enviar archivo
        echo $archivo['contenido'];
        exit;
    }
    
    // Obtener lista de archivos de una novedad
    public function archivosNovedad($novedad_id) {
        header('Content-Type: application/json');
        
        $archivoModel = new \Models\ArchivoAdjunto();
        $archivos = $archivoModel->getByNovedadId($novedad_id);
        
        echo json_encode($archivos);
        exit;
    }
}

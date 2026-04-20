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
    
    // Servir archivo desde disco (para visualizar en navegador)
    public function servirArchivo($archivo_id) {
        $archivoModel = new \Models\ArchivoAdjunto();
        $archivo = $archivoModel->getById($archivo_id);

        if (!$archivo) {
            http_response_code(404);
            echo "Archivo no encontrado";
            exit;
        }

        $ruta = $archivoModel->getRutaFisica($archivo['ruta_archivo']);

        if (!file_exists($ruta)) {
            http_response_code(404);
            echo "Archivo no encontrado en disco";
            exit;
        }

        if (ob_get_level()) ob_end_clean();

        header('Content-Type: ' . $archivo['tipo_mime']);
        header('Content-Disposition: inline; filename="' . $archivo['nombre_archivo'] . '"');
        header('Content-Length: ' . filesize($ruta));
        header('Cache-Control: public, max-age=3600');

        readfile($ruta);
        exit;
    }

    // Descargar archivo desde disco
    public function descargarArchivo($archivo_id) {
        $archivoModel = new \Models\ArchivoAdjunto();
        $archivo = $archivoModel->getById($archivo_id);

        if (!$archivo) {
            http_response_code(404);
            echo "Archivo no encontrado";
            exit;
        }

        $ruta = $archivoModel->getRutaFisica($archivo['ruta_archivo']);

        if (!file_exists($ruta)) {
            http_response_code(404);
            echo "Archivo no encontrado en disco";
            exit;
        }

        if (ob_get_level()) ob_end_clean();

        header('Content-Type: ' . $archivo['tipo_mime']);
        header('Content-Disposition: attachment; filename="' . $archivo['nombre_archivo'] . '"');
        header('Content-Length: ' . filesize($ruta));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Expires: 0');

        readfile($ruta);
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

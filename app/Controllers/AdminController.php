<?php
// app/Controllers/AdminController.php

namespace Controllers;

use Core\Controller;
use Models\Sede;
use Models\AreaTrabajo;
use Models\TipoNovedad;

class AdminController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        // Solo director puede acceder
        $user = $this->getUser();
        if ($user['rol'] !== 'director') {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            $this->redirect('novedades');
        }
        
        $sedeModel = new Sede();
        $areaModel = new AreaTrabajo();
        $tipoNovedadModel = new TipoNovedad();
        
        $data = [
            'title' => 'Administración',
            'user' => $this->getUser(),
            'sedes' => $sedeModel->getAll(false),
            'areas' => $areaModel->getAll(false),
            'tipos_novedad' => $tipoNovedadModel->getAll(false)
        ];
        
        $this->view('admin/index', $data);
    }
    
    // ===== SEDES =====
    public function crearSede() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin');
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        
        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre de la sede es obligatorio';
            $this->redirect('admin');
        }

        $sedeModel = new Sede();
        if ($sedeModel->create($nombre)) {
            $_SESSION['success'] = 'Sede creada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al crear la sede';
        }
        $this->redirect('admin');
    }
    
    public function actualizarSede() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin');
        }
        
        $id = $_POST['id'] ?? 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre de la sede es obligatorio';
            $this->redirect('admin');
        }
        
        $sedeModel = new Sede();
        if ($sedeModel->update($id, $nombre, $activo)) {
            $_SESSION['success'] = 'Sede actualizada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar la sede';
        }
        
        $this->redirect('admin');
    }
    
    public function eliminarSede() {
        $this->requireAuth();
        
        $id = $_POST['id'] ?? 0;
        
        $sedeModel = new Sede();
        if ($sedeModel->delete($id)) {
            $_SESSION['success'] = 'Sede eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la sede';
        }
        
        $this->redirect('admin');
    }
    
    // ===== ÁREAS =====
    public function crearArea() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin');
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        
        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre del área es obligatorio';
            $this->redirect('admin');
        }
        
        $areaModel = new AreaTrabajo();
        if ($areaModel->create($nombre)) {
            $_SESSION['success'] = 'Área creada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al crear el área';
        }
        
        $this->redirect('admin');
    }
    
    public function actualizarArea() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin');
        }
        
        $id = $_POST['id'] ?? 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre del área es obligatorio';
            $this->redirect('admin');
        }
        
        $areaModel = new AreaTrabajo();
        if ($areaModel->update($id, $nombre, $activo)) {
            $_SESSION['success'] = 'Área actualizada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar el área';
        }
        
        $this->redirect('admin');
    }
    
    public function eliminarArea() {
        $this->requireAuth();
        
        $id = $_POST['id'] ?? 0;
        
        $areaModel = new AreaTrabajo();
        if ($areaModel->delete($id)) {
            $_SESSION['success'] = 'Área eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el área';
        }
        
        
        $this->redirect('admin');
    }
    
    // ===== TIPOS DE NOVEDAD =====
    public function crearTipoNovedad() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin');
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        
        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre del tipo de novedad es obligatorio';
            $this->redirect('admin');
        }
        
        $tipoNovedadModel = new TipoNovedad();
        if ($tipoNovedadModel->create($nombre)) {
            $_SESSION['success'] = 'Tipo de novedad creado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al crear el tipo de novedad';
        }
        
        $this->redirect('admin');
    }
    
    public function actualizarTipoNovedad() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin');
        }
        
        $id = $_POST['id'] ?? 0;
        $nombre = trim($_POST['nombre'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre del tipo de novedad es obligatorio';
            $this->redirect('admin');
        }
        
        $tipoNovedadModel = new TipoNovedad();
        if ($tipoNovedadModel->update($id, $nombre, $activo)) {
            $_SESSION['success'] = 'Tipo de novedad actualizado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar el tipo de novedad';
        }
        
        $this->redirect('admin');
    }
    
    public function eliminarTipoNovedad() {
        $this->requireAuth();
        
        $id = $_POST['id'] ?? 0;
        
        $tipoNovedadModel = new TipoNovedad();
        if ($tipoNovedadModel->delete($id)) {
            $_SESSION['success'] = 'Tipo de novedad eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el tipo de novedad';
        }
        
        $this->redirect('admin');
    }
}

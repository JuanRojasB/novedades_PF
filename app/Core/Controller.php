<?php
// app/Core/Controller.php - Controlador base

namespace Core;

class Controller {
    
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = APP_PATH . '/Views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vista no encontrada: $view");
        }
    }
    
    protected function redirect($path) {
        header("Location: " . $this->url($path));
        exit;
    }
    
    protected function url($path = '') {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        return $base . '/' . ltrim($path, '/');
    }
    
    protected function isAuthenticated() {
        return isset($_SESSION['user']);
    }
    
    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            $this->redirect('login');
        }
    }
    
    protected function getUser() {
        return $_SESSION['user'] ?? null;
    }
}

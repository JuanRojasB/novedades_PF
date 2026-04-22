<?php
// app/Controllers/AuthController.php

namespace Controllers;

use Core\Controller;
use Models\Usuario;

class AuthController extends Controller {
    
    public function login() {
        if ($this->isAuthenticated()) {
            $this->redirect('novedades/crear');
        }
        
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $usuarioModel = new Usuario();
            $user = $usuarioModel->getByUsername($username);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'usuario'  => $user['usuario'],
                    'nombre'   => $user['nombre'],
                    'cargo'    => $user['cargo'] ?? '',
                    'rol'      => $user['rol'],
                    'logged_in'=> true
                ];
                
                $this->redirect('novedades/crear');
            } else {
                $error = 'Usuario o contraseña incorrectos';
            }
        }
        
        $data = [
            'title' => 'Iniciar Sesión',
            'error' => $error
        ];
        
        $this->view('auth/login', $data);
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('login');
    }
}

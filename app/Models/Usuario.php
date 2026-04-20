<?php
// app/Models/Usuario.php

namespace Models;

use Core\Database;
use PDO;

class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM usuarios ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getByUsername($usuario) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $usuario]);
        return $stmt->fetch();
    }
    
    public function create($usuario, $password, $nombre, $rol = 'jefe') {
        $stmt = $this->db->prepare("INSERT INTO usuarios (usuario, password, nombre, rol) VALUES (:usuario, :password, :nombre, :rol)");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        return $stmt->execute([
            ':usuario' => $usuario,
            ':password' => $hashedPassword,
            ':nombre' => $nombre,
            ':rol' => $rol
        ]);
    }
    
    public function update($id, $usuario, $nombre, $rol, $password = null) {
        if ($password) {
            $sql = "UPDATE usuarios SET usuario = :usuario, nombre = :nombre, rol = :rol, password = :password WHERE id = :id";
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            return $this->db->prepare($sql)->execute([
                ':id' => $id,
                ':usuario' => $usuario,
                ':nombre' => $nombre,
                ':rol' => $rol,
                ':password' => $hashedPassword
            ]);
        } else {
            $sql = "UPDATE usuarios SET usuario = :usuario, nombre = :nombre, rol = :rol WHERE id = :id";
            return $this->db->prepare($sql)->execute([
                ':id' => $id,
                ':usuario' => $usuario,
                ':nombre' => $nombre,
                ':rol' => $rol
            ]);
        }
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // Obtener sedes asignadas a un usuario
    public function getSedesAsignadas($usuarioId) {
        $sql = "SELECT s.* FROM sedes s
                INNER JOIN usuario_sedes us ON s.id = us.sede_id
                WHERE us.usuario_id = :usuario_id AND s.activo = 1
                ORDER BY s.nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll();
    }
    
    // Asignar sede a usuario
    public function asignarSede($usuarioId, $sedeId) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO usuario_sedes (usuario_id, sede_id) VALUES (:usuario_id, :sede_id)");
        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':sede_id' => $sedeId
        ]);
    }
    
    // Remover sede de usuario
    public function removerSede($usuarioId, $sedeId) {
        $stmt = $this->db->prepare("DELETE FROM usuario_sedes WHERE usuario_id = :usuario_id AND sede_id = :sede_id");
        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':sede_id' => $sedeId
        ]);
    }
    
    // Actualizar todas las sedes de un usuario
    public function actualizarSedes($usuarioId, $sedesIds) {
        // Primero eliminar todas las asignaciones actuales
        $stmt = $this->db->prepare("DELETE FROM usuario_sedes WHERE usuario_id = :usuario_id");
        $stmt->execute([':usuario_id' => $usuarioId]);
        
        // Luego insertar las nuevas
        if (!empty($sedesIds)) {
            $stmt = $this->db->prepare("INSERT INTO usuario_sedes (usuario_id, sede_id) VALUES (:usuario_id, :sede_id)");
            foreach ($sedesIds as $sedeId) {
                $stmt->execute([
                    ':usuario_id' => $usuarioId,
                    ':sede_id' => $sedeId
                ]);
            }
        }
        
        return true;
    }
    
    // Verificar si un usuario tiene acceso a una sede
    public function tieneAccesoSede($usuarioId, $sedeNombre) {
        $sql = "SELECT COUNT(*) as count FROM usuario_sedes us
                INNER JOIN sedes s ON us.sede_id = s.id
                WHERE us.usuario_id = :usuario_id AND s.nombre = :sede_nombre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':sede_nombre' => $sedeNombre
        ]);
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}

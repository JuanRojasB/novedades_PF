<?php
// app/Models/TipoNovedad.php

namespace Models;

use Core\Database;
use PDO;

class TipoNovedad {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($soloActivos = true) {
        $sql = "SELECT * FROM tipos_novedad";
        if ($soloActivos) {
            $sql .= " WHERE activo = 1";
        }
        $sql .= " ORDER BY nombre ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tipos_novedad WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($nombre) {
        $stmt = $this->db->prepare("INSERT INTO tipos_novedad (nombre) VALUES (:nombre)");
        return $stmt->execute([':nombre' => $nombre]);
    }
    
    public function update($id, $nombre, $activo = 1) {
        $stmt = $this->db->prepare("UPDATE tipos_novedad SET nombre = :nombre, activo = :activo WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':activo' => $activo
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tipos_novedad WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

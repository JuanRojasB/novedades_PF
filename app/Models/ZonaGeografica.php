<?php
// app/Models/ZonaGeografica.php

namespace Models;

use Core\Database;
use PDO;

class ZonaGeografica {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($soloActivos = true) {
        $sql = "SELECT zg.*, s.nombre as sede_nombre 
                FROM zonas_geograficas zg
                INNER JOIN sedes s ON zg.sede_id = s.id";
        if ($soloActivos) {
            $sql .= " WHERE zg.activo = 1";
        }
        $sql .= " ORDER BY s.nombre ASC, zg.nombre ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getBySede($sedeId, $soloActivos = true) {
        $sql = "SELECT * FROM zonas_geograficas WHERE sede_id = :sede_id";
        if ($soloActivos) {
            $sql .= " AND activo = 1";
        }
        $sql .= " ORDER BY nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':sede_id' => $sedeId]);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM zonas_geograficas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}

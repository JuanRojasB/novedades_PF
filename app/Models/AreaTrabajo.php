<?php
// app/Models/AreaTrabajo.php

namespace Models;

use Core\Database;
use PDO;

class AreaTrabajo {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($soloActivos = true) {
        $sql = "SELECT at.*, s.nombre as sede_nombre, zg.nombre as zona_nombre 
                FROM areas_trabajo at
                INNER JOIN sedes s ON at.sede_id = s.id
                LEFT JOIN zonas_geograficas zg ON at.zona_geografica_id = zg.id";
        if ($soloActivos) {
            $sql .= " WHERE at.activo = 1";
        }
        $sql .= " ORDER BY s.nombre ASC, zg.nombre ASC, at.nombre ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getBySede($sedeId, $soloActivos = true) {
        $sql = "SELECT at.*, zg.nombre as zona_nombre 
                FROM areas_trabajo at
                LEFT JOIN zonas_geograficas zg ON at.zona_geografica_id = zg.id
                WHERE at.sede_id = :sede_id";
        if ($soloActivos) {
            $sql .= " AND at.activo = 1";
        }
        $sql .= " ORDER BY zg.nombre ASC, at.nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':sede_id' => $sedeId]);
        return $stmt->fetchAll();
    }
    
    public function getByZona($zonaId, $soloActivos = true) {
        $sql = "SELECT * FROM areas_trabajo WHERE zona_geografica_id = :zona_id";
        if ($soloActivos) {
            $sql .= " AND activo = 1";
        }
        $sql .= " ORDER BY nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':zona_id' => $zonaId]);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM areas_trabajo WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($nombre, $sede_id = null, $zona_geografica_id = null) {
        $stmt = $this->db->prepare("INSERT INTO areas_trabajo (nombre, sede_id, zona_geografica_id) VALUES (:nombre, :sede_id, :zona_geografica_id)");
        return $stmt->execute([
            ':nombre'             => $nombre,
            ':sede_id'            => $sede_id,
            ':zona_geografica_id' => $zona_geografica_id
        ]);
    }
    
    public function update($id, $nombre, $activo = 1) {
        $stmt = $this->db->prepare("UPDATE areas_trabajo SET nombre = :nombre, activo = :activo WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':activo' => $activo
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM areas_trabajo WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

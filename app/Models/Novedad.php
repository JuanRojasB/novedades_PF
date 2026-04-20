<?php
// app/Models/Novedad.php

namespace Models;

use Core\Database;
use PDO;

class Novedad {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT n.*, 
                (SELECT COUNT(*) FROM archivos_adjuntos WHERE novedad_id = n.id) as total_archivos
                FROM novedades n WHERE 1=1";
        $params = [];
        
        // Filtro por sedes permitidas (para jefes)
        if (!empty($filters['sedes_permitidas'])) {
            $placeholders = str_repeat('?,', count($filters['sedes_permitidas']) - 1) . '?';
            $sql .= " AND n.sede IN ($placeholders)";
            $params = array_merge($params, $filters['sedes_permitidas']);
        }
        
        // Filtro por zona/área
        if (!empty($filters['area_trabajo'])) {
            $sql .= " AND n.area_trabajo = ?";
            $params[] = $filters['area_trabajo'];
        }
        
        // Filtro por sede
        if (!empty($filters['sede'])) {
            $sql .= " AND n.sede = ?";
            $params[] = $filters['sede'];
        }
        
        // Filtro por fecha
        if (!empty($filters['fecha_desde'])) {
            $sql .= " AND n.fecha_novedad >= ?";
            $params[] = $filters['fecha_desde'];
        }
        
        if (!empty($filters['fecha_hasta'])) {
            $sql .= " AND n.fecha_novedad <= ?";
            $params[] = $filters['fecha_hasta'];
        }
        
        $sql .= " ORDER BY n.fecha_novedad DESC, n.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM novedades WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $sql = "INSERT INTO novedades (
            nombres_apellidos, numero_cedula, sede, zona_geografica, area_trabajo,
            fecha_novedad, turno, novedad, justificacion,
            descontar_dominical, observacion_novedad, nota, responsable
        ) VALUES (
            :nombres_apellidos, :numero_cedula, :sede, :zona_geografica, :area_trabajo,
            :fecha_novedad, :turno, :novedad, :justificacion,
            :descontar_dominical, :observacion_novedad, :nota, :responsable
        )";

        $stmt = $this->db->prepare($sql);
        
        $result = $stmt->execute([
            ':nombres_apellidos'  => $data['nombres_apellidos'],
            ':numero_cedula'      => $data['numero_cedula'],
            ':sede'               => $data['sede'],
            ':zona_geografica'    => $data['zona_geografica'] ?? null,
            ':area_trabajo'       => $data['area_trabajo'],
            ':fecha_novedad'      => $data['fecha_novedad'],
            ':turno'              => $data['turno'],
            ':novedad'            => $data['novedad'],
            ':justificacion'      => $data['justificacion'],
            ':descontar_dominical'=> $data['descontar_dominical'],
            ':observacion_novedad'=> $data['observacion_novedad'],
            ':nota'               => $data['nota'] ?? null,
            ':responsable'        => $data['responsable']
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    public function update($id, $data) {
        $sql = "UPDATE novedades SET
            nombres_apellidos = :nombres_apellidos,
            numero_cedula = :numero_cedula,
            sede = :sede,
            zona_geografica = :zona_geografica,
            area_trabajo = :area_trabajo,
            fecha_novedad = :fecha_novedad,
            turno = :turno,
            novedad = :novedad,
            justificacion = :justificacion,
            descontar_dominical = :descontar_dominical,
            observacion_novedad = :observacion_novedad,
            nota = :nota,
            responsable = :responsable
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':nombres_apellidos' => $data['nombres_apellidos'],
            ':numero_cedula' => $data['numero_cedula'],
            ':sede' => $data['sede'],
            ':zona_geografica' => $data['zona_geografica'] ?? null,
            ':area_trabajo' => $data['area_trabajo'],
            ':fecha_novedad' => $data['fecha_novedad'],
            ':turno' => $data['turno'],
            ':novedad' => $data['novedad'],
            ':justificacion' => $data['justificacion'],
            ':descontar_dominical' => $data['descontar_dominical'],
            ':observacion_novedad' => $data['observacion_novedad'],
            ':nota' => $data['nota'],
            ':responsable' => $data['responsable']
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM novedades WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // Obtener estadísticas por zona
    public function getEstadisticasPorZona() {
        $sql = "SELECT 
            area_trabajo,
            COUNT(*) as total_novedades,
            COUNT(CASE WHEN justificacion = 'SI' THEN 1 END) as justificadas,
            COUNT(CASE WHEN justificacion = 'NO' THEN 1 END) as no_justificadas
        FROM novedades
        GROUP BY area_trabajo
        ORDER BY total_novedades DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener novedades por sede
    public function getNovedadesPorSede() {
        $sql = "SELECT 
            sede,
            COUNT(*) as total
        FROM novedades
        GROUP BY sede
        ORDER BY total DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}

<?php
// app/Models/Novedad.php

namespace Models;

use Core\Database;
use PDO;

class Novedad {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        // Asegurar UTF-8 en las consultas
        $this->db->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT n.*, 
                (SELECT COUNT(*) FROM archivos_adjuntos WHERE novedad_id = n.id) as total_archivos
                FROM novedades n WHERE 1=1";
        $params = [];
        
        // Filtro por responsable (para usuarios que no son Johanna)
        if (!empty($filters['responsable'])) {
            $sql .= " AND n.responsable = ?";
            $params[] = $filters['responsable'];
        }
        
        // Filtro por sedes permitidas (para jefes)
        if (!empty($filters['sedes_permitidas'])) {
            $placeholders = str_repeat('?,', count($filters['sedes_permitidas']) - 1) . '?';
            $sql .= " AND n.sede IN ($placeholders)";
            $params = array_merge($params, $filters['sedes_permitidas']);
        }
        
        // Filtro por zona/área (soporta múltiples valores)
        if (!empty($filters['area_trabajo'])) {
            if (is_array($filters['area_trabajo']) && count($filters['area_trabajo']) > 0) {
                $placeholders = str_repeat('?,', count($filters['area_trabajo']) - 1) . '?';
                $sql .= " AND n.area_trabajo IN ($placeholders)";
                $params = array_merge($params, $filters['area_trabajo']);
            } elseif (!is_array($filters['area_trabajo'])) {
                $sql .= " AND n.area_trabajo = ?";
                $params[] = $filters['area_trabajo'];
            }
        }
        
        // Filtro por sede (soporta múltiples valores)
        if (!empty($filters['sede'])) {
            if (is_array($filters['sede']) && count($filters['sede']) > 0) {
                $placeholders = str_repeat('?,', count($filters['sede']) - 1) . '?';
                $sql .= " AND n.sede IN ($placeholders)";
                $params = array_merge($params, $filters['sede']);
            } elseif (!is_array($filters['sede'])) {
                $sql .= " AND n.sede = ?";
                $params[] = $filters['sede'];
            }
        }
        
        // Filtro por tipo de novedad (soporta múltiples valores)
        if (!empty($filters['novedad'])) {
            if (is_array($filters['novedad']) && count($filters['novedad']) > 0) {
                $placeholders = str_repeat('?,', count($filters['novedad']) - 1) . '?';
                $sql .= " AND n.novedad IN ($placeholders)";
                $params = array_merge($params, $filters['novedad']);
            } elseif (!is_array($filters['novedad'])) {
                $sql .= " AND n.novedad = ?";
                $params[] = $filters['novedad'];
            }
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
        
        // Filtro por justificación (soporta múltiples valores)
        if (!empty($filters['justificacion'])) {
            if (is_array($filters['justificacion']) && count($filters['justificacion']) > 0) {
                $placeholders = str_repeat('?,', count($filters['justificacion']) - 1) . '?';
                $sql .= " AND n.justificacion IN ($placeholders)";
                $params = array_merge($params, $filters['justificacion']);
            } elseif (!is_array($filters['justificacion'])) {
                $sql .= " AND n.justificacion = ?";
                $params[] = $filters['justificacion'];
            }
        }
        
        $sql .= " ORDER BY n.created_at DESC, n.fecha_novedad DESC";
        
        // Paginación
        if (isset($filters['limit'])) {
            $sql .= " LIMIT " . intval($filters['limit']);
            if (isset($filters['offset'])) {
                $sql .= " OFFSET " . intval($filters['offset']);
            }
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    public function getTotalNovedades($filters = []) {
        $sql = "SELECT COUNT(*) as total FROM novedades n WHERE 1=1";
        $params = [];
        
        // Aplicar los mismos filtros que en getAll()
        if (!empty($filters['responsable'])) {
            $sql .= " AND n.responsable = ?";
            $params[] = $filters['responsable'];
        }
        
        if (!empty($filters['sedes_permitidas'])) {
            $placeholders = str_repeat('?,', count($filters['sedes_permitidas']) - 1) . '?';
            $sql .= " AND n.sede IN ($placeholders)";
            $params = array_merge($params, $filters['sedes_permitidas']);
        }
        
        // Filtro por zona/área (soporta múltiples valores)
        if (!empty($filters['area_trabajo'])) {
            if (is_array($filters['area_trabajo']) && count($filters['area_trabajo']) > 0) {
                $placeholders = str_repeat('?,', count($filters['area_trabajo']) - 1) . '?';
                $sql .= " AND n.area_trabajo IN ($placeholders)";
                $params = array_merge($params, $filters['area_trabajo']);
            } elseif (!is_array($filters['area_trabajo'])) {
                $sql .= " AND n.area_trabajo = ?";
                $params[] = $filters['area_trabajo'];
            }
        }
        
        // Filtro por sede (soporta múltiples valores)
        if (!empty($filters['sede'])) {
            if (is_array($filters['sede']) && count($filters['sede']) > 0) {
                $placeholders = str_repeat('?,', count($filters['sede']) - 1) . '?';
                $sql .= " AND n.sede IN ($placeholders)";
                $params = array_merge($params, $filters['sede']);
            } elseif (!is_array($filters['sede'])) {
                $sql .= " AND n.sede = ?";
                $params[] = $filters['sede'];
            }
        }
        
        // Filtro por tipo de novedad (soporta múltiples valores)
        if (!empty($filters['novedad'])) {
            if (is_array($filters['novedad']) && count($filters['novedad']) > 0) {
                $placeholders = str_repeat('?,', count($filters['novedad']) - 1) . '?';
                $sql .= " AND n.novedad IN ($placeholders)";
                $params = array_merge($params, $filters['novedad']);
            } elseif (!is_array($filters['novedad'])) {
                $sql .= " AND n.novedad = ?";
                $params[] = $filters['novedad'];
            }
        }
        
        if (!empty($filters['fecha_desde'])) {
            $sql .= " AND n.fecha_novedad >= ?";
            $params[] = $filters['fecha_desde'];
        }
        
        if (!empty($filters['fecha_hasta'])) {
            $sql .= " AND n.fecha_novedad <= ?";
            $params[] = $filters['fecha_hasta'];
        }
        
        // Filtro por justificación (soporta múltiples valores)
        if (!empty($filters['justificacion'])) {
            if (is_array($filters['justificacion']) && count($filters['justificacion']) > 0) {
                $placeholders = str_repeat('?,', count($filters['justificacion']) - 1) . '?';
                $sql .= " AND n.justificacion IN ($placeholders)";
                $params = array_merge($params, $filters['justificacion']);
            } elseif (!is_array($filters['justificacion'])) {
                $sql .= " AND n.justificacion = ?";
                $params[] = $filters['justificacion'];
            }
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM novedades WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log('getById error: ' . $e->getMessage());
            return null;
        }
    }
    
    public function create($data) {
        $sql = "INSERT INTO novedades (
            nombres_apellidos, numero_cedula, sede, zona_geografica, area_trabajo,
            fecha_novedad, turno, novedad, justificacion, es_correccion, motivo_correccion,
            descontar_dominical, observacion_novedad, nota, responsable
        ) VALUES (
            :nombres_apellidos, :numero_cedula, :sede, :zona_geografica, :area_trabajo,
            :fecha_novedad, :turno, :novedad, :justificacion, :es_correccion, :motivo_correccion,
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
            ':es_correccion'      => $data['es_correccion'],
            ':motivo_correccion'  => $data['motivo_correccion'] ?? null,
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
    
    // Método auxiliar para construir WHERE clause con filtros de fecha
    private function buildDateFilter($filtro = 'todos') {
        switch ($filtro) {
            case 'ultimo_mes':
                return "WHERE fecha_novedad >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            case '3_meses':
                return "WHERE fecha_novedad >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            case '6_meses':
                return "WHERE fecha_novedad >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
            case '2024':
                return "WHERE YEAR(fecha_novedad) = 2024";
            case '2025':
                return "WHERE YEAR(fecha_novedad) = 2025";
            case '2026':
                return "WHERE YEAR(fecha_novedad) = 2026";
            case 'todos':
            default:
                return "";
        }
    }
    
    // Obtener estadísticas por zona con filtro
    public function getEstadisticasPorZona($filtro = 'todos') {
        $whereClause = $this->buildDateFilter($filtro);
        
        $sql = "SELECT 
            area_trabajo,
            COUNT(*) as total_novedades,
            COUNT(CASE WHEN justificacion = 'SI' THEN 1 END) as justificadas,
            COUNT(CASE WHEN justificacion = 'NO' THEN 1 END) as no_justificadas
        FROM novedades
        $whereClause
        GROUP BY area_trabajo
        ORDER BY total_novedades DESC
        LIMIT 15";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener novedades por sede con filtro
    public function getNovedadesPorSede($filtro = 'todos') {
        $whereClause = $this->buildDateFilter($filtro);
        
        $sql = "SELECT 
            sede,
            COUNT(*) as total
        FROM novedades
        $whereClause
        GROUP BY sede
        ORDER BY total DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener novedades por tipo con filtro
    public function getNovedadesPorTipo($filtro = 'todos') {
        $whereClause = $this->buildDateFilter($filtro);
        
        $sql = "SELECT 
            novedad as tipo,
            COUNT(*) as total
        FROM novedades
        $whereClause
        GROUP BY novedad
        ORDER BY total DESC
        LIMIT 10";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener novedades por justificación con filtro
    public function getNovedadesPorJustificacion($filtro = 'todos') {
        $whereClause = $this->buildDateFilter($filtro);
        
        $sql = "SELECT 
            justificacion,
            COUNT(*) as total
        FROM novedades
        $whereClause
        GROUP BY justificacion";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener novedades por turno con filtro
    public function getNovedadesPorTurno($filtro = 'todos') {
        $whereClause = $this->buildDateFilter($filtro);
        
        $sql = "SELECT 
            turno,
            COUNT(*) as total
        FROM novedades
        $whereClause
        GROUP BY turno";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener novedades con descuento dominical con filtro
    public function getNovedadesDescontarDominical($filtro = 'todos') {
        $whereClause = $this->buildDateFilter($filtro);
        
        $sql = "SELECT 
            descontar_dominical,
            COUNT(*) as total
        FROM novedades
        $whereClause
        GROUP BY descontar_dominical";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener novedades por mes con filtro de rango
    public function getNovedadesPorMes($filtro = 'ultimo_mes') {
        $whereClause = "";
        
        switch ($filtro) {
            case 'ultimo_mes':
                $whereClause = "WHERE fecha_novedad >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
                break;
            case '3_meses':
                $whereClause = "WHERE fecha_novedad >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
                break;
            case '2025':
                $whereClause = "WHERE YEAR(fecha_novedad) = 2025";
                break;
            case '2026':
                $whereClause = "WHERE YEAR(fecha_novedad) = 2026";
                break;
            case 'todos':
            default:
                $whereClause = "";
                break;
        }
        
        $sql = "SELECT 
            DATE_FORMAT(fecha_novedad, '%Y-%m') as mes,
            COUNT(*) as total
        FROM novedades
        $whereClause
        GROUP BY mes
        ORDER BY mes DESC
        LIMIT 12";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener comparativa 2025 vs 2026
    public function getComparativa2025vs2026() {
        $sql = "SELECT 
            YEAR(fecha_novedad) as anio,
            MONTH(fecha_novedad) as mes,
            COUNT(*) as total
        FROM novedades
        WHERE YEAR(fecha_novedad) IN (2025, 2026)
        GROUP BY anio, mes
        ORDER BY anio, mes";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Obtener top responsables
    public function getTopResponsables() {
        $sql = "SELECT 
            responsable,
            COUNT(*) as total
        FROM novedades
        GROUP BY responsable
        ORDER BY total DESC
        LIMIT 10";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getEstadisticasPorEmpleado() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    (SELECT nombres_apellidos FROM novedades n2 WHERE n2.numero_cedula = n.numero_cedula ORDER BY n2.created_at DESC LIMIT 1) as nombres_apellidos,
                    numero_cedula,
                    (SELECT sede FROM novedades n3 WHERE n3.numero_cedula = n.numero_cedula ORDER BY n3.created_at DESC LIMIT 1) as sede,
                    (SELECT area_trabajo FROM novedades n4 WHERE n4.numero_cedula = n.numero_cedula ORDER BY n4.created_at DESC LIMIT 1) as area_trabajo,
                    COUNT(*) as total_novedades,
                    SUM(CASE WHEN novedad = 'AUSENCIA' THEN 1 ELSE 0 END) as ausencias,
                    SUM(CASE WHEN novedad = 'INCAPACIDAD' THEN 1 ELSE 0 END) as incapacidades,
                    SUM(CASE WHEN novedad = 'VACACIONES' THEN 1 ELSE 0 END) as vacaciones,
                    SUM(CASE WHEN novedad = 'PERMISO REMUNERADO' THEN 1 ELSE 0 END) as permisos_remunerados,
                    SUM(CASE WHEN novedad = 'PERMISO NO REMUNERADO' THEN 1 ELSE 0 END) as permisos_no_remunerados,
                    SUM(CASE WHEN novedad = 'RENUNCIA' THEN 1 ELSE 0 END) as renuncias,
                    SUM(CASE WHEN justificacion = 'SI' THEN 1 ELSE 0 END) as justificadas,
                    SUM(CASE WHEN justificacion = 'NO' THEN 1 ELSE 0 END) as no_justificadas,
                    SUM(CASE WHEN descontar_dominical = 'SI' THEN 1 ELSE 0 END) as con_descuento_dominical,
                    MIN(fecha_novedad) as primera_novedad,
                    MAX(fecha_novedad) as ultima_novedad,
                    MAX(created_at) as ultimo_registro
                FROM novedades n
                WHERE numero_cedula IS NOT NULL AND numero_cedula != ''
                GROUP BY numero_cedula
                ORDER BY total_novedades DESC, nombres_apellidos ASC
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('getEstadisticasPorEmpleado error: ' . $e->getMessage());
            return [];
        }
    }

    public function getEstadisticasTiposNovedad() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    novedad as tipo,
                    COUNT(*) as total,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM novedades)), 1) as porcentaje
                FROM novedades 
                GROUP BY novedad 
                ORDER BY total DESC
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function getEstadisticasTiposNovedadPorEmpleado($cedula) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    novedad as tipo,
                    COUNT(*) as total,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM novedades WHERE numero_cedula = ?)), 1) as porcentaje
                FROM novedades 
                WHERE numero_cedula = ?
                GROUP BY novedad 
                ORDER BY total DESC
            ");
            $stmt->execute([$cedula, $cedula]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getTotalEmpleadosConNovedades() {
        try {
            $stmt = $this->db->query("
                SELECT COUNT(DISTINCT numero_cedula) as total 
                FROM novedades
            ");
            $result = $stmt->fetch();
            return (int)$result['total'];
        } catch (Exception $e) {
            return 0;
        }
    }
    
    public function getNovedadesPorEmpleado($nombre, $cedula) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    id, fecha_novedad, nombres_apellidos, numero_cedula, sede, zona_geografica,
                    area_trabajo, turno, novedad, justificacion, es_correccion, motivo_correccion,
                    descontar_dominical, observacion_novedad, nota, responsable, created_at, updated_at
                FROM novedades 
                WHERE numero_cedula = ?
                ORDER BY fecha_novedad DESC, created_at DESC
            ");
            $stmt->execute([$cedula]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('getNovedadesPorEmpleado error: ' . $e->getMessage());
            return [];
        }
    }
}

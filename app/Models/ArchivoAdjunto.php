<?php
// app/Models/ArchivoAdjunto.php

namespace Models;

use Core\Database;
use PDO;

class ArchivoAdjunto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Guardar archivo en la base de datos
    public function create($novedad_id, $nombre_archivo, $tipo_mime, $contenido) {
        $sql = "INSERT INTO archivos_adjuntos (novedad_id, nombre_archivo, tipo_mime, tamanio, contenido) 
                VALUES (:novedad_id, :nombre_archivo, :tipo_mime, :tamanio, :contenido)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':novedad_id' => $novedad_id,
            ':nombre_archivo' => $nombre_archivo,
            ':tipo_mime' => $tipo_mime,
            ':tamanio' => strlen($contenido),
            ':contenido' => $contenido
        ]);
    }
    
    // Obtener archivos de una novedad
    public function getByNovedadId($novedad_id) {
        $sql = "SELECT id, nombre_archivo, tipo_mime, tamanio, created_at 
                FROM archivos_adjuntos 
                WHERE novedad_id = :novedad_id 
                ORDER BY created_at ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':novedad_id' => $novedad_id]);
        
        return $stmt->fetchAll();
    }
    
    // Obtener un archivo específico (con contenido)
    public function getById($id) {
        $sql = "SELECT * FROM archivos_adjuntos WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch();
    }
    
    // Eliminar archivo
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM archivos_adjuntos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    // Eliminar todos los archivos de una novedad
    public function deleteByNovedadId($novedad_id) {
        $stmt = $this->db->prepare("DELETE FROM archivos_adjuntos WHERE novedad_id = :novedad_id");
        return $stmt->execute([':novedad_id' => $novedad_id]);
    }
}

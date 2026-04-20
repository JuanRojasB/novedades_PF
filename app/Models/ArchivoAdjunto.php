<?php
// app/Models/ArchivoAdjunto.php

namespace Models;

use Core\Database;
use PDO;

class ArchivoAdjunto {
    private $db;
    private $uploadPath;

    public function __construct() {
        $this->db         = Database::getInstance()->getConnection();
        $this->uploadPath = STORAGE_PATH . '/uploads';

        // Crear carpeta si no existe
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    /**
     * Guarda el archivo en disco y registra la ruta en BD.
     * $contenido puede ser la ruta temporal (tmp_name) o el contenido binario.
     */
    public function create($novedad_id, $nombre_archivo, $tipo_mime, $tmp_path_or_content, $es_ruta_tmp = false) {
        // Generar nombre único para evitar colisiones
        $extension    = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        $nombre_unico = uniqid('arch_', true) . '.' . $extension;
        $ruta_disco   = $this->uploadPath . '/' . $nombre_unico;

        // Mover o escribir el archivo
        if ($es_ruta_tmp) {
            if (!move_uploaded_file($tmp_path_or_content, $ruta_disco)) {
                // Fallback: copy
                if (!copy($tmp_path_or_content, $ruta_disco)) {
                    return false;
                }
            }
            $tamanio = filesize($ruta_disco);
        } else {
            // Contenido binario directo
            if (file_put_contents($ruta_disco, $tmp_path_or_content) === false) {
                return false;
            }
            $tamanio = strlen($tmp_path_or_content);
        }

        $sql = "INSERT INTO archivos_adjuntos 
                    (novedad_id, nombre_archivo, tipo_mime, tamanio, ruta_archivo)
                VALUES 
                    (:novedad_id, :nombre_archivo, :tipo_mime, :tamanio, :ruta_archivo)";

        $stmt = $this->db->prepare($sql);
        $ok   = $stmt->execute([
            ':novedad_id'     => $novedad_id,
            ':nombre_archivo' => $nombre_archivo,
            ':tipo_mime'      => $tipo_mime,
            ':tamanio'        => $tamanio,
            ':ruta_archivo'   => $nombre_unico,   // solo el nombre, no la ruta completa
        ]);

        return $ok ? $this->db->lastInsertId() : false;
    }

    // Obtener archivos de una novedad (sin contenido binario)
    public function getByNovedadId($novedad_id) {
        $sql = "SELECT id, nombre_archivo, tipo_mime, tamanio, ruta_archivo, created_at
                FROM archivos_adjuntos
                WHERE novedad_id = :novedad_id
                ORDER BY created_at ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':novedad_id' => $novedad_id]);
        return $stmt->fetchAll();
    }

    // Obtener un archivo específico
    public function getById($id) {
        $sql = "SELECT * FROM archivos_adjuntos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Obtener ruta física completa
    public function getRutaFisica($ruta_archivo) {
        return $this->uploadPath . '/' . $ruta_archivo;
    }

    // Eliminar archivo (disco + BD)
    public function delete($id) {
        $archivo = $this->getById($id);
        if ($archivo) {
            $ruta = $this->uploadPath . '/' . $archivo['ruta_archivo'];
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }
        $stmt = $this->db->prepare("DELETE FROM archivos_adjuntos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Eliminar todos los archivos de una novedad
    public function deleteByNovedadId($novedad_id) {
        $archivos = $this->getByNovedadId($novedad_id);
        foreach ($archivos as $archivo) {
            $ruta = $this->uploadPath . '/' . $archivo['ruta_archivo'];
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }
        $stmt = $this->db->prepare("DELETE FROM archivos_adjuntos WHERE novedad_id = :novedad_id");
        return $stmt->execute([':novedad_id' => $novedad_id]);
    }
}

<?php
require_once "Database.php";

class Image extends Database {
    public function __construct() {
        parent::__construct();
        
        $this->db->exec('CREATE TABLE IF NOT EXISTS pimage (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pid INTEGER NOT NULL,
            label TEXT NOT NULL,
            path TEXT NOT NULL,
            FOREIGN KEY (pid) REFERENCES project(id) ON DELETE CASCADE
        )');
    }

    public function create(int $pid, string $label, string $path): int {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO pimage (pid, label, path)
                VALUES (:pid, :label, :path);
            ');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            $stmt->bindValue(':label', $label, PDO::PARAM_STR);
            $stmt->bindValue(':path', $path, PDO::PARAM_STR);
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création d'une image : " . $e->getMessage());
        }
    }

    public function delete(int $pid): bool {
        try {
            $stmt = $this->db->prepare('DELETE FROM pimage WHERE pid = :pid');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression des images : " . $e->getMessage());
        }
    }

    public function fetchImage(int $pid): array {
        try {
            $stmt = $this->db->prepare('SELECT * FROM pimage WHERE pid = :pid');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return []; // Échec, tableau vide
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des images : " . $e->getMessage());
        }
    }
}
<?php
require_once "Database.php";

class Link extends Database {
    public function __construct(){
        parent::__construct();
        
        $this->db->exec('CREATE TABLE IF NOT EXISTS plink (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pid INTEGER NOT NULL,
            label TEXT NOT NULL,
            url TEXT NOT NULL,
            FOREIGN KEY (pid) REFERENCES project(id) ON DELETE CASCADE
        )');
    }

    public function create(int $pid, string $label, string $url): int {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO plink (pid, label, url)
                VALUES (:pid, :label, :url);
            ');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            $stmt->bindValue(':label', $label, PDO::PARAM_STR);
            $stmt->bindValue(':url', $url, PDO::PARAM_STR);
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création d'un lien : " . $e->getMessage());
        }
    }

    public function delete(int $pid): bool {
        try {
            $stmt = $this->db->prepare('DELETE FROM plink WHERE pid = :pid');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression des liens : " . $e->getMessage());
        }
    }

    public function fetchLink(int $pid): array {
        try {
            $stmt = $this->db->prepare('SELECT * FROM plink WHERE pid = :pid');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return []; // Échec, tableau vide
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des liens : " . $e->getMessage());
        }
    }
}
<?php
require_once "Project.php";

class Text extends Database {
    public function __construct() {
        parent::__construct();

        $this->db->exec('CREATE TABLE IF NOT EXISTS ptext (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pid INTEGER NOT NULL,
            label TEXT NOT NULL,  
            lang TEXT NOT NULL,
            txt TEXT NOT NULL DEFAULT "NO CONTENT",
            FOREIGN KEY (pid) REFERENCES project(id) ON DELETE CASCADE
        )');
    }

    // Créer un texte pour un projet avec un label
    public function create(int $pid, string $label, string $lang, string $txt): int {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO ptext (pid, label, lang, txt)
                VALUES (:pid, :label, :lang, :txt)
            ');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            $stmt->bindValue(':label', $label, PDO::PARAM_STR);
            $stmt->bindValue(':lang', $lang, PDO::PARAM_STR);
            $stmt->bindValue(':txt', $txt, PDO::PARAM_STR);
            $stmt->execute();
            return $this->db->lastInsertId(); // Retourne l'ID du texte créé
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création d'un texte : " . $e->getMessage());
        }
    }

    // Supprimer un texte de projet par son ID
    public function delete(int $pid): bool {
        try {
            $stmt = $this->db->prepare('DELETE FROM ptext WHERE pid = :pid');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression d'un texte : " . $e->getMessage());
        }
    }

    // Récupérer un texte par projet et langue avec le label
    public function fetchText(int $pid, string $label): string {
        try {
            $lang = $_COOKIE["lang"] ?? "fr" ; // recup la langue du cookie pour eviter un argument

            $stmt = $this->db->prepare('SELECT txt FROM ptext WHERE pid = :pid AND label = :label AND lang = :lang');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            $stmt->bindValue(':label', $label, PDO::PARAM_STR);
            $stmt->bindValue(':lang', $lang, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_COLUMN);
            } else {
                return ""; // Échec, tableau vide
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération d'un texte : " . $e->getMessage());
        }
    }
}
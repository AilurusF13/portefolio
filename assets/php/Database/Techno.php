<?php
require_once "Database.php";

class Techno extends Database {
    public function __construct() {
        parent::__construct();

        // Création de la table des technologies (techno)
        $this->db->exec('CREATE TABLE IF NOT EXISTS techno (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL
        )');

        // Création de la table des relations projet-technologie (ptechno)
        $this->db->exec('CREATE TABLE IF NOT EXISTS ptechno (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pid INTEGER NOT NULL,
            tid INTEGER NOT NULL,
            FOREIGN KEY (pid) REFERENCES project(id) ON DELETE CASCADE,
            FOREIGN KEY (tid) REFERENCES techno(id)
        )');
    }

    // Ajouter une technologie à un projet
    public function create(int $pid, string $name): bool {
        try {
            // Vérifier si la technologie existe déjà dans la table `techno`
            $stmt = $this->db->prepare('SELECT id FROM techno WHERE name = :name');
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $techno = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$techno) {
                // Insérer la nouvelle technologie dans la table `techno`
                $stmt = $this->db->prepare('INSERT INTO techno (name) VALUES (:name)');
                $stmt->bindValue(':name', $name, PDO::PARAM_STR);
                $stmt->execute();
                $technoId = $this->db->lastInsertId();
            } else {
                $technoId = $techno['id'];
            }

            // Ajouter la relation dans la table `ptechno`
            $stmt = $this->db->prepare('INSERT INTO ptechno (pid, tid) VALUES (:pid, :tid)');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            $stmt->bindValue(':tid', $technoId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de la technologie : " . $e->getMessage());
        }
    }

    // Supprimer une technologie associée à un projet
    public function delete(int $pid, string $technoName): bool {
        try {
            // Récupérer l'id de la technologie
            $stmt = $this->db->prepare('SELECT id FROM techno WHERE name = :name');
            $stmt->bindValue(':name', $technoName, PDO::PARAM_STR);
            $stmt->execute();
            $techno = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$techno) {
                return false; // La technologie n'existe pas
            }

            // Supprimer la relation dans la table `ptechno`
            $stmt = $this->db->prepare('DELETE FROM ptechno WHERE pid = :pid AND tid = :tid');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            $stmt->bindValue(':tid', $techno['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression de la technologie : " . $e->getMessage());
        }
    }

    // Récupérer toutes les technologies utilisées par un projet
    public function fetchByProject(int $pid): array {
        try {
            $stmt = $this->db->prepare('
                SELECT t.name
                FROM techno t
                JOIN ptechno pt ON t.id = pt.tid
                WHERE pt.pid = :pid
            ');
            $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return [];
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des technologies pour le projet : " . $e->getMessage());
        }
    }

    // Récupérer tous les ligne techno associés à une technologie
    public function fetchByTechno(string $name): array {
        try {
            $stmt = $this->db->prepare('
                SELECT pt.*
                FROM ptechno pt
                JOIN techno t ON t.id = pt.tid
                WHERE t.name = :name
            ');
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return [];
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des projets pour la technologie : " . $e->getMessage());
        }
    }
}
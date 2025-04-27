<?php

require_once "Database.php" ;

class Project extends Database {
        public function __construct(){
                parent::__construct() ;
                $this->db->exec('CREATE TABLE IF NOT EXISTS project(
                        id INTEGER PRIMARY KEY AUTOINCREMENT
                )');
        }

        public function create(): int {
                $stmt = $this->db->prepare('INSERT INTO project DEFAULT VALUES');
                $stmt->execute();
                return $this->db->lastInsertId(); // retourne l id qu on vient de mettre
        }

        public function delete(int $id):bool {
                $stmt = $this->db->prepare('DELETE FROM project WHERE id = :id') ;
                $stmt->bindValue(':id', $id, PDO::PARAM_INT) ;
                return $stmt->execute() ;
        }

        public function listProject(): array {
                $stmt = $this->db->prepare("SELECT * FROM project");
                if ($stmt->execute()) {
                    return $stmt->fetchAll(PDO::FETCH_COLUMN);
                }
                return [];  // Retourne un tableau vide en cas d'échec
        }
}
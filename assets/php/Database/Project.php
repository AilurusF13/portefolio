<?php

use PhpParser\Node\Stmt;

require_once "Database.php" ;

class Project extends Database {
        public function __construct(){
                parent::__construct() ;
                $this->db->exec('CREATE TABLE IF NOT EXISTS project(
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        label TEXT UNIQUE
                )');
        }

        /*
                ceci a été changé apres tous les test, la creation d un lable aléatoire est une étape importante pour ne pas réécrire tous les testes
        */

        public function create(?string $label = null): int {
                // Vérifiez si un label est fourni, sinon générez un label aléatoire
                if (empty($label)) {
                    $label = "project_" . bin2hex(random_bytes(4)); // Génère un label aléatoire sécurisé
                }
            
                // Vérifiez si le label existe déjà
                $stmt = $this->db->prepare('SELECT COUNT(*) FROM project WHERE label = :label');
                $stmt->bindValue(':label', $label, PDO::PARAM_STR);
                $stmt->execute();
            
                if ($stmt->fetchColumn() > 0) {
                    // Le projet avec ce label existe déjà
                    return 0;
                }
            
                // Insérez le nouveau projet avec le label
                $stmt = $this->db->prepare('INSERT INTO project (label) VALUES (:label)');
                $stmt->bindValue(':label', $label, PDO::PARAM_STR);
                $stmt->execute();
            
                return $this->db->lastInsertId(); // Retourne l'ID du projet créé
            }

        public function delete(int $id):bool {
                $stmt = $this->db->prepare('DELETE FROM project WHERE id = :id') ;
                $stmt->bindValue(':id', $id, PDO::PARAM_INT) ;
                return $stmt->execute() ;
        }

        public function listProject(): array {
                $stmt = $this->db->prepare("SELECT * FROM project");
                if ($stmt->execute()) {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                return [];  // Retourne un tableau vide en cas d'échec
        }

        public function getLabel(int $id): string {
                $stmt = $this->db->prepare('SELECT label FROM project WHERE id = :id') ;
                $stmt->bindValue(':id',$id, PDO::PARAM_INT) ;
                if ($stmt->execute()){
                        return $stmt->fetch(PDO::FETCH_COLUMN) ;
                }
                return "" ;
        }
}
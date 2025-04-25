<?php

require_once "Database.php" ;

class Project extends Database {
        public function __construct(){
                parent::__construct() ;
                $this->db->exec('CREATE TABLE IF NOT EXISTS projet(
                        id INTEGER PRIMARY KEY AUTOINCREMENT
                )');
        }

        public function create(): int {
                $stmt = $this->db->prepare('INSERT INTO projet DEFAULT VALUES');
                $stmt->execute();
                return $this->db->lastInsertId(); // retourne l id qu on vient de mettre
        }

        public function delete(int $id):bool {
                $stmt = $this->db->prepare('DELETE FROM projet WHERE id = :id') ;
                $stmt->bindValue(':id', $id, PDO::PARAM_INT) ;
                return $stmt->execute() ;
        }

        public function listerProjets(): array {
                $stmt = $this->db->prepare("SELECT * FROM projet");
                if ($stmt->execute()) {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                return [];  // Retourne un tableau vide en cas d'échec
            }
            
        
}


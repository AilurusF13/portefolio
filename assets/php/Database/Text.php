<?php
require_once "Project.php" ;

class Text extends Database {
        public function __construct() {
        parent::__construct();

        $this->db->exec('CREATE TABLE IF NOT EXISTS project_text (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                projet_id INTEGER NOT NULL,
                lang TEXT NOT NULL,
                txt TEXT NOT NULL DEFAULT "NO CONTENT",
                FOREIGN KEY (projet_id) REFERENCES projet(id) ON DELETE CASCADE
        )');
        }

        // Créer un txt pour un projet
        public function create(int $id, string $lang, string $txt): int {
                try {
                        $stmt = $this->db->prepare('
                                INSERT INTO project_text (projet_id, lang, txt)
                                VALUES (:projet_id, :lang, :txt)
                        ');
                        $stmt->bindValue(':projet_id', $id, PDO::PARAM_INT);
                        $stmt->bindValue(':lang', $lang, PDO::PARAM_STR);
                        $stmt->bindValue(':txt', $txt, PDO::PARAM_STR);
                        $stmt->execute();
                        return $this->db->lastInsertId(); // Retourne l'ID du txt créé
                }catch(PDOException $e){
                        echo $e->getMessage();
                        return 0 ; // id invalide            
                }       
        }

        // Supprimer un txt de projet par son ID
        public function delete(int $id): bool {
                $stmt = $this->db->prepare('DELETE FROM project_text WHERE id = :id');
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();
        }

        // Lister les txts associés à un projet
        public function listerTextes(int $projet_id): array {
                $stmt = $this->db->prepare("SELECT * FROM project_text WHERE projet_id = :projet_id");
                $stmt->bindValue(':projet_id', $projet_id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function fetchText(int $id, string $lang): array{
                try{
                        $stmt = $this->db->prepare('SELECT * FROM project_text WHERE project_id = :id AND lang = :lang') ;
                        $stmt->bindValue(':id', $id, PDO::PARAM_INT) ;
                        $stmt->bindValue(':lang', $lang, PDO::PARAM_STR) ;
                        if ($stmt->execute()){
                                return $stmt->fetchAll(PDO::FETCH_ASSOC) ;
                        } else {
                                return [] ; // ehec, array vide
                        }
                }catch(PDOException $e){
                        echo $e->getMessage() ;
                        return [] ; 
                }
        }
}
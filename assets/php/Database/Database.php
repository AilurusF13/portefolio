<?php

class Database
{
    protected PDO $db;
    public function __construct()
    {
        try { 
            
            // Use DB_PATH from environment
            $chemin = getenv('DB_PATH');

            $this->db = new PDO('sqlite:' . $chemin);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Activer les contraintes de clé étrangère
            $this->db->exec("PRAGMA foreign_keys = ON");
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }   
    }
}
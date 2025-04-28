<?php

class Database
{
    protected PDO $db;
    public function __construct()
    {
        try {
            
            // // bdd de prod
            // assets/php/Database/db.sqlite
            // bdd de test
            // /assets/php/Database/Tests/test_db.sqlite 
            $chemin = $_SERVER['DOCUMENT_ROOT'] . '/assets/php/Database/db.sqlite';
            if (file_exists($chemin)) {
            } else {
                echo "Erreur : Le fichier n'existe pas à l'emplacement spécifié : $chemin";
            }

            $this->db = new PDO('sqlite:' . $chemin); // a changer en fonction des tests
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Activer les contraintes de clé étrangère
            $this->db->exec("PRAGMA foreign_keys = ON");
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }   
    }
}
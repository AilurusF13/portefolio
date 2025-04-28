<?php

require_once "Database/Project.php";
require_once "Database/Text.php";
require_once "Database/Link.php";
require_once "Database/Techno.php";
class DatabaseHandler {
    private static ?DatabaseHandler $instance = null; // L'instance unique
    public Project $project;
    public Text $text;
    public Link $link;
    public Techno $techno;

    private function __construct() {
        $this->project = new Project();
        $this->text = new Text();
        $this->link = new Link();
        $this->techno = new Techno();
    }

    // Méthode pour obtenir l'instance unique
    public static function getInstance(): DatabaseHandler {
        if (self::$instance === null) {
            self::$instance = new DatabaseHandler();
        }
        return self::$instance;
    }

    // Empêcher le clonage ou l'instanciation directe
    private function __clone() {}
    private function __wakeup() {}

    // Exemple de méthode pour insérer un projet
    public function fillDatabase(array $projets): void {
        foreach ($projets as $projet) {
            $projectId = $this->project->create($projet["label"]);

            if ($projectId === 0) {
                continue ;
            }

            // Insérer les textes associés
            foreach ($projet["langText"] as $lang => $textData) {
                foreach ($textData as $label => $content) {
                    $result = $this->text->create($projectId, $label, $lang, $content);
                    if ($result === 0) {
                        throw new Exception("Impossible d'ajouter le texte '$label' pour la langue '$lang'.");
                    }
                }
            }

            // Insérer les technologies
            foreach ($projet["technologies"] as $tech) {
                $result = $this->techno->create($projectId, $tech);
                if (!$result) {
                    throw new Exception("Impossible d'ajouter la technologie '$tech'.");
                }
            }

            // Insérer les liens
            foreach ($projet["link"] as $link) {
                $linkId = $this->link->create($projectId, $link["label"], $link["url"]);
                if ($linkId === 0) {
                    throw new Exception("Impossible d'ajouter le lien '{$link['label']}'.");
                }

                foreach ($link["langText"] as $lang => $labelContent) {
                    $result = $this->text->create($projectId, $link["label"], $lang, $labelContent);
                    if ($result === 0) {
                        throw new Exception("Impossible d'ajouter le texte pour le lien '{$link['label']}' dans la langue '$lang'.");
                    }
                }
            }

            // Insérer les images
            foreach ($projet["image"] as $image) {
                $imageId = $this->link->create($projectId, $image["label"], $image["path"]);
                if ($imageId === 0) {
                    throw new Exception("Impossible d'ajouter l'image avec le label '{$image['label']}'.");
                }

                foreach ($image["langText"] as $lang => $labelContent) {
                    $result = $this->text->create($projectId, $image["label"], $lang, $labelContent);
                    if ($result === 0) {
                        throw new Exception("Impossible d'ajouter le texte pour l'image avec le label '{$image['label']}' dans la langue '$lang'.");
                    }
                }
            }
        }
    }
}
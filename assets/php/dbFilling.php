<?php

require_once "Database/Project.php";
require_once "Database/Text.php";
require_once "Database/Link.php";
require_once "Database/Techno.php";

// Charger les données depuis le fichier tabProjets.php
require_once "tabProjets.php";

// Créer des instances des classes
$projectHandler = new Project();
$textHandler = new Text();
$technoHandler = new Techno();
$linkHandler = new Link();

try {
    foreach ($projets as $projet) {
        // Étape 1 : Créer le projet
        $projectId = $projectHandler->create($projet["label"]);

        if ($projectId === 0) {
            throw new Exception("Le projet avec le label '{$projet['label']}' existe déjà ou n'a pas pu être créé.");
        }

        echo "Projet créé avec succès, ID : $projectId.\n";

        // Étape 2 : Insérer les textes (nom, résumé, détails)
        foreach ($projet["langText"] as $lang => $textData) {
            foreach ($textData as $label => $content) {
                $result = $textHandler->create($projectId, $label, $lang, $content);

                if ($result === 0) {
                    throw new Exception("Impossible d'ajouter le texte '$label' pour la langue '$lang'.");
                }

                echo "Texte '$label' ajouté pour la langue '$lang'.\n";
            }
        }

        // Étape 3 : Insérer les technologies
        foreach ($projet["technologies"] as $tech) {
            $result = $technoHandler->create($projectId, $tech);

            if (!$result) {
                throw new Exception("Impossible d'ajouter la technologie '$tech'.");
            }

            echo "Technologie '$tech' ajoutée.\n";
        }

        // Étape 4 : Insérer les liens et leurs labels multilingues
        foreach ($projet["link"] as $link) {
            $linkId = $linkHandler->create($projectId, $link["label"], $link["url"]);

            if ($linkId === 0) {
                throw new Exception("Impossible d'ajouter le lien '{$link['label']}'.");
            }

            echo "Lien '{$link['label']}' ajouté avec succès.\n";

            // Ajouter les labels multilingues dans le tableau Text
            foreach ($link["langText"] as $lang => $labelContent) {
                $result = $textHandler->create($projectId, $link["label"], $lang, $labelContent);

                if ($result === 0) {
                    throw new Exception("Impossible d'ajouter le texte pour le lien '{$link['label']}' dans la langue '$lang'.");
                }

                echo "Texte pour le lien '{$link['label']}' ajouté dans la langue '$lang'.\n";
            }
        }

        // Étape 5 : Insérer les images et leurs labels multilingues
        foreach ($projet["image"] as $image) {
            $imageId = $linkHandler->create($projectId, $image["label"], $image["path"]);

            if ($imageId === 0) {
                throw new Exception("Impossible d'ajouter l'image avec le label '{$image['label']}'.");
            }

            echo "Image '{$image['label']}' ajoutée avec succès.\n";

            // Ajouter les labels multilingues dans le tableau Text
            foreach ($image["langText"] as $lang => $labelContent) {
                $result = $textHandler->create($projectId, $image["label"], $lang, $labelContent);

                if ($result === 0) {
                    throw new Exception("Impossible d'ajouter le texte pour l'image avec le label '{$image['label']}' dans la langue '$lang'.");
                }

                echo "Texte pour l'image avec le label '{$image['label']}' ajouté dans la langue '$lang'.\n";
            }
        }
    }
} catch (Exception $e) {
    // Loguer l'erreur au lieu de l'afficher directement
    error_log($e->getMessage(), 3, 'logs/errors.log');
    // Optionnel : message silencieux ou redirection
    die("Une erreur est survenue. Consultez les journaux pour plus de détails.");
}
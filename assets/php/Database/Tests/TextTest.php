<?php

use PHPUnit\Framework\TestCase;

require_once "assets/php/Database/Text.php";

class TextTest extends TestCase {
        private Project $project;
        private Text $text;
    
        public function testCreateText() {
                $this->project = new Project();
                $this->text = new Text();
                // Création d'un projet pour garantir un `project_id` valide
                $projectId = $this->project->create();
                $this->assertGreaterThan(0, $projectId);

                // Ajout d'un texte pour le projet avec un label
                $textId = $this->text->create($projectId, "titre1","fr", "Texte de test");
                $this->assertGreaterThan(0, $textId);
        }
    
        public function testDeleteText() {
                $this->project = new Project();
                $this->text = new Text();
                $projectId = $this->project->create();
                $this->text->create($projectId, "titre2", "fr", "Sample Text");

                // Vérification de la suppression
                $this->assertTrue($this->text->delete($projectId));

                // Vérification que le texte supprimé n'est plus dans la base
                $texts = $this->text->fetchText($projectId, "titre2");
                $this->assertEmpty($texts);
        }

        public function testFetchText() {
                $this->project = new Project();
                $this->text = new Text();

                // Création d'un projet et ajout de textes
                $projectId = $this->project->create();
                $this->assertGreaterThan(0, $projectId);

                $this->text->create($projectId, "titre1", "fr", "Texte en français");
                $this->text->create($projectId, "titre1", "en", "Text in English");

                // Sauvegarde de la valeur initiale du cookie (s'il existe)
                $originalLang = $_COOKIE["lang"] ?? null;

                // Test avec la langue "fr"
                $_COOKIE["lang"] = "fr";
                $textsFr = $this->text->fetchText($projectId, "titre1");
                var_dump($textsFr) ;
                $this->assertNotEmpty($textsFr);
                $this->assertEquals("Texte en français", $textsFr);

                // Test avec la langue "en"
                $_COOKIE["lang"] = "en";
                $textsEn = $this->text->fetchText($projectId, "titre1");
                $this->assertNotEmpty($textsEn);
                $this->assertEquals("Text in English", $textsEn);

                // Réinitialisation du cookie à sa valeur d'origine
                if ($originalLang !== null) {
                        $_COOKIE["lang"] = $originalLang;
                } else {
                        unset($_COOKIE["lang"]);
                }

            }
    }

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
                $textId = $this->text->create($projectId, "titre1", "fr", "Texte de test");
                $this->assertGreaterThan(0, $textId);

                // Vérification que le texte est bien inséré
                $texts = $this->text->fetchText($projectId, "titre1", "fr");
                $this->assertNotEmpty($texts);
                $this->assertEquals("Texte de test", $texts[0]['txt']);
                $this->assertEquals("titre1", $texts[0]['label']);
}
    
        public function testDeleteText() {
                $this->project = new Project();
                $this->text = new Text();
                $projectId = $this->project->create();
                $this->text->create($projectId, "titre2", "en", "Sample Text");

                // Vérification de la suppression
                $this->assertTrue($this->text->delete($projectId));

                // Vérification que le texte supprimé n'est plus dans la base
                $texts = $this->text->fetchText($projectId, "titre2", "en");
                var_dump($texts) ;
                $this->assertEmpty($texts);
        }
    }

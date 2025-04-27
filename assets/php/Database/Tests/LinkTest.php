<?php
use PHPUnit\Framework\TestCase;
require_once "assets/php/Database/Link.php";
require_once "assets/php/Database/Project.php"; // Ajouté pour créer des projets

class LinkTest extends TestCase {
    private Link $link;
    private Project $project; // Pour gérer les projets liés aux liens

    protected function setUp(): void {
        $this->link = new Link();
        $this->project = new Project(); // Initialisation de la classe Project
    }

    // Test de création de lien
    public function testCreateLink() {
        $pid = $this->project->create(); // Crée un projet et récupère son ID
        $label = "Lien d'exemple";
        $url = "https://example.com";

        $linkId = $this->link->create($pid, $label, $url);
        $this->assertIsInt($linkId, "L'ID du lien créé n'est pas un entier.");
        $this->assertGreaterThan(0, $linkId, "L'ID du lien créé doit être supérieur à zéro.");
    }

    // Test de suppression de lien
    public function testDeleteLink() {
        $pid = $this->project->create(); // Crée un projet et récupère son ID
        $label = "Lien à supprimer";
        $url = "https://delete.com";

        $linkId = $this->link->create($pid, $label, $url);
        $this->assertIsInt($linkId, "L'ID du lien créé pour suppression n'est pas un entier.");
        $this->assertGreaterThan(0, $linkId, "L'ID du lien doit être supérieur à zéro pour un test de suppression.");

        $this->assertTrue(
            $this->link->delete($pid),
            "La suppression du lien a échoué."
        );
    }

    // Test de récupération de liens
    public function testFetchLinks() {
        $pid = $this->project->create(); // Crée un projet et récupère son ID
        $label1 = "Premier lien";
        $url1 = "https://first.com";
        $label2 = "Deuxième lien";
        $url2 = "https://second.com";

        $this->link->create($pid, $label1, $url1);
        $this->link->create($pid, $label2, $url2);

        $links = $this->link->fetchLink($pid, $label1);
        $this->assertEquals($url1, $links, "L'URL du premier lien ne correspond pas.");
        $links = $this->link->fetchLink($pid, $label2);
        $this->assertEquals($url2, $links, "L'URL du deuxième lien ne correspond pas.");
    }
}
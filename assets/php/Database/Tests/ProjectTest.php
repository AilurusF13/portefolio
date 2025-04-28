<?php
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertContains;
require_once "assets/php/Database/Project.php";

class ProjectTest extends TestCase {
    private Project $project;

    // Test de création d'un projet
    public function testCreateProject() {
        $this->project = new Project();
        // Création d'un projet et vérifications
        $id = $this->project->create();
        $this->assertIsInt($id, "L'ID créé n'est pas un entier.");
        $this->assertGreaterThan(0, $id, "L'ID doit être supérieur à zéro.");

        // Vérification que le projet existe dans la liste des projets
        $projects = $this->project->listProject();
        $label = $this->project->getLabel($id) ;
        $this->assertNotEmpty($projects, "La liste des projets est vide après création.");
        $array = [] ;
        foreach($projects  as $p){
            array_push( $array, $p["label"] );
        }
        assertContains($label,  $array) ;
    }

    // Test de suppression d'un projet
    public function testDeleteProject() {
        $this->project = new Project();
        // Création d'un projet pour effectuer un test de suppression
        $id = $this->project->create();
        $this->assertIsInt($id, "L'ID créé pour suppression n'est pas un entier.");
        $this->assertGreaterThan(0, $id, "L'ID doit être supérieur à zéro pour un test de suppression.");

        // Suppression du projet et vérifications
        $this->assertTrue(
            $this->project->delete($id),
            "La suppression du projet a échoué."
        );

        // Vérification que le projet n'est plus dans la liste des projets
        $projects = $this->project->listProject();
        foreach ($projects as $project) {
            $this->assertNotEquals(
                $id,
                $project["id"],
                "Le projet supprimé est toujours présent dans la liste."
            );
        }
    }
}
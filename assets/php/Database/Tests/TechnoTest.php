<?php
use PHPUnit\Framework\TestCase;
require_once "assets/php/Database/Techno.php";
require_once "assets/php/Database/Project.php";

class TechnoTest extends TestCase {
    private Techno $techno;
    private Project $project;

    public function testCreateTechno() {
        $this->techno = new Techno();
        $this->project = new Project();
        // Création d'un projet pour garantir un `pid` valide
        $pid = $this->project->create();
        $this->assertGreaterThan(0, $pid);
    
        // Ajout d'une nouvelle technologie au projet
        $result = $this->techno->create($pid, "PHP");
        $this->assertTrue($result);
    
        // Vérification que la technologie est associée au projet
        $technos = $this->techno->fetchByProject($pid);
        $this->assertNotEmpty($technos);
        $this->assertContains("PHP", $technos);
    }
    
    public function testDeleteTechno() {
        $this->techno = new Techno();
        $this->project = new Project();
        // Création d'un projet et ajout d'une technologie
        $pid = $this->project->create();
        $this->techno->create($pid, "MySQL");
    
        // Suppression de la technologie du projet
        $result = $this->techno->delete($pid, "MySQL");
        $this->assertTrue($result);
    }

    public function testFetchByProject() {
        $this->techno = new Techno();
        $this->project = new Project();
        // Création d'un projet et ajout de plusieurs technologies
        $pid = $this->project->create();
        $this->assertGreaterThan(0, $pid);
    
        $this->techno->create($pid, "HTML");
        $this->techno->create($pid, "CSS");
        $this->techno->create($pid, "JavaScript");
    
        // Récupération des technologies associées au projet
        $technos = $this->techno->fetchByProject($pid);
        // Vérification que les données sont correctes
        $this->assertCount(3, $technos);
        $this->assertContains("HTML", $technos);
        $this->assertContains("CSS", $technos);
        $this->assertContains("JavaScript", $technos);
    }
    
    public function testFetchByTechno() {
        $this->techno = new Techno();
        $this->project = new Project();
        // Création de plusieurs projets
        $pid1 = $this->project->create();
        $pid2 = $this->project->create();
        $pid3 = $this->project->create();
        var_dump( $pid1) ;
        var_dump( $pid2) ;
        var_dump( $pid3) ;
    
        // Ajout de la même technologie à différents projets
        $this->techno->create($pid1, "GO");
        $this->techno->create($pid2, "GO");
        $this->techno->create($pid3, "GO");
    
        // Récupération des projets associés à la technologie "PHP"
        $projects = $this->techno->fetchByTechno("GO");
        var_dump($projects) ;
        // Vérification que tous les projets sont bien associés
        $this->assertContains($pid1, $projects);
        $this->assertContains($pid2, $projects);
        $this->assertContains($pid3, $projects);
    }
 }
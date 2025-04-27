<?php
use PHPUnit\Framework\TestCase;
require_once "assets/php/Database/Techno.php";

class TechnoTest extends TestCase {
    private Techno $techno;
    private Project $project;

    protected function setUp(): void {
        $this->techno = new Techno();
        $this->project = new Project();
    }

    public function testCreateTechno() {
        // Création d'un projet pour garantir un `pid` valide
        $pid = $this->project->create();
        $this->assertGreaterThan(0, $pid);
    
        // Ajout d'une nouvelle technologie au projet
        $result = $this->techno->create($pid, "PHP");
        $this->assertTrue($result);
    
        // Vérification que la technologie est associée au projet
        $technos = $this->techno->fetchByProject($pid);
        $this->assertNotEmpty($technos);
        $this->assertEquals("PHP", $technos[0]["name"]);
    }
    
    public function testDeleteTechno() {
        // Création d'un projet et ajout d'une technologie
        $pid = $this->project->create();
        $this->techno->create($pid, "MySQL");
    
        // Suppression de la technologie du projet
        $result = $this->techno->delete($pid, "MySQL");
        $this->assertTrue($result);
    
        // Vérification que la technologie n'est plus associée au projet
        $technos = $this->techno->fetchByProject($pid);
        $this->assertEmpty($technos);
    }

    public function testFetchByProject() {
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
        $array = [] ;
        foreach ($technos as $t) { array_push($array, $t["name"]) ;}
        $this->assertContains("HTML", $array);
        $this->assertContains("CSS", $array);
        $this->assertContains("JavaScript", $array);
    }
    
    public function testFetchByTechno() {
        // Création de plusieurs projets
        $pid1 = $this->project->create();
        $pid2 = $this->project->create();
        $pid3 = $this->project->create();
    
        // Ajout de la même technologie à différents projets
        $this->techno->create($pid1, "PHP");
        $this->techno->create($pid2, "PHP");
        $this->techno->create($pid3, "PHP");
    
        // Récupération des projets associés à la technologie "PHP"
        $projects = $this->techno->fetchByTechno("PHP");
        $array = [] ;
        foreach($projects as $p) { array_push($array, $p["pid"]) ; }
        var_dump($pid1) ;
        var_dump($array) ;
        // Vérification que tous les projets sont bien associés
        $this->assertContains($pid1, $array);
        $this->assertContains($pid2, $array);
        $this->assertContains($pid3, $array);
    }
 }
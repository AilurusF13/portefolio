<?php

use function PHPUnit\Framework\isEmpty;
require_once "DatabaseHandler.php" ;
$data = json_decode(file_get_contents('php://input'), true) ;
$tech = $data['filters'] ;

// requette envoyée avec un array des string (les technos)
// pour chaque pid, on regarde sa liste de techno de celui ci et on fait l intersection avec cette liste la
$dbHandler = DatabaseHandler::getInstance() ;
// recuperer les id des projets
$pids = $dbHandler->project->listProject() ;
foreach ($pids as $pid) {
        $projectId = $pid['id']; // Assurez-vous que l'objet retourné contient l'ID
        if ($tech != [] && array_intersect($tech, $dbHandler->techno->fetchByProject($projectId)) == 0 ){
                continue ; // on n affiche pas la boite en question si ...
        }
        $projectName = $dbHandler->text->fetchText($projectId, 'nom');
        $projectLink =  "https://www.google.com"; //TODO mettre a jour avec le liens de la page associé au projet 
        $projectResume = $dbHandler->text->fetchText($projectId, 'resume');
        $projectImage = $dbHandler->link->fetchLink($projectId, 'miniature');
        echo "
        <a class='content' href='{$projectLink}' target='_blank'>
                <h3>{$projectName}</h3>
                <img src='{$projectImage}' draggable='false' alt='{$projectName}' />
                <p>{$projectResume}</p>
        </a>
        ";
}
?>
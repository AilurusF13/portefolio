<?php

require_once "DatabaseHandler.php" ;
$searchPattern = isset($_GET['search']) ? $_GET['search'] : ''; // Termes de recherche
$tech = isset($_POST['filters']) ? $_POST['filters'] : []; // Filtres de technos envoyés via POST

$dbHandler = DatabaseHandler::getInstance();

// Récupérer les ID des projets
$pids = $dbHandler->project->listProject();

foreach ($pids as $pid) {
    $projectId = $pid['id']; // Assurez-vous que l'objet retourné contient l'ID
    $projectName = $dbHandler->text->fetchText($projectId, 'nom'); // Récupère le nom du projet

    // Si le tableau de techno n'est pas vide et que le projet ne contient pas les technos, on le saute
    if ($tech != [] && array_intersect($tech, $dbHandler->techno->fetchByProject($projectId)) == 0) {
        continue; // Ne pas afficher ce projet si les technos ne correspondent pas
    }

    // Vérification de la correspondance avec le motif de recherche
    if (!empty($searchPattern) && strpos(strtolower($projectName), strtolower($searchPattern)) === false) {
        continue; // Si le nom du projet ne contient pas le motif de recherche, on le saute
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
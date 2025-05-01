<?php

require_once "DatabaseHandler.php" ;
$searchPattern = isset($_GET['search']) ? $_GET['search'] : ''; // Termes de recherche
// filter var pour empecher injections
// Filtres de technos envoyés via POST
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);
$tech = isset($data['filters']) ? $data['filters'] : [];
// $tech = isset($_POST['filters']) ? $_POST['filters'] : []; 
$dbHandler = DatabaseHandler::getInstance();

// avant de continuer on va vérifier que les données soient conformes dan,s l array et le texte
$searchPattern = htmlspecialchars(substr($searchPattern, 0, 30));
foreach ($tech as $techItem) {
    $techItem = htmlspecialchars(substr($techItem, 0, 30)) ;
}

// Récupérer les ID des projets
$pids = $dbHandler->project->listProject();

foreach ($pids as $pid) {
    $projectId = $pid['id']; // Assurez-vous que l'objet retourné contient l'ID
    $projectName = $dbHandler->text->fetchText($projectId, 'nom'); // Récupère le nom du projet

    // Si le tableau de techno n'est pas vide et que le projet ne contient pas les technos, on le saute
    $ptech = $dbHandler->techno->fetchByProject($projectId) ;
    if ($tech != [] && array_intersect($tech, $ptech) == [] ) {
        continue; // Ne pas afficher ce projet si les technos ne correspondent pas
    }

    // Vérification de la correspondance avec le motif de recherche
    if (!empty($searchPattern) && strpos(strtolower($projectName), strtolower($searchPattern)) === false) {
        continue; // Si le nom du projet ne contient pas le motif de recherche, on le saute
    }
        $projectName = $dbHandler->text->fetchText($projectId, 'nom');
        $projectLink =  "/projectPage.php?id=$projectId";
        $projectResume = $dbHandler->text->fetchText($projectId, 'resume');
        $projectImage = $dbHandler->link->fetchLink($projectId, 'miniature');
        echo "
        <a class='content' href='{$projectLink}'>
                <h3>{$projectName}</h3>
                <img src='{$projectImage}' draggable='false' alt='{$projectName}' />
                <p>{$projectResume}</p>
        </a>
        ";
}
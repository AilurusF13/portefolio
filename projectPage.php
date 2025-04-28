<?php
require_once "assets/php/DatabaseHandler.php" ;
require_once "assets/locales/trad.php";
$dbHandler = DatabaseHandler::getInstance() ;

$searchPattern = isset($_GET['search']) ? $_GET['search'] : ''; // Termes de recherche
$pid = (int) ($_GET['id']) ;

?>
<!DOCTYPE html>
<html lang=fr xmlns="http://www.w3.org/1999/html">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $dbHandler->text->fetchText($pid, 'name')?></title>
        <link rel="icon" type="image/svg" href="/assets/images/logoA.svg">
        <link rel="stylesheet" href="/assets/css/style.css">

        <!--    ROBVBOTO font-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
       <?php
        include_once "assets/php/header.php" ;
       ?> 


       <?php
        include_once "assets/php/footer.php" ;
       ?> 
</body>


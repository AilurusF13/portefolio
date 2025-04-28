<?php
require_once "assets/locales/trad.php";
require_once "assets/php/DatabaseHandler.php" ;
require_once "assets/php/tabProjets.php" ; // importe le tableau "projets"
$dbHandler = DatabaseHandler::getInstance() ;
$dbHandler->fillDatabase($projets) ;
?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porte Folio</title>
    <link rel="icon" type="image/svg" href="/assets/images/logoA.svg">
    <link rel="stylesheet" href="/assets/css/style.css">

<!--    ROBVBOTO font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
<?php
include_once "assets/php/header.php"; /*Head bar*/
?>

<section id="showcase-section">
    <div class="showcase about-me">
        <div class="me image no-select">
            <img src="assets/images/logo.svg" draggable="false" alt="temporary">
        </div>
        <div class="me resume">
            <?= $t["me"]["resume"]?>
        </div>
        <div class="me info">
            <ul>
                <li>-Franck</li>
                <li>-<?= $t["me"]["activity"] ?></li>
                <li>-<?= $t["me"]["age"]?></li>
                <li>-<?= $t["me"]["location"]?></li>
            </ul>
        </div>
    </div>
    <div class="project-slider">
        <?php
            include_once "assets/php/slider.php";
        ?>

    </div>

</section>

<section id="more-section">
    <?php
        include_once "assets/php/more-section.php";
    ?>
</section>
    <?php
        include_once "assets/php/footer.php"; /*Section a proro: footer*/
    ?>
</body>

<?php
require_once "assets/php/DatabaseHandler.php" ;
require_once "assets/locales/trad.php";
$dbHandler = DatabaseHandler::getInstance() ;
$pid = (int) isset($_GET['id']) ? (int) $_GET['id'] : 0; // recuperer l id u projet qui sera chargé sur la page

$label = $dbHandler->project->getLabel($pid);
if ($pid == 0 || $label == ""){
        echo "ERREUR : Projet non trouvé" ;
        exit() ;
}
?>
<!DOCTYPE html>
<html lang=fr xmlns="http://www.w3.org/1999/html">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $dbHandler->text->fetchText($pid, 'nom')?></title>
        <link rel="icon" type="image/svg" href="/assets/images/logoA.svg">
        <link rel="stylesheet" href="/assets/css/slider.css">
        <link rel="stylesheet" href="/assets/css/project-page.css">

        <!--    ROBVBOTO font-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
       <?php
        include_once "assets/php/header.php" ;
       ?> 

        <main>
                <!-- nom -->
                <section class="name-sec  pelem">
                        <?php
                                $pname = $dbHandler->text->fetchText($pid, 'nom') ;
                                echo "<h1>{$pname}</h1>" ;
                        ?>
                </section>
                <section class="resume-sec  pelem text-sections">
                        <h3><?= $t["page-project"]["resume"] ?></h3>
                        <?php
                                $presume = $dbHandler->text->fetchText($pid, 'resume') ;
                                echo $presume ;
                        ?>
                </section>
                <!-- details -->
                 <section class="details-sec  pelem text-sections">
                        <h3><?= $t["page-project"]["details"] ?></h3>
                        <?php
                                $pdetails = $dbHandler->text->fetchText($pid, 'details') ;
                                echo $pdetails ;
                        ?>
                 </section>
                <!-- slider -->
                <section class="slider slider-sec slider-container no-select slider-maximisable pelem">
                        <button class="slider-button left">
                                <img class="rotate180" src="assets/images/arrow.svg" draggable="false" alt="previous">
                        </button>
                        <section class="slider-viewport">
                                <?php
                                // on recupere tous les liens 'image' du projet 
                                // j ai mal foutu les labels de la db donc on recup tous les liens du project id et on trie ceux qui finissent par PNG (liste d'extension)
                                $linkList = $dbHandler->link->fetchAllLinks($pid) ;
                                $imgList = [] ;
                                $oList = [] ; // on le garde pour les liens clickables
                                foreach($linkList as $link){
                                        if ( str_ends_with($link['url'], ".png") ){
                                                array_push($imgList, $link) ;
                                        } else {
                                                array_push($oList, $link) ;
                                        }
                                } // on ne garde que les PNG
                                
                                // creer la structure d image des elem de slider
                                foreach($imgList as $img){
                                        $url = $img['url'] ;
                                        if ($img['label'] == 'miniature') continue ;
                                        $name = $dbHandler->text->fetchText($pid, $img["label"]) ;
                                        echo "
                                                <figure class='slider-item'>
                                                        <label for=''>{$name}</label>
                                                        <a><img src='{$url}' alt='$name' draggable='false'></a>
                                                </figure>
                                        " ;
                                }
                                ?>
                        </section>
                        <button class="slider-button right">
                                <img src="assets/images/arrow.svg" draggable="false" alt="next">
                        </button>
                </section>
                <section class="tech-sec pelem">
                <!-- lst techno -->
                        <h3><?= $t["page-project"]["tech"] ?></h3>
                        <?php
                                // lister les technos dans une ul-li
                                // 1 - recup toutes les technos
                                $technos = $dbHandler->techno->fetchByProject($pid) ;
                                echo "<ul>" ;
                                foreach($technos as $tech){
                                        echo "<li>{$tech}</li>" ;
                                }
                                echo "</ul> " ;
                        ?>
                </section>
                <!-- lst liens -->
                 <section class="link-sec pelem">
                        <h3><?= $t["page-project"]["link"] ?></h3>
                        <?php
                                // on utilise la liste deja existante "otherList"
                                // on liste a la maniere de techno sauf qu on rajoute des labels
                                echo "<ul>" ; 
                                foreach($oList as $link){
                                        echo "
                                                <li>
                                                        <a href='{$link['url']}' target='_blank'> {$dbHandler->text->fetchText($pid, $link['label'])}</a>
                                                </li>
                                        " ;
                                }
                                echo "</ul>"
                        ?>
                 </section>
        </main>

       <?php
        include_once "assets/php/footer.php" ;
       ?> 
</body>
<script src="assets/js/slider.js"></script>

<link rel="stylesheet" href="/assets/css/slider.css">
<div class="showcase slider-container no-select">
    <button class="slider-button left">
        <img class="rotate180" src="assets/images/arrow.svg" draggable="false" alt="previous">
    </button>
    <div class="slider-viewport">
        
        <!-- slider content in "slider-item" class Example : -->
        <!-- <div class="slider-item">
            <a href="https://neovim.io/" target="_blank">
                <img src="assets/images/neovim.webp" alt="nvim" draggable="false">
            </a>
        </div> -->
        <?php
        // Generer en php le slide 
        require_once "DatabaseHandler.php" ;
        $dbHandler = DatabaseHandler::getInstance() ;
        // MODS (tag des endroit modulables)
        $plabels = [ // on peut entrer ici les labels des projet que je veux mettre en avant
            "sokoban",
            "carnet-conduite"
        ] ;
        foreach ($plabels as $plabel){
            $pid =  $dbHandler->project->getId($plabel) ;
            if ($pid == 0) continue ;
            $projectLink =  "/projectPage.php?id=$pid";
            $projectName = $dbHandler->text->fetchText($pid, 'nom');
            $projectImage = $dbHandler->link->fetchLink($pid, 'miniature');
            echo "
                <div class='slider-item'>
                    <a href='{$projectLink}' >
                        <img src='{$projectImage}' alt='$projetName' draggable='false'>
                    </a>
                </div>
            " ;
        }
        ?>
    </div>
    <button class="slider-button right">
        <img src="assets/images/arrow.svg" draggable="false" alt="left">
    </button>
</div>
<script src="/assets/js/slider.js"></script>
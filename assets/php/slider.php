<section class="showcase slider-container no-select">
    <button class="slider-button left">
        <img class="rotate180" src="assets/images/arrow.svg" draggable="false" alt="previous">
    </button>
    <div class="slider-viewport">
        <?php
        require_once "DatabaseHandler.php";
        $dbHandler = DatabaseHandler::getInstance();

        $jsonPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/data/featured.json';
        
        if (!file_exists($jsonPath)) {
            $jsonPath = 'assets/data/featured.json';
        }

        $plabels = [];

        if (file_exists($jsonPath)) {
            $content = file_get_contents($jsonPath);
            $plabels = json_decode($content, true);
            // Sécurité si le json est corrompu ou vide
            if (!is_array($plabels)) $plabels = [];
        }

        foreach ($plabels as $plabel){
            // Sécurité : on s'assure que le slug existe bien en BDD
            $pid = $dbHandler->project->getId($plabel);
            if ($pid == 0) continue;

            $projectLink = "/projectPage.php?id=$pid";
            $projectName = $dbHandler->text->fetchText($pid, 'nom');
            // On récupère la miniature
            $projectImage = $dbHandler->link->fetchLink($pid, 'miniature');
            
            // Fallback image par défaut si vide
            if(empty($projectImage)) $projectImage = 'assets/images/default.webp';

            echo "
                <figure class='slider-item'>
                    <a href='{$projectLink}'>
                        <img src='{$projectImage}' alt='$projectName' draggable='false'>
                    </a>
                </figure>
            ";
        }
        ?>
    </div>
    <button class="slider-button right">
        <img src="assets/images/arrow.svg" draggable="false" alt="next">
    </button>
</section>
<script src="/assets/js/slider.js"></script>
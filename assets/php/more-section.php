<link rel="stylesheet" href="/assets/css/more-section.css">
<div class="more-container">
    <div id="more-sec" class="content-filter cache show-sec hidden">
        <div class="fixed">
            <form id="keyword-form" class="search-bar" method="get">
                <label for="keyword">Rechercher :</label>
                <input id="keyword" type="text" name="nom" placeholder="Example"/>
                <button id="keyword-button" type="submit">
                    <img src="assets/images/loupe.svg" alt="rechercher" data-src="https://www.svgrepo.com/svg/201683/loupe-search" draggable="false">
                </button>
            </form>
            <button id="filter-button" class="cache-button more-filter">
                <?= $t["projet"]["filterbutton"] ?>
                <img class="rotate90" src="assets/images/arrow.svg" alt="Develop filter" draggable="false">
            </button>
            <form id="filter-sec" class="cache more-filter hidden" method="post">
                <!-- <div class="option">
                    <label for="check-1">Option 1</label>
                    <input type="checkbox" id="check-1">
                </div> -->
                <?php
                    require_once "DatabaseHandler.php" ;
                    $dbHandler = DatabaseHandler::getInstance() ;
                    // recup link et creer la checkbox foreach of them
                    $tabTechno = $dbHandler->techno->fetchByProject(0) ; // recup toutes les techno
                    foreach( $tabTechno as $techno ){
                        $safeTechno = htmlspecialchars($techno) ;
                        echo "
                            <div class=option>
                                <label for='checkbox_{$safeTechno}'>{$techno}</label>
                                <input type=checkbox id=checkbox_{$safeTechno}>
                            </div>
                        " ;
                    }
                ?>
            </form>
        </div>
        <div id="id-project-content" class="project-content">
            <!-- Exemple de content  
                <a class="content">
                <h3>Exploration Numérique</h3>
                <svg width="50" height="50">
                    <circle cx="25" cy="25" r="20" stroke="black" stroke-width="3" fill="none" />
                </svg>
                <p>Un projet qui redéfinit les interactions numériques à travers une approche immersive.</p>
            </a> -->
            <?php
                require_once "DatabaseHandler.php" ;
                $dbHandler = DatabaseHandler::getInstance() ;
                // recuperer les id des projets
                $pids = $dbHandler->project->listProject() ;
                foreach ($pids as $pid) {
                    $projectId = $pid['id']; // Assurez-vous que l'objet retourné contient l'ID
                    $projectName = $dbHandler->text->fetchText($projectId, 'nom');
                    $projectResume = $dbHandler->text->fetchText($projectId, 'resume');
                    $projectImage = $dbHandler->link->fetchLink($projectId, 'miniature');
                    echo "
                        <a class='content'>
                            <h3>{$projectName}</h3>
                            <img src='{$projectImage}' draggable='false' alt='{$projectName}' />
                            <p>{$projectResume}</p>
                        </a>
                    ";
                }
            ?>
        </div>
    </div>
    <button id="more-button" class="cache-button more-filter">
        <img class="rotate270" src="assets/images/arrow.svg" alt="develop see-more" draggable="false">
        <?= $t["projet"]["morebutton"]?>
    </button>
</div>
<script src="assets/js/displayButtons.js" ></script>
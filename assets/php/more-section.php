<div class="more-container">
    <div id="more-sec" class="content-filter cache show-sec hidden">
        <div class="fixed">
            <form id="keyword-form" class="search-bar">
                <label for="keyword">Rechercher :</label>
                <input id="keyword" type="text" name="nom" placeholder="Example"/>
                <button id="keyword-button">
                    <img src="assets/images/loupe.svg" alt="rechercher" data-src="https://www.svgrepo.com/svg/201683/loupe-search" draggable="false">
                </button>
            </form>
            <button id="filter-button" class="cache-button more-filter">
                <?= $t["projet"]["filterbutton"] ?>
                <img class="rotate90" src="assets/images/arrow.svg" alt="Develop filter" draggable="false">
            </button>
            <form id="filter-sec" class="cache more-filter hidden">
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
                            <div class='option'>
                                <label for='checkbox_{$safeTechno}'>{$safeTechno}</label>
                                <input type=checkbox class='box filter' id=checkbox_{$safeTechno} value='$safeTechno'>
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
                // require_once "ajax-projects.php" ;
            ?>
        </div>
    </div>
    <button id="more-button" class="cache-button more-filter">
        <img class="rotate270" src="assets/images/arrow.svg" alt="develop see-more" draggable="false">
        <?= $t["projet"]["morebutton"]?>
    </button>
</div>
<script src="assets/js/displayButtons.js" ></script>
<script src="assets/js/ajaxFilter.js" ></script>
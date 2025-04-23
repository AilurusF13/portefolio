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
                Show filters
                <img class="rotate90" src="assets/images/arrow.svg" alt="Develop filter" draggable="false">
            </button>
            <div id="filter-sec" class="cache more-filter hidden">
                <form id="filter-form" method="post">
                    <!--                Filter options temporary-->
                    <div class="filter-options">
                        <div class="option">
                            <label for="check-1">Option 1</label>
                            <input type="checkbox" id="check-1">
                        </div>
                        <div class="options">
                            <label for="check-2">Option 2</label>
                            <input type="checkbox" id="check-2">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="id-project-content" class="project-content">
            <a class="content">
                <h3>Exploration Numérique</h3>
                <svg width="50" height="50">
                    <circle cx="25" cy="25" r="20" stroke="black" stroke-width="3" fill="none" />
                </svg>
                <p>Un projet qui redéfinit les interactions numériques à travers une approche immersive.</p>
            </a>

            <a class="content">
                <h3>Vision Architecturale</h3>
                <svg width="50" height="50">
                    <rect x="10" y="10" width="30" height="30" stroke="black" stroke-width="3" fill="none" />
                </svg>
                <p>Concept futuriste qui allie esthétique et innovation pour les espaces urbains.</p>
            </a>

            <a class="content">
                <h3>Réseau Créatif</h3>
                <svg width="50" height="50">
                    <polygon points="10,10 40,10 25,40" stroke="black" stroke-width="3" fill="none" />
                </svg>
                <p>Une plateforme où les idées se rencontrent et se transforment en projets concrets.</p>
            </a>

            <a class="content">
                <h3>Éco-Technologie</h3>
                <svg width="50" height="50">
                    <path d="M10 30 Q 25 10, 40 30" stroke="black" stroke-width="3" fill="none"/>
                </svg>
                <p>Solutions éco-responsables pour une transition énergétique durable.</p>
            </a>

            <a class="content">
                <h3>Art Digital</h3>
                <svg width="50" height="50">
                    <ellipse cx="25" cy="25" rx="20" ry="10" stroke="black" stroke-width="3" fill="none"/>
                </svg>
                <p>Fusion entre technologie et expression artistique pour des œuvres interactives.</p>
            </a>

            <a class="content">
                <h3>Mobilité Intelligente</h3>
                <svg width="50" height="50">
                    <line x1="10" y1="25" x2="40" y2="25" stroke="black" stroke-width="3" />
                </svg>
                <p>Optimisation des déplacements urbains grâce à des solutions intelligentes.</p>
            </a>
        </div>
    </div>
    <button id="more-button" class="cache-button more-filter">
        <img class="rotate270" src="assets/images/arrow.svg" alt="develop see-more" draggable="false">
        See more projects
    </button>
</div>
<script src="assets/js/displayButtons.js" ></script>
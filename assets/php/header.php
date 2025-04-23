<link rel="stylesheet" href="/assets/css/header.css">
<header class="header no-select">
    <div>
        <a href="/portefolio/index.php" class="logo">
            <img src="/assets/images/logo.svg" alt="Acceuil" class="logo" draggable="false">
        </a>
    </div>
    <div>
        <menu>
            <li><!--suppress HtmlUnknownAnchorTarget -->
                <a class="more-link" href="#more-section">Projets</a></li>
            <li><!--suppress HtmlUnknownAnchorTarget -->
                <a href="#footer-section">Contact</a></li>
            <li>
                <form id="select-language-post" action="/assets/php/changeLanguage.php" method="POST">
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <select id="select-language" name="language" onchange="this.form.submit()">
                        <option value="fr">Français</option>
                        <option value="en">English</option>
                    </select>
                </form>
            </li>
        </menu>
    </div>
</header>
<script src="/assets/js/refLink.js"></script>
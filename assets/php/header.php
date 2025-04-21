<link rel="stylesheet" href="/assets/css/header.css">
<header class="header">
    <div>
        <a href="/portefolio/index.php" class="logo">
            <img src="/assets/images/logo.svg" alt="Acceuil" class="logo">
        </a>
    </div>
    <div>
        <menu>
            <li><!--suppress HtmlUnknownAnchorTarget -->
                <a href="#more-section">Projets</a></li>
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
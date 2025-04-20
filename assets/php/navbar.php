<link rel="stylesheet" href="/assets/css/navbar.css">
<nav class="main_navbar">
    <div>
        <a href="/portefolio/index.php" class="logo">
            <img src="/assets/images/logo.svg" alt="Acceuil" class="logo">
        </a>
    </div>
    <div>
        <menu>
            <li><a href="#more-section">Projets</a></li>
            <li><a href="#footer-section">Contact</a></li>
            <li>
                <form id="select-language-post" action="/assets/php/changeLanguage.php" method="POST">
                    <select id="select-language" name="language" onchange="this.form.submit()">
                        <option value="fr">Français</option>
                        <option value="en">English</option>
                    </select>
                </form>
            </li>
        </menu>
    </div>
</nav>
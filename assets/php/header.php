<header class="header no-select">
    <div>
        <a href="/" class="logo">
            <img src="/assets/images/logo.svg" alt="Acceuil" class="logo" draggable="false">
        </a>
    </div>
    <div>
        <menu>
            <li>
                <a class="more-link" href="/#more-section"><?= $t["header"]["projet"] ?></a></li>
            <li>
                <a href="#footer-section"><?= $t["header"]["contact"] ?></a></li>
            <li>
                <form id="select-language-post" action="/lang.php" method="GET">
                    <?php
                    $lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr'; // Récupère la langue stockée
                    ?>
                    <select id="langSelect" name="lang" onchange="this.form.submit()">
                        <option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>Français</option>
                        <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>English</option>
                    </select>
                </form>
            </li>
        </menu>
    </div>
</header>
<script src="/assets/js/refLink.js"></script>
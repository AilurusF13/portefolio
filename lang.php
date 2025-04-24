<?php
if (!empty($_GET['lang'])) {
    setcookie(
        'lang',
        $_GET['lang'],
        time() + 3600 * 24 * 365,
        '/',
        '',
        true,
        false
    );
    header("Location: /");
    exit;
} else {
    echo "Erreur : aucune langue sélectionnée.";
}
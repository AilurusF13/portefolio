<?php
if (!empty($_GET['lang'])) {
    // sécurité de la langue entrée
    $lng = substr(strtolower(htmlspecialchars($_GET['lang'])), 0, 2);

    // Optionnel : vérifier que la langue est autorisée
    $allowedLangs = ['fr', 'en']; // Exemple
    if (!in_array($lng, $allowedLangs)) {
        $lgn = $allowedLangs[0];
    }
    setcookie(
        'lang'
        ,$lng,
        [
            'expires' => time() + 3600 * 24 * 7,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']), // True si HTTPS
            'httponly' => true,
            'samesite' => 'Lax' // ou 'Strict' selon vos besoins
        ]
    );
    header("Location: /");
    exit;
} else {
    echo "Erreur : aucune langue sélectionnée.";
}
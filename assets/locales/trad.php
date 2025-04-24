<?php
if (array_key_exists('lang', $_COOKIE) && $_COOKIE['lang'] === 'en') {
    require_once 'assets/locales/en.php';
} else {
    require_once 'assets/locales/fr.php';
}
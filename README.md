# Requirements

- php (v8)
- php-sqlite

# Adding new content :
Edit file `assets/php/tabProjets.php`

# adding new language 
- Edit file the tabProjet file to add the language support to each project `assets/php/tabProjets.php`
- add the associated file's language in  `locales`
- add the language option in `trad.php`
- then finaly in `lang.php`

# Docker

```docker build -t portefolio-php .```

```docker run -p 8080:80 portefolio-php```
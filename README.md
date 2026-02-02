# Portfolio Project

# Requirements

- PHP (v8)
- php-sqlite

# Adding New Content

Edit the file `assets/php/tabProjets.php`.

# Adding a New Language

1. Edit `assets/php/tabProjets.php` to add language support to each project.
2. Add the associated language file in `locales`.
3. Add the language option in `trad.php`.
4. Finally, update `lang.php`.

# Docker

```bash
docker compose up -d --build
```
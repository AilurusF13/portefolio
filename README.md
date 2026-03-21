# Portfolio Project

# Requirements

- PHP (v8)
- php-sqlite

# Adding New Content

Via the admin.php sub page

# Adding a New Language

1. Edit `assets/php/tabProjets.php` to add language support to each project.
2. Add the associated language file in `locales`.
3. Add the language option in `trad.php`.
4. Finally, update `lang.php`.

# Deployment (Quadlet / Podman)

This project is designed to be deployed via **Quadlet** 

## Server File Architecture

The source code is not required on the server for execution, only the container image and the database are needed.

### 1. Database
Place the production SQLite file in the user configuration folder:
`~/.config/databases/portfolio.sqlite`

### 2. Systemd Configuration (Quadlet)
Place the `.container` file (and `.build` if building locally) in:
`~/.config/containers/systemd/`

* **portfolio.container**: Defines the service, port (8080), and DB volume.
* **portfolio.build**: Image building using the project's Dockerfile

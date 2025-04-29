<?php
$projets = [
        [
                "label" => "carnet-conduite",
                "langText" => [

                        "fr" => [
                                "nom" => "Carnet de Conduite",
                                "resume" => "Un système interactif pour enregistrer et visualiser les trajets en fonction des véhicules.",
                                "details" => "Ce projet de carnet de conduite permet de gérer les trajets liés à différents véhicules en utilisant le stockage local du navigateur (LocalStorage). Il inclut des fonctionnalités dynamiques comme l'ajout de véhicules, la saisie de trajets avec des détails personnalisés (date, distance, météo, état du trafic, etc.), et un affichage du bilan total des trajets. L'interface est conçue pour être accessible et intuitive, avec des formulaires dynamiques et des mises à jour en temps réel des données saisies.",
                        ],
                        "en" => [
                                "nom" => "Driving Logbook",
                                "resume" => "An interactive system to log and visualize trips based on vehicles.",
                                "details" => "This driving logbook project helps manage trips linked to different vehicles using the browser's local storage (LocalStorage). It features dynamic functionalities such as adding vehicles, recording trips with customized details (date, distance, weather, traffic conditions, etc.), and displaying a complete trip summary. The interface is designed to be accessible and intuitive, with dynamic forms and real-time data updates.",
                        ],
                ],
                "technologies" => ["HTML", "CSS", "JavaScript"],
                "link" => [
                        [
                                "label" => "download-project" ,
                                "langText" => [
                                        "fr" => "Télécharger le projet",
                                        "en" => "Download project"
                                ],
                                "url" => "assets/downloads/projet/conduite/carnet_conduite.zip"
                        ]
                ],
                "image" => [
                        [
                                "label" => "miniature" ,
                                "path" => "assets/images/projet/conduite/miniature.png"
                        ],
                        [
                                "label" => "conduite-page-1" ,
                                "langText" => [
                                        "fr" => "Page 1",
                                        "en" => "Page 1"
                                ],
                                "path" => "assets/images/projet/conduite/page1.png"
                        ],
                        [
                                "label" => "conduite-page-2" ,
                                "langText" => [
                                        "fr" => "Page 2",
                                        "en" => "Page 2"
                                ],

                                "path" => "assets/images/projet/conduite/page2.png"
                        ],
                        [
                                "label" => "conduite-code" ,
                                "langText" => [
                                        "fr" => "Exemple de code",
                                        "en" => "Code example"
                                ],

                                "path" => "assets/images/projet/conduite/code.png"
                        ]
                ]       
        ],
        [
                "label" => "sokoban",
                "langText" => [
                        "fr" => [
                                "nom" => "Sokoban en C",
                                "resume" => "Un jeu Sokoban en terminal avec interface ASCII, écrit en C avec ncurses fait en groupe et documenté avec doxygen.",
                                "details" => "Ce projet consiste en une implémentation du jeu Sokoban en C, utilisant exclusivement la librairie ncurses pour l'affichage en terminal. La structure du projet repose sur une matrice à deux dimensions représentant la carte de jeu, et une architecture modulaire avec des headers et fichiers sources bien organisés. La documentation du projet est générée avec Doxygen. Un Makefile complet permet la compilation, l'exécution, la génération d'archive, ainsi que l'utilisation d'outils de débogage comme valgrind et gdb. Le jeu prend en charge des fichiers de niveau personnalisables et une gestion rigoureuse des erreurs.",
                        ],
                        "en" => [
                                "nom" => "Sokoban in C",
                                "resume" => "A terminal-based Sokoban game with ASCII interface, written in C using ncurses made in a team and documented with doxygen.",
                                "details" => "This project is a C implementation of the Sokoban game, using the ncurses library for terminal-based ASCII rendering. It features a modular structure with separate header and source files, a matrix-based level representation, and extensive error handling. The project includes a Doxygen-generated documentation, and a robust Makefile supporting compilation, execution, archiving, and debugging with valgrind and gdb. Levels are loaded from customizable text files, and texture formatting supports flexible terminal display.",
                        ],
                ],
                "technologies" => ["C"],
                "link" => [
                        [
                                "label" => "git-zip",
                                "langText" => [
                                        "fr" => "Télécharger le répértoire git",
                                        "en" => "Download git repository"
                                ],
                                "url" => "assets/downloads/projet/sokoban.zip"
                        ],
                        [
                                "label" => "documentation",
                                "langText" => [
                                        "fr" => "Voir la documentation",
                                        "en" => "See documentation"
                                ],
                                "url" => "assets/projects-documentation/sokoban/index.html"
                        ]
                ],
                "image" => [
                        [
                                "label" => "miniature",
                                "path" => "assets/images/projet/sokoban/miniature.png"
                        ],
                        [
                                "label" => "sokoban-level",
                                "langText" => [
                                        "fr" => "Fichiers de niveau",
                                        "en" => "Level files"
                                ],
                                "path" => "assets/images/projet/sokoban/level.png"
                        ],
                        [
                                "label" => "code-example",
                                "langText" => [
                                        "fr" => "Extrait de code",
                                        "en" => "Code example"
                                ],
                                "path" => "assets/images/projet/sokoban/code.png"
                        ]
                ]
        ]
];
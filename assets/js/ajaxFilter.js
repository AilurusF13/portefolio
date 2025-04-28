let selectedValues = [];
let wrotePattern = "";

// Fonction AJAX pour envoyer les filtres (POST)
function postFilters(array) {
    fetch('/assets/php/ajaxProjects.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ filters: array })
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("id-project-content").innerHTML = data;
    })
    .catch(error => {
        console.log("Erreur AJAX POST (filtres)", error);
    });
}

// Fonction AJAX pour envoyer la recherche (GET)
function getSearch(string) {
    const searchParam = string ? '&search=' + encodeURIComponent(string) : '';
    const url = '/assets/php/ajaxProjects.php?1' + searchParam; 

    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.text())
    .then(data => {
        console.log("Search response: ", data);
        document.getElementById("id-project-content").innerHTML = data;
    })
    .catch(error => {
        console.log("Erreur AJAX GET (recherche)", error);
    });
}

// Lors du chargement de la page, effectuer une requête avec les valeurs initiales des filtres
document.addEventListener('DOMContentLoaded', () => {
    postFilters(selectedValues);  // Par défaut, envoie les filtres avec `POST`
});

// Récupération des checkboxes et ajout des écouteurs d'événements pour `POST` (filtres)
const checkboxs = document.querySelectorAll("input.box.filter");
checkboxs.forEach(element => {
    element.addEventListener('change', function() {
        // Réinitialiser selectedValues à chaque changement pour éviter les doublons
        selectedValues = [];

        // Ajouter les valeurs des checkboxes sélectionnées dans selectedValues
        checkboxs.forEach(box => {
            if (box.checked) {
                selectedValues.push(box.value);
            }
        });

        // Appeler la fonction AJAX pour envoyer les filtres avec `POST`
        postFilters(selectedValues);
    });
});

// Gestion de la recherche (champ de texte) pour `GET`
const textButton = document.getElementById("keyword-button");
textButton.addEventListener('click', (event) => {
    // Empêcher le formulaire de se soumettre normalement
    event.preventDefault();

    // Récupérer le terme de recherche
    wrotePattern = document.getElementById("keyword").value;

    // Appeler la fonction AJAX pour la recherche avec `GET`
    getSearch(wrotePattern);
});

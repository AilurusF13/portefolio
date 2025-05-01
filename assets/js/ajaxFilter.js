let selectedValues = [];
let wrotePattern = "";

const fadeTime= 500 ;

// Fonction AJAX pour envoyer les filtres (POST)
function postFilters(array) {
    targetZone = document.getElementById("id-project-content") ;
    targetZone.style.opacity = "0" ;
    targetZone.style.transform = "translateY(-100px)";

    fetch('/assets/php/ajaxProjects.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ filters: array })
    })
    .then(response => response.text())
    .then(data => {

        setTimeout(() => {
            targetZone.innerHTML = data;
            targetZone.style.opacity = "1";
            targetZone.style.transform = "translateY(0)";
        }, fadeTime);

    })
    .catch(error => {
        console.log("Erreur AJAX POST (filtres)", error);
    });
}

// Fonction AJAX pour envoyer la recherche (GET)
function getSearch(string) {
    targetZone = document.getElementById("id-project-content") ;
    targetZone.style.opacity = "0" ;
    targetZone.style.transform = "translateY(-100px)";

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
        setTimeout( () => {
            targetZone.innerHTML = data;
            targetZone.style.opacity = "1"
            targetZone.style.transform = "translateY(0)";
        }, fadeTime) ;

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
const searchInput = document.getElementById("keyword");
const searchBar = document.querySelector('.search-bar') ;
searchInput.addEventListener("input", () => {
    if (searchInput.value.length > 15) {
        let length = searchInput.value.length;
        let redIntensity = Math.min(length * 8, 255); // Augmente progressivement jusqu'à rouge

        searchBar.style.border = `solid 1px rgb(${redIntensity}, 0, 0)`;
        if (searchInput.value.length > 30) {
            searchInput.style.color = 'red';
        }else{
            searchInput.style.color = 'black';
        }
    } else {
        searchBar.style.border = `solid 1px rgb(0, 0, 0)`
    }
});
textButton.addEventListener('click', (event) => {
    // Empêcher le formulaire de se soumettre normalement
    event.preventDefault();

    // Récupérer le terme de recherche
    wrotePattern = document.getElementById("keyword").value;

    // verifier la longeur de wrote pattern pour des raisons de sécurité
    if (wrotePattern.length > 30 || wrotePattern == '') {
        searchInput.value = "";

        // Stocker le placeholder original
        let originalPlaceholder = searchInput.placeholder;

        // Modifier temporairement le placeholder
        searchInput.placeholder = "...!";
        searchBar.style.border = `solid 1px rgb(0, 0, 0)`
        searchInput.style.color = 'black';

        // Reset après 3 secondes
        setTimeout(() => {
            searchInput.placeholder = originalPlaceholder;

        }, 3000);

        wrotePattern = '';
    }

    getSearch(wrotePattern);
});


function toggleVisibility(buttonId, displayId) {
    let button = document.getElementById(buttonId);
    let element = document.getElementById(displayId);

    button.addEventListener('click', () => {
        element.classList.toggle('hidden'); // Alterne la classe CSS
        element.classList.toggle('visible'); // Alterne la classe CSS
    });
}

// Active la fonction sur les boutons
toggleVisibility('more-button', 'more-sec');
toggleVisibility('filter-button', 'filter-sec');

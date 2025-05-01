const sliderViewport = document.querySelector('.slider-viewport');
const sliderItems = document.querySelectorAll('.slider-item');
const nextButton = document.querySelector('.slider-button.right');
const prevButton = document.querySelector('.slider-button.left');
let currentIndex = 0; // Index de l'élément visible
const totalItems = sliderItems.length; // Nombre total d'éléments dans le slider

function updateSlider() {
    const translateValue = -currentIndex * 100; // Déplace la viewport
    sliderViewport.style.transform = `translateX(${translateValue}%)`;
}

// Bouton "Suivant"
function incrementCurrent(){
    currentIndex = (currentIndex+1) % totalItems ;
}
function decrementCurrent(){
    currentIndex = (currentIndex - 1 + totalItems) % totalItems; // Passe au dernier élément après le premier
}
nextButton.addEventListener('click', () => {
    console.log("next clicked") ;
    incrementCurrent();
    updateSlider();
});

// Bouton "Précédent"
prevButton.addEventListener('click', () => {
    decrementCurrent()
    updateSlider();
});

// Slider avec pad ou souris
let minMovement = 30
let startX = 0;
let startY = 0;
let isSwiping = false; // Pour indiquer si c'est un swipe ou un clic classique

sliderItems.forEach((item) => {
    item.addEventListener('mousedown', (e) => {
        e.preventDefault();  // Empêche la sélection de texte ou autre comportement par défaut
        startX = e.clientX;
        startY = e.clientY;
        isSwiping = false;  // Réinitialiser à chaque début de mouvement
    });

    item.addEventListener('click', (e) => {
        // Si on détecte un glissement, le clic doit être annulé
        if (isSwiping) {
            e.preventDefault();
        }
    });
});

sliderViewport.addEventListener('mousedown', (e) => {
    startX = e.clientX; // Pour PC (souris)
    isSwiping = false;  // Réinitialise pour le démarrage d'un swipe
});

sliderViewport.addEventListener('touchstart', (e) => {
    if (e.touches.length > 1 ) return ; // pas de swip si on veut zoomer
    startX = e.touches[0].clientX; // Pour mobile (tactile)
    startY = e.touches[0].clientY; // Pour mobile (tactile)
    isSwiping = false;  // Réinitialise pour le démarrage d'un swipe
});

sliderViewport.addEventListener('mouseup', (e) => {
    const endX = e.clientX;
    handleSwipe(startX, endX);
});

sliderViewport.addEventListener('touchend', (e) => {
    const endX = e.changedTouches[0].clientX;
    handleSwipe(startX, endX);
});

function handleSwipe(startX, endX) {
    const deltaX = endX - startX;
    if (Math.abs(deltaX) > minMovement) { // Si un swipe significatif est détecté
        isSwiping = true; // Indiquer qu'on a fait un swipe
        if (deltaX < 0) {
            incrementCurrent(); // Glissement vers la gauche
        } else if (deltaX > 0) {
            decrementCurrent(); // Glissement vers la droite
        }
        updateSlider();
    }
}

// test de la maximisation du slider

let slidermaxi = document.querySelector(".slider-maximisable");
let maxStartX = 0;
let maxStartY = 0;
let maxMoved = false;

if (slidermaxi != null) {
    slidermaxi.addEventListener("mousedown", (e) => {
        maxStartX = e.clientX;
        maxStartY = e.clientY;
        maxMoved = false;
    });

    slidermaxi.addEventListener("mousemove", (e) => {
        const dx = Math.abs(e.clientX - maxStartX);
        const dy = Math.abs(e.clientY - maxStartY);
        if (dx > minMovement || dy > minMovement) {
            maxMoved = true;
        }
    });

    slidermaxi.addEventListener("mouseup", (e) => {
        if (!maxMoved) {
            slidermaxi.classList.toggle("fullscreen-slider");
        }
    });
}

setInterval(() => {
    if (slidermaxi != null && !slidermaxi.classList.contains("fullscreen-slider")){
        incrementCurrent() ;
        updateSlider() ;
    }
}, 15000); // Toutes les 15 secondes

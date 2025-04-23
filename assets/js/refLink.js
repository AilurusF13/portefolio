document.addEventListener("DOMContentLoaded", () => {
    let refLink = document.querySelector('.more-link');
    refLink.addEventListener('click', () => {
        document.getElementById('more-sec').classList.remove('hidden');
        document.getElementById('more-sec').classList.add('visible');
    }) ;

    // Active la fonction sur les boutons
})
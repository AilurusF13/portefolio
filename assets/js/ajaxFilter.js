// recuperer les id des checkbox zvevc query slector


function ajaxRequest(array){
        fetch('/assets/php/ajaxProjects.php', {
                method:'POST',
                headers:{
                        'Content-Type' : 'application/json',
                },
                body: JSON.stringify({filters: array})
        })
        .then(response => response.text())
        .then(data =>  {
                console.log(data) ;
                document.getElementById("id-project-content").innerHTML = data ;
        })
        .catch(error => {
                console.log("Erreur AJAX", error) ;
        }) ;
}

document.addEventListener('DOMContentLoaded',()=>{
        ajaxRequest([]) ;
})

const checkboxs = document.querySelectorAll("input.box.filter");
checkboxs.forEach(element => {
        element.addEventListener('change', function() {
                let selectedValues = [] ;
                checkboxs.forEach(box => {
                if (box.checked)
                        selectedValues.push(box.value) ; 
                }) ;
                // envoyer le tableau en ajax
                ajaxRequest(selectedValues) ;
        });
});

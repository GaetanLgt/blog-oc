console.log('Hello World');

// complete le champ #content avec le contenu de la variable content
let content = document.getElementById('content');

// au changement de valeur du champ content, console.log "coucou"
content.addEventListener('change', function() {
    console.log('coucou');
}
);
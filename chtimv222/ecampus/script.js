
function getHint(labId) {
    // Afficher un message de chargement dans l'élément avec l'ID "hint-text" pendant que l'indice est récupéré
    document.getElementById("hint-text").innerText = "Chargement...";
    
    // Afficher la boîte modale (élément avec l'ID "modal") en utilisant display: "flex" pour la rendre visible
    document.getElementById("modal").style.display = "flex";

    // Utiliser fetch pour envoyer une requête POST à "get_hint.php" pour récupérer l'indice du lab
    fetch('get_hint.php', {
        method: 'POST',  // Spécifier la méthode POST pour envoyer des données au serveur
        headers: {
            'Content-Type': 'application/json'  // Indiquer que les données envoyées sont au format JSON
        },
        body: JSON.stringify({ lab_id: labId })  // Envoyer l'ID du lab sous forme de JSON dans le corps de la requête
    })
    // Lorsque la réponse est reçue, la convertir en JSON pour la traiter (on suppose que get_hint.php renvoie du JSON)
    .then(response => response.json())
    
    // Lorsque les données JSON sont prêtes, les utiliser pour mettre à jour le texte de l'élément "hint-text"
    .then(data => {
        document.getElementById("hint-text").innerText = data.hint;  // Mettre à jour l'élément avec l'indice récupéré
    })
    
    // Si une erreur survient pendant la requête, afficher un message d'erreur et logguer l'erreur dans la console
    .catch(error => {
        document.getElementById("hint-text").innerText = "Erreur lors de la récupération de l'indice.";  // Afficher un message d'erreur à l'utilisateur
        console.error('Erreur:', error);  // Afficher l'erreur dans la console pour aider au débogage
    });
}


function getSolution(labId) {
    // Afficher un message de chargement pendant la récupération de l'indice
    document.getElementById("hint-text").innerText = "Chargement...";
    document.getElementById("modal").style.display = "flex";

    // Utiliser fetch pour récupérer l'indice depuis get_hint.php
    fetch('get_solution.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ lab_id: labId })
    })
    .then(response => response.json())  // On suppose que get_hint.php renvoie du JSON
    .then(data => {
        // Mettre à jour la boîte de dialogue avec l'indice reçu
        document.getElementById("hint-text").innerText = data.solution;
    })
    .catch(error => {
        // Afficher un message d'erreur en cas de problème
        document.getElementById("hint-text").innerText = "Erreur lors de la récupération de l'indice.";
        console.error('Erreur:', error);
    });
    
}

function closeModal() {
    // Fermer la boîte de dialogue
    document.getElementById("modal").style.display = "none";
} 


document.getElementById('rechercheInput').addEventListener('keydown', function(event) {
    const rechercheInput = document.getElementById('rechercheInput').value;
    const envoieMail = document.getElementById('envoieMail');

    // Commande que l'on cherche
    const command = "ang";

    // Vérifier si la touche "Entrée" est pressée
    if (event.key === 'Enter') {
        event.preventDefault(); // Empêche le rechargement ou le comportement par défaut du formulaire

        if (rechercheInput === command) {
            envoieMail.style.display = 'block'; // Affiche le formulaire
        } else {
            envoieMail.style.display = 'none'; // Cache le formulaire
        }
    }
});

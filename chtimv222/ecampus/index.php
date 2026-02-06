<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCampus</title>
    <link rel="stylesheet" href="stylesIndex.css">
    <script src="script.js"></script>
</head>
<body>
<?php include('db.php'); ?>
<?php include('header.php'); ?>

<div class="container">
    <h1>Tableau de bord</h1> 
    
    <div class="cours-recent">
        <h2>Cours récents</h2>
        <?php
        try {
            $stmt = $conn->query('SELECT * FROM cours LIMIT 3'); 
            while ($row = $stmt->fetch_assoc()) {
                $titre = isset($row['nom']) ? $row['nom'] : 'Titre sans nom';
                $ressource = isset($row['ressource']) ? $row['ressource'] : 'Ressource inconnue';
                $universite = isset($row['universite']) ? $row['universite'] : 'Université inconnue';
                
                $titre_ressource = $titre . '_(' . $ressource . ')';
        
                echo '<div class="cours-card">';
                echo '<img src="' . $row['image_ressource'] . '" alt="' . $titre_ressource . '">';
                echo '<div class="cours-info">';
                echo '<div class="cours-title">' . $titre_ressource . '</div>';
                echo '<div class="cours-university">' . $universite . '</div>';
                echo '</div>';
                echo '</div>'; 
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo 'Erreur de base de données : ' . $e->getMessage();
        }
        ?>
    </div>
    
    <h2>Mes cours</h2>

    <div class="recherche-container">
        <div class="barre2recherche">
            <form id="rechercheForm" method="GET">
                <input type="text" id="rechercheInput" name="rechercheInput" placeholder="Rechercher">
            </form>
        </div>

        <div class="envoie-mail" id="envoieMail" style="display: none;">
            <button onclick="LancerBot()" style="background-color: red; color: white;border-radius: 5px">
                Envoie d'un mail de phishing à bastien.bechadergue@uvsq.fr
            </button>
        </div>

        <script>
                function LancerBot() {
                    fetch("executer_script.php")  // Le nom du fichier PHP que vous avez créé
                        .then(response => response.text())
                        .then(data => {
                            console.log(data);  // Affiche la sortie dans la console
                        })
                        .catch(error => console.error("Erreur:", error));
                }
        </script>

    </div>


    <div class="cours-grid">
    <?php 
    if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["rechercheInput"])) {
        $search = $_GET["rechercheInput"];

        
        // Afficher le champ d'envoi d'email si adresse mail de Bastien Bechadergue rentrée
        if ($search == 'bastien.bechadergue@uvsq.fr') {
            echo '<script>document.getElementById("envoieMail").style.display = "block";</script>';
        }

        try {
            // Exécute la requête
            $stmt = $conn->query("SELECT nom, ressource, universite, image_ressource FROM cours WHERE nom LIKE '%$search%'");
            
            if (empty($stmt)) {
                echo 'Aucun cours trouvé';
            }  
            // Vérifie si la requête a renvoyé des résultats
            if ($stmt !== false) {
                $num_rows = $stmt->num_rows; // Compte le nombre de résultats
                
                
                // Affiche les résultats
                if ($num_rows > 0) {
                    while ($row = $stmt->fetch_assoc()) {
                        $titre = $row['nom'];
                        $ressource = $row['ressource'];
                        $universite = $row['universite'];
                        
                        $titre_ressource = $titre . '_(' . $ressource . ')';
                        
                        echo '<div class="cours-card">';
                        echo '<img src="' . $row['image_ressource'] . '" alt="' . $titre_ressource . '">';
                        echo '<div class="cours-info">';
                        echo '<div class="cours-title">' . $titre_ressource . '</div>';
                        echo '<div class="cours-university">' . $universite . '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo'<p> Aucun cours trouvé </p>';
                }
            }
        } catch (Exception $e) {
            // Renvoyer un code d'état 400 si une erreur de base de données se produit
            http_response_code(400);
            echo 'Aucun cours trouvé';
        }
    } else {
            try {
                $stmt = $conn->query('SELECT nom, ressource, universite, image_ressource FROM cours');
                while ($row = $stmt->fetch_assoc()) {
                    $titre = $row['nom'];
                    $ressource = $row['ressource'];
                    $universite = $row['universite'];
                    
                    $titre_ressource = $titre . '_(' . $ressource . ')';
                    
                    echo '<div class="cours-card">';
                    echo '<img src="' . $row['image_ressource'] . '" alt="' . $titre_ressource . '">';
                    echo '<div class="cours-info">';
                    echo '<div class="cours-title">' . $titre_ressource . '</div>';
                    echo '<div class="cours-university">' . $universite . '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } catch (Exception $e) {
                // Renvoyer un code d'état 400 si une erreur de base de données se produit
                http_response_code(400);
                echo 'Erreur de base de données : ' . $e->getMessage();
            }
        }
        ?>
    </div>
</div>

        <?php include('footer.php'); ?>

    <!-- Boutons pour Indice et Solution -->
    <button class="indice-button">Indice</button>
    <button class="solution-button">Solution</button>

    <!-- Modale pour Indice -->
    <div id="modalIndice" class="modal">
        <div class="modal-content">
            <span class="close" id="closeIndice"></span>
            <p>INDICE : </br> Table : professeurs / colonne : email</p>
        </div>
    </div>

    <!-- Modale pour Solution -->
    <div id="modalSolution" class="modal">
        <div class="modal-content">
            <span class="close" id="closeSolution"></span>
            <p>SOLUTION : </br> Entrez la requete “ s' UNION SELECT email,1,2,3 FROM professeurs -- “</p>
        </div>
    </div>

    <script>
        // Sélection des éléments
        var modalIndice = document.getElementById("modalIndice");
        var modalSolution = document.getElementById("modalSolution");
        var btnIndice = document.querySelector(".indice-button");
        var btnSolution = document.querySelector(".solution-button");
        var spanCloseIndice = document.getElementById("closeIndice");
        var spanCloseSolution = document.getElementById("closeSolution");

        btnIndice.onclick = function() { modalIndice.style.display = "flex"; }
        btnSolution.onclick = function() { modalSolution.style.display = "flex"; }
        spanCloseIndice.onclick = function() { modalIndice.style.display = "none"; }
        spanCloseSolution.onclick = function() { modalSolution.style.display = "none"; }

        window.onclick = function(event) {
            if (event.target == modalIndice) modalIndice.style.display = "none";
            if (event.target == modalSolution) modalSolution.style.display = "none";
        }
    </script>

    </body>
</html>
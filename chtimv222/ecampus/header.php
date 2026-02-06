<?php
session_start();

if (!isset($_SESSION['ecampus']['loggedIn']) || $_SESSION['ecampus']['loggedIn'] !== true) {
    // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
    header("Location: page_connexion.php");
    exit();
}


$nom = isset($_SESSION['ecampus']['nom']) ? $_SESSION['ecampus']['nom'] : '';
$prenom = isset($_SESSION['ecampus']['prenom']) ? $_SESSION['ecampus']['prenom'] : '';

// Récupérer les initiales
$initialeNom = strtoupper(substr($nom, 0, 1));  // Première lettre du nom
$initialePrenom = strtoupper(substr($prenom, 0, 1));  // Première lettre du prénom

// Combiner les initiales
$initiales = $initialePrenom . $initialeNom;

?>  

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCampus Header</title>
    <link rel="stylesheet" href="stylesHeader.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="index.php">
                <img src="Image/ecampus.png">
            </a>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="index.php">Tableau de bord</a></li>
                <li>
                    <a href="#">Outils <i class="fa-solid fa-chevron-down"></i></a>
                    <ul class="dropdown">
                        <li><a href='../site/index.php'>Forum</a></li>
                        <li><a href='../pjweb/index.php'>Inscription administrative</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Informations Piratage <i class="fa-solid fa-chevron-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="#">Procédure pour les enseignants de l'Université</a></li>
                        <li><a href="#">FAQ sur le site de l'Université</a></li>
                    </ul>
                </li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <div class="header-right">
            <div class="icons">
                <span class="icon"><i class="fa-regular fa-bell"></i></span>
                <span class="icon"><i class="fa-regular fa-comment-dots"></i></span>
                <?php if (isset($_SESSION['ecampus']['loggedIn']) && $_SESSION['ecampus']['loggedIn']): ?>
                    <span class="profile-initials"><?= $initiales ?></span>
                <?php endif; ?>
            </div>
        </div>
    </header>
</body>
</html>

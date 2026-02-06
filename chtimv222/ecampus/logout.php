<?php 
session_start();
unset($_SESSION['ecampus']);
// Redirection vers la page d'accueil après déconnexion
header('Location: page_connexion.php');
exit;
?>
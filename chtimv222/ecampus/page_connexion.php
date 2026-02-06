<?php
session_start();
include('db.php'); // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];  
    $password = $_POST['password'];

    $query = "SELECT email, password, role, nom, prenom FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        echo "<p style='color: red;'>Utilisateur non trouvé.</p>";
    } else {
        if ($password === $user['password']) {
            $_SESSION['ecampus'] = [
                'loggedIn' => true,
                'email' => $user['email'],
                'role' => $user['role'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom']
            ];
            // Redirection en fonction du rôle
            if ($user['role'] === 'student') {
                header('Location: index.php');
            } elseif ($user['role'] === 'teacher') {
                header('Location: admin_eCampus.php');
            } elseif ($user['role'] === 'admin') {
                header('Location: administration.php');
            }
            exit();
        } else {
            echo "<p style='color: red;'>Mot de passe incorrect.</p>";
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion - UVSQ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="header">
                <img src="Image/logo_uvsq.png" alt="Logo UVSQ" class="logo">
                <h2>Connexion</h2>
            </div>
            <form action="" method="post">
                <div class="input-group">
                    <label for="email">Identifiant :</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="submit" class="login-btn">SE CONNECTER</button>
            </form>
            <div class="additional-links">
                <p><a href="page_inscription.html">Vous n'avez pas de compte, CRÉEZ EN UN !!</a></p>
            </div>
        </div>
    </div>
    <button class="fixed-button" onclick="window.location.href='/votre-page.php'">Indice</button>
    <button class="solution-button" onclick="window.location.href='/votre-page.php'">Solution</button>

</body>
</html>

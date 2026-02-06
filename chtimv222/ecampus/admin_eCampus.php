<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - eCampus</title>
    <link rel="stylesheet" href="admin.css">
    <script>
        // Fonction pour afficher le message d'erreur lorsqu'un lien est cliqué
        function showErrorMessage(event) {
            event.preventDefault(); // Empêche le comportement par défaut du lien
            alert("Les fichiers ne peuvent être téléchargés que dans la base de données.");
        }

        // Attache la fonction aux liens des fichiers sensibles après le chargement de la page
        window.onload = function() {
            document.getElementById('rapport-financier').addEventListener('click', showErrorMessage);
            document.getElementById('emails-corruption').addEventListener('click', showErrorMessage);
            document.getElementById('resultats-modifies').addEventListener('click', showErrorMessage);
            document.getElementById('dossier-budget').addEventListener('click', showErrorMessage);
        };
    </script>
</head>
<body>
    <header>
        <h1>eCampus - Page Professeur</h1>
        <nav>
            <ul>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="user-management">
            <h2>Gestion des utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>User1</td>
                        <td>user1@ecampus.com</td>
                        <td>Étudiant</td>
                        <td>Actif</td>
                    </tr>
                    <tr>
                        <td>User2</td>
                        <td>user2@ecampus.com</td>
                        <td>Enseignant</td>
                        <td>Inactif</td>
                    </tr>
                    <tr>
                        <td>Admin1</td>
                        <td>admin1@ecampus.com</td>
                        <td>Administrateur</td>
                        <td>Actif</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="logs">
            <h2>Journal d'activités</h2>
            <ul>
                <li>[14h32] Connexion de l'administrateur Admin1</li>
                <li>[13h45] Modification des résultats d'examens</li>
                <li>[12h10] Falsification de documents administratifs</li>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 eCampus</p>
    </footer>
</body>
</html>

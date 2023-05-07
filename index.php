<?php
/**
 * Ce fichier est le script de la page de connexion et d'inscription pour les utilisateurs.
 * Il gère l'affichage du formulaire d'inscription et de connexion, et la gestion de la session utilisateur.
 * Il utilise le fichier 'account.php' pour les opérations liées au compte utilisateur.
 */
require 'account.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Styles CSS pour la mise en forme de la page -->
    <link rel="stylesheet" href="style.css">
    <!-- Charge les icônes Font Awesome -->
    <script src="https://kit.fontawesome.com/ba85ac3084.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <!-- Affiche les boutons de déconnexion et suppression du compte si l'utilisateur est connecté -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Bienvenue !</p>
            <!-- Formulaire pour se déconnecter -->
            <form action="" method="POST">
                <input type="submit" name="logout" value="Se deconnecter">
            </form>
            <!-- Formulaire pour supprimer le compte -->
            <form action="" method="POST">
                <input type="submit" name="delete" value="Supprimer mon compte">
            </form>
        <!-- Sinon, affiche les formulaires d'inscription et de connexion -->
        <?php else: ?>
            <h2>Creer un compte</h2>
            <!-- Formulaire d'inscription -->
            <form action="" method="POST">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" name="username" required>
                <label for="password">Mot de passe:</label>
                <div class="password-field">
                    <input type="password" name="password" id="register-password" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('register-password')">
                        <i class="fas fa-eye" id="register-eye-icon"></i>
                    </button>
                </div>
                <input type="submit" name="register" value="Créer un compte">
            </form>
            <!-- Séparateur entre les formulaires d'inscription et de connexion -->
            <div class="separator">
                <hr>
                <span>ou</span>
                <hr>
            </div>
            <h2>Se connecter</h2>
            <!-- Formulaire de connexion -->
            <form action="" method="POST">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" name="username" required>
                <label for="password">Mot de passe:</label>
                <div class="password-field">
                    <input type="password" name="password" id="login-password" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('login-password')">
                        <i class="fas fa-eye" id="login-eye-icon"></i>
                    </button>
                </div>
                <input type="submit" name="login" value="Connexion">
            </form>
        <?php endif; ?>
    </div>
    <!-- Script JavaScript pour basculer la visibilité du mot de passe -->
    <script>
        function togglePasswordVisibility(passwordFieldId) {
            const passwordField = document.getElementById(passwordFieldId);
            const eyeIcon = passwordField.nextElementSibling.firstElementChild;

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html> 
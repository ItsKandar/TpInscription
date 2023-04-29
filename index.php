<?php
session_start();
require 'config.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $salt = bin2hex(openssl_random_pseudo_bytes(32));
    $hashed_password = hash('sha256', $password . $salt);

    $stmt = $conn->prepare("INSERT INTO users (username, password, salt) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $salt);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, salt FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password, $salt);
    $stmt->fetch();

    if (hash('sha256', $password . $salt) == $hashed_password) {
        $_SESSION['user_id'] = $id;
    }
    $stmt->close();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
}

if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    session_destroy();
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            padding: 20px;
            width: 400px;
        }
        h2 {
            margin: 0 0 15px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"] {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 3px;
            color: white;
            cursor: pointer;
            margin-bottom: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .separator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .separator hr {
            width: 45%;
            margin: 10px 0;
        }
        .toggle-password {
            background: none;
            border: none;
            color: black;
            cursor: pointer;
            font-size: 0.8em;
            padding-left: 5px;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .password-field {
            position: relative;
        }
        input[id="register-password"], input[id="login-password"] {
            width: 90%;
            box-sizing: border-box;
        }
    </style>
    <script src="https://kit.fontawesome.com/ba85ac3084.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Bienvenue !</p>
            <form action="" method="POST">
                <input type="submit" name="logout" value="Se deconnecter">
            </form>
            <form action="" method="POST">
                <input type="submit" name="delete" value="Supprimer mon compte">
            </form>
        <?php else: ?>
            <h2>Creer un compte</h2>
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
            <div class="separator">
                <hr>
                <span>ou</span>
                <hr>
            </div>
            <h2>Se connecter</h2>
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
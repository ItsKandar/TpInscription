<?php
/**
 * Ce script permet aux utilisateurs de s'inscrire, se connecter, se déconnecter et supprimer leurs comptes.
 * Il utilise PHP, MySQL et des requêtes préparées pour garantir la sécurité.
 */

// Démarre une session pour stocker les données utilisateur
session_start();
// Inclut le fichier de configuration de la base de données
require 'config.php';

// Vérifie si une demande d'inscription a été soumise
if (isset($_POST['register'])) {
    // Obtiens le nom d'utilisateur et le mot de passe soumis
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Génère un sel aléatoire pour hacher le mot de passe
    $salt = bin2hex(openssl_random_pseudo_bytes(32));
    // Hache le mot de passe en utilisant le sel généré
    $hashed_password = hash('sha256', $password . $salt);

    // Prépare une requête SQL pour insérer le nouvel utilisateur dans la base de données
    $stmt = $conn->prepare("INSERT INTO users (username, password, salt) VALUES (?, ?, ?)");
    // Lie les paramètres à la requête
    $stmt->bind_param("sss", $username, $hashed_password, $salt);
    // Exécute la requête
    $stmt->execute();
    // Ferme la requête
    $stmt->close();
}

// Vérifie si une demande de connexion a été soumise
if (isset($_POST['login'])) {
    // Obtiens le nom d'utilisateur et le mot de passe soumis
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prépare une requête SQL pour obtenir les données de l'utilisateur
    $stmt = $conn->prepare("SELECT id, password, salt FROM users WHERE username = ?");
    // Lie le paramètre à la requête
    $stmt->bind_param("s", $username);
    // Exécute la requête
    $stmt->execute();
    // Lie les variables de résultat à la requête
    $stmt->bind_result($id, $hashed_password, $salt);
    // Récupère les données de l'utilisateur
    $stmt->fetch();

    // Vérifie si le mot de passe soumis correspond au mot de passe haché stocké
    if (hash('sha256', $password . $salt) == $hashed_password) {
        // Définit l'ID utilisateur dans la session
        $_SESSION['user_id'] = $id;
    }
    // Ferme la requête
    $stmt->close();
}

// Vérifie si une demande de déconnexion a été soumise
if (isset($_POST['logout'])) {
    // Détruit la session
    session_destroy();
    // Redirige vers la page d'index
    header("Location: index.php");
}

// Vérifie si une demande de suppression a été soumise
if (isset($_POST['delete'])) {
    // Prépare une requête SQL pour supprimer l'utilisateur de la base de données
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    // Lie le paramètre à la requête
    $stmt->bind_param("i", $_SESSION['user_id']);
    // Exécute la requête
    $stmt->execute();
    // Ferme la requête
    $stmt->close();
    // Détruit la session
    session_destroy();
    // Redirige vers la page d'index
    header("Location: index.php");
    }
?>
<?php
/**
 * Ce fichier établit une connexion à la base de données 'users' en utilisant les identifiants fournis.
 * Il utilise l'extension MySQLi pour créer une nouvelle instance de connexion et vérifier les erreurs de connexion.
 */

// Nom du serveur de base de données
$servername = "localhost";
// Nom d'utilisateur pour accéder à la base de données
$username = "root";
// Mot de passe pour accéder à la base de données
$password = "";
// Nom de la base de données
$dbname = "users";

// Crée une nouvelle connexion à la base de données en utilisant les informations fournies
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie si la connexion a échoué
if ($conn->connect_error) {
    // Arrête le script et affiche un message d'erreur
    die("Échec de la connexion: " . $conn->connect_error);
}
?>
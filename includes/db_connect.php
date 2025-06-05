<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Paramètres de connexion à la base de données
$db_host = '91.216.107.162'; // A modifier selon votre hébergement
$db_user = 'osour2189855';   // A modifier selon votre hébergement
$db_pass = 'kU8!fVfVK1TPq8W'; // A modifier selon votre hébergement
$db_name = 'osour2189855_9keggg'; // A modifier selon votre hébergement

try {
    // Création de la connexion avec gestion des erreurs
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Vérification de la connexion
    if ($conn->connect_error) {
        throw new Exception("Erreur de connexion à la base de données: " . $conn->connect_error);
    }

    // Définir l'encodage UTF-8
    if (!$conn->set_charset("utf8mb4")) {
        throw new Exception("Erreur lors de la configuration du jeu de caractères utf8mb4: " . $conn->error);
    }
} catch (Exception $e) {
    // Log l'erreur et affiche un message utilisateur
    error_log($e->getMessage());
    die("Une erreur est survenue lors de la connexion à la base de données. Veuillez contacter l'administrateur.");
}
<?php
// Paramètres de connexion à la base de données
$db_host = '91.216.107.162'; // A modifier selon votre hébergement
$db_user = 'osour2189855';      // A modifier selon votre hébergement
$db_pass = 'kU8!fVfVK1TPq8W';          // A modifier selon votre hébergement
$db_name = 'osour2189855_9keggg';   // A modifier selon votre hébergement

// Création de la connexion
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données: " . $conn->connect_error);
}

// Définir l'encodage UTF-8
$conn->set_charset("utf8");
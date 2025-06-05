<?php
// Mot de passe à hacher
$password = 'admin';

// Utilisation de la méthode de hachage bcrypt avec un coût de 10
$hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);

// Affichage du mot de passe haché
echo "Mot de passe haché: " . $hashedPassword;
?>
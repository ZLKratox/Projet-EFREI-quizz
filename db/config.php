<?php
// config.php

// Le serveur de base de données MySQL (généralement 'localhost')
$servername = "localhost";

// Votre nom d'utilisateur de base de données MySQL
$username = "root";

// Votre mot de passe de base de données MySQL
$password = "mathis*3310";

// Le nom de votre base de données MySQL
$dbname = "projet";

// Vous pouvez également définir une option pour afficher ou non les erreurs PHP pour le débogage
// Pour les environnements de production, il est généralement recommandé de les désactiver
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vous pouvez utiliser ces variables pour vous connecter à votre base de données
// dans vos autres scripts PHP en incluant ce fichier config.php
?>

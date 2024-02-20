<?php
$servername = "127.0.0.1:3306";
$username = "root";
$password = "PROJETHTML";
$dbname = "PROJET";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else {
    echo "Connected successfully";
}
?>

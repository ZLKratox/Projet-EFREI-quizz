<?php
$servername = "127.0.0.1:3306";
$username = "root";
$password = "PROJETHTML";
$dbname = "PROJET";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully"; // Cette ligne doit être commentée ou supprimée
?>
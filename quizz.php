<?php
include 'db.php';

$user_id = 1; // Assurez-vous que cet ID existe dans votre table users
$quiz_title = $conn->real_escape_string("Titre du Quiz");

$sql = "INSERT INTO quizzes (user_id, quiz_title) VALUES ($user_id, '$quiz_title')";

if ($conn->query($sql) === TRUE) {
    echo "Nouveau quiz créé avec succès";
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

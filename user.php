<?php
include 'db.php';

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez les données du formulaire
    $username = $conn->real_escape_string($_POST['username']);

    // Préparez et exécutez la requête SQL
    $sql = "INSERT INTO users (username) VALUES ('$username')";

    if ($conn->query($sql) === TRUE) {
        echo "Nouvel utilisateur créé avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }

    // Fermez la connexion
    $conn->close();
} else {
    echo "Méthode HTTP non autorisée.";
}
?>
<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "mathis*3310";
$dbname = "projet";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le champ du pseudonyme est défini
    if (isset($_POST["username"])) {
        // Récupérer le pseudonyme saisi dans le formulaire
        $username = $_POST["username"];

        // Vérifier si le pseudonyme existe déjà dans la base de données
        $sql_check = "SELECT * FROM users WHERE username = '$username'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            // Nom d'utilisateur déjà pris, afficher un message d'erreur
            echo "Ce pseudonyme est déjà pris, veuillez en choisir un autre.";
            echo "<script>setTimeout(function(){ window.location.href = 'login.html'; }, 2000);</script>";
        } else {
            // Insérer le nouvel utilisateur dans la base de données
            $sql_insert = "INSERT INTO users (username) VALUES ('$username')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "Inscription réussie, vous pouvez maintenant vous connecter. <br>Vous serez redirigé vers la page de connexion dans 2 secondes...";
                echo "<script>setTimeout(function(){ window.location.href = 'login.html'; }, 2000);</script>";
            } else {
                echo "Erreur lors de l'inscription : " . $conn->error;
            }
        }
    } else {
        // Champ du pseudonyme non défini, afficher un message d'erreur
        echo "Veuillez saisir un pseudonyme";
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>

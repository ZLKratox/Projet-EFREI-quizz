<?php
$servername = "localhost";
$username = "root";
$password = "efrei";
$dbname = "quizz-efrei";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Exemple de requête
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Renvoyer les données au format JSON
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
echo json_encode($data);

$conn->close();

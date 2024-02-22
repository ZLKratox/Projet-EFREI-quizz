<?php
include 'db.php';
session_start(); // Démarre ou reprend une session PHP

header("Access-Control-Allow-Origin: http://localhost");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Récupération des données POST
$user_id = isset($_POST['userId']) ? $conn->real_escape_string($_POST['userId']) : null;
$quiz_title = isset($_POST['quizTitle']) ? $conn->real_escape_string($_POST['quizTitle']) : null;

// Vérifie d'abord si un quiz avec le même titre existe déjà pour cet utilisateur
$sql_check = "SELECT quiz_id FROM quizzes WHERE user_id = '$user_id' AND quiz_title = '$quiz_title'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    // Le quiz existe déjà, récupère son quiz_id
    $row = $result->fetch_assoc();
    $response = ['action' => 'open', 'quiz_id' => $row['quiz_id']];
} else {
    // Le quiz n'existe pas, crée un nouveau quiz
    $sql = "INSERT INTO quizzes (user_id, quiz_title, created_at) VALUES ('$user_id', '$quiz_title', NOW())";
    if ($conn->query($sql) === TRUE) {
        $quiz_id = $conn->insert_id;
        $response = ['action' => 'create', 'quiz_id' => $quiz_id];
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
        exit();
    }
}

$conn->close();

// Retourne une réponse JSON
header('Content-Type: application/json');
echo json_encode($response);
?>

<?php
include 'db.php';
session_start(); // Démarre ou reprend une session PHP

header("Access-Control-Allow-Origin: http://localhost");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Assurez-vous que la requête est une requête POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId']) && isset($_POST['quizTitle'])) {
    $userId = $conn->real_escape_string($_POST['userId']);
    $quizTitle = $conn->real_escape_string($_POST['quizTitle']);

    // Vérifiez si un quiz avec le même titre existe déjà pour cet utilisateur
    $sql_check = "SELECT quiz_id FROM quizzes WHERE user_id = '$userId' AND quiz_title = '$quizTitle'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // Si le quiz existe, récupérez son ID et redirigez l'utilisateur vers la page du quiz
        $row = $result->fetch_assoc();
        $quizId = $row['quiz_id'];
        header("Location:creation_quizz.html?quiz_id=$quizId"); // Remplacez quiz_page.php par le chemin de votre page de quiz
        exit();
    } else {
        // Si le quiz n'existe pas, créez-le
        $sql_insert = "INSERT INTO quizzes (user_id, quiz_title, created_at) VALUES ('$userId', '$quizTitle', NOW())";
        if ($conn->query($sql_insert) === TRUE) {
            $quizId = $conn->insert_id;
            // Redirigez l'utilisateur vers la page pour ajouter des questions au nouveau quiz
            header("Location: add_questions.php?quiz_id=$quizId"); // Remplacez add_questions.php par le chemin de votre page d'ajout de questions
            exit();
        } else {
            echo "Erreur lors de la création du quiz: " . $conn->error;
        }
    }
    $conn->close();
} else {
    echo "Méthode HTTP non autorisée ou données manquantes.";
}
?>


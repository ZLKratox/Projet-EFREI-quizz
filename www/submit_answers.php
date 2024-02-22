<?php
include 'db.php';
session_start(); // Démarre ou reprend une session PHP

header("Access-Control-Allow-Origin: http://localhost");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['quiz_id'], $data['answers'])) {
    $quiz_id = $data['quiz_id'];
    foreach ($data['answers'] as $item) {
        $question_id = $item['question_id'];
        $answer_id = $item['answer_id'];

        // Vérifier si la réponse est correcte
        $isCorrectQuery = "SELECT is_correct FROM answers WHERE answer_id = '$answer_id'";
        $result = $conn->query($isCorrectQuery);
        $isCorrect = $result->fetch_assoc()['is_correct'];

        // Insérer la réponse de l'utilisateur
        $insertQuery = "INSERT INTO user_answers (question_id, answer_id, is_correct) VALUES ('$question_id', '$answer_id', '$isCorrect')";
        $conn->query($insertQuery);
    }

    echo json_encode(['success' => true, 'message' => 'Réponses soumises avec succès']);
} else {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
}

$conn->close();
?>

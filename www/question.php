<?php
include 'db.php';
session_start(); // Démarre ou reprend une session PHP

header("Access-Control-Allow-Origin: http://localhost");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['quiz_id'], $data['question_text'], $data['answers'], $data['correct_answer_index'])) {
    $quiz_id = $conn->real_escape_string($data['quiz_id']);
    $question_text = $conn->real_escape_string($data['question_text']);
    $correct_answer_index = $conn->real_escape_string($data['correct_answer_index']);
    
    $sql = "INSERT INTO questions (quiz_id, question_text) VALUES ('$quiz_id', '$question_text')";
    if($conn->query($sql) === TRUE) {
        $question_id = $conn->insert_id;
        $is_correct = FALSE;
        
        foreach($data['answers'] as $index => $answer_text) {
            $is_correct = ($index == $correct_answer_index) ? 'TRUE' : 'FALSE';
            $answer_text = $conn->real_escape_string($answer_text);
            $sql_answers = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES ('$question_id', '$answer_text', $is_correct)";
            $conn->query($sql_answers);
        }
        
        echo json_encode(["success" => "Question et réponses ajoutées avec succès"]);
    } else {
        echo json_encode(["error" => "Erreur lors de l'insertion de la question: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Données de formulaire incomplètes"]);
}
$conn->close();
?>

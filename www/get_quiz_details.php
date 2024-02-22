<?php
include 'db.php';
session_start(); // Démarre ou reprend une session PHP

header("Access-Control-Allow-Origin: http://localhost");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if(isset($_GET['quiz_title'])) {
    $quiz_title = $conn->real_escape_string($_GET['quiz_title']);

    // Trouvez l'ID du quiz basé sur le titre
    $quizQuery = "SELECT quiz_id FROM quizzes WHERE quiz_title = '$quiz_title'";
    $quizResult = $conn->query($quizQuery);

    if($quizResult->num_rows > 0) {
        $quizData = $quizResult->fetch_assoc();
        $quiz_id = $quizData['quiz_id'];

        // Récupérez les questions pour ce quiz
        $questionsQuery = "SELECT * FROM questions WHERE quiz_id = '$quiz_id'";
        $questionsResult = $conn->query($questionsQuery);
        $questions = [];

        while($row = $questionsResult->fetch_assoc()) {
            $question_id = $row['question_id'];
            $answersQuery = "SELECT * FROM answers WHERE question_id = '$question_id'";
            $answersResult = $conn->query($answersQuery);
            $answers = [];

            while($answerRow = $answersResult->fetch_assoc()) {
                $answers[] = $answerRow;
            }

            $row['answers'] = $answers;
            $questions[] = $row;
        }

        echo json_encode(['success' => true, 'questions' => $questions]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Quiz not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Quiz title not provided']);
}
$conn->close();
?>

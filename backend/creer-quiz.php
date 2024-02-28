<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => "Vous devez être connecté pour créer un quiz."]);
    exit;
}

$host = 'localhost';
$dbname = 'quiz_app';
$username = 'root';
$password = 'mathis*3310';

header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $quizTitle = $_POST['quiz_title'] ?? '';
        $questions = $_POST['questions'] ?? [];
        $answers = $_POST['answers'] ?? [];
        $correctAnswers = $_POST['correct'] ?? [];

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM quizzes WHERE title = ?");
        $stmt->execute([$quizTitle]);
        $quizExists = $stmt->fetchColumn() > 0;

        if ($quizExists) {
            echo json_encode(['success' => false, 'message' => 'Nom de quiz déjà utilisé.']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO quizzes (title, user_id) VALUES (?, ?)");
        $stmt->execute([$quizTitle, $_SESSION['user_id']]);
        $quizId = $pdo->lastInsertId();

        foreach ($questions as $index => $questionText) {
            $correctAnswerText = $answers[$index][$correctAnswers[$index] - 1];
            
            $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
            $stmt->execute([$quizId, $questionText]);
            $questionId = $pdo->lastInsertId();
        
            foreach ($answers[$index] as $answer) {
                $isCorrect = ($answer === $correctAnswerText) ? 1 : 0;
                $stmt = $pdo->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
                $stmt->execute([$questionId, $answer, $isCorrect]);
            }
        }

        echo json_encode(['success' => true, 'message' => 'Quiz créé avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
}
?>

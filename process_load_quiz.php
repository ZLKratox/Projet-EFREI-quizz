<?php
session_start();
include 'db/config.php';

$quizLoaded = false;
$questionsHTML = '';
$quiz_title = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quiz_title'])) {
    $quiz_title = $_POST['quiz_title'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    $stmt_quiz = $conn->prepare("SELECT quiz_id FROM quizzes WHERE quiz_title = ?");
    $stmt_quiz->bind_param("s", $quiz_title);
    $stmt_quiz->execute();
    $result_quiz = $stmt_quiz->get_result();

    if ($result_quiz->num_rows == 1) {
        $row_quiz = $result_quiz->fetch_assoc();
        $quiz_id = $row_quiz['quiz_id'];
        $quizLoaded = true;

        $stmt_questions = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ?");
        $stmt_questions->bind_param("i", $quiz_id);
        $stmt_questions->execute();
        $result_questions = $stmt_questions->get_result();

        while ($row_question = $result_questions->fetch_assoc()) {
            $questionsHTML .= "<div><p>" . htmlspecialchars($row_question['question_text']) . "</p>";
            
            $stmt_answers = $conn->prepare("SELECT * FROM answers WHERE question_id = ?");
            $stmt_answers->bind_param("i", $row_question['question_id']);
            $stmt_answers->execute();
            $result_answers = $stmt_answers->get_result();

            while ($row_answer = $result_answers->fetch_assoc()) {
                $questionsHTML .= "<input type='radio' name='answers[" . $row_question['question_id'] . "]' value='" . $row_answer['answer_id'] . "'>";
                $questionsHTML .= "<label for='answer_" . $row_question['question_id'] . "'>" . htmlspecialchars($row_answer['answer_text']) . "</label><br>";
            }
            $questionsHTML .= "</div>";
        }

    } else {
        $quiz_title = "Quiz non trouvé ou titre incorrect.";
    }
    $stmt_quiz->close();
    $conn->close();
} else {
    header("Location: load_quiz.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Quiz: <?php echo htmlspecialchars($quiz_title); ?></title>
</head>
<body>
    <!-- Navigation et autres éléments HTML ici -->

    <?php if ($quizLoaded): ?>
        <main class="container">
            <div class="grid">
                <section>
                    <h2>Questions du quiz: <?php echo htmlspecialchars($quiz_title); ?></h2>
                    <form action="submit_answers.php" method="post">
                        <?php echo $questionsHTML; ?>
                        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
                        <button type="submit" class="primary">Soumettre les réponses</button>
                    </form>
                </section>
            </div>
        </main>
    <?php else: ?>
        <p><?php echo $quiz_title; // Ici, $quiz_title contient le message d'erreur ?></p>
    <?php endif; ?>

        </section>
    </div>
</main>
<footer class="container">
    <small><a href="#">Politique de confidentialité</a> • <a href="#">Conditions d'utilisation</a></small>
</footer>
</body>
</html>
</body>
</html>

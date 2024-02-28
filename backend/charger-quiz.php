<?php
session_start();

$host_quiz_app = 'localhost';
$dbname_quiz_app = 'quiz_app';
$username_quiz_app = 'root';
$password_quiz_app = 'mathis*3310';

$host_quiz_responses = 'localhost';
$dbname_quiz_responses = 'quiz_responses';
$username_quiz_responses = 'root';
$password_quiz_responses = 'mathis*3310';

try {
    $pdo_quiz_app = new PDO("mysql:host=$host_quiz_app;dbname=$dbname_quiz_app;charset=utf8", $username_quiz_app, $password_quiz_app);
    $pdo_quiz_app->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo_quiz_responses = new PDO("mysql:host=$host_quiz_responses;dbname=$dbname_quiz_responses;charset=utf8", $username_quiz_responses, $password_quiz_responses);
    $pdo_quiz_responses->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quiz_title'])) {
        $quiz_title = $_POST['quiz_title'];

        $stmt_quiz = $pdo_quiz_app->prepare("SELECT id FROM quizzes WHERE title = ?");
        $stmt_quiz->execute([$quiz_title]);
        $quiz = $stmt_quiz->fetch(PDO::FETCH_ASSOC);

        if ($quiz) {
            $quiz_id = $quiz['id'];
            echo "<h3>Questions du Quiz: " . htmlspecialchars($quiz_title) . "</h3>";
            echo "<form id='submitQuizForm'>";
            echo "<input type='text' id='userName' placeholder='Votre nom' required>";

            
            $stmt_questions = $pdo_quiz_app->prepare("SELECT * FROM questions WHERE quiz_id = ?");
            $stmt_questions->execute([$quiz_id]);
            while ($question = $stmt_questions->fetch(PDO::FETCH_ASSOC)) {
                echo "<div><p>" . htmlspecialchars($question['question_text']) . "</p>";
                echo "<input type='hidden' name='questionIds[]' value='" . $question['id'] . "'>";
                $stmt_answers = $pdo_quiz_app->prepare("SELECT * FROM answers WHERE question_id = ?");
                $stmt_answers->execute([$question['id']]);
                while ($answer = $stmt_answers->fetch(PDO::FETCH_ASSOC)) {
                    echo "<input type='radio' name='answers[" . $question['id'] . "]' value='" . $answer['id'] . "'>";
                    echo "<label>" . htmlspecialchars($answer['answer_text']) . "</label><br>";
                }
                echo "</div>";
            }
            echo "<button type='button' id='submitAnswers'>Soumettre les réponses</button>";
            echo "<input type='hidden' id='quizId' value='" . $quiz_id . "'>";
            echo "</form>";
            ?>
            <script>
            document.getElementById('submitAnswers').addEventListener('click', function() {
                var answers = {};
                var questionIds = [];
                document.querySelectorAll('[name^="answers["]').forEach((input) => {
                    if (input.checked) {
                        var questionId = input.name.match(/\[(\d+)\]/)[1];
                        answers[questionId] = input.value;
                    }
                });
                document.querySelectorAll('[name="questionIds[]"]').forEach((input) => {
                    questionIds.push(input.value);
                });

                var userName = document.getElementById('userName').value;
                var quizId = document.getElementById('quizId').value;

                fetch('http://localhost:3001/submit-quiz', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({quizId: quizId, questionIds: questionIds, answers: answers, name: userName})
                })
                .then(response => response.json())
                .then(data => {
                    alert("Votre score est : " + data.score);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
            </script>
            <?php
        } else {
            echo "Quiz non trouvé ou titre incorrect.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

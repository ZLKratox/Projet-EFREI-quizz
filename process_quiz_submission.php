<?php
session_start();
include 'db\config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quiz_title'])) {
    $quiz_title = $_POST['quiz_title'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    // Recherchez le quiz par titre
    $stmt_quiz = $conn->prepare("SELECT quiz_id FROM quizzes WHERE quiz_title = ?");
    $stmt_quiz->bind_param("s", $quiz_title);
    $stmt_quiz->execute();
    $result_quiz = $stmt_quiz->get_result();
    if ($result_quiz->num_rows == 1) {
        $row_quiz = $result_quiz->fetch_assoc();
        $quiz_id = $row_quiz['quiz_id'];

        // Recherchez les questions pour ce quiz
        $stmt_questions = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ?");
        $stmt_questions->bind_param("i", $quiz_id);
        $stmt_questions->execute();
        $result_questions = $stmt_questions->get_result();
?>
        <form action="submit_answers.php" method="post">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        <?php
        while ($row_question = $result_questions->fetch_assoc()) {
            echo "<div>";
            echo "<p>" . $row_question['question_text'] . "</p>";

            // Recherchez les réponses pour cette question
            $stmt_answers = $conn->prepare("SELECT * FROM answers WHERE question_id = ?");
            $stmt_answers->bind_param("i", $row_question['question_id']);
            $stmt_answers->execute();
            $result_answers = $stmt_answers->get_result();

            while ($row_answer = $result_answers->fetch_assoc()) {
                echo "<input type='radio' name='answers[" . $row_question['question_id'] . "]' value='" . $row_answer['answer_id'] . "'>";
                echo "<label for='answer_" . $row_question['question_id'] . "'>" . $row_answer['answer_text'] . "</label><br>";
            }
            echo "</div>";
            $stmt_answers->close();
        }
        ?>
            <input type="submit" value="Soumettre les réponses">
        </form>
<?php
        $stmt_questions->close();
    } else {
        echo "Quiz non trouvé ou titre incorrect.";
    }
    $stmt_quiz->close();
    $conn->close();
} else {
    header("Location: load_quiz.php");
    exit();
}
?>

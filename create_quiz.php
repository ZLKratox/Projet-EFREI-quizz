<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db/config.php'; // Assurez-vous que ce fichier contient vos informations de connexion à la base de données.

$user_id = $_SESSION['user_id'];
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['quiz_title']) && isset($_POST['questions']) && isset($_POST['answers']) && isset($_POST['correct'])) {
        $quiz_title = $_POST['quiz_title'];
        $questions = $_POST['questions'];
        $answers = $_POST['answers'];
        $correct = $_POST['correct'];

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO quizzes (user_id, quiz_title) VALUES (:user_id, :quiz_title)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':quiz_title', $quiz_title);
            $stmt->execute();
            $quiz_id = $conn->lastInsertId();

            foreach ($questions as $index => $question_text) {
                $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (:quiz_id, :question_text)");
                $stmt->bindParam(':quiz_id', $quiz_id);
                $stmt->bindParam(':question_text', $question_text);
                $stmt->execute();
                $question_id = $conn->lastInsertId();

                foreach ($answers[$index] as $answer_index => $answer_text) {
                    $is_correct = ($correct[$index] == $answer_index) ? 1 : 0;
                    $stmt = $conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (:question_id, :answer_text, :is_correct)");
                    $stmt->bindParam(':question_id', $question_id);
                    $stmt->bindParam(':answer_text', $answer_text);
                    $stmt->bindParam(':is_correct', $is_correct);
                    $stmt->execute();
                }
            }

            $conn->commit();
            $success_message = "Quiz bien enregistré.";
        } catch(Exception $e) {
            $conn->rollBack();
            $success_message = "Erreur lors de l'enregistrement du quiz : " . $e->getMessage();
        }
    } else {
        $success_message = "Erreur : Tous les champs requis n'ont pas été soumis.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Créer un Quiz</title>
</head>
<body>
<nav class="container-fluid">
    <ul><li><strong>Création de Quiz</strong></li></ul>
    <ul>
        <li><a href="login.html">Accueil</a></li>
        <li><a href="dashboard.php">Tableau de bord</a></li>
        <li><a href="#" role="button">Déconnexion</a></li>
    </ul>
</nav>
<main class="container">
    <div class="grid">
        <section>
            <hgroup>
                <h2>Créer un nouveau quiz</h2>
                <h3>Commencez par donner un titre à votre quiz</h3>
            </hgroup>
            <?php if ($success_message) : ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="quiz_title">Titre du quiz :</label>
                <input type="text" id="quiz_title" name="quiz_title" required>
                <div id="questionsContainer">
                    <!-- Les questions seront ajoutées ici dynamiquement via JavaScript -->
                </div>
                <button type="button" id="addQuestion">Ajouter une question</button>
                <input type="submit" value="Enregistrer le quiz">
            </form>
        </section>
    </div>
</main>
<script>
        document.getElementById("addQuestion").addEventListener("click", function(event) {
            event.preventDefault();
            var questionsContainer = document.getElementById("questionsContainer");
            var questionIndex = questionsContainer.childElementCount + 1;
            var questionDiv = document.createElement("div");
            questionDiv.innerHTML = "<label for='question_" + questionIndex + "'>Question " + questionIndex + " :</label>" +
                "<input type='text' id='question_" + questionIndex + "' name='questions[]' required>" +
                "<button onclick='addAnswer(this, " + questionIndex + ")'>Ajouter une réponse</button><br>";
            questionsContainer.appendChild(questionDiv);
        });

        function addAnswer(button, questionIndex) {
            event.preventDefault();
            var parentDiv = button.parentElement;
            var answerIndex = parentDiv.querySelectorAll("input[type='text']").length;
            var answerDiv = document.createElement("div");
            answerDiv.innerHTML = "<label for='answer_" + questionIndex + "_" + answerIndex + "'>Réponse " + answerIndex + " :</label>" +
                "<input type='text' id='answer_" + questionIndex + "_" + answerIndex + "' name='answers[" + (questionIndex - 1) + "][]' required>" +
                "<input type='radio' id='correct_" + questionIndex + "_" + answerIndex + "' name='correct[" + (questionIndex - 1) + "]' value='" + answerIndex + "' required>" +
                "<label for='correct_" + questionIndex + "_" + answerIndex + "'> Correct</label><br>";
            parentDiv.insertBefore(answerDiv, button);
        }
    </script>
</body>
</html>

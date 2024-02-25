<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifiez si le titre du quiz a été soumis
    if(isset($_POST['quiz_title']) && isset($_POST['questions']) && isset($_POST['answers']) && isset($_POST['correct'])) {
        $quiz_title = $_POST['quiz_title'];
        $questions = $_POST['questions'];
        $answers = $_POST['answers'];
        $correct_answers = $_POST['correct'];

        // Inclure le fichier de configuration de la base de données
        include 'db/config.php'; // Assurez-vous que le chemin est correct

        // Connexion à la base de données
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        // Préparer la requête d'insertion du quiz
        $stmt_quiz = $conn->prepare("INSERT INTO quizzes (user_id, quiz_title) VALUES (?, ?)");
        $stmt_quiz->bind_param("is", $user_id, $quiz_title);

        // Exécuter la requête d'insertion du quiz
        if ($stmt_quiz->execute()) {
            $quiz_id = $conn->insert_id; // Récupérer l'ID du quiz inséré

            // Préparer la requête d'insertion des questions et des réponses
            $stmt_question = $conn->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
            $stmt_answer = $conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");

            // Parcourir les questions et les réponses et les insérer dans la base de données
            for ($i = 0; $i < count($questions); $i++) {
                $stmt_question->bind_param("is", $quiz_id, $questions[$i]);
                if ($stmt_question->execute()) {
                    $question_id = $conn->insert_id; // Récupérer l'ID de la question insérée

                    // Insérer les réponses associées à cette question
                    foreach ($answers[$i] as $index => $answer_text) {
                        $is_correct = ($correct_answers[$i] == $index) ? 1 : 0;
                        $stmt_answer->bind_param("isi", $question_id, $answer_text, $is_correct);
                        $stmt_answer->execute();
                    }
                }
            }

            // Fermer les déclarations et la connexion
            $stmt_quiz->close();
            $stmt_question->close();
            $stmt_answer->close();
            $conn->close();

            // Redirection vers le tableau de bord avec un message de succès
            header("Location: dashboard.php?success=1");
            exit();
        } else {
            echo "Erreur lors de l'insertion du quiz : " . $conn->error;
        }
    } else {
        echo "Erreur : Tous les champs requis n'ont pas été soumis.";
    }
}
?>

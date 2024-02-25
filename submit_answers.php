<?php
session_start();
include 'db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifiez si l'ID de l'utilisateur est stocké dans la session
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Vérifiez si les réponses ont été soumises
        if (isset($_POST['quiz_id']) && isset($_POST['answers'])) {
            $quiz_id = $_POST['quiz_id'];
            $answers = $_POST['answers'];

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Erreur de connexion : " . $conn->connect_error);
            }

            // Parcourez les réponses soumises et insérez-les dans la table user_answers
            foreach ($answers as $question_id => $answer_id) {
                // Préparez la requête pour insérer la réponse dans la base de données
                $stmt_answer = $conn->prepare("INSERT INTO user_answers (user_id, quiz_id, question_id, answer_id) VALUES (?, ?, ?, ?)");
                $stmt_answer->bind_param("iiii", $user_id, $quiz_id, $question_id, $answer_id);

                // Exécutez la requête pour insérer la réponse
                if ($stmt_answer->execute()) {
                    //echo "Réponse insérée avec succès pour la question $question_id.";
                } else {
                    echo "Erreur lors de l'insertion de la réponse pour la question $question_id : " . $conn->error;
                }

                // Fermez la déclaration
                $stmt_answer->close();
            }

            // Fermez la connexion
            $conn->close();

            // Affichez le message de remerciement avec le nom du quiz
            echo "<p>Merci d'avoir répondu au quiz. Vous allez être redirigé vers le tableau de bord.</p>";

            // Redirection vers le tableau de bord après quelques secondes
            header("refresh:3;url=dashboard.php");
            exit();
        } else {
            echo "Les réponses n'ont pas été soumises.";
        }
    } else {
        // Rediriger vers la page de connexion si l'ID de l'utilisateur n'est pas disponible dans la session
        header("Location: login.html");
        exit();
    }
} else {
    header("Location: load_quiz.php");
    exit();
}

?>

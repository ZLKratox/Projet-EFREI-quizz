<?php
// Assurez-vous que l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Charger un Quiz</title>
</head>
<body>
<nav class="container-fluid">
<ul><li><strong>Charger Quiz</strong></li></ul>
<ul>
    <li><a href="dashboard.php">Accueil</a></li>
    <li><a href="dashboard.php">Tableau de bord</a></li>
    <li><a href="index.html" role="button">Déconnexion</a></li>
</ul>
</nav>
<main class="container">
    <div class="grid">
        <section>
            <h2>Charger un quiz existant</h2>
            <form action="process_load_quiz.php" method="post">
                <label for="quiz_title">Entrez le titre du quiz :</label>
                <input type="text" id="quiz_title" name="quiz_title" required>
                <button type="submit" class="primary">Charger le quiz</button>
            </form>
        </section>
    </div>
</main>
<footer class="container">
    <small><a href="#">Politique de confidentialité</a> • <a href="#">Conditions d'utilisation</a></small>
</footer>
</body>
</html>

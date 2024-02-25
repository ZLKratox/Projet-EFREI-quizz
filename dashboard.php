<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Assurez-vous que le chemin d'accès est correct et que le fichier existe.
    header("Location: login.php"); // Modification pour pointer vers login.php si c'est le script de connexion correct
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <title>Tableau de bord</title>
</head>
<body>
<nav class="container-fluid">
<ul><li><strong>Tableau de bord</strong></li></ul>
<ul>
    <li><a href="create_quiz.php">Créer un quiz</a></li>
    <li><a href="load_quiz.php">Charger un quiz</a></li>
    <li><a href="index.html">Déconnexion</a></li>
</ul>
</nav>
<main class="container">
    <div class="grid">
        <section>
            <hgroup>
                <h2>Bienvenue sur votre tableau de bord</h2>
                <?php
                // Affichage sécurisé du nom d'utilisateur pour se prémunir contre les attaques XSS
                if(isset($_SESSION['username'])) {
                    echo "<h3>Bienvenue, " . htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') . "!</h3>";
                }
                ?>
            </hgroup>
            <p>Accédez rapidement aux fonctionnalités principales de votre espace personnel.</p>
            <figure>
                <img src="css\cyber.png" alt="Tableau de bord" />
                <figcaption>Explorez votre espace personnel</figcaption>
            </figure>
            <h3>Créez et gérez vos quiz</h3>
            <p>Utilisez les liens en haut de la page pour créer ou charger vos quiz.</p>
        </section>
    </div>
</main>
<section aria-label="Subscribe example">
    <div class="container">
        <article>
            <hgroup>
                <h2>Restez informé</h2>
                <h3>Inscrivez-vous à notre newsletter</h3>
            </hgroup>
            <form class="grid">
                <input type="text" id="firstname" name="firstname" placeholder="Votre nom" aria-label="Votre nom" required />
                <input type="email" id="email" name="email" placeholder="Votre email" aria-label="Votre email" required />
                <button type="submit" onclick="event.preventDefault()">S'inscrire</button>
            </form>
        </article>
    </div>
</section>
<footer class="container">
    <small><a href="https://youtu.be/dQw4w9WgXcQ?si=ElZZnlnsb_Zf4UlN" target="_blank">Politique de confidentialité</a> • <a href="#">Contact</a></small>
</footer>
</body>
</html>

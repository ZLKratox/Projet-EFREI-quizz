<?php
session_start(); // Démarrer la session PHP

// Ces variables devraient être adaptées à votre configuration de la base de données
$host = "127.0.0.1"; // ou localhost
$dbname = "projet"; // Nom de votre base de données
$dbUsername = "root"; // Votre nom d'utilisateur pour la base de données
$dbPassword = "mathis*3310"; // Votre mot de passe pour la base de données

// Créer une connexion PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['username'])) {
    $usernameForm = trim($_POST['username']);

    // Préparer une déclaration pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT user_id, username FROM users WHERE username = :username");
    $stmt->bindParam(':username', $usernameForm, PDO::PARAM_STR);

    $stmt->execute();

    // Vérifier si l'utilisateur existe
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Enregistrer les données utilisateur dans la session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username']; // Ici, utilisez la valeur récupérée de la base de données

        // Rediriger vers le tableau de bord
        header("Location: dashboard.php");
        exit();
    } else {
        // Utilisateur n'existe pas, rediriger vers la page d'inscription
        header("Location: index.html"); // Assurez-vous que 'signup.php' est le chemin correct vers votre page d'inscription
        exit();
    }
}
?>

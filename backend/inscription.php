<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'quiz_app';
$user = 'root';
$pass = 'mathis*3310';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['error' => 'Un utilisateur avec cet email existe déjà.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $password])) {
        echo json_encode(['success' => 'Utilisateur créé avec succès.']);
    } else {
        echo json_encode(['error' => 'Erreur lors de la création de l\'utilisateur.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
}
?>

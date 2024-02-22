<?php
include 'db.php'; // Assurez-vous que ce fichier contient vos informations de connexion à la base de données

session_start(); // Démarre ou reprend une session PHP

header("Access-Control-Allow-Origin: http://localhost");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Récupérez le titre du quiz depuis les données envoyées en GET ou POST
$quizTitle = isset($_GET['quiz_title']) ? $_GET['quiz_title'] : null;

if (!$quizTitle) {
    echo json_encode(['exists' => false, 'message' => 'Titre du quiz non fourni']);
    exit;
}

// Préparez et exécutez la requête pour vérifier l'existence du quiz
$query = $conn->prepare("SELECT quiz_id FROM quizzes WHERE quiz_title = ?");
$query->bind_param("s", $quizTitle);
$query->execute();
$result = $query->get_result();

if ($result->num_rows){
    $quizData = $result->fetch_assoc();
    echo json_encode(['exists' => true, 'quiz_id' => $quizData['quiz_id']]);
    } else {
    echo json_encode(['exists' => false]);
    }

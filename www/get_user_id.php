<?php
include 'db.php';
session_start(); // Démarre ou reprend une session PHP

header("Access-Control-Allow-Origin: http://localhost");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');


if (isset($_SESSION['userId'])) {
    echo json_encode(['userId' => $_SESSION['userId']]);
} else {
    echo json_encode(['error' => 'User not logged in']);
}
?>
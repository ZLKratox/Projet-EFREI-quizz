<?php
include 'db.php';
session_start(); // Démarre ou reprend une session PHP

header("Access-Control-Allow-Origin: http://localhost");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';

$sql = "SELECT user_id FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['exists' => true, 'userId' => $row['user_id']]);
} else {
    echo json_encode(['exists' => false]);
}

$conn->close();
?>

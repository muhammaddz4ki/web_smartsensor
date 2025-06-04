<?php
session_start();

// Koneksi ke database
$db = new mysqli('localhost', 'username', 'password', '2aec2');

if ($db->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user dari database
$stmt = $db->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
} else {
    $user = $result->fetch_assoc();
    echo json_encode(['success' => true, 'user' => $user]);
}
?>
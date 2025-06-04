<?php
session_start();

$action = $_GET['action'] ?? '';

// Koneksi ke database
$db = new mysqli('localhost', 'username', 'password', '2aec2');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

switch ($action) {
    case 'login':
        handleLogin($db);
        break;
    case 'logout':
        handleLogout();
        break;
    case 'check':
        checkSession();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function handleLogin($db) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username dan password harus diisi']);
        return;
    }
    
    // Cari user di database
    $stmt = $db->prepare("SELECT id, username, email, password, created_at FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Username tidak ditemukan']);
        return;
    }
    
    $user = $result->fetch_assoc();
    
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Simpan data user di session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        echo json_encode(['success' => true, 'message' => 'Login berhasil']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Password salah']);
    }
}

function handleLogout() {
    session_unset();
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logout berhasil']);
}

function checkSession() {
    if (isset($_SESSION['user_id'])) {
        echo json_encode(['loggedIn' => true]);
    } else {
        echo json_encode(['loggedIn' => false]);
    }
}
?>
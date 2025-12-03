<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        // Simpan session
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama']     = $user['nama'];
        $_SESSION['role']     = $user['role'];

        // KALAU ADMIN → LANGSUNG REDIRECT KE DASHBOARD ADMIN
        if ($user['role'] === 'admin') {
            echo json_encode(['success' => true, 'redirect' => 'admin/admin_dashboard.php']);
        } else {
            echo json_encode(['success' => true, 'redirect' => 'index.php']); // pelanggan tetap di index
        }
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}
?>
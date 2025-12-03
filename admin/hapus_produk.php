<?php
session_start();
require '../includes/db.php';
if ($_SESSION['role'] !== 'admin') exit;

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT gambar FROM produk WHERE id = ?");
$stmt->execute([$id]);
$gambar = $stmt->fetchColumn();

if ($gambar && file_exists("../assets/img/$gambar")) {
    unlink("../assets/img/$gambar");
}

$pdo->prepare("DELETE FROM produk WHERE id = ?")->execute([$id]);
header("Location: admin_dashboard.php?sukses=1");
exit;
?>
<?php
require 'db.php';

if ($_GET['action'] === 'read') {
    $stmt = $pdo->query("SELECT * FROM produk ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($_GET['action'] === 'get' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    echo json_encode($stmt->fetch());
    exit;
}
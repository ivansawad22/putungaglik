<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$produk = $pdo->query("SELECT * FROM produk ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Putu Ngaglik</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen pt-20">
  <div class="container mx-auto px-4">
    <div class="bg-white rounded-xl shadow-xl p-8 mb-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-orange-600">Admin Dashboard</h1>
        <a href="../logout.php" class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg font-bold">Logout</a>
      </div>
      <a href="tambah_produk.php" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-bold inline-block mb-8">
        + Tambah Produk Baru
      </a>

      <?php if (empty($produk)): ?>
        <p class="text-center text-gray-500 text-xl">Belum ada produk. <a href="tambah_produk.php" class="text-blue-600 underline">Tambah sekarang</a></p>
      <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <?php foreach ($produk as $p): ?>
            <div class="bg-white border rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition">
              <img src="../assets/img/<?= $p['gambar'] ?>" class="w-full h-56 object-cover">
              <div class="p-6">
                <h3 class="text-2xl font-bold"><?= htmlspecialchars($p['nama']) ?></h3>
                <p class="text-orange-600 text-2xl font-bold mt-2">Rp <?= number_format($p['harga'],0,',','.') ?></p>
                <div class="flex gap-3 mt-6">
                  <a href="edit_produk.php?id=<?= $p['id'] ?>" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-bold">Edit</a>
                  <a href="hapus_produk.php?id=<?= $p['id'] ?>" onclick="return confirm('Yakin hapus <?= htmlspecialchars($p['nama']) ?>?')" 
                     class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-3 rounded-lg font-bold">Hapus</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
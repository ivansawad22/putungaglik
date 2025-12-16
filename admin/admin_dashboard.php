<?php
session_start();
require '../includes/db.php';

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$nama_admin = $_SESSION['nama'] ?? 'Admin'; // Ambil nama dari session

$produk = $pdo->query("SELECT * FROM produk ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Putu Ngaglik</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-orange-50 to-amber-50 min-h-screen">

  <!-- Header Admin -->
  <div class="bg-white shadow-2xl border-b-4 border-orange-600">
    <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
      <div>
        <h1 class="text-4xl font-bold text-orange-600">Admin Dashboard</h1>
        <p class="text-xl text-gray-700 mt-2">
          <i class="fas fa-user-circle text-orange-600"></i> 
          Selamat datang, <strong class="text-orange-600"><?= htmlspecialchars($nama_admin) ?></strong>
        </p>
      </div>
      <div class="flex gap-4">
        <a href="tambah_produk.php" class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg transition transform hover:scale-105 flex items-center gap-3">
          <i class="fas fa-plus-circle"></i> Tambah Produk
        </a>
        <a href="../logout.php" class="bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg transition transform hover:scale-105 flex items-center gap-3">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Jumlah Produk -->
    <div class="text-center mb-10">
      <p class="text-2xl font-semibold text-gray-700">
        Total Produk: <span class="text-4xl font-bold text-orange-600"><?= count($produk) ?></span>
      </p>
    </div>

    <?php if (empty($produk)): ?>
      <div class="text-center py-20">
        <i class="fas fa-box-open text-9xl text-gray-300 mb-8"></i>
        <p class="text-2xl text-gray-600 mb-6">Belum ada produk di database</p>
        <a href="tambah_produk.php" class="bg-green-600 hover:bg-green-700 text-white font-bold px-10 py-5 rounded-xl text-xl shadow-lg transition transform hover:scale-105">
          + Tambah Produk Pertama
        </a>
      </div>
    <?php else: ?>
      <!-- Grid Produk -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php foreach ($produk as $p): ?>
          <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-gray-200">
            <div class="relative">
              <img src="../assets/img/<?= htmlspecialchars($p['gambar']) ?>" alt="<?= htmlspecialchars($p['nama']) ?>" class="w-full h-64 object-cover">
              <?php if ($p['bestseller']): ?>
                <span class="absolute top-4 left-4 bg-green-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">BEST SELLER</span>
              <?php endif; ?>
              <?php if ($p['unik']): ?>
                <span class="absolute top-4 right-4 bg-purple-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">UNIK</span>
              <?php endif; ?>
            </div>
            <div class="p-6">
              <h3 class="text-2xl font-bold text-gray-800 mb-3"><?= htmlspecialchars($p['nama']) ?></h3>
              <p class="text-orange-600 text-3xl font-bold mb-4">Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
              <p class="text-gray-600 text-sm line-clamp-2 mb-6"><?= htmlspecialchars($p['deskripsi'] ?: 'Tidak ada deskripsi') ?></p>
              
              <div class="flex gap-3">
                <a href="edit_produk.php?id=<?= $p['id'] ?>" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-bold transition flex items-center justify-center gap-2">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <a href="hapus_produk.php?id=<?= $p['id'] ?>" 
                   onclick="return confirm('Yakin ingin menghapus <?= htmlspecialchars(addslashes($p['nama'])) ?>?')" 
                   class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-3 rounded-lg font-bold transition flex items-center justify-center gap-2">
                  <i class="fas fa-trash"></i> Hapus
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Footer kecil -->
  <footer class="bg-gray-800 text-white py-8 mt-20 text-center">
    <p class="text-lg">&copy; 2025 Putu Ngaglik Surabaya - Admin Panel</p>
  </footer>

</body>
</html>
<?php
session_start();
require '../includes/db.php';
if ($_SESSION['role'] !== 'admin') header("Location: ../index.php");

if ($_POST) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $bestseller = isset($_POST['bestseller']) ? 1 : 0;
    $unik = isset($_POST['unik']) ? 1 : 0;
    $gambar = $_FILES['gambar']['name'];
    $target = "../assets/img/" . basename($gambar);

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
        $stmt = $pdo->prepare("INSERT INTO produk (nama,harga,gambar,deskripsi,bestseller,unik) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$nama,$harga,$gambar,$deskripsi,$bestseller,$unik]);
        header("Location: admin_dashboard.php?sukses=1");
        exit;
    } else {
        $error = "Gagal upload gambar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen pt-20">
  <div class="container mx-auto px-4 max-w-2xl">
    <div class="bg-white rounded-xl shadow-xl p-8">
      <h1 class="text-3xl font-bold text-orange-600 mb-8">Tambah Produk Baru</h1>
      <?php if(isset($error)) echo "<p class='text-red-600 mb-4'>$error</p>"; ?>
      <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="text" name="nama" placeholder="Nama Produk" required class="w-full px-4 py-3 border rounded-lg">
        <input type="number" name="harga" placeholder="Harga" required class="w-full px-4 py-3 border rounded-lg">
        <textarea name="deskripsi" placeholder="Deskripsi (opsional)" class="w-full px-4 py-3 border rounded-lg" rows="4"></textarea>
        <input type="file" name="gambar" accept="image/*" required class="w-full px-4 py-3 border rounded-lg">
        <div class="flex gap-6">
          <label><input type="checkbox" name="bestseller"> Best Seller</label>
          <label><input type="checkbox" name="unik"> Unik</label>
        </div>
        <div class="flex gap-4">
          <a href="admin_dashboard.php" class="flex-1 text-center py-3 bg-gray-400 text-white rounded-lg">Batal</a>
          <button type="submit" class="flex-1 py-3 bg-green-600 text-white rounded-lg font-bold">Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
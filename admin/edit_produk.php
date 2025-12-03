<?php
session_start();
require '../includes/db.php';
if ($_SESSION['role'] !== 'admin') header("Location: ../index.php");

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch();

if ($_POST) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $bestseller = isset($_POST['bestseller']) ? 1 : 0;
    $unik = isset($_POST['unik']) ? 1 : 0;

    if (!empty($_FILES['gambar']['name'])) {
        // hapus gambar lama
        if(file_exists("../assets/img/".$produk['gambar'])) unlink("../assets/img/".$produk['gambar']);
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/".$gambar);
        $sql = "UPDATE produk SET nama=?, harga=?, gambar=?, deskripsi=?, bestseller=?, unik=? WHERE id=?";
        $pdo->prepare($sql)->execute([$nama,$harga,$gambar,$deskripsi,$bestseller,$unik,$id]);
    } else {
        $sql = "UPDATE produk SET nama=?, harga=?, deskripsi=?, bestseller=?, unik=? WHERE id=?";
        $pdo->prepare($sql)->execute([$nama,$harga,$deskripsi,$bestseller,$unik,$id]);
    }
    header("Location: admin_dashboard.php?sukses=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen pt-20">
  <div class="container mx-auto px-4 max-w-2xl">
    <div class="bg-white rounded-xl shadow-xl p-8">
      <h1 class="text-3xl font-bold text-orange-600 mb-8">Edit Produk</h1>
      <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="id" value="<?= $produk['id'] ?>">
        <input type="text" name="nama" value="<?= htmlspecialchars($produk['nama']) ?>" required class="w-full px-4 py-3 border rounded-lg">
        <input type="number" name="harga" value="<?= $produk['harga'] ?>" required class="w-full px-4 py-3 border rounded-lg">
        <textarea name="deskripsi" class="w-full px-4 py-3 border rounded-lg" rows="4"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
        <div>
          <p class="mb-2">Gambar saat ini:</p>
          <img src="../assets/img/<?= $produk['gambar'] ?>" class="h-48 object-cover rounded-lg">
          <input type="file" name="gambar" accept="image/*" class="mt-3 w-full">
          <small>Kosongkan jika tidak ganti gambar</small>
        </div>
        <div class="flex gap-6">
          <label><input type="checkbox" name="bestseller" <?= $produk['bestseller']?'checked':'' ?>> Best Seller</label>
          <label><input type="checkbox" name="unik" <?= $produk['unik']?'checked':'' ?>> Unik</label>
        </div>
        <div class="flex gap-4">
          <a href="admin_dashboard.php" class="flex-1 text-center py-3 bg-gray-400 text-white rounded-lg">Batal</a>
          <button type="submit" class="flex-1 py-3 bg-orange-600 text-white rounded-lg font-bold">Update Produk</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
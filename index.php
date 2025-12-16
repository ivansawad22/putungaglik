<?php 
// Bersihkan cache jika logout
if (isset($_GET['t'])) {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
}
session_start(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Putu Ngaglik Surabaya</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
</head>
<body class="font-sans bg-gray-100">

<!-- Navigation Bar -->
<nav class="bg-white shadow-lg fixed w-full z-20 top-0">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex items-center">
        <img src="assets/img/logo.png" alt="Logo" class="h-10 w-10">
        <span class="ml-3 text-xl font-bold text-gray-800">Putu Ngaglik Surabaya</span>
      </div>
      <div class="flex items-center space-x-6">
        <a href="#home" class="text-gray-600 hover:text-orange-500">Beranda</a>
        <a href="#about" class="text-gray-600 hover:text-orange-500">Tentang</a>
        <a href="#menu" class="text-gray-600 hover:text-orange-500">Menu</a>
        <a href="#contact" class="text-gray-600 hover:text-orange-500">Kontak</a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <a href="admin/admin_dashboard.php" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700">Admin Dashboard</a>
          <a href="logout.php" class="text-red-600 hover:text-red-800 font-medium ml-4">Logout</a>
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'pelanggan'): ?>
          <span class="text-green-600 font-bold">Hi, <?= htmlspecialchars($_SESSION['nama']) ?>!</span>
          <a href="logout.php" class="text-red-600 hover:text-red-800 font-medium ml-4">Logout</a>
        <?php else: ?>
          <button type="button" id="loginBtn" onclick="openLoginModal()" class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-lg shadow-lg transition transform hover:scale-105">
            Login
          </button>
        <?php endif; ?>

        <!-- Ikon Keranjang -->
        <button onclick="openCartModal()" class="relative ml-4">
          <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
          <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold">0</span>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
    <div class="text-center mb-8">
      <h2 class="text-3xl font-bold text-gray-800">Selamat Datang Kembali!</h2>
    </div>

    <form id="loginForm" class="space-y-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Username / Nama</label>
        <input type="text" id="username" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Masukkan username" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" id="password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Masukkan password" required>
      </div>
      <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg hidden text-center font-medium">
        Username atau password salah!
      </div>
      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeLoginModal()" class="flex-1 py-3 bg-gray-200 text-gray-800 rounded-xl font-bold hover:bg-gray-300">Batal</button>
        <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 shadow-lg">Masuk</button>
      </div>
    </form>
  </div>
</div>

<!-- Cart Modal -->
<div id="cartModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-40 hidden">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
    <div class="p-6 border-b">
      <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Keranjang Belanja</h2>
        <button onclick="closeCartModal()" class="text-gray-500 hover:text-gray-800">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>
    <div id="cartItems" class="p-6 space-y-4"></div>
    <div class="p-6 border-t bg-gray-50">
      <div class="flex justify-between text-xl font-bold mb-4">
        <span>Total</span>
        <span id="cartTotal">Rp 0</span>
      </div>
      <button onclick="checkoutWhatsApp()" class="w-full py-4 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl text-lg transition">
        Checkout via WhatsApp
      </button>
    </div>
  </div>
</div>

<!-- Hero Section -->
<section id="home" class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c')">
  <div class="text-center text-white bg-black bg-opacity-50 p-8 rounded-lg">
    <h1 class="text-4xl md:text-5xl font-bold mb-4 animate__fadeIn">Selamat Datang di Putu Ngaglik Surabaya</h1>
    <p class="text-lg md:text-xl mb-6 animate__fadeIn" style="animation-delay: 0.3s;">Nikmati cita rasa khas nusantara dengan sentuhan modern.</p>
    <a href="#menu" class="px-6 py-3 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition">Lihat Menu</a>
  </div>
</section>

<!-- About Section -->
<section id="about" class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-center mb-12 animate__fadeIn">Tentang Kami</h2>
    <div class="text-center text-gray-600 animate__fadeIn" style="animation-delay: 0.3s;">
      <p class="text-lg mb-4">Selamat datang di Putu Ngaglik Surabaya, tempat di mana cita rasa Nusantara berpadu dengan kehangatan tradisi! Sejak 1974, kami menghidupkan kembali kenangan masa kecil melalui jajanan tradisional seperti kue putu, klepon, dan nagasari yang dibuat dengan cinta. Setiap gigitan adalah perjalanan ke pasar tradisional, dengan aroma pandan segar, gula merah yang lumer, dan kelapa parut yang menggoda. Kami berkomitmen menggunakan bahan lokal terbaik untuk melestarikan warisan kuliner Indonesia, sambil menyajikannya dengan sentuhan modern yang memanjakan lidah Anda. Ayo, rasakan kelezatan yang membawa Anda pulang ke rumah!</p>
    </div>
  </div>
</section>

<!-- Menu Section - DINAMIS -->
<section id="menu" class="py-20 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Menu Jajanan Tradisional Kami</h2>
    <div id="menuContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
      <div class="text-center col-span-full text-gray-500">Memuat menu...</div>
    </div>
  </div>
</section>

<!-- Location, Contact, Rating, Footer -->
<section id="location" class="py-16 bg-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Lokasi Kami</h2>
    <div class="flex flex-col md:flex-row gap-8 items-start">
      <div class="md:w-1/2 bg-white p-6 rounded-xl shadow-md">
        <h3 class="text-xl font-bold text-green-700 mb-3">Kunjungi Kami!</h3>
        <p class="text-lg font-semibold mb-2">Putu Ngaglik</p>
        <p class="text-gray-700 mb-1">Jl. Ngaglik No.6, Kapasari, Kec. Genteng, Surabaya, Jawa Timur 60273</p>
        <p class="text-sm text-gray-600 mb-4">Buka: 16.00 - 22.00 WIB</p>
      </div>
      <div class="md:w-1/2">
        <div class="rounded-xl overflow-hidden shadow-lg">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.9268877812615!2d112.74839507532032!3d-7.249161792757379!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f98bdd9506b7%3A0x86f6e4e12521b052!2sPutu%20Ngaglik!5e0!3m2!1sid!2sid!4v1761713955747!5m2!1sid!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <p class="text-sm text-gray-500 text-center mt-2">Klik peta untuk buka di Google Maps</p>
      </div>
    </div>
  </div>
</section>

<section id="contact" class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-center mb-12">Hubungi Kami</h2>
    <div class="flex justify-center">
      <a href="https://wa.me/6287851522742" class="px-6 py-3 bg-green-500 text-white rounded-full hover:bg-green-600 transition">Chat via WhatsApp</a>
    </div>
  </div>
</section>


<footer class="bg-gray-800 text-white py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-center">Putu Ngaglik Surabaya</p>
  </div>
</footer>

<!-- JavaScript (SUDAH DIPERBAIKI 100%) -->
<script>
// === ANIMASI SCROLL CANTIK — TAMBAHAN SAJA, TIDAK GANGGU YANG LAIN ===
document.addEventListener("DOMContentLoaded", function () {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, { threshold: 0.1 });

    // Animasi untuk section utama
    document.querySelectorAll('#home, #about, #menu, #location, #contact').forEach(el => {
        el.classList.add('opacity-0', 'translate-y-16', 'transition-all', 'duration-1000', 'ease-out');
        observer.observe(el);
    });

    // Animasi bertahap untuk setiap card menu
    document.querySelectorAll('#menuContainer > div').forEach((card, i) => {
        card.classList.add('opacity-0', 'translate-y-20', 'transition-all', 'duration-1000', 'ease-out');
        card.style.transitionDelay = `${i * 100}ms`;
        observer.observe(card);
    });
});

// CSS animasi (langsung di JS biar pasti jalan)
const animCSS = document.createElement('style');
animCSS.textContent = `
    .animate { 
        opacity: 1 !important; 
        transform: translateY(0) !important; 
    }
`;
document.head.appendChild(animCSS);

// FUNGSI MODAL LOGIN — INI YANG MEMBUAT TOMBOL LOGIN BERFUNGSI
function openLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('errorMessage').classList.add('hidden');
    document.getElementById('loginForm').reset();
}

// Tutup kalau klik luar modal
document.getElementById('loginModal').addEventListener('click', function(e) {
    if (e.target === this) closeLoginModal();
});

// PROSES LOGIN
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;

    fetch('login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `username=${username}&password=${password}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect || 'index.php';
        } else {
            document.getElementById('errorMessage').classList.remove('hidden');
        }
    })
    .catch(() => alert('Gagal terhubung. Cek file login.php'));
});

// KERANJANG
let cart = [];
function addToCart(name, price, img) {
    const existing = cart.find(item => item.name === name);
    if (existing) existing.qty += 1;
    else cart.push({ name, price, qty: 1, img });
    updateCartUI();
    openCartModal();
}
function updateCartUI() {
    const cartItems = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    const cartTotal = document.getElementById('cartTotal');
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-center text-gray-500">Keranjang masih kosong</p>';
        cartCount.textContent = '0';
        cartTotal.textContent = 'Rp 0';
        return;
    }
    cartItems.innerHTML = '';
    let total = 0;
    cart.forEach((item, index) => {
        total += item.price * item.qty;
        cartItems.innerHTML += `
        <div class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow">
            <img src="assets/img/${item.img}" class="w-16 h-16 object-cover rounded">
            <div class="flex-1">
                <h4 class="font-semibold">${item.name}</h4>
                <p class="text-orange-600 font-bold">Rp ${item.price.toLocaleString('id-ID')}</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="changeQty(${index},-1)" class="w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300">-</button>
                <span class="w-12 text-center font-bold">${item.qty}</span>
                <button onclick="changeQty(${index},1)" class="w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300">+</button>
            </div>
            <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>`;
    });
    cartCount.textContent = cart.reduce((s,i)=>s+i.qty,0);
    cartTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
}
function changeQty(i,c){ cart[i].qty+=c; if(cart[i].qty<=0) removeFromCart(i); else updateCartUI(); }
function removeFromCart(i){ cart.splice(i,1); updateCartUI(); }
function openCartModal(){ document.getElementById('cartModal').classList.remove('hidden'); }
function closeCartModal(){ document.getElementById('cartModal').classList.add('hidden'); }
document.getElementById('cartModal').addEventListener('click',e=>{if(e.target===e.currentTarget) closeCartModal();});
function checkoutWhatsApp() {
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }

    let msg = "Halo Putu Ngaglik Surabaya!%0aSaya ingin pesan:%0a%0a";
    let total = 0;

    cart.forEach(item => {
        let subtotal = item.price * item.qty;
        total += subtotal;
        msg += `• ${item.name} x${item.qty} = Rp ${subtotal.toLocaleString('id-ID')}%0a`;
    });

    msg += `%0a*Total: Rp ${total.toLocaleString('id-ID')}*%0a%0aTerima kasih!`;

    const whatsappURL = 'https://wa.me/6287851522742?text=' + msg;
    window.open(whatsappURL, '_blank');
}

// LOAD MENU DARI DATABASE
fetch('includes/produk.php?action=read')
.then(r => r.json())
.then(data => {
    const c = document.getElementById('menuContainer');
    c.innerHTML = '';

    if (data.length === 0) {
        c.innerHTML = '<p class="text-center col-span-full text-gray-600 text-xl">Belum ada menu.</p>';
        return;
    }

    data.forEach(p => {
        const badge = p.bestseller 
            ? '<span class="bg-green-600 text-white text-xs px-3 py-1 rounded-full font-bold">BEST SELLER</span>' 
            : p.unik 
            ? '<span class="bg-purple-600 text-white text-xs px-3 py-1 rounded-full font-bold">UNIK</span>' 
            : '';

        c.innerHTML += `
        <div class="bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition transform hover:-translate-y-3 border border-gray-200 flex flex-col h-full">
            <img src="assets/img/${p.gambar}" alt="${p.nama}" class="w-full h-64 object-cover">
            
            <!-- Bagian isi card — pakai flex-grow biar tombol selalu di bawah -->
            <div class="p-6 flex flex-col flex-grow">
                ${badge ? '<div class="mb-3">' + badge + '</div>' : ''}
                <h3 class="text-2xl font-bold text-gray-800 mb-2">${p.nama}</h3>
                <p class="text-gray-600 text-sm flex-grow">${p.deskripsi || 'Jajanan tradisional khas Surabaya'}</p>
                
                <!-- Harga tetap di atas tombol -->
                <p class="text-3xl font-bold text-orange-600 mb-4">
                    Rp ${parseInt(p.harga).toLocaleString('id-ID')}/bungkus
                </p>
                
                <!-- Tombol SELALU di paling bawah & rata tinggi semua card -->
                <div class="mt-auto">
                    <button onclick='addToCart("${p.nama}", ${p.harga}, "${p.gambar}")' 
                            class="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:scale-105">
                        TAMBAH KE KERANJANG
                    </button>
                </div>
            </div>
        </div>`;
    });
})
.catch(() => {
    document.getElementById('menuContainer').innerHTML = '<p class="text-center col-span-full text-red-500">Gagal memuat menu.</p>';
});
// GSAP
gsap.from(".animate__fadeIn", {opacity:0,y:50,duration:1,stagger:.2,ease:"power2.out"});
</script>


</body>
</html>
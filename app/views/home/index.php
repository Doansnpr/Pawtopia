<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pawtopia</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Comic Neue', cursive;
    }
html {
      scroll-behavior: smooth; /* scroll smooth untuk anchor */
    }
    /* Background untuk seluruh halaman */
body {
  color: #8d8d8dff;
  position: relative;
}

    /* Navbar */
    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      background-color: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      height: 70px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      width: 100%;
      z-index: 1000;
    }

    .logo img {
      height: 120px;
      width: 100px;
    }

    .nav-links a {
      margin-left: 30px;
      text-decoration: none;
      color: #7e7c7cff;
      font-weight: bold;
      transition: color 0.3s;
    }

    .nav-links a:hover {
      color: #ff9933;
    }

    /* Hero Section */
    .hero {
  background-image: url('<?= BASEURL ?>/images/BERANDA.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 70vh;
  color: white;
  position: relative;
  text-align: center;
  padding: 150px 20px 120px;
  overflow: hidden;
}
 
.hero-content {
  position: relative;
  top: 40%;
  transform: translateY(-40%);
}
    .hero h1 {
      color: #ff9933;
      font-size: 28px;
      margin-bottom: 100px;
      text-shadow: 2px 2px 5px rgba(255, 255, 255, 1);
  line-height: 1.2;
  padding: 0 20px;
    }

    .hero h1,
.fitur h2 {
  font-family: 'Patrick Hand', cursive !important;
  font-weight: bold;
  color: #ff9933;
  font-size: 42px;
  margin-bottom: 15px;
  text-shadow: 3px 3px 0px rgba(255, 153, 51, 0.2);
  position: relative;
  z-index: 1;
}

    .hero .desc {
      background-color: rgba(255, 254, 254, 1);
      display: inline-block;
      padding: 20px 30px;
      border-radius: 10px;
      max-width: 600px;
      font-size: 17px;
      color: #8a8686ff;
      line-height: 1.5;
       margin-top: 40px; 
    }
    .hero button {
      margin-top: 40px;
      position: relative;
      background-color: #ff8a16ff;
      border: none;
      padding: 15px 35px;
      border-radius: 5px;
      font-size: 19px;
      font-weight: bold;
      color: white;
      cursor: pointer;
      overflow: hidden;
      z-index: 1;
      transition: background-color 0.3s, box-shadow 0.3s;
      box-shadow: 0 5px 15px rgba(255, 138, 22, 0.6);
    }

    .hero button:hover {
      background-color: #ff7b00ff;
      box-shadow: 0 8px 20px rgba(255, 123, 0, 0.8);
    }

    .hero button::before {
      content: '';
      position: absolute;
      top: -2px; left: -2px; right: -2px; bottom: -2px;
      border-radius: 8px;
      background: linear-gradient(270deg, #ff9933, #fdb77eff, #ff7b00, #ff7b00);
      background-size: 400% 400%;
      z-index: -1;
      animation: borderMove 3s linear infinite;
    }

    @keyframes borderMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Fitur Section */
    .fitur {
      position: relative;
      background-image: url('<?= BASEURL ?>/images/BERANDA2.jpeg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding: 200px 20px;
      text-align: center;
      color: white;
      overflow: visible;
    }

.fitur::after {
  content: "";
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 100%;
  height: 100px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120' preserveAspectRatio='none'%3E%3Cpath d='M0,0 C150,100 350,0 600,50 C850,100 1050,0 1200,50 L1200,120 L0,120 Z' fill='%23dcf3ff'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-size: cover;
  z-index: 1;
}

    .fitur h2,
    .fitur p,
    .fitur .fitur-container,
    .fitur img {
      position: relative;
      z-index: 1;
    }

    .fitur h2 {
      color: #ff9933;
      font-size: 40px;
      margin-bottom: 15px;
      text-shadow: 2px 2px 5px rgba(255, 255, 255, 1);
    }

    .fitur p {
      font-size: 17px;
      margin-bottom: 40px;
      color: #1a3046ff; 
    }

    @keyframes blinkBox {
      0%, 100% { opacity: 1; transform: translateY(0); }
      50% { opacity: 0.7; transform: translateY(-5px); }
    }

    .fitur-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
    }

    /* Card Styling */
    .fitur-box1 { border: 2px solid #4fc3f7; }
    .fitur-box2 { border: 2px solid #81c784; }
    .fitur-box3 { border: 2px solid #ffd54f; }
    .fitur-box4 { border: 2px solid #f48fb1; }

    .fitur-box1, .fitur-box2, .fitur-box3, .fitur-box4 {
      background: linear-gradient(145deg, #ffffff, #f5f5f5);
      border-radius: 12px;
      padding: 20px;
      width: 250px;
      text-align: left;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .fitur-box1:hover, .fitur-box2:hover, .fitur-box3:hover, .fitur-box4:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

/* H3 card jadi hitam, rata tengah, ada jarak ke paragraf bawah */
.fitur-box1 h3,
.fitur-box2 h3,
.fitur-box3 h3,
.fitur-box4 h3 {
    color: #ff9933 !important;      /* hitam */
    text-align: center;           /* rata tengah */
    margin-bottom: 12px;          /* jarak ke teks bawah */
    font-size: 20px;
}

    .fitur-box p { font-size: 15px; color: #444; }

  </style>
</head>
<body>

  <!-- Hero Section -->
  <section class="hero">
    <h1>Platform Penitipan Kucing Terpercaya untuk Petshop & Cat Owners</h1>
    <div class="desc">
      Titipkan kucing kesayanganmu tanpa khawatir, dan temukan tempat penitipan yang nyaman dan aman. 
      Sementara itu, pemilik petshop bisa mengatur booking, pembayaran,dan laporan harian dengan mudah,
      semua dalam satu aplikasi praktis yang cepat, aman, dan gampang dipakai!
    </div>
    <br>
    <a href="<?= BASEURL; ?>/auth/login">
      <button>Daftar Sekarang</button>
    </a>
  </section>

  <!-- Kenapa Harus Memilih Pawtopia -->
<section class="fitur">
  <h2>Keunggulan Pawtopia</h2>
  <p>Kami ingin memberikan pengalaman terbaik bagi pemilik kucing dan mitra petshop, sehingga semua proses menjadi mudah, aman, dan transparan.</p>

  <div class="fitur-container">
    <!-- Card 1 -->
    <div class="fitur-box1">
      <h3>Aman & Terpercaya</h3>
      <p>Pawtopia menyediakan sistem verifikasi dan keamanan sehingga kamu bisa menitipkan kucing dengan tenang melalui platform kami.</p>
    </div>

    <!-- Card 2 -->
    <div class="fitur-box2">
      <h3>Mitra Terpercaya</h3>
      <p>Semua petshop & hotel kucing terverifikasi, profesional, dan ramah. Kamu bisa menitipkan kucing dengan nyaman.</p>
    </div>

    <!-- Card 3 -->
    <div class="fitur-box3">
      <h3>Mudah & Praktis</h3>
      <p>Booking, pembayaran, dan laporan harian kucingmu dilakukan langsung melalui Pawtopia, cepat dan efisien.</p>
    </div>

    <!-- Card 4 -->
    <div class="fitur-box4">
      <h3>Pengalaman Lengkap</h3>
      <p>Semua informasi fasilitas, harga, dan update kucingmu tersedia di satu tempat, membuat pengalaman menggunakan Pawtopia nyaman dan transparan.</p>
    </div>
  </div>
</section>

   <section id="benefits"><?php require_once __DIR__ . '/benefits.php'; ?></section>
  <section id="masalah"><?php require_once __DIR__ . '/masalah.php'; ?></section>
  <section id="solusi"><?php require_once __DIR__ . '/solusi.php'; ?></section>
  <section id="carakerja"><?php require_once __DIR__ . '/cara-kerja.php'; ?></section>
  <section id="testimoni"><?php require_once __DIR__ . '/testimoni.php'; ?></section>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <?php if (isset($_SESSION['flash'])) : ?>
  <script>
      Swal.fire({
          icon: '<?php echo $_SESSION['flash']['tipe']; ?>',
          title: '<?php echo $_SESSION['flash']['pesan']; ?>',
          text: '<?php echo $_SESSION['flash']['aksi']; ?>',
          confirmButtonColor: '#f39c12'
      });
  </script>
  <?php 
      unset($_SESSION['flash']); 
  ?>
  <?php endif; ?>
</body>
</html>
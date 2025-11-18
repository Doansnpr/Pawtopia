<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pawtopia</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Comic Neue', cursive;
    }

    /* Background untuk seluruh halaman */
    body {
      /* background-image: url('<?= BASEURL ?>/images/BERANDA.png'); ✅ perbaikan path */
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
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

      position: relative;
      text-align: center;
      padding: 180px 40px 120px;
      color: white;
      overflow: hidden;
      min-height: 70vh;
    }
 

.hero-content {
  position: relative;
  top: 40%; /* buat teks agak ke tengah */
  transform: translateY(-40%);
}

    .hero h1 {
      color: #ff9933;
      font-size: 36px;
      margin-bottom: 20px;
      text-shadow: 2px 2px 5px rgba(255, 255, 255, 1);
    }

    .hero .desc {
      background-color: rgba(255, 254, 254, 1);
      display: inline-block;
      padding: 20px 30px;
      border-radius: 10px;
      max-width: 600px;
      font-size: 18px;
      color: #8a8686ff;
      line-height: 1.5;
    }

    .hero button {
      margin-top: 40px;
      position: relative;
      background-color: #ff8a16ff;
      border: none;
      padding: 10px 25px;
      border-radius: 5px;
      font-size: 20px;
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
      background-image: url('<?= BASEURL ?>/images/BERANDA2.jpeg'); /* ✅ perbaikan path */

      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      padding: 200px 20px;
      text-align: center;
      color: white;
      overflow: hidden;
    }

    .fitur::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;

      /* background-color: rgba(226, 222, 222, 0.3); */
      z-index: 0;
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
      font-size: 36px;
      margin-bottom: 15px;
      text-shadow: 2px 2px 5px rgba(255, 255, 255, 1);
    }

    .fitur-box1,
    .fitur-box2,
    .fitur-box3,
    .fitur-box4 {
      text-align: center !important;  /* bikin tengah */
    }

    .fitur-box1 h3,
    .fitur-box2 h3,
    .fitur-box3 h3,
    .fitur-box4 h3 {
      color: #ff9933;
      margin-bottom: 15px !important;
      font-size: 18px;
    }

    .fitur p {
      font-size: 18px;
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

    .fitur-box1, .fitur-box2, .fitur-box3, .fitur-box4 {
      background-color: white;
      border-radius: 12px;
      width: 250px;
      padding: 20px;
      text-align: left;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      animation: blinkBox 2s infinite;
    }

    .fitur-box1 { border: 2px solid #048b41ff; }
    .fitur-box2 { border: 2px solid #3674f8ff; }
    .fitur-box3 { border: 2px solid #da6a8bff; }
    .fitur-box4 { border: 2px solid #8a2297ff; }

    .fitur-box:hover {
      transform: translateY(-5px);
    }

    .fitur-box h3 {
      color: #ff9933;
      margin-bottom: 10px;
      font-size: 18px;
    }

    .fitur-box p {
      font-size: 15px;
      color: #444;
    }
    
  </style>
</head>
<body>

  <!-- Hero Section -->
  <section class="hero">
    <h1>Titipkan Kucingmu dengan Nyaman & Aman</h1>
    <div class="desc">
      Pawtopia adalah platform berbasis web yang mempertemukan pemilik kucing dan penitipan kucing terpercaya. 
      Semua proses dari booking, pembayaran, hingga laporan harian bisa dilakukan dalam satu aplikasi.
    </div>
    <br>
    <a href="<?= BASEURL; ?>/auth/login">
      <button>Daftar Sekarang</button>
    </a>
  </section>

  <!-- Kenapa Memilih Pawtopia -->
  <section class="fitur">
    <h2>Kenapa Memilih Pawtopia</h2>
    <p>Kami membantu kamu baik sebagai pemilik kucing maupun pemilik hotel kucing untuk mendapatkan pengalaman terbaik.</p>

    <div class="fitur-container">
      <div class="fitur-box1">
        <h3>Booking Mudah & Cepat</h3>
        <p>Cari dan pesan hotel kucing sesuai fasilitas dan harga.</p>
      </div>
      <div class="fitur-box2">
        <h3>Laporan Harian Lengkap</h3>
        <p>Update perkembangan kucing dengan foto/video dari mitra.</p>
      </div>
      <div class="fitur-box3">
        <h3>Pembayaran Fleksibel & Aman</h3>
        <p>Metode pembayaran online atau bayar di tempat.</p>
      </div>
      <div class="fitur-box4">
        <h3>Kelola Praktis</h3>
        <p>Mitra dapat mengatur profil, kapasitas, tarif, dan memantau laporan keuangan.</p>
      </div>
    </div>
  </section>


  <!-- Fasilitas -->
  <?php require_once __DIR__ . '/fasilitas.php'; ?>

  <?php require_once __DIR__ . '/cara-kerja.php'; ?>

  <?php require_once __DIR__ . '/testimoni.php'; ?>

    <?php require_once __DIR__ . '/layanan.php'; ?>

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
/
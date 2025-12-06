<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pawtopia</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Comic Neue', cursive; }
    html { scroll-behavior: smooth; }
    body { color: #8d8d8dff; position: relative; }

    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      background-color: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      height: 70px;
      position: fixed;
      top: 0; left: 0; right: 0;
      width: 100%;
      z-index: 1000;
    }

    .logo img { height: 50px; width: auto; }

    .nav-links { display: flex; }
    .nav-links a { margin-left: 30px; text-decoration: none; color: #7e7c7cff; font-weight: bold; transition: color 0.3s; }
    .nav-links a:hover { color: #ff9933; }

    /* Tombol Hamburger */
    .menu-toggle {
      display: none; /* Default hidden di desktop */
      background: none;
      border: none;
      cursor: pointer;
      padding: 10px; /* Area sentuh lebih besar */
      z-index: 2000;
    }
    .menu-toggle .bar {
      display: block;
      width: 25px;
      height: 3px;
      margin: 5px auto;
      background-color: #7e7c7cff;
      transition: all 0.3s ease-in-out;
      /* PENTING: Agar saat garis diklik, yang merespon tetap tombolnya */
      pointer-events: none; 
    }

    /* Animasi X */
    .menu-toggle.is-open .bar:nth-child(1) { transform: translateY(8px) rotate(45deg); }
    .menu-toggle.is-open .bar:nth-child(2) { opacity: 0; }
    .menu-toggle.is-open .bar:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

    /* Hero & Lainnya */
    .hero {
      background-image: url('<?= BASEURL ?>/images/BERANDA 1.png');
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
    .hero-content { position: relative; top: 40%; transform: translateY(-40%); }
    .hero h1 { color: #ff9933; font-size: 28px; margin-bottom: 100px; text-shadow: 2px 2px 5px rgba(255, 255, 255, 1); line-height: 1.2; padding: 0 20px; }
    .hero h1, .fitur h2 { font-family: 'Patrick Hand', cursive !important; font-weight: bold; color: #ff9933; font-size: 42px; margin-bottom: 15px; text-shadow: 3px 3px 0px rgba(255, 153, 51, 0.2); position: relative; z-index: 1; }
    .hero .desc { background: linear-gradient(145deg, #fff4e6, #ffe0b2); display: inline-block; padding: 20px 30px; border-radius: 15px; max-width: 600px; font-size: 18px; color: #1d1d1dff; line-height: 1.5; margin-top: 40px; box-shadow: 0 8px 25px rgba(255, 152, 0, 0.4); border: 2px solid #ff9933; position: relative; overflow: hidden; }
    .hero .desc::before { content: ''; position: absolute; top: -3px; left: -3px; right: -3px; bottom: -3px; border-radius: 17px; background: linear-gradient(45deg, #ff9933, #ffb74d, #ffa726, #ff9933); background-size: 300% 300%; z-index: -1; animation: glowPulse 3s ease-in-out infinite; }
    .hero button { margin-top: 40px; position: relative; background: linear-gradient(135deg, #f3b83f 0%, #e6a02f 100%); border: none; padding: 1rem 2.5rem; border-radius: 50px; font-size: 1.2rem; font-weight: 700; color: white; cursor: pointer; transition: transform 0.3s ease; display: inline-block; outline: none; -webkit-tap-highlight-color: transparent; }
    .hero button:hover { transform: scale(1.05); }
    .hero button:active { transform: scale(1.05); outline: none; }
    .hero button:focus { outline: none; }
    .hero button svg { position: absolute; top: -4px; left: -4px; width: calc(100% + 8px); height: calc(100% + 8px); pointer-events: none; }
    .hero button svg rect { fill: none; stroke: #ff8c00; stroke-width: 4; stroke-dasharray: 10 5; rx: 25; ry: 25; animation: moveBorder 1s linear infinite; filter: drop-shadow(0 0 8px #ff8c00); }
    @keyframes moveBorder { 0% { stroke-dashoffset: 0; } 100% { stroke-dashoffset: 60; } }
    .hero a { text-decoration: none; outline: none; }

    /* Fitur */
    .fitur { position: relative; background-image: url('<?= BASEURL ?>/images/BERANDA2.jpeg'); background-size: cover; background-position: center; background-repeat: no-repeat; padding: 200px 20px; text-align: center; color: white; overflow: visible; }
    .fitur::after { content: ""; position: absolute; bottom: -1px; left: 0; width: 100%; height: 100px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120' preserveAspectRatio='none'%3E%3Cpath d='M0,0 C150,100 350,0 600,50 C850,100 1050,0 1200,50 L1200,120 L0,120 Z' fill='%23dcf3ff'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-size: cover; z-index: 1; }
    .fitur h2, .fitur p, .fitur .fitur-container, .fitur img { position: relative; z-index: 1; }
    .fitur h2 { color: #ff9933; font-size: 40px; margin-bottom: 15px; text-shadow: 2px 2px 5px rgba(255, 255, 255, 1); }
    .fitur p { font-size: 17px; margin-bottom: 40px; color: #1a3046ff; }
    .fitur-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; }
    .fitur-box1 { border: 2px solid #4fc3f7; } .fitur-box2 { border: 2px solid #81c784; } .fitur-box3 { border: 2px solid #ffd54f; } .fitur-box4 { border: 2px solid #f48fb1; }
    .fitur-box1, .fitur-box2, .fitur-box3, .fitur-box4 { background: linear-gradient(145deg, #ffffff, #f5f5f5); border-radius: 12px; padding: 20px; width: 250px; text-align: left; box-shadow: 0 3px 6px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s; }
    .fitur-box1:hover, .fitur-box2:hover, .fitur-box3:hover, .fitur-box4:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.15); }
    .fitur-box1 h3, .fitur-box2 h3, .fitur-box3 h3, .fitur-box4 h3 { color: #ff9933 !important; text-align: center; margin-bottom: 12px; font-size: 20px; }
    .fitur-box p { font-size: 15px; color: #444; }

    /* MEDIA QUERY MOBILE */
    @media (max-width: 768px) {
      .navbar { padding: 10px 15px; height: 60px; }
      .logo img { height: 40px; width: auto; }
      
      /* Tombol Toggle */
      .menu-toggle { display: block; }

      /* Menu Links */
      .nav-links {
        display: none; 
        flex-direction: column; 
        position: absolute;
        top: 60px; left: 0; width: 100%;
        background-color: white; 
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1005; 
        padding: 10px 0;
        min-height: 50vh;
      }
      
      .nav-links.active { display: flex; }
      .nav-links a { margin: 0; padding: 12px 20px; display: block; text-align: center; border-bottom: 1px solid #eee; }
      .nav-links a:last-child { border-bottom: none; }
      
      .hero { padding: 100px 15px 80px; min-height: 90vh; }
      .hero h1 { font-size: 30px; margin-bottom: 30px; }
      .hero .desc { max-width: 100%; padding: 15px 20px; font-size: 15px; margin-top: 20px; }
      .hero button { padding: 0.8rem 2rem; font-size: 1rem; }
      
      .fitur { padding: 100px 15px; }
      .fitur h2 { font-size: 32px; }
      .fitur-container { flex-direction: column; align-items: center; gap: 20px; }
      .fitur-box1, .fitur-box2, .fitur-box3, .fitur-box4 { width: 100%; max-width: 350px; }
      .fitur-box1 h3, .fitur-box2 h3, .fitur-box3 h3, .fitur-box4 h3 { font-size: 18px; }
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="logo">
      <img src="<?= BASEURL; ?>/images/logo_pawtopia.png" alt="Pawtopia Logo">
    </div>
    
    <button class="menu-toggle" aria-label="Toggle navigation" onclick="bukaMenu()">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
    
    <div class="nav-links nav-menu" id="menuSaya">
      <a href="#home" onclick="tutupMenu()">Beranda</a>
      <a href="#masalah" onclick="tutupMenu()">Masalah</a>
      <a href="#solusi" onclick="tutupMenu()">Solusi</a>
      <a href="#benefits" onclick="tutupMenu()">Benefit</a>
      <a href="#carakerja" onclick="tutupMenu()">Cara Kerja</a>
      <a href="#testimoni" onclick="tutupMenu()">Testimoni</a>
    </div>
  </nav>

  <section class="hero" id="home">
    <h1>Platform Penitipan Kucing Terpercaya untuk Petshop & Cat Owners</h1>
    <div class="desc">
      Titipkan kucing kesayanganmu tanpa khawatir, dan temukan tempat penitipan yang nyaman dan aman. 
      Sementara itu, pemilik petshop bisa mengatur booking, pembayaran,dan laporan harian dengan mudah,
      semua dalam satu aplikasi praktis yang cepat, aman, dan gampang dipakai!
    </div>
    <br>
    <a href="<?= BASEURL; ?>/auth/login">
      <button>
      <svg><rect x="2" y="2" rx="25" ry="25" width="calc(100% - 4px)" height="calc(100% - 4px)"></rect></svg>
      Daftar Sekarang
      </button>
    </a>
  </section>

  <section class="fitur">
    <h2>Keunggulan Pawtopia</h2>
    <p>Kami ingin memberikan pengalaman terbaik bagi pemilik kucing dan mitra petshop, sehingga semua proses menjadi mudah, aman, dan transparan.</p>
    <div class="fitur-container">
      <div class="fitur-box1">
        <h3>Aman & Terpercaya</h3>
        <p>Pawtopia menyediakan sistem verifikasi dan keamanan sehingga kamu bisa menitipkan kucing dengan tenang melalui platform kami.</p>
      </div>
      <div class="fitur-box2">
        <h3>Mitra Terpercaya</h3>
        <p>Semua petshop & hotel kucing terverifikasi, profesional, dan ramah. Kamu bisa menitipkan kucing dengan nyaman.</p>
      </div>
      <div class="fitur-box3">
        <h3>Mudah & Praktis</h3>
        <p>Booking, pembayaran, dan laporan harian kucingmu dilakukan langsung melalui Pawtopia, cepat dan efisien.</p>
      </div>
      <div class="fitur-box4">
        <h3>Pengalaman Lengkap</h3>
        <p>Semua informasi fasilitas, harga, dan update kucingmu tersedia di satu tempat, membuat pengalaman menggunakan Pawtopia nyaman dan transparan.</p>
      </div>
    </div>
  </section>

  <section id="masalah"><?php require_once __DIR__ . '/masalah.php'; ?></section>
  <section id="solusi"><?php require_once __DIR__ . '/solusi.php'; ?></section>
  <section id="benefits"><?php require_once __DIR__ . '/benefits.php'; ?></section>
  <section id="carakerja"><?php require_once __DIR__ . '/cara-kerja.php'; ?></section>
  <section id="carakerja"><?php require_once __DIR__ . '/preview.php'; ?></section>
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
  <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <script>
    function bukaMenu() {
        var menu = document.getElementById("menuSaya");
        var tombol = document.querySelector(".menu-toggle");
        
        // Cek manual, jika ada class active, kita hapus. Jika tidak, kita tambah.
        if (menu.classList.contains("active")) {
            menu.classList.remove("active");
            tombol.classList.remove("is-open");
        } else {
            menu.classList.add("active");
            tombol.classList.add("is-open");
        }
    }

    function tutupMenu() {
        var menu = document.getElementById("menuSaya");
        var tombol = document.querySelector(".menu-toggle");
        menu.classList.remove("active");
        tombol.classList.remove("is-open");
    }
  </script>
</body>
</html>
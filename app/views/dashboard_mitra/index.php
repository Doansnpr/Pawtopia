<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard Mitra</title>
  <style>
    /* RESET */
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:Inter, Poppins, sans-serif;background:#f5f7fb;color:#333;min-height:100vh;display:flex;flex-direction:row}

    /* SIDEBAR */
    .sidebar{
      width:240px;background:#fff;padding:20px;
      box-shadow:0 2px 10px rgba(0,0,0,.05);
      display:flex;flex-direction:column;gap:16px;
      transition:transform .28s ease, box-shadow .28s ease;
      position:relative; z-index:50;
    }
    .profile{display:flex;align-items:center;gap:10px}
    .profile img{width:45px;height:45px;border-radius:50%}
    nav.nav-links{display:flex;flex-direction:column;gap:8px}
    nav.nav-links a{display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;color:#333;text-decoration:none;transition:background .15s, color .15s}
    nav.nav-links a.active{background:#f3f3f3;font-weight:600}
    nav.nav-links a:hover{background:#fafafa;color:#f59e0b}
    .logout{margin-top:auto;color:#555;font-size:14px}

    /* MAIN */
    main{flex:1;padding:40px;transition:margin-left .28s ease}
    h1{font-size:22px;margin-bottom:6px}
    p.sub{color:#777;font-size:14px;margin-bottom:24px}

    /* CARDS */
    .cards{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:30px}
    .card{background:#fff;border-radius:12px;padding:18px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
    .card.orange{background:#f59e0b;color:#fff}
    .card h2{font-size:26px;margin:8px 0}

    /* BOTTOM */
    .bottom{display:grid;grid-template-columns:1fr 1fr;gap:20px}
    .chart,.rating{background:#fff;border-radius:12px;padding:18px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
    .chart-bars{display:flex;align-items:flex-end;gap:10px;height:120px;margin-top:10px}
    .chart-bars div{width:26px;background:#ddd;border-radius:6px}

    /* TOGGLE BUTTON: desktop hidden, mobile visible */
    .toggle-btn{
      display:none;
      background:#f59e0b;color:#fff;border:none;padding:8px 10px;border-radius:8px;
      cursor:pointer;font-weight:600;
    }

    /* RESPONSIVE RULES */
    @media (max-width:1024px){
      .cards{grid-template-columns:repeat(2,1fr)}
      .bottom{grid-template-columns:1fr}
    }

    @media (max-width:768px){
      body{flex-direction:column}
      .sidebar{
        width:100%;flex-direction:row;align-items:center;justify-content:space-between;padding:12px 16px;
        box-shadow:0 2px 6px rgba(0,0,0,.08)
      }

      /* hide nav by default on mobile; it will slide down when active */
      nav.nav-links{
        display:none;
        position:absolute;
        left:0;right:0;
        top:64px; /* sidebar height */
        background:#fff;
        padding:12px 16px;
        flex-direction:column;
        gap:8px;
        box-shadow:0 6px 18px rgba(0,0,0,.08);
        border-bottom-left-radius:12px;
        border-bottom-right-radius:12px;
        z-index:40;
        transform-origin:top;
        transform:scaleY(0);
        transition:transform .22s ease, opacity .22s ease;
        opacity:0;
      }

      /* when sidebar has .open, show nav */
      .sidebar.open nav.nav-links{
        display:flex;
        transform:scaleY(1);
        opacity:1;
      }

      .toggle-btn{display:block;position:relative;z-index:60}

      main{padding:20px}
      .cards{grid-template-columns:1fr}
      .bottom{grid-template-columns:1fr}
      h1{font-size:18px}
    }

    /* small visual adjustments */
    .nav-sep{height:1px;background:#f0f0f0;margin:8px 0;border-radius:2px}
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div style="display:flex;align-items:center;gap:12px;">
      <div class="profile" style="margin:0;">
        <img src="https://placekitten.com/60/60" alt="avatar">
        <div>
          <div style="font-weight:700">kapron</div>
          <div style="font-size:12px;color:#777">Mitra</div>
        </div>
      </div>
    </div>

    <!-- tombol toggle (muncul di mobile) -->
    <button id="toggleBtn" class="toggle-btn" aria-controls="mainNav" aria-expanded="false">☰</button>

    <!-- navigasi -->
    <nav class="nav-links" id="mainNav" aria-hidden="true">
      <a href="#" class="active">🏠 Dasbor</a>
      <a href="#">📅 Reservasi</a>
      <a href="#">🐾 Kucing</a>
      <a href="#">⭐ Ulasan</a>
      <a href="#">📊 Laporan</a>
      <a href="#">👤 Profil</a>
      <div class="nav-sep"></div>
      <a href="#" class="logout">← Keluar</a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main id="mainContent" tabindex="-1">
    <h1>Halo, kapron petshop!</h1>
    <p class="sub">Berikut adalah ringkasan singkat tentang bisnis Anda</p>

    <div class="cards">
      <div class="card orange">
        <p>Pemesanan Aktif</p>
        <h2>12</h2>
        <small>+10%</small>
      </div>
      <div class="card">
        <p>Total Pendapatan Bulan Ini</p>
        <h2>Rp 1.000.000</h2>
        <small style="color:green">+5%</small>
      </div>
      <div class="card">
        <p>Tingkat Hunian</p>
        <h2>75%</h2>
        <small style="color:red">−2%</small>
      </div>
      <div class="card">
        <p>Peringkat Rata-Rata</p>
        <h2>4.8</h2>
        <small style="color:green">+1%</small>
      </div>
    </div>

    <div class="bottom">
      <div class="chart">
        <h3>Pemesanan Bulanan</h3>
        <h2>150</h2>
        <small style="color:green">Bulan Ini +20%</small>
        <div class="chart-bars">
          <div style="height:40%"></div>
          <div style="height:60%"></div>
          <div style="height:50%"></div>
          <div style="height:70%"></div>
          <div style="height:80%"></div>
          <div style="height:75%"></div>
        </div>
        <div style="display:flex;justify-content:space-between;color:#777;font-size:12px;margin-top:10px">
          <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
        </div>
      </div>

      <div class="rating">
        <h3>Rating</h3>
        <h2>4.8</h2>
        <small style="color:green">Rata-Rata +5%</small>

        <?php
          $stars = [15,25,40,60,80];
          for($i=1;$i<=5;$i++):
        ?>
        <div style="display:flex;align-items:center;gap:10px;margin-top:8px">
          <div style="width:70px;font-size:13px;color:#444"><?php echo $i ?> Bintang</div>
          <div style="flex:1;background:#eee;border-radius:6px;overflow:hidden;height:10px">
            <div style="width:<?php echo $stars[$i-1] ?>%;height:100%;background:#fbbf24"></div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </main>

  <script>
    (function(){
      const sidebar = document.getElementById('sidebar');
      const toggleBtn = document.getElementById('toggleBtn');
      const mainNav = document.getElementById('mainNav');
      const mainContent = document.getElementById('mainContent');

      function openSidebar() {
        sidebar.classList.add('open');
        toggleBtn.setAttribute('aria-expanded', 'true');
        mainNav.setAttribute('aria-hidden', 'false');
      }
      function closeSidebar() {
        sidebar.classList.remove('open');
        toggleBtn.setAttribute('aria-expanded', 'false');
        mainNav.setAttribute('aria-hidden', 'true');
      }

      // Toggle ketika tombol diklik
      toggleBtn.addEventListener('click', function(e){
        if(sidebar.classList.contains('open')) closeSidebar(); else openSidebar();
      });

      // Tutup menu saat klik di luar (hanya di mobile: window width <= 768)
      document.addEventListener('click', function(e){
        const w = window.innerWidth || document.documentElement.clientWidth;
        if(w <= 768){
          if(!sidebar.contains(e.target) && sidebar.classList.contains('open')){
            closeSidebar();
          }
        }
      });

      // Tutup menu saat tekan Escape
      document.addEventListener('keyup', function(e){
        if(e.key === 'Escape' && sidebar.classList.contains('open')){
          closeSidebar();
          toggleBtn.focus();
        }
      });

      // Optional: saat ukuran layar berubah, pastikan state konsisten
      window.addEventListener('resize', function(){
        const w = window.innerWidth || document.documentElement.clientWidth;
        if(w > 768){
          // selalu pastikan nav terlihat di layar besar
          sidebar.classList.remove('open');
          mainNav.style.transform = '';
        } else {
          // di mobile hide nav by default
          mainNav.setAttribute('aria-hidden', 'true');
          toggleBtn.setAttribute('aria-expanded', 'false');
        }
      });
    })();
  </script>
</body>
</html>

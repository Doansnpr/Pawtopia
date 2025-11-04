<?php
// app/views/dashboard_mitra/index.php
// FILE INI HANYA BERISI KONTEN, BUKAN TAG HTML LENGKAP!
?>

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

    <button id="toggleBtn" class="toggle-btn" aria-controls="mainNav" aria-expanded="false">☰</button>

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

<main id="mainContent" tabindex="-1">
    <h1>Halo, kapron petshop!</h1>
    <p class="sub">Berikut adalah ringkasan singkat tentang bisnis Anda</p>
</main>
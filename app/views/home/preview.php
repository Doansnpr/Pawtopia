<section class="preview-section" id="preview">
  <style>
    /* ====== PREVIEW / DEMO APLIKASI ====== */
    .preview-section {
      background-color: #dcf3ffff;
      padding: 0;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    /* Wave Atas - CEMBUNG KE ATAS */
    .wave-separator-top-preview {
      width: 100%;
      line-height: 0;
      background-color: #f0faff; /* Background dari section sebelumnya */
      height: 80px;
      overflow: hidden;
    }

    .wave-separator-top-preview svg {
      display: block;
      width: 100%;
      height: 80px;
      fill: #dcf3ffff; /* Warna section preview */
    }

    /* Content dengan padding */
    .preview-content-wrapper {
      padding: 100px 40px;
      position: relative;
    }

    /* Dekorasi Background Pattern */
    .preview-content-wrapper::before {
      content: "";
      position: absolute;
      top: -50px;
      left: -50px;
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, rgba(255,153,51,0.1) 0%, transparent 70%);
      border-radius: 50%;
    }

    .preview-content-wrapper::after {
      content: "";
      position: absolute;
      bottom: -80px;
      right: -80px;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(26,168,224,0.1) 0%, transparent 70%);
      border-radius: 50%;
    }

    .judul-preview {
      font-family: 'Patrick Hand', cursive;
      color: #ff9933;
      font-size: 42px;
      margin-bottom: 15px;
      text-shadow: 3px 3px 0px rgba(255, 153, 51, 0.2);
      position: relative;
      z-index: 1;
    }

    .deskripsi-preview {
      font-size: 18px;
      color: #1a3046;
      margin-bottom: 70px;
      max-width: 650px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.6;
      position: relative;
      z-index: 1;
    }

    .preview-container {
      display: flex;
      flex-direction: column;
      gap: 80px;
      max-width: 1200px;
      margin: 0 auto;
      position: relative;
      z-index: 1;
    }

    .preview-card {
      display: grid;
      grid-template-columns: 1fr 1.2fr;
      gap: 40px;
      align-items: center;
      background: white;
      border-radius: 30px;
      padding: 40px;
      box-shadow: 0 15px 40px rgba(0,0,0,0.08);
      transition: all 0.4s ease;
      position: relative;
    }

    /* Alternating Layout */
    .preview-card:nth-child(even) {
      grid-template-columns: 1.2fr 1fr;
    }

    .preview-card:nth-child(even) .preview-content {
      order: 2;
    }

    .preview-card:nth-child(even) .preview-image {
      order: 1;
    }

    .preview-card:hover {
      transform: scale(1.02);
      box-shadow: 0 20px 50px rgba(0,0,0,0.12);
    }

    /* Badge Style untuk Header */
    .preview-badge {
      display: inline-block;
      font-family: 'Patrick Hand', cursive;
      color: white;
      font-size: 20px;
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: bold;
      margin-bottom: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      position: relative;
    }

    .preview-mitra .preview-badge {
      background: linear-gradient(135deg, #1ba8e0 0%, #0d8ab8 100%);
    }

    .preview-customer .preview-badge {
      background: linear-gradient(135deg, #267a4d 0%, #1a5a37 100%);
    }

    /* Icon Dekoratif */
    .preview-badge::before {
      position: absolute;
      left: -25px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 24px;
    }

    .preview-content {
      text-align: left;
    }

    .preview-title {
      font-family: 'Patrick Hand', cursive;
      font-size: 28px;
      color: #1a3046;
      margin-bottom: 15px;
      font-weight: bold;
    }

    .preview-description {
      font-size: 16px;
      color: #666;
      line-height: 1.8;
      margin-bottom: 20px;
    }

    .preview-features {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .preview-features li {
      padding: 8px 0;
      color: #555;
      font-size: 15px;
      display: flex;
      align-items: center;
    }

    .preview-features li::before {
      content: "✨";
      margin-right: 10px;
      font-size: 18px;
    }

    .preview-image {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      transition: transform 0.3s ease;
    }

    .preview-image:hover {
      transform: scale(1.05);
    }

    .preview-image img {
      width: 100%;
      height: auto;
      display: block;
      border-radius: 20px;
    }

    /* Decorative Border */
    .preview-image::before {
      content: "";
      position: absolute;
      top: -5px;
      left: -5px;
      right: -5px;
      bottom: -5px;
      border: 3px dashed rgba(255, 153, 51, 0.3);
      border-radius: 25px;
      pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 992px) {
      .preview-card,
      .preview-card:nth-child(even) {
        grid-template-columns: 1fr;
        gap: 30px;
        padding: 30px;
      }

      .preview-card:nth-child(even) .preview-content,
      .preview-card:nth-child(even) .preview-image {
        order: unset;
      }

      .preview-content {
        text-align: center;
      }

      .preview-features li {
        justify-content: center;
      }
    }

    @media (max-width: 768px) {
      .wave-separator-top-preview {
        height: 50px;
      }

      .wave-separator-top-preview svg {
        height: 50px;
      }

      .preview-content-wrapper {
        padding: 60px 20px;
      }

      .judul-preview {
        font-size: 32px;
      }

      .deskripsi-preview {
        font-size: 16px;
      }

      .preview-container {
        gap: 50px;
      }

      .preview-title {
        font-size: 24px;
      }

      .preview-badge {
        font-size: 18px;
        padding: 10px 25px;
      }
    }
  </style>

  <!-- Wave Atas -->
  <div class="wave-separator-top-preview">
    <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
      <path fill-opacity="1" d="M0,100L48,85.3C96,71,192,43,288,37.3C384,32,480,48,576,58.7C672,69,768,75,864,69.3C960,64,1056,48,1152,42.7C1248,37,1344,43,1392,45.3L1440,48L1440,100L1392,100C1344,100,1248,100,1152,100C1056,100,960,100,864,100C768,100,672,100,576,100C480,100,384,100,288,100C192,100,96,100,48,100L0,100Z"></path>
    </svg>
  </div>

  <!-- Content Preview -->
  <div class="preview-content-wrapper">
    <div class="container">
      <h2 class="judul-preview">Lihat Kemudahan Pawtopia</h2>
      <p class="deskripsi-preview">
        Platform yang dirancang khusus untuk memudahkan customer dan mitra dalam mengelola penitipan kucing
      </p>

      <div class="preview-container">
        <!-- Preview Dashboard Mitra -->
        <div class="preview-card preview-mitra">
          <div class="preview-content">
            <span class="preview-badge">Untuk Mitra</span>
            <h3 class="preview-title">Dashboard Pengelolaan</h3>
            <p class="preview-description">
              Kelola bisnis petshop dengan mudah melalui dashboard yang informatif dan user-friendly
            </p>
            <ul class="preview-features">
              <li>Statistik pendapatan real-time</li>
              <li>Monitor pemesanan aktif</li>
              <li>Lihat rating & review pelanggan</li>
            </ul>
          </div>
          <div class="preview-image">
            <img src="<?= BASEURL; ?>/images/dashboard-mitra.png" alt="Dashboard Mitra Pawtopia">
          </div>
        </div>

        <!-- Preview Booking Customer -->
        <div class="preview-card preview-customer">
          <div class="preview-content">
            <span class="preview-badge">Untuk Customer</span>
            <h3 class="preview-title">Booking Penitipan</h3>
            <p class="preview-description">
              Pesan tempat penitipan kucing dengan mudah dan cepat melalui form booking yang simple
            </p>
            <ul class="preview-features">
              <li>Isi data penitipan dengan lengkap</li>
              <li>Pilih metode pembayaran</li>
              <li>Konfirmasi booking langsung</li>
            </ul>
          </div>
          <div class="preview-image">
            <img src="<?= BASEURL; ?>/images/booking-customer.jpeg" alt="Booking Customer Pawtopia">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
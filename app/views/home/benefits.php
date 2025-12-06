<section class="benefits-section" id="benefits">
  <style>
    /* ====== KENAPA MEMILIH PAWTOPIA ====== */
    .benefits-section {
      background-color: #dcf3ffff;
      padding: 0;
      text-align: center;
      position: relative;
    }

    /* Wave Atas */
    .wave-separator-top {
      width: 100%;
      line-height: 0;
      background-color: transparent;
      height: 80px;
      overflow: hidden;
    }

    .wave-separator-top svg {
      display: block;
      width: 100%;
      height: 80px;
      fill: #f0faff;
    }

    /* Content dengan padding */
    .benefits-content {
      padding: 60px 40px 80px 40px;
    }

    .judul-benefits {
      font-family: 'Patrick Hand', cursive;
      color: #ff9933;
      font-size: 42px;
      margin-bottom: 50px;
      text-shadow: 3px 3px 0px rgba(255, 153, 51, 0.2);
    }

    .benefits-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .benefit-group {
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: 0.3s ease;
    }

    .benefit-group:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .benefit-title {
      font-family: 'Patrick Hand', cursive;
      font-size: 24px;
      color: white;
      padding: 15px;
      border-radius: 15px;
      margin-bottom: 25px;
    }

    .for-customer .benefit-title {
      background: #267a4d;
    }

    .for-mitra .benefit-title {
      background: #1ba8e0;
    }

    .benefit-list {
      list-style: none;
      padding: 0;
      margin: 0;
      text-align: left;
    }

    .benefit-item {
      font-size: 16px;
      color: #1a3046;
      padding: 12px 0;
      border-bottom: 1px solid #e0e0e0;
      display: flex;
      align-items: center;
    }

    .benefit-item:last-child {
      border-bottom: none;
    }

    .benefit-item::before {
      content: "✓";
      color: #ff9933;
      font-weight: bold;
      font-size: 20px;
      margin-right: 12px;
      flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .wave-separator-top {
        height: 50px;
      }

      .wave-separator-top svg {
        height: 50px;
      }

      .benefits-content {
        padding: 40px 20px 60px 20px;
      }

      .judul-benefits {
        font-size: 28px;
      }

      .benefit-title {
        font-size: 20px;
      }

      .benefit-item {
        font-size: 14px;
      }
    }
  </style>

  <!-- Wave Atas -->
  <div class="wave-separator-top">
    <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
      <path fill-opacity="1" d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,64C960,75,1056,85,1152,80C1248,75,1344,53,1392,42.7L1440,32L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
    </svg>
  </div>

  <!-- Konten Benefits -->
  <div class="benefits-content">
    <div class="container">
      <h2 class="judul-benefits">Kenapa Memilih Pawtopia?</h2>

      <div class="benefits-container">
        <!-- Benefits untuk Customer -->
        <div class="benefit-group for-customer">
          <div class="benefit-title">Untuk Customer</div>
          <ul class="benefit-list">
            <li class="benefit-item">Lihat review & rating asli</li>
            <li class="benefit-item">Tahu harga dan fasilitas langsung</li>
            <li class="benefit-item">Booking aman & terverifikasi</li>
          </ul>
        </div>

        <!-- Benefits untuk Mitra -->
        <div class="benefit-group for-mitra">
          <div class="benefit-title">Untuk Mitra</div>
          <ul class="benefit-list">
            <li class="benefit-item">Dapat pelanggan baru</li>
            <li class="benefit-item">Sistem laporan otomatis</li>
            <li class="benefit-item">Dashboard simpel</li>
            <li class="benefit-item">Gratis tampil di platform</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
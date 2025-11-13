<section class="fasilitas-section" id="fasilitas">
  <style>
    /* ====== FASILITAS PAWTOPIA ====== */
    .fasilitas-section {
      background-image: url('images/FASILITAS.png'); /* ganti sesuai lokasi gambarmu */
      background-size: cover;     /* biar gambar nutup penuh */
      background-position: center;
      background-repeat: no-repeat;
      padding: 60px 20px;
      text-align: center;
      border-radius: 0 0 60px 60px;
      color: white;
      position: relative;
    }


    .judul-fasilitas {
      /* font-family: 'Irish Gover', cursive; */
      /* color: #d68a00;
      font-size: 2em;
      margin-bottom: 10px; */
      color: #ff9933;
      font-size: 26px;
      margin-bottom: 15px;
      text-shadow: 2px 2px 5px rgba(255, 255, 255, 1);
    }

    .deskripsi-fasilitas {
      margin-bottom: 40px;
      color: #1a3046ff; 
      max-width: 650px;
      line-height: 1;
      display: inline-block;
      border-radius: 10px;
      font-size: 17px;
    }

    .fasilitas-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
      justify-items: center;
    }

    .fasilitas-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      width: 230px;
      overflow: hidden;
      transition: 0.3s ease;
    }

    .fasilitas-card:hover {
      transform: translateY(-8px);
    }

    .fasilitas-img img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .fasilitas-text {
      font-family: 'Patrick Hand', cursive;
      color: white;
      font-size: 1.1em;
      padding: 15px;
      border-radius: 0 0 20px 20px;
      margin: 0;
    }

    /* Warna Header Tiap Kartu */
    .green .fasilitas-text {
      background: #267a4d;
    }
    .orange .fasilitas-text {
      background: #e78a00;
    }
    .red .fasilitas-text {
      background: #b80000;
    }
    .blue .fasilitas-text {
      background: #1ba8e0;
    }
  </style>

  <div class="container">
    <h2 class="judul-fasilitas">Fasilitas Pawtopia</h2>
    <p class="deskripsi-fasilitas">
      Setiap mitra Pawtopia menyediakan fasilitas terbaik agar kucingmu nyaman dan bahagia
    </p>

    <div class="fasilitas-grid">
      <div class="fasilitas-card green">
        <div class="fasilitas-img">
          <img src="<?= BASEURL; ?>/images/kandang.png" alt="Kandang Bersih & Luas">
        </div>
        <p class="fasilitas-text">Kandang Bersih & Luas</p>
      </div>

      <div class="fasilitas-card orange">
        <div class="fasilitas-img">
          <img src="<?= BASEURL; ?>/images/area_bermain.png" alt="Area Bermain Aman">
        </div>
        <p class="fasilitas-text">Area Bermain Aman</p>
      </div>

      <div class="fasilitas-card red">
        <div class="fasilitas-img">
          <img src="<?= BASEURL; ?>/images/grooming.png" alt="Grooming & Perawatan">
        </div>
        <p class="fasilitas-text">Grooming & Perawatan</p>
      </div>

      <div class="fasilitas-card blue">
        <div class="fasilitas-img">
          <img src="<?= BASEURL; ?>/images/cctv.png" alt="CCTV & Pengawas Staf">
        </div>
        <p class="fasilitas-text">CCTV & Pengawas Staf</p>
      </div>
    </div>
  </div>
</section>
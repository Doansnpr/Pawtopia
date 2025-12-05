<style>
    /* --- WADAH UTAMA KONTEN (PENTING AGAR TIDAK MELEBAR) --- */
    /* Ini memastikan konten tidak akan pernah lebih lebar dari .main */
    .content-wrapper {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box; 
    }

    .container {
        max-width: 100%;
        width: 100%;
        margin: 0 auto;
        padding: 5px;
    }


    /* --- 1. HEADER & TYPOGRAPHY --- */
    .page-header { margin-bottom: 1.5rem; }
    .page-header h2 { font-size: 1.5rem; color: #333; margin: 0; font-weight: 700; }
    .page-header p { color: #666; font-size: 0.9rem; margin-top: 5px; }

    /* --- 2. HERO SEARCH (LEBAR DIJAMIN 100%) --- */
    .search-hero {
        background: linear-gradient(135deg, #f3b83f 0%, #ff9f43 100%);
        border-radius: 16px;
        padding: 2rem; /* DIKURANGI padding */
        color: white;
        box-shadow: 0 4px 15px rgba(243, 184, 63, 0.3);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        width: 100%; 
        box-sizing: border-box; 
    }
    
    /* Icon Background */
    .search-hero .bg-icon {
        position: absolute; right: -20px; bottom: -30px;
        font-size: 10rem; opacity: 0.15; color: white; transform: rotate(-15deg);
        pointer-events: none;
    }

    .search-content {
        position: relative; z-index: 2; text-align: center;
        max-width: 700px; margin: 0 auto; 
    }

    .search-input-wrapper {
        position: relative;
        width: 100%;
        margin-top: 1.5rem;
    }
    
    .search-input {
        width: 100%;
        padding: 15px 55px 15px 25px; /* DIKURANGI padding vertical */
        border-radius: 50px;
        border: none;
        outline: none;
        font-size: 1rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        box-sizing: border-box; 
    }
    
    .search-btn-circle {
        position: absolute; right: 8px; top: 8px;
        height: 40px; width: 40px; /* DIKURANGI ukuran tombol */
        background: #f3b83f; border: none; border-radius: 50%;
        color: white; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
    }
    .search-btn-circle:hover { background: #d99f28; transform: scale(1.05); }

    /* --- 3. SLIDER SECTION (FIXED OVERFLOW) --- */
    .slider-section {
        margin-bottom: 2.5rem;
        width: 100%; 
        position: relative;
        /* Tambahkan padding agar card tidak menempel di tepi */
        padding: 0 5px; 
        box-sizing: border-box;
    }
    
    .slider-outer-wrapper {
        position: relative;
        width: 100%;
        overflow: hidden; 
    }

    /* ********* PERBAIKAN KRITIS SLIDER ********* */
    .slider-container {
        display: flex;
        overflow-x: auto;
        gap: 1.2rem;
        padding: 5px 0 20px 0; /* Padding horizontal 0 agar tidak melebar */
        scroll-behavior: smooth;
        -ms-overflow-style: none;
        scrollbar-width: none;
        width: 100%;
        box-sizing: border-box;
    }
    .slider-container::-webkit-scrollbar { display: none; }

    /* HILANGKAN TOMBOL PANAH AGAR TIDAK MELEBAR */
    .slide-nav-btn { display: none !important; }

    /* --- 4. GRID SYSTEM (MITRA LIST) --- */
    .mitra-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
        gap: 1.5rem;
        width: 100%;
        box-sizing: border-box;
    }

    /* --- 5. CARD STYLE (KONSISTEN) --- */
    .mitra-card {
        background: white;
        border-radius: 16px;
        padding: 1.2rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
        display: flex; flex-direction: column;
        text-decoration: none; color: inherit;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%; 
        position: relative;
    }
    .mitra-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-color: #f3b83f;
    }

    .card-img {
        width: 100%; height: 180px;
        object-fit: cover; border-radius: 12px;
        margin-bottom: 1rem; border: 1px solid #f9f9f9;
    }

    .card-title { font-size: 1.1rem; font-weight: 700; color: #333; margin: 0 0 5px 0; }
    .card-loc { font-size: 0.85rem; color: #888; display: flex; align-items: center; gap: 5px; margin-bottom: 15px; }
    
    .price-section {
        margin-top: auto; padding-top: 15px;
        border-top: 1px dashed #eee;
        display: flex; justify-content: space-between; align-items: center;
    }
    .price-tag { color: #f3b83f; font-weight: 700; font-size: 1rem; }
    .rating-tag { background:#fff8e1; color:#ffb300; padding:4px 8px; border-radius:6px; font-size:0.8rem; font-weight:600; }

    /* Badge New */
    .badge-new {
        position: absolute; top: 15px; left: 15px;
        background: #ff4757; color: white;
        padding: 4px 10px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 700;
        box-shadow: 0 2px 5px rgba(255, 71, 87, 0.4);
        z-index: 5;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .search-hero { padding: 1.5rem 1rem; } 
        .bg-icon { display: none; }
        .mitra-grid { 
            grid-template-columns: 1fr; /* Di HP, paksa 1 kolom agar aman */
        }
        .slider-section { padding: 0; } /* Hapus padding agar slider full */
    }
</style>

<div class="content-wrapper">

    <div class="page-header">
        <h2>Cari Penitipan <i class="fa-solid fa-magnifying-glass-location" style="color:#f3b83f;"></i></h2>
        <p>Temukan mitra terbaik untuk anabul kesayanganmu.</p>
    </div>

    <div class="search-hero">
        <i class="fa-solid fa-cat bg-icon"></i>
        
        <div class="search-content">
            <h3 style="margin:0; font-size:1.8rem; font-weight:700;">Mau titip di mana hari ini?</h3>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Kami punya banyak mitra terpercaya untukmu.</p>
            
            <form action="" method="GET">
                <div class="search-input-wrapper">
                    <input type="text" name="q" class="search-input" placeholder="Cari nama petshop, kota, atau daerah..." value="<?= isset($data['keyword']) ? htmlspecialchars($data['keyword']) : '' ?>">
                    <button type="submit" class="search-btn-circle"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <?php if(empty($data['keyword']) && !empty($data['hotArrivals'])): ?>
    <div class="slider-section">
        <h3 style="font-size:1.2rem; color:#333; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">
            <i class="fa-solid fa-fire" style="color:#ff4757;"></i> Pendatang Baru
        </h3>
        
        <div class="slider-outer-wrapper">
            <div class="slider-container" id="hotSlider">
                <?php foreach($data['hotArrivals'] as $new): ?>
                <a href="<?= BASEURL ?>/DashboardCustomer/detail_mitra/<?= $new['id_mitra'] ?>" class="mitra-card" style="min-width: 250px; max-width: 250px;">
                    <div class="badge-new">NEW</div>
                    <img src="<?= BASEURL ?>/images/mitra/<?= $new['foto_profil'] ?>" class="card-img" style="height:140px;">
                    
                    <div>
                        <h4 class="card-title" style="font-size:1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars($new['nama_petshop']) ?></h4>
                    </div>
                    
                    <div class="price-section" style="padding-top:10px; margin-top:5px;">
                        <span class="price-tag" style="font-size:0.9rem;">
                            <?= !empty($new['harga_mulai']) ? 'Rp '.number_format($new['harga_mulai'],0,',','.') : 'Cek Harga' ?>
                        </span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div style="margin-top: 2rem;">
        <h3 style="font-size:1.2rem; color:#333; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">
            <?= !empty($data['keyword']) ? '🔍 Hasil Pencarian' : '<i class="fa-solid fa-store" style="color:#f3b83f;"></i> Jelajahi Mitra' ?>
        </h3>

        <div class="mitra-grid">
            <?php if(empty($data['mitraList'])): ?>
                <div style="grid-column: 1/-1; text-align:center; padding:3rem; background:white; border-radius:16px; border:2px dashed #ddd; color:#999;">
                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 10px; display:block; opacity:0.5;"></i>
                    Belum ada data penitipan yang tersedia :(
                </div>
            <?php else: ?>

                <?php foreach($data['mitraList'] as $mitra): ?>
                <a href="<?= BASEURL ?>/DashboardCustomer/detail_mitra/<?= $mitra['id_mitra'] ?>" class="mitra-card">
                    <img src="<?= BASEURL ?>/images/mitra/<?= $mitra['foto_profil'] ?>" class="card-img">
                    
                    <div>
                        <h4 class="card-title"><?= htmlspecialchars($mitra['nama_petshop']) ?></h4>
                        <div class="card-loc">
                            <i class="fas fa-map-marker-alt" style="color:#f3b83f;"></i>
                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars(substr($mitra['alamat'], 0, 30)) ?>...
                            </span>
                        </div>
                    </div>

                    <div class="price-section">
                        <span class="price-tag">
                            <?php if(!empty($mitra['harga_mulai'])): ?>
                                Rp <?= number_format($mitra['harga_mulai'], 0, ',', '.') ?>
                            <?php else: ?>
                                Tanya Admin
                            <?php endif; ?>
                        </span>
                        <span class="rating-tag">
                            <i class="fas fa-star"></i> <?= !empty($mitra['rating_rata']) ? number_format($mitra['rating_rata'], 1) : 'New' ?>
                        </span>
                    </div>
                </a>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>

</div> <script>
    // Fungsi scroll tetap dipertahankan jika pengguna ingin menggunakan JS untuk scroll
    function scrollSlider(direction) {
        const container = document.getElementById('hotSlider');
        const scrollAmount = 320; 
        if (direction === 1) {
            container.scrollLeft += scrollAmount;
        } else {
            container.scrollLeft -= scrollAmount;
        }
    }
</script>
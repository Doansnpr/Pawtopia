<?php
$data_stats = [
    [
        'title' => 'Pemesanan Aktif',
        'value' => '12',
        'change' => '+10%',
        'change_class' => 'positive',
        'highlight' => true
    ],
    [
        'title' => 'Total Pendapatan Bulan Ini',
        'value' => 'Rp 1.000.000',
        'change' => '+5%',
        'change_class' => 'positive',
        'highlight' => false
    ],
    [
        'title' => 'Tingkat Hunian',
        'value' => '75%',
        'change' => '-2%',
        'change_class' => 'negative',
        'highlight' => false
    ],
    [
        'title' => 'Peringkat Rata-Rata',
        'value' => '4.8',
        'change' => '+1%',
        'change_class' => 'positive',
        'highlight' => false
    ]
];
?>

<style>
    /* Tambahan CSS khusus untuk konten ini */
    .stat-card {
        padding: 20px;
        border-radius: 8px;
        background-color: var(--main-bg);
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .stat-card.highlight {
        background-color: #ff990f; /* Orange sesuai gambar */
        color: white;
        border: none;
    }

    .stat-card.highlight .stat-title,
    .stat-card.highlight .stat-change.positive,
    .stat-card.highlight .stat-change.negative {
        color: white; /* Semua teks jadi putih di highlight card */
    }

    .stat-title {
        font-size: 0.9rem;
        color: var(--text-gray);
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.2;
        margin-bottom: 5px;
    }

    .stat-change {
        font-size: 0.85rem;
        font-weight: 500;
    }

    .stat-change.positive {
        color: #10b981; /* Green */
    }

    .stat-change.negative {
        color: #ef4444; /* Red */
    }

    .block-header {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 15px;
    }

    /* Styling Rating Bar */
    .rating-bar {
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: var(--text-gray);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .bar-container {
        flex-grow: 1;
        height: 8px;
        background-color: var(--placeholder-gray);
        border-radius: 4px;
        overflow: hidden;
    }
    .bar-fill {
        height: 100%;
        background-color: var(--primary-blue); /* Gunakan warna primary */
    }
    
    /* Override grid untuk statistik (membuat 4 kartu di baris pertama) */
    .stats-container {
        grid-column: 1 / -1; /* Ambil seluruh lebar */
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    /* Penyesuaian blok utama agar Pemesanan Bulanan (kiri) dan Rating (kanan) sejajar */
    .dashboard-content {
        /* Hapus definisi grid utama, kita akan membuatnya manual */
        display: block;
        padding: 30px;
    }

    .main-grid-row {
        display: grid;
        grid-template-columns: 1fr 1fr; /* Dua kolom besar */
        gap: 20px;
    }
    
    .main-grid-item {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 20px;
        background-color: var(--main-bg);
    }
    
    .rating-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.1;
    }

    .rating-subtitle {
        font-size: 0.9rem;
        color: var(--text-gray);
        margin-top: -5px;
        margin-bottom: 20px;
    }
</style>

<div class="dashboard-content">

    <div class="content-header-placeholder" style="margin-bottom: 20px;">
        <div style="display: block;">
            <h1 style="font-size: 1.8rem; color: var(--text-dark); font-weight: 700;">Halo, kapron petshop!</h1>
            <p style="font-size: 0.95rem; color: var(--text-gray); margin-top: 5px;">Berikut adalah ringkasan singkat tentang bisnis Anda</p>
        </div>
        <!-- <div style="width: 80px; height: 30px; background-color: transparent; font-size: 1.5rem; font-weight: bold; color: var(--text-dark);">
            P T.
        </div> -->
    </div>
    
    <div class="stats-container">
        <?php foreach ($data_stats as $stat): ?>
        <div class="stat-card <?= $stat['highlight'] ? 'highlight' : ''; ?>">
            <p class="stat-title"><?= htmlspecialchars($stat['title']); ?></p>
            <p class="stat-value" style="<?= $stat['highlight'] ? 'color: white !important;' : ''; ?>">
                <?= htmlspecialchars($stat['value']); ?>
            </p>
            <p class="stat-change <?= htmlspecialchars($stat['change_class']); ?>">
                <?= htmlspecialchars($stat['change']); ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="main-grid-row">
        
        <div class="main-grid-item" style="min-height: 350px;">
            <div class="block-header">Pemesanan Bulanan</div>
            <div class="rating-value">150</div>
            <p class="rating-subtitle stat-change positive">Bulan Ini +20%</p>
            
            <div style="height: 180px; background-color: var(--light-bg); border-radius: 6px; display: flex; align-items: flex-end; justify-content: space-around; padding: 10px;">
                <?php for($i=1; $i<=6; $i++): ?>
                <div style="width: 10%; height: <?= rand(30, 80); ?>%; background-color: var(--placeholder-gray); border-radius: 4px;"></div>
                <?php endfor; ?>
            </div>
            <div style="display: flex; justify-content: space-around; font-size: 0.8rem; color: var(--text-gray); margin-top: 5px;">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
            </div>
        </div>

        <div class="main-grid-item" style="min-height: 350px;">
            <div class="block-header">Rating</div>
            <div class="rating-value">4.8</div>
            <p class="rating-subtitle stat-change positive">Rata-Rata +5%</p>

            <?php 
                $ratings = ['1 Bintang' => 15, '2 Bintang' => 40, '3 Bintang' => 30, '4 Bintang' => 55, '5 Bintang' => 75];
                $max_rating = max($ratings);
            ?>
            <?php foreach ($ratings as $label => $value): ?>
            <div class="rating-bar">
                <span style="width: 70px;"><?= $label; ?></span>
                <div class="bar-container">
                    <div class="bar-fill" style="width: <?= ($value / $max_rating) * 100; ?>%;"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
    </div>
    
    </div>
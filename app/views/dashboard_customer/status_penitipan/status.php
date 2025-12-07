<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>
<style>
    .container { width: 100%; margin: 0 auto; }
    .text-muted { color: #888; font-size: 0.85rem; }

    /* --- NAVIGASI TABS (Booking List) --- */
    .booking-tabs {
        display: flex; gap: 10px; overflow-x: auto; 
        padding-bottom: 15px; margin-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }
    .booking-tab-item {
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 20px;
        background: white;
        border: 1px solid #ddd;
        color: #555;
        font-weight: 600;
        font-size: 0.9rem;
        white-space: nowrap;
        transition: all 0.2s;
        display: flex; flex-direction: column; align-items: center;
    }
    .booking-tab-item:hover { background: #f9f9f9; border-color: #ccc; }
    
    .booking-tab-item.active {
        background: #f3b83f; color: white; border-color: #f3b83f;
        box-shadow: 0 4px 10px rgba(243, 184, 63, 0.3);
    }
    .booking-tab-date { font-size: 0.75rem; font-weight: 400; opacity: 0.9; }

    /* --- GRID SYSTEM --- */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        align-items: start;
    }

    /* CARD STYLE */
    .card-box {
        background: white; border-radius: 16px; padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;
        height: fit-content; transition: transform 0.2s; position: relative;
    }
    .card-box:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }

    /* PROFIL KUCING */
    .cat-profile-header { text-align: center; margin-bottom: 1.5rem; }
    .cat-image-wrapper { width: 120px; height: 120px; margin: 0 auto 15px; position: relative; }
    .cat-image {
        width: 100%; height: 100%; object-fit: cover; border-radius: 50%;
        border: 4px solid #fff8e1; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .cat-name { font-size: 1.4rem; font-weight: 700; color: #333; margin-bottom: 5px; }
    .status-badge {
        display: inline-block; padding: 6px 16px; border-radius: 20px;
        font-size: 0.85rem; font-weight: 600;
        background: #fff8e1; color: #f3b83f; border: 1px solid #ffe082;
    }

    .info-list { list-style: none; margin-top: 20px; padding: 0; }
    .info-item {
        display: flex; justify-content: space-between; padding: 12px 0;
        border-bottom: 1px dashed #eee; font-size: 0.9rem;
    }
    .info-label { color: #888; font-weight: 500; }
    .info-val { font-weight: 600; color: #555; text-align: right; }

    /* BUTTONS */
    .btn-action {
        display: flex; justify-content: center; align-items: center; gap: 8px;
        width: 100%; padding: 12px; border-radius: 12px;
        text-align: center; text-decoration: none; font-weight: 600; margin-top: 15px;
        border: none; cursor: pointer; box-sizing: border-box;
    }
    .btn-primary {
        background: linear-gradient(135deg, #f3b83f 0%, #ff9f43 100%);
        color: white; box-shadow: 0 4px 10px rgba(243, 184, 63, 0.3);
    }
    .btn-disabled { background: #e0e0e0; color: #999; cursor: default; }

    .btn-journal {
        display: flex; justify-content: center; align-items: center; gap: 8px;
        width: 100%; padding: 10px; border-radius: 12px;
        background: #fff8e1; color: #f3b83f; border: 1px solid #ffe082;
        font-weight: 600; margin-top: 20px; cursor: pointer; transition: all 0.2s;
    }
    .btn-journal:hover { background: #ffe082; color: #fff; }

    /* MODAL */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); z-index: 1000;
        justify-content: center; align-items: center;
        opacity: 0; transition: opacity 0.3s ease;
    }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-content {
        background: white; width: 90%; max-width: 500px; border-radius: 16px;
        padding: 25px; max-height: 80vh; overflow-y: auto;
        transform: translateY(20px); transition: transform 0.3s ease;
    }
    .modal-overlay.active .modal-content { transform: translateY(0); }
    .close-btn {
        position: absolute; top: 15px; right: 20px; font-size: 1.5rem;
        color: #999; cursor: pointer; background: none; border: none;
    }

    /* TIMELINE */
    .timeline-container { position: relative; padding-left: 20px; margin-top: 10px; }
    .timeline-container::before {
        content: ''; position: absolute; left: 6px; top: 5px; bottom: 0; width: 2px; background: #f0f0f0;
    }
    .timeline-item { position: relative; margin-bottom: 25px; padding-left: 30px; }
    .timeline-dot {
        position: absolute; left: 0; top: 0; width: 14px; height: 14px;
        background: #fff; border: 3px solid #f3b83f; border-radius: 50%; z-index: 2;
    }
    .t-time { font-size: 0.75rem; color: #999; margin-bottom: 4px; display: block; }
    .t-title { font-size: 1rem; font-weight: 600; color: #333; display: flex; align-items: center; gap: 8px; }
    .t-desc { 
        font-size: 0.9rem; color: #666; margin-top: 5px; background: #fafafa;
        padding: 10px; border-radius: 8px; border-left: 3px solid #ffe082;
    }
    .log-img {
        margin-top: 10px; width: 80px; height: 80px; object-fit: cover;
        border-radius: 8px; border: 1px solid #eee; cursor: pointer;
    }
    .empty-state { text-align: center; color: #999; padding: 20px 0; }

    @media (max-width: 900px) { .status-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <h2>Status Penitipan <i class="fa-solid fa-satellite-dish" style="color:#f3b83f; font-size:1.2rem;"></i></h2>
    <p>Pantau aktivitas kucing kesayanganmu secara realtime.</p>
</div>

<?php if (!empty($sidebar_bookings)): ?>

    <div class="booking-tabs">
        <?php foreach ($sidebar_bookings as $b): ?>
            <?php 
                $isActive = ($b['id_booking'] == $active_id) ? 'active' : ''; 
                $tgl = date('d M Y', strtotime($b['tgl_booking']));
            ?>
            
            <a href="<?= BASEURL; ?>/DashboardCustomer/status_penitipan/<?= $b['id_booking']; ?>" 
            class="booking-tab-item <?= $isActive; ?>">
                
                <span>Booking #<?= $b['id_booking']; ?></span>
                <span class="booking-tab-date"><?= $tgl; ?> • <?= $b['status']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="status-grid">
        
        <?php foreach ($detail_booking as $cat): ?>
            <div class="card-box">
                <div class="cat-profile-header">
                    <div class="cat-image-wrapper">
                        <img src="<?= $cat['foto_kucing_url'] . '?v=' . time(); ?>" 
                             class="cat-image" 
                             alt="Foto Kucing"
                             onerror="this.onerror=null; this.src='/pawtopia/public/images/default-cat.jpg';">
                    </div>
                    <h3 class="cat-name"><?= htmlspecialchars($cat['nama_kucing']); ?></h3>
                    
                    <div class="status-badge">
                        <?= htmlspecialchars($cat['status_utama']); ?>
                    </div>
                </div>

                <ul class="info-list">
                    <li class="info-item">
                        <span class="info-label"><i class="fa-solid fa-dna"></i> Ras</span>
                        <span class="info-val"><?= htmlspecialchars($cat['ras']); ?></span>
                    </li>
                    <li class="info-item">
                        <span class="info-label"><i class="fa-solid fa-venus-mars"></i> Gender</span>
                        <span class="info-val"><?= htmlspecialchars($cat['jenis_kelamin']); ?></span>
                    </li>
                    <li class="info-item">
                        <span class="info-label"><i class="fa-solid fa-cake-candles"></i> Umur</span>
                        <span class="info-val"><?= htmlspecialchars($cat['umur']); ?> Bulan</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label"><i class="fa-solid fa-shop"></i> Mitra</span>
                        <span class="info-val"><?= htmlspecialchars($cat['nama_petshop']); ?></span>
                    </li>
                </ul>
                
                <div style="margin-top: 15px; padding: 10px; background: #fff8e1; border-radius: 8px; font-size: 0.85rem; color: #666;">
                    <strong><i class="fa-regular fa-note-sticky"></i> Catatan:</strong><br>
                    <?= htmlspecialchars($cat['keterangan'] ?? '-'); ?>
                </div>

                <button onclick="openModal('modal-<?= $cat['id_kucing']; ?>')" class="btn-journal">
                    <i class="fa-solid fa-list-ul"></i> Lihat Jurnal Aktivitas
                </button>

                <?php if($cat['status_utama'] == 'Siap Dijemput' || $cat['status_utama'] == 'Selesai'): ?>
                    <a href="<?= BASEURL; ?>/DashboardCustomer/selesaikan_pesanan/<?= $cat['id_booking']; ?>" class="btn-action btn-primary">
                        <i class="fa-solid fa-check-circle"></i> Konfirmasi Selesai
                    </a>
                <?php else: ?>
                    <button class="btn-action btn-disabled">
                        <i class="fa-solid fa-clock"></i> Menunggu Proses
                    </button>
                <?php endif; ?>
            </div>

            <div id="modal-<?= $cat['id_kucing']; ?>" class="modal-overlay">
                <div class="modal-content">
                    <button class="close-btn" onclick="closeModal('modal-<?= $cat['id_kucing']; ?>')">&times;</button>
                    
                    <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                        <h3 style="color:#333; margin:0;">Jurnal: <?= htmlspecialchars($cat['nama_kucing']); ?></h3>
                        <span class="text-muted">Riwayat aktivitas selama penitipan</span>
                    </div>

                    <div class="timeline-container">
                        <?php 
                            // Mengambil log spesifik untuk kucing ini
                            $catLogs = $logs_by_cat[$cat['id_kucing']] ?? []; 
                        ?>
                        <?php if (!empty($catLogs)): ?>
                            <?php foreach ($catLogs as $log): 
                                $iconClass = 'fa-paw'; 
                                $jenis = strtolower($log['jenis_aktivitas']);
                                if(strpos($jenis, 'makan') !== false) $iconClass = 'fa-utensils';
                                elseif(strpos($jenis, 'main') !== false) $iconClass = 'fa-baseball-bat-ball';
                                elseif(strpos($jenis, 'tidur') !== false) $iconClass = 'fa-bed';
                                elseif(strpos($jenis, 'mandi') !== false || strpos($jenis, 'grooming') !== false) $iconClass = 'fa-shower';
                                elseif(strpos($jenis, 'litter') !== false || strpos($jenis, 'pup') !== false) $iconClass = 'fa-poop';
                                elseif(strpos($jenis, 'foto') !== false) $iconClass = 'fa-camera';
                                
                                $waktu = date('d M, H:i', strtotime($log['waktu_log']));
                            ?>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <span class="t-time"><?= $waktu; ?></span>
                                    <div class="t-title">
                                        <i class="fa-solid <?= $iconClass; ?>" style="color:#f3b83f;"></i> 
                                        <?= htmlspecialchars($log['jenis_aktivitas']); ?>
                                    </div>
                                    
                                    <?php if (!empty($log['catatan'])): ?>
                                        <div class="t-desc"><?= htmlspecialchars($log['catatan']); ?></div>
                                    <?php endif; ?>

                                    <?php if (!empty($log['url_foto_fixed'])): ?>
                                        <img src="<?= $log['url_foto_fixed']; ?>" 
                                             class="log-img" 
                                             onclick="window.open(this.src, '_blank')"
                                             alt="Bukti Foto"> 
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fa-solid fa-hourglass-half" style="font-size:2rem; margin-bottom:10px;"></i>
                                <p>Belum ada aktivitas yang dicatat oleh Mitra.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php else: ?>
    <div class="card-box" style="text-align: center; padding: 50px; max-width: 600px; margin: 40px auto;">
        <i class="fa-solid fa-cat" style="font-size: 4rem; color: #f3b83f; margin-bottom: 20px;"></i>
        <h3>Belum Ada Penitipan Aktif</h3>
        <p class="text-muted">Kamu tidak memiliki kucing yang sedang dititipkan saat ini.</p>
        <a href="<?= BASEURL; ?>/DashboardCustomer/pilih_penitipan" class="btn-action btn-primary" style="max-width: 250px; margin: 20px auto;">
            Booking Sekarang
        </a>
    </div>
<?php endif; ?>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('active');
        document.body.style.overflow = 'hidden'; 
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        document.body.style.overflow = 'auto'; 
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }
</script>
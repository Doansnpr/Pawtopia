
    <style>
       
        .container {
                max-width: 100%;
                width: 100%;
                margin: 0 auto;
            }
    
        .text-muted { color: #888; font-size: 0.85rem; }
        .text-primary { color: #f3b83f; }
        
        /* --- HEADER SECTION --- */
        .page-header { margin-bottom: 1.5rem; }
        .page-header h2 { font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 5px; }
        .page-header p { color: #666; font-size: 0.9rem; }

        /* --- GRID SYSTEM --- */
        .status-grid {
            display: grid;
            grid-template-columns: 350px 1fr; /* Kolom Kiri Fixed, Kanan Flexible */
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* --- CARD STYLE (Sesuai Referensi) --- */
        .card-box {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            border: 1px solid #f0f0f0;
            height: fit-content;
        }

        /* --- KOLOM KIRI: PROFIL KUCING --- */
        .cat-profile-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .cat-image-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 15px;
        }

        .cat-image {
            width: 100%; height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff8e1; /* Kuning sangat muda */
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .cat-name { font-size: 1.4rem; font-weight: 700; color: #333; margin-bottom: 5px; }
        
        /* Status Badge Modern */
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: #fff8e1; 
            color: #f3b83f;
            border: 1px solid #ffe082;
        }

        .info-list { list-style: none; margin-top: 20px; }
        .info-item {
            display: flex; justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #eee;
            font-size: 0.9rem;
        }
        .info-item:last-child { border-bottom: none; }
        .info-label { color: #888; font-weight: 500; }
        .info-val { font-weight: 600; color: #555; text-align: right; }

        /* --- TOMBOL AKSI --- */
        .btn-action {
            display: block; width: 100%;
            padding: 12px;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
            border: none; cursor: pointer;
        }
        
        /* Gradient Button sesuai Stat Card */
        .btn-primary {
            background: linear-gradient(135deg, #f3b83f 0%, #ff9f43 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(243, 184, 63, 0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(243, 184, 63, 0.4); }
        
        .btn-disabled {
            background: #e0e0e0; color: #999; cursor: default;
        }

        /* --- KOLOM KANAN: TIMELINE --- */
        .timeline-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem; padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .timeline-title { font-size: 1.1rem; font-weight: 600; }

        .timeline-container {
            position: relative;
            padding-left: 20px;
        }
        /* Garis vertikal tipis */
        .timeline-container::before {
            content: ''; position: absolute; left: 6px; top: 5px; bottom: 0;
            width: 2px; background: #f0f0f0;
        }

        .timeline-item {
            position: relative; margin-bottom: 25px; padding-left: 30px;
        }
        
        /* Titik Timeline */
        .timeline-dot {
            position: absolute; left: 0; top: 0;
            width: 14px; height: 14px;
            background: #fff;
            border: 3px solid #f3b83f;
            border-radius: 50%;
            z-index: 2;
        }

        .timeline-content {
            background: #fff;
        }

        .t-time { font-size: 0.75rem; color: #999; margin-bottom: 4px; display: block; }
        .t-title { font-size: 1rem; font-weight: 600; color: #333; display: flex; align-items: center; gap: 8px; }
        .t-desc { 
            font-size: 0.9rem; color: #666; margin-top: 5px; 
            background: #fafafa; padding: 10px; border-radius: 8px;
            border-left: 3px solid #ffe082;
        }

        .log-img {
            margin-top: 10px;
            width: 100px; height: 100px; object-fit: cover;
            border-radius: 8px; border: 1px solid #eee;
            cursor: pointer; transition: transform 0.2s;
        }
        .log-img:hover { transform: scale(1.05); }

        /* --- EMPTY STATES --- */
        .empty-state { text-align: center; padding: 40px 20px; color: #999; }
        .empty-icon { font-size: 3rem; margin-bottom: 15px; color: #eee; }

        /* --- RESPONSIVE --- */
        @media (max-width: 900px) {
            .status-grid { grid-template-columns: 1fr; }
            .cat-image-wrapper { width: 100px; height: 100px; }
        }
    </style>

    <div class="page-header">
        <h2>Status Penitipan <i class="fa-solid fa-satellite-dish" style="color:#f3b83f; font-size:1.2rem;"></i></h2>
        <p>Pantau aktivitas kucing kesayanganmu secara realtime.</p>
    </div>

    <?php if ($booking): ?>
        <div class="status-grid">
            
            <div class="card-box">
                <div class="cat-profile-header">
                    <?php 
                        // Logic Gambar
                        $fotoKucingName = $booking['foto_kucing'] ?? ''; 
                        $pathKucingFolder = '/pawtopia/public/images/kucing/';
                        $pathDefaultCat   = '/pawtopia/public/images/default-cat.jpg'; 
                        $fotoKucingUrl = (!empty($fotoKucingName)) ? $pathKucingFolder . htmlspecialchars($fotoKucingName) : $pathDefaultCat;
                        $finalFotoKucing = $fotoKucingUrl . '?v=' . time(); 
                    ?>
                    <div class="cat-image-wrapper">
                        <img src="<?= $finalFotoKucing; ?>" 
                             class="cat-image" 
                             alt="Foto Kucing"
                             onerror="this.onerror=null; this.src='<?= $pathDefaultCat; ?>';">
                    </div>
                    
                    <h3 class="cat-name"><?= htmlspecialchars($booking['nama_kucing']); ?></h3>
                    <div class="status-badge">
                        <?= htmlspecialchars($booking['status_utama']); ?>
                    </div>
                </div>

                <ul class="info-list">
                    <li class="info-item">
                        <span class="info-label"><i class="fa-solid fa-dna"></i> Ras</span>
                        <span class="info-val"><?= htmlspecialchars($booking['ras']); ?></span>
                    </li>
                    <li class="info-item">
                        <span class="info-label"><i class="fa-solid fa-venus-mars"></i> Gender</span>
                        <span class="info-val"><?= htmlspecialchars($booking['jenis_kelamin']); ?></span>
                    </li>
                    <li class="info-item">
                        <span class="info-label"><i class="fa-solid fa-cake-candles"></i> Umur</span>
                        <span class="info-val"><?= htmlspecialchars($booking['umur']); ?> Bulan</span>
                    </li>
                    <li class="info-item">
                        <span class="info-label"><i class="fa-solid fa-shop"></i> Mitra</span>
                        <span class="info-val"><?= htmlspecialchars($booking['nama_petshop']); ?></span>
                    </li>
                </ul>
                
                <div style="margin-top: 15px; padding: 10px; background: #fff8e1; border-radius: 8px; font-size: 0.85rem; color: #666;">
                    <strong><i class="fa-regular fa-note-sticky"></i> Catatan:</strong><br>
                    <?= htmlspecialchars($booking['keterangan'] ?? '-'); ?>
                </div>

                <?php if($booking['status_utama'] == 'Siap Dijemput'): ?>
                    <a href="<?= BASEURL; ?>/DashboardCustomer/selesaikan_pesanan/<?= $booking['id_booking']; ?>" class="btn-action btn-primary">
                        <i class="fa-solid fa-check-circle"></i> Konfirmasi Selesai
                    </a>
                <?php else: ?>
                    <button class="btn-action btn-disabled">
                        <i class="fa-solid fa-clock"></i> Menunggu Proses
                    </button>
                <?php endif; ?>
            </div>

            <div class="card-box">
                <div class="timeline-header">
                    <span class="timeline-title"><i class="fa-solid fa-list-ul" style="color:#f3b83f;"></i> Jurnal Aktivitas</span>
                    <span class="text-muted" style="font-size:0.8rem;">Update Terbaru</span>
                </div>

                <div class="timeline-container">
                    <?php if (!empty($log_activity)): ?>
                        <?php foreach ($log_activity as $log): 
                            // Icon Logic dengan FontAwesome
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

                                <?php if (!empty($log['url_foto'])): ?>
                                    <img src="<?= $log['url_foto_fixed'] ?? (BASEURL . '/public/images/logs/' . $log['url_foto']); ?>" 
                                         class="log-img" 
                                         onclick="window.open(this.src, '_blank')"
                                         alt="Bukti Foto"
                                         onerror="this.style.display='none';"> 
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fa-solid fa-hourglass-half empty-icon"></i>
                            <p>Belum ada aktivitas yang dicatat hari ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

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

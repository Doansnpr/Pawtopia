<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Penitipan - PawTopia</title>
    <style>
        /* --- RESET & BASIC --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFF8E1 100%); /* Background Krem Utama */
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        /* --- CONTAINER (WADAH UTAMA) --- */
        /* INI KUNCINYA: Background Putih DIHILANGKAN jadi Transparan */
        .container {
            height: auto; 
            max-width: 900px; 
            width: 100%;
            
            /* Background jadi TRANSPARAN (Putih pinggir hilang) */
            background: transparent; 
            
            /* Hapus bayangan container besar */
            box-shadow: none; 
            
            margin: 0 auto; 
            padding-bottom: 40px;
        }

        /* --- HEADER --- */
        .header {
            background: linear-gradient(135deg, #FFA726 0%, #FF9800 100%);
            color: white; 
            padding: 30px; 
            text-align: center; 
            position: relative;
            
            /* Header kita buat jadi kotak sendiri yang cantik */
            border-radius: 20px; 
            box-shadow: 0 10px 20px rgba(255, 152, 0, 0.2); 
            margin-bottom: 30px;
        }
        .logo { font-size: 36px; font-weight: bold; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .header-subtitle { font-size: 14px; opacity: 0.95; }
        
        .content { 
            padding: 0; /* Hapus padding agar elemen bisa lebar maksimal */
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            width: 100%;
        }

        /* --- STYLE KARTU INFO (BOX KUCING) --- */
        .booking-card {
            width: 100%; 
            border: 2px solid #FFA726; 
            border-radius: 20px; /* Sudut lebih tumpul */
            padding: 30px; 
            margin-bottom: 30px; 
            
            /* GANTI BACKGROUND JADI PUTIH (Biar kontras sama background body) */
            background: white; 
            
            /* Tambah bayangan biar box ini 'mengapung' */
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .booking-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            gap: 30px; 
        }
        
        .booking-title { font-size: 26px; font-weight: bold; color: #E65100; margin-bottom: 15px; }
        
        .cat-image {
            width: 130px; 
            height: 130px; 
            object-fit: cover; 
            border-radius: 50%;
            border: 5px solid #FFA726; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            background-color: #fff;
            flex-shrink: 0;
        }
        
        .info-row { display: flex; margin-bottom: 10px; font-size: 15px; }
        .info-label { width: 140px; color: #666; font-weight: 500; }
        .info-value { color: #333; font-weight: 600; flex: 1; }

        /* --- STATUS UTAMA (LIFECYCLE) --- */
        .main-status-badge {
            background-color: #E65100; 
            color: white; 
            padding: 10px 30px;
            border-radius: 30px; 
            font-size: 16px; 
            font-weight: bold;
            display: inline-block; 
            margin-bottom: 30px; 
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(230, 81, 0, 0.3);
            letter-spacing: 1px;
            position: relative;
            z-index: 10;
        }

        /* --- TIMELINE SECTION --- */
        .status-section { width: 100%; margin-top: 10px; }
        .status-title {
            font-size: 22px; font-weight: bold; color: #333; margin-bottom: 30px;
            padding-bottom: 15px; border-bottom: 3px solid #FFA726; text-align: center;
        }
        
        .status-timeline { position: relative; padding-left: 20px; max-width: 800px; margin: 0 auto; }
        
        .status-timeline::before {
            content: ''; position: absolute; left: 28px; top: 10px; bottom: 0;
            width: 3px; background: #e0e0e0; z-index: 0;
        }

        .status-item {
            position: relative; margin-bottom: 30px; padding-left: 60px; z-index: 1;
        }
        
        .status-icon {
            position: absolute; left: 0; top: 0;
            width: 56px; height: 56px;
            background: white; border: 3px solid #FFA726; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; z-index: 2; box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .status-content {
            background: white; border: none; border-radius: 15px;
            padding: 20px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); /* Card Timeline Putih */
            transition: transform 0.2s;
        }
        .status-content:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }

        .status-text { font-size: 17px; font-weight: 700; color: #333; margin-bottom: 5px; }
        .status-note { font-size: 15px; color: #555; margin-bottom: 10px; font-style: italic; background: #fdf6e3; padding: 10px; border-radius: 8px; border-left: 3px solid #FFA726; }
        .status-time { font-size: 13px; color: #999; text-align: right; margin-top: 5px; }
        
        .log-photo {
            width: 100%; max-width: 250px; height: auto; border-radius: 8px;
            margin-top: 10px; border: 1px solid #ddd;
        }

        /* Tombol Selesai */
        .complete-button {
            background: linear-gradient(135deg, #FFA726 0%, #FF9800 100%);
            color: white; padding: 18px 50px; border-radius: 30px;
            font-size: 16px; font-weight: bold; text-decoration: none;
            display: block; text-align: center; width: 100%; max-width: 400px; 
            margin: 40px auto 0;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4);
            transition: all 0.3s ease;
        }
        .complete-button:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(255, 152, 0, 0.5); }

        .empty-state { text-align: center; padding: 50px 20px; color: #888; }
        .empty-state-icon { font-size: 60px; margin-bottom: 15px; }

        /* Responsive */
        @media (max-width: 768px) {
            .booking-header { flex-direction: column-reverse; align-items: center; text-align: center; gap: 20px; }
            .info-label { width: 100px; text-align: left; }
            .info-row { justify-content: flex-start; text-align: left; width: 100%; }
            .status-timeline::before { left: 28px; }
            .content { padding: 0 10px; } 
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            <div class="logo">PawTopia</div>
            <div class="header-subtitle">Pantau Kucing Kesayanganmu Secara Realtime</div>
        </div>
        
        <div class="content">
            <?php if ($booking): ?>
                
                <div class="main-status-badge">
                    Status: <?= htmlspecialchars($booking['status_utama']); ?>
                </div>

                <div class="booking-card">
                    <div class="booking-header">
                        <div class="booking-info">
                            <div class="booking-title"><?= htmlspecialchars($booking['nama_kucing']); ?></div>
                            
                            <div class="info-row">
                                <span class="info-label">Ras</span>
                                <span class="info-value">: <?= htmlspecialchars($booking['ras']); ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Jenis Kelamin</span>
                                <span class="info-value">: <?= htmlspecialchars($booking['jenis_kelamin']); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Umur</span>
                                <span class="info-value">: <?= htmlspecialchars($booking['umur']); ?> Bulan</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Mitra</span>
                                <span class="info-value">: <?= htmlspecialchars($booking['nama_petshop']); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Keterangan</span>
                                <span class="info-value">: <?= htmlspecialchars($booking['keterangan']); ?></span>
                            </div>
                        </div>

                        <?php 
                        // --- LOGIKA GAMBAR ---
                        $fotoKucingName = $booking['foto_kucing'] ?? ''; 
                        $pathKucingFolder = '/pawtopia/public/images/kucing/';
                        $pathDefaultCat   = '/pawtopia/public/images/default-cat.jpg'; 

                        if (!empty($fotoKucingName)) {
                            $fotoKucingUrl = $pathKucingFolder . htmlspecialchars($fotoKucingName);
                        } else {
                            $fotoKucingUrl = $pathDefaultCat;
                        }

                        $finalFotoKucing = $fotoKucingUrl . '?v=' . time(); 
                        ?>

                        <img src="<?= $finalFotoKucing; ?>" 
                             alt="Foto Kucing" 
                             class="cat-image"
                             onerror="this.onerror=null; this.src='<?= $pathDefaultCat; ?>';">
                    </div>
                </div>
                
                <div class="status-section">
                    <div class="status-title">Jurnal Aktivitas Harian</div>
                    
                    <div class="status-timeline">
                        <?php if (!empty($log_activity)): ?>
                            <?php foreach ($log_activity as $log): 
                                $icon = '🐾'; 
                                $jenis = strtolower($log['jenis_aktivitas']);
                                if(strpos($jenis, 'makan') !== false) $icon = '🍽️';
                                elseif(strpos($jenis, 'main') !== false) $icon = '🧶';
                                elseif(strpos($jenis, 'tidur') !== false) $icon = '😴';
                                elseif(strpos($jenis, 'mandi') !== false || strpos($jenis, 'grooming') !== false) $icon = '🚿';
                                elseif(strpos($jenis, 'litter') !== false || strpos($jenis, 'pup') !== false) $icon = '💩';
                                elseif(strpos($jenis, 'foto') !== false) $icon = '📸';
                                
                                $waktu = date('d M Y, H:i', strtotime($log['waktu_log']));
                            ?>
                                <div class="status-item">
                                    <div class="status-icon"><?= $icon; ?></div>
                                    <div class="status-content">
                                        <div class="status-text"><?= htmlspecialchars($log['jenis_aktivitas']); ?></div>
                                        
                                        <?php if (!empty($log['catatan'])): ?>
                                            <div class="status-note">"<?= htmlspecialchars($log['catatan']); ?>"</div>
                                        <?php endif; ?>

                                        <?php if (!empty($log['url_foto'])): ?>
                                            <img src="<?= $log['url_foto_fixed'] ?? (BASEURL . '/public/images/logs/' . $log['url_foto']); ?>" 
                                                 class="log-photo" 
                                                 alt="Bukti Foto"
                                                 onerror="this.style.display='none';"> 
                                        <?php endif; ?>
                                        
                                        <div class="status-time"><?= $waktu; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">⏳</div>
                                <p>Belum ada aktivitas yang dicatat oleh Mitra hari ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if($booking['status_utama'] == 'Siap Dijemput'): ?>
                    <a href="<?= BASEURL; ?>/DashboardCustomer/selesaikan_pesanan/<?= $booking['id_booking']; ?>" class="complete-button">
                        Konfirmasi Penjemputan (Selesai)
                    </a>
                <?php else: ?>
                    <button class="complete-button" style="background: #ccc; box-shadow: none; cursor: default;">
                        Menunggu Proses Selesai
                    </button>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🐱</div>
                    <h3>Belum Ada Penitipan Aktif</h3>
                    <p>Kamu tidak memiliki kucing yang sedang dititipkan saat ini.</p>
                    <a href="<?= BASEURL; ?>/DashboardCustomer/pilih_penitipan" class="complete-button" style="max-width: 300px; margin: 20px auto 0;">
                        Booking Sekarang
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
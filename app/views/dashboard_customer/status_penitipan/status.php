<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Penitipan - PawTopia</title>
    <style>
        /* --- CSS BAWAAN ANDA (TIDAK BERUBAH) --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFF8E1 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: start;
        }
        .container {
            height: 95vh; overflow-y: auto; max-width: 900px; width: 100%;
            background: white; border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #FFA726 0%, #FF9800 100%);
            color: white; padding: 30px; text-align: center; position: relative;
        }
        .logo { font-size: 36px; font-weight: bold; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .header-subtitle { font-size: 14px; opacity: 0.95; }
        .content { padding: 40px; display: flex; flex-direction: column; align-items: center; }

        /* --- STYLE KARTU INFO --- */
        .booking-card {
            width: 100%; border: 2px solid #FFA726; border-radius: 12px;
            padding: 25px; margin-bottom: 30px; background: #FFF8E1;
        }
        .booking-header { display: flex; justify-content: space-between; align-items: start; gap: 30px; }
        .booking-title { font-size: 24px; font-weight: bold; color: #E65100; margin-bottom: 15px; }
        
        .cat-image {
            width: 120px; height: 120px; object-fit: cover; border-radius: 50%;
            border: 4px solid #FFA726; box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .info-row { display: flex; margin-bottom: 8px; font-size: 15px; }
        .info-label { width: 140px; color: #666; font-weight: 500; }
        .info-value { color: #333; font-weight: 600; flex: 1; }

        /* --- STATUS UTAMA (LIFECYCLE) --- */
        .main-status-badge {
            background-color: #E65100; color: white; padding: 8px 16px;
            border-radius: 20px; font-size: 14px; font-weight: bold;
            display: inline-block; margin-bottom: 20px; text-transform: uppercase;
            box-shadow: 0 2px 5px rgba(230, 81, 0, 0.3);
        }

        /* --- TIMELINE SECTION --- */
        .status-section { width: 100%; margin-top: 10px; }
        .status-title {
            font-size: 20px; font-weight: bold; color: #333; margin-bottom: 20px;
            padding-bottom: 10px; border-bottom: 2px solid #FFA726; text-align: center;
        }
        
        .status-timeline { position: relative; padding-left: 20px; }
        /* Garis Vertikal */
        .status-timeline::before {
            content: ''; position: absolute; left: 28px; top: 10px; bottom: 0;
            width: 3px; background: #ddd; z-index: 0;
        }

        .status-item {
            position: relative; margin-bottom: 25px; padding-left: 50px; z-index: 1;
        }
        
        /* Lingkaran Ikon */
        .status-icon {
            position: absolute; left: 0; top: 0;
            width: 56px; height: 56px;
            background: white; border: 3px solid #FFA726; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; z-index: 2; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .status-content {
            background: white; border: 1px solid #eee; border-radius: 10px;
            padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .status-content:hover { transform: translateX(5px); border-color: #FFA726; }

        .status-text { font-size: 16px; font-weight: 700; color: #333; margin-bottom: 4px; }
        .status-note { font-size: 14px; color: #666; margin-bottom: 8px; font-style: italic; }
        .status-time { font-size: 12px; color: #999; text-align: right; }
        
        /* Jika ada foto bukti */
        .log-photo {
            width: 100%; max-width: 200px; height: auto; border-radius: 8px;
            margin-top: 8px; border: 1px solid #ddd;
        }

        /* Tombol Selesai */
        .complete-button {
            background: linear-gradient(135deg, #FFA726 0%, #FF9800 100%);
            color: white; padding: 15px 40px; border-radius: 25px;
            font-size: 16px; font-weight: bold; text-decoration: none;
            display: block; text-align: center; width: 100%; margin-top: 30px;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
        }
        .complete-button:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(255, 152, 0, 0.4); }

        .empty-state { text-align: center; padding: 50px 20px; color: #888; }
        .empty-state-icon { font-size: 50px; margin-bottom: 10px; }

        /* Responsive */
        @media (max-width: 768px) {
            .booking-header { flex-direction: column-reverse; align-items: center; text-align: center; }
            .info-label { width: 100px; }
            .info-row { justify-content: center; }
            .status-timeline::before { left: 28px; }
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

                        <img src="<?= BASEURL; ?>/public/images/kucing/<?= htmlspecialchars($booking['foto_kucing'] ?? 'default.jpg'); ?>" 
                             alt="Foto Kucing" class="cat-image"
                             onerror="this.src='<?= BASEURL; ?>/public/images/default-cat.jpg'">
                    </div>
                </div>
                
                <div class="status-section">
                    <div class="status-title">Jurnal Aktivitas Harian</div>
                    
                    <div class="status-timeline">
                        <?php if (!empty($log_activity)): ?>
                            <?php foreach ($log_activity as $log): 
                                // Tentukan Ikon Berdasarkan Jenis Aktivitas
                                $icon = '🐾'; // Default
                                $jenis = strtolower($log['jenis_aktivitas']);
                                if(strpos($jenis, 'makan') !== false) $icon = '🍽️';
                                elseif(strpos($jenis, 'main') !== false) $icon = '🧶';
                                elseif(strpos($jenis, 'tidur') !== false) $icon = '😴';
                                elseif(strpos($jenis, 'mandi') !== false || strpos($jenis, 'grooming') !== false) $icon = '🚿';
                                elseif(strpos($jenis, 'litter') !== false || strpos($jenis, 'pup') !== false) $icon = '💩';
                                elseif(strpos($jenis, 'foto') !== false) $icon = '📸';
                                
                                // Format Waktu
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
                                            <img src="<?= BASEURL; ?>/public/images/logs/<?= htmlspecialchars($log['url_foto']); ?>" class="log-photo" alt="Bukti Foto">
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
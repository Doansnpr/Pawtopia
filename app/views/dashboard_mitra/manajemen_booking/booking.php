<?php

// Menangani Flash Message (SweetAlert)
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    
    echo "<script>
        Swal.fire({
            title: '{$flash['pesan']}',
            text: '{$flash['aksi']}',
            icon: '{$flash['tipe']}'
        });
    </script>";
}

// Inisialisasi Data Default
$reservations = $reservations ?? [];
// Ambil value pencarian/filter dari controller (jika ada), jika tidak kosongkan
$search_val = $search_val ?? '';
$filter_val = $filter_val ?? '';

$statusCounts = $statusCounts ?? [
    'Menunggu Konfirmasi' => 0,
    'Menunggu DP' => 0,
    'Verifikasi DP' => 0,
    'DP Ditolak' => 0,
    'Aktif' => 0,
    'Selesai' => 0,
    'Dibatalkan' => 0,
    'Booking Ditolak' => 0
];

$countPembayaran = ($statusCounts['Menunggu DP'] ?? 0) + 
                   ($statusCounts['Verifikasi DP'] ?? 0) + 
                   ($statusCounts['DP Ditolak'] ?? 0);

$countRiwayat = ($statusCounts['Selesai'] ?? 0) + 
                ($statusCounts['Dibatalkan'] ?? 0) + 
                ($statusCounts['Booking Ditolak'] ?? 0);
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- VARIABEL WARNA & SHADOW --- */
    /* --- PAGINATION CSS --- */
    .pagination-container {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 20px; padding-top: 20px; border-top: 1px solid #f0f0f0;
    }
    .pagination-info { color: var(--text-grey); font-size: 0.9rem; }
    .pagination-nav { display: flex; gap: 5px; }
    .page-link {
        display: inline-flex; align-items: center; justify-content: center;
        width: 35px; height: 35px; border-radius: 8px;
        background: white; border: 1px solid #dfe6e9;
        color: var(--text-grey); font-weight: 600; text-decoration: none;
        transition: all 0.2s;
    }
    .page-link:hover {
        border-color: var(--primary-orange); color: var(--primary-orange);
        transform: translateY(-2px);
    }
    .page-link.active {
        background: var(--primary-orange); color: white; border-color: var(--primary-orange);
        box-shadow: 0 4px 10px rgba(255, 159, 67, 0.3);
    }
    .page-link.disabled {
        background: #f1f2f6; color: #b2bec3; cursor: not-allowed; border-color: #f1f2f6;
        transform: none;
    }
    :root {
        --primary-orange: #FF9F43;
        --primary-orange-dark: #EE801E;
        --primary-orange-light: #FFF2E3;
        --text-dark: #2D3436;
        --text-grey: #636E72;
        --bg-color: #F8F9FD;
        --white: #FFFFFF;
        --success-bg: #e0f9f4;
        --success-green: #00b894;
        --danger-red: #ff7675; /* Warna merah untuk tombol close/hapus */
        --danger-bg: #fff0f0;
        --shadow-soft: 0 5px 15px rgba(0, 0, 0, 0.05);
        --shadow-hover: 0 8px 25px rgba(255, 159, 67, 0.25);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-color);
        color: var(--text-dark);
        margin: 0; padding: 0;
    }

    /* --- LAYOUT UTAMA --- */
    .reservasi-content {
        padding: 30px 20px;
        max-width: 98%;
        margin: 0 auto;
    }

    /* HEADER */
    .reservasi-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; background: var(--white); padding: 20px 30px;
        border-radius: 20px; box-shadow: var(--shadow-soft);
    }
    .reservasi-header h1 {
        font-size: 1.6rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 12px;
    }

    /* SEARCH & FILTER */
    .search-filter-container {
        background: var(--white); padding: 20px 30px; border-radius: 20px;
        box-shadow: var(--shadow-soft); margin-bottom: 25px;
    }
    .search-group { display: flex; gap: 15px; width: 100%; }
    .search-input, .filter-select {
        padding: 12px 20px; border: 2px solid #f1f2f6; border-radius: 12px;
        font-family: 'Poppins', sans-serif; font-size: 0.95rem; transition: 0.3s; background: #fcfcfc;
    }
    .search-input { flex: 3; } .filter-select { flex: 1; cursor: pointer; }
    .search-input:focus, .filter-select:focus {
        border-color: var(--primary-orange); outline: none; box-shadow: 0 0 0 4px var(--primary-orange-light);
    }

    /* --- BUTTONS (PERBAIKAN DI SINI) --- */
    
    /* Tombol Utama (Orange) */
    .btn-primary {
        background: linear-gradient(135deg, #FF9F43, #FF7F50); color: white;
        padding: 12px 25px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Efek membal */
        box-shadow: 0 4px 15px rgba(255, 159, 67, 0.4);
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-primary:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 8px 25px rgba(255, 159, 67, 0.6); 
        filter: brightness(1.05);
    }

    /* --- TOMBOL SEKUNDER (Batal/Tutup) - VERSI KEREN --- */
    /* Dibuat style-nya mirip Primary (Gradient & Shadow), tapi warna Abu */
    .btn-secondary {
        /* 1. Pakai Gradient Abu-abu (bukan warna solid datar) */
        background: linear-gradient(135deg, #f8f9fa, #e9ecef); 
        
        /* 2. Warna Teks Abu Tua (biar kontras) */
        color: #636E72; 
        
        /* 3. Struktur sama persis dengan btn-primary */
        padding: 12px 25px; 
        border: none; /* Hilangkan garis pinggir biar terlihat modern */
        border-radius: 12px; 
        font-weight: 600; 
        cursor: pointer;
        
        /* 4. Efek Transisi & Layout */
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;

        /* 5. Bayangan (Shadow) Abu-abu lembut (Biar timbul kayak yg oranye) */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    /* Efek Hover (Saat kursor diarahkan) */
    .btn-secondary:hover {
        /* Gradient jadi sedikit lebih gelap */
        background: linear-gradient(135deg, #e9ecef, #dee2e6);
        
        /* Teks jadi lebih hitam */
        color: #2d3436;
        
        /* Efek naik ke atas (Membal) */
        transform: translateY(-3px);
        
        /* Bayangan makin lebar */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    /* --- 3. TOMBOL SILANG (X) KHUSUS HEADER --- */
    /* Class baru khusus untuk ikon X agar tidak merusak tombol lain */
    .btn-close-icon {
        background: transparent;
        border: none;
        font-size: 1.8rem; /* Ukuran X */
        color: #b2bec3;
        cursor: pointer;
        transition: all 0.3s;
        width: 35px; height: 35px; /* Lingkaran */
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        padding: 0;
        line-height: 1;
    }
    .btn-close-icon:hover {
        color: #ff7675; /* Merah */
        background-color: #fff0f0;
        transform: rotate(90deg); /* Efek putar HANYA disini */
    }

    /* --- PENTING: Class Logika JS (Jangan dikasih Style) --- */
   
    /* Tombol Search */
    .btn-search {
        background: var(--primary-orange); color: white; padding: 0 30px;
        border: none; border-radius: 12px; font-weight: 600; cursor: pointer;
        transition: 0.3s;
    }
    .btn-search:hover { background: var(--primary-orange-dark); transform: translateY(-2px); }


    /* TABS */
    .tab-container { display: flex; gap: 10px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 5px; }
    .tab-item {
        padding: 8px 20px; background: white; border-radius: 50px; cursor: pointer;
        font-weight: 600; color: var(--text-grey); transition: 0.3s; border: 1px solid transparent;
    }
    .tab-item:hover { color: var(--primary-orange); transform: translateY(-2px); }
    .tab-item.active { background: var(--primary-orange); color: white; box-shadow: var(--shadow-hover); }

    /* TABLE */
    .data-card { background: white; border-radius: 20px; padding: 20px; box-shadow: var(--shadow-soft); overflow-x: auto; }
    .data-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
    .data-table th { text-align: left; padding: 15px; color: var(--text-grey); font-weight: 600; text-transform: uppercase; font-size: 0.85rem; }
    .data-table tbody tr { background: white; transition: 0.3s; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .data-table tbody tr:hover { transform: scale(1.005); box-shadow: 0 5px 15px rgba(0,0,0,0.1); z-index: 10; position: relative; }
    .data-table td { padding: 15px; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; font-size: 0.9rem; vertical-align: middle; }
    .data-table td:first-child { border-left: 1px solid #f0f0f0; border-top-left-radius: 15px; border-bottom-left-radius: 15px; }
    .data-table td:last-child { border-right: 1px solid #f0f0f0; border-top-right-radius: 15px; border-bottom-right-radius: 15px; }

    /* BADGES */
    .status-badge { padding: 6px 15px; border-radius: 30px; font-size: 0.75rem; font-weight: 700; display: inline-flex; gap: 5px; align-items: center; }
    .pay-status-lunas { background: var(--success-bg); color: var(--success-green); }
    .pay-status-dp { background: var(--primary-orange-light); color: var(--primary-orange-dark); }
    
    /* ACTION BUTTONS IN TABLE */
    .action-links { display: flex; flex-direction: column; gap: 6px; }
    .btn-action-base {
        padding: 8px; border-radius: 8px; color: white; text-decoration: none; font-size: 0.8rem; font-weight: 600; text-align: center; border: none; cursor: pointer; display: block; width: 100%; transition: 0.2s;
    }
    .btn-action-base:hover { transform: translateY(-2px); filter: brightness(1.1); }
    
    .btn-detail-view { background: linear-gradient(135deg, #6c757d, #495057); }
    .btn-act-bayar { background: linear-gradient(135deg, #00b894, #00cec9); }
    .btn-act-check-dp { background: linear-gradient(135deg, #0984e3, #74b9ff); }
    .btn-act-terima { background: #2ecc71; }
    .btn-act-tolak { background: #e17055; }

    /* --- MODAL CSS --- */
    .modal-backdrop {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        display: none !important;
        justify-content: center; align-items: center;
        padding: 20px; backdrop-filter: blur(5px);
        overflow-y: auto;
    }
    .modal-backdrop.show { display: flex !important; }

    .modal-content {
        background: white; width: 100%; max-width: 650px;
        border-radius: 20px; overflow: hidden; position: relative;
        animation: slideIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Animasi muncul lebih membal */
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    }
    .modal-header { padding: 20px 25px; background: linear-gradient(to right, #FFF2E3, #fff); border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { margin: 0; color: var(--primary-orange-dark); font-size: 1.3rem; display: flex; align-items: center; gap: 10px; }
    
    

    .modal-body { padding: 25px; max-height: 70vh; overflow-y: auto; }
    .modal-footer { padding: 20px 25px; background: #fcfcfc; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
    
    .modal-step { display: none; }
    .modal-step.active { display: block; animation: fadeIn 0.4s; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-dark); }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    @keyframes slideIn { from { transform: translateY(-50px) scale(0.9); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* Fix Z-Index SweetAlert agar di atas modal */
    .swal2-container { z-index: 20000 !important; }
    /* --- TOMBOL HAPUS KUCING (CUTE TRASH BIN) --- */
    .btnRemoveCat {
        width: 35px;
        height: 35px;
        border-radius: 10px; /* Sudut melengkung */
        background-color: #ffecec; /* Merah muda sangat soft */
        color: #ff7675; /* Merah soft */
        border: 1px solid #ffcece; /* Garis tipis */
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    /* Efek saat mouse diarahkan */
    .btnRemoveCat:hover {
        background-color: #ff7675; /* Jadi merah solid */
        color: white; /* Ikon jadi putih */
        border-color: #ff7675;
        transform: scale(1.1) rotate(10deg); /* Efek membesar & miring sedikit */
        box-shadow: 0 4px 10px rgba(255, 118, 117, 0.4);
    }
</style>

<div class="reservasi-content">
    
    <div class="reservasi-header">
        <h1><i class="fas fa-paw" style="color: var(--primary-orange);"></i> <?= htmlspecialchars($title ?? 'Manajemen Booking'); ?></h1>
        <button id="btnTambahOffline" class="btn-primary">
            <i class="fas fa-plus-circle"></i> Booking Offline
        </button>
    </div>

    <div class="search-filter-container">
        <form method="GET" action="<?= BASEURL ?>/DashboardMitra" style="width: 100%;">
            <input type="hidden" name="page" value="reservasi"> 
            <div class="search-group">
                <input type="text" name="search" class="search-input" placeholder="🔍 Cari ID Booking atau Nama Pelanggan..." value="<?= htmlspecialchars($search_val) ?>">
                <select name="status_bayar" class="filter-select" onchange="this.form.submit()">
                    <option value="">📂 Semua Pembayaran</option>
                    <option value="dp" <?= $filter_val == 'dp' ? 'selected' : '' ?>>⏳ Belum Lunas</option>
                    <option value="lunas" <?= $filter_val == 'lunas' ? 'selected' : '' ?>>✅ Sudah Lunas</option>
                </select>
                <button type="submit" class="btn-search">Cari</button>
            </div>
        </form>
    </div>

    <div class="tab-container">
        <div class="tab-item active" data-status="Semua">Semua (<?= array_sum($statusCounts); ?>)</div>
        <div class="tab-item" data-status="Menunggu Konfirmasi">Permintaan Baru (<?= $statusCounts['Menunggu Konfirmasi'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Menunggu DP,Verifikasi DP,DP Ditolak">Pembayaran (<?= $countPembayaran; ?>)</div>
        <div class="tab-item" data-status="Aktif">Aktif (<?= $statusCounts['Aktif'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Selesai,Dibatalkan,Booking Ditolak">Riwayat (<?= $countRiwayat; ?>)</div>
    </div>

    <div class="data-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="20%">ID & Pelanggan</th>
                    <th>Jadwal</th>
                    <th>Paket</th>
                    <th>Biaya</th>
                    <th>Status Pembayaran</th> 
                    <th>Status Booking</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody id="reservasi-body">
                <?php if (!empty($reservations)): ?>
                    <?php foreach ($reservations as $res): ?>
                        <tr data-status="<?= htmlspecialchars($res['status'] ?? ''); ?>">
                            <td>
                                <div style="font-weight: 700;"><?= htmlspecialchars($res['nama_lengkap']); ?></div>
                                <div style="color: var(--primary-orange); font-size: 0.8rem; font-weight:600;">#<?= $res['id_booking']; ?></div>
                            </td>
                            <td><i class="far fa-calendar-alt"></i> <?= date('d M', strtotime($res['tgl_mulai'])); ?> - <?= date('d M Y', strtotime($res['tgl_selesai'])); ?></td>
                            <td><span style="background: #f1f2f6; padding: 4px 10px; border-radius: 8px; font-weight: 600; color: #555;"><?= $res['paket']; ?></span></td>
                            <td style="font-weight: 700; color: var(--primary-orange-dark);">Rp <?= number_format($res['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($res['status_bayar_text'] == 'Lunas') : ?>
                                    <span class="status-badge pay-status-lunas"><i class="fas fa-check-circle"></i> Lunas</span>
                                <?php else : ?>
                                    <span class="status-badge pay-status-dp"><i class="fas fa-hourglass-half"></i> Belum Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="status-badge" style="background:#eee; color:#333;"><?= $res['status']; ?></span></td>
                            <td class="action-links">
                                <button type="button" class="btn-action-base btn-detail-view" data-id="<?= $res['id_booking']; ?>">
                                    <i class="fas fa-eye"></i> Detail
                                </button>

                                <?php if ($res['status_bayar_text'] != 'Lunas' && !in_array($res['status'], ['Dibatalkan', 'Booking Ditolak', 'DP Ditolak'])): ?>
                                    <a href="<?= BASEURL; ?>/BookingMitra/lunas_booking/<?= $res['id_booking']; ?>" class="btn-action-base btn-act-bayar" onclick="konfirmasiBayar(event, this.href)">
                                        <i class="fas fa-wallet"></i> Bayar
                                    </a>
                                <?php endif; ?>

                                <?php if ($res['status'] == 'Menunggu Konfirmasi'): ?>
                                    <div style="display:flex; gap:5px;">
                                        <a href="<?= BASEURL; ?>/BookingMitra/terima_booking/<?= $res['id_booking']; ?>" class="btn-action-base btn-act-terima"><i class="fas fa-check"></i></a>
                                        <a href="<?= BASEURL; ?>/BookingMitra/tolak_booking/<?= $res['id_booking']; ?>" class="btn-action-base btn-act-tolak"><i class="fas fa-times"></i></a>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($res['status'] == 'Verifikasi DP'): ?>
                                    <a href="javascript:void(0);" class="btn-action-base btn-act-check-dp" data-id="<?= $res['id_booking']; ?>">
                                        <i class="fas fa-receipt"></i> Cek DP
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center" style="padding: 40px;">Tidak ada data booking.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if ($pagination['total_pages'] > 1): ?>
        <div class="pagination-container">
            <div class="pagination-info">
                Hal <strong><?= $pagination['current_page']; ?></strong> dari <strong><?= $pagination['total_pages']; ?></strong> 
                (Total <?= $pagination['total_data']; ?> Data)
            </div>
            <div class="pagination-nav">
                <?php 
                    // Helper untuk mempertahankan search query saat ganti halaman
                    $queryParams = $_GET; 
                    unset($queryParams['page_no']); // Hapus page lama
                    $queryString = http_build_query($queryParams);
                ?>

                <?php if ($pagination['current_page'] > 1): ?>
                    <a href="?<?= $queryString ?>&page_no=<?= $pagination['current_page'] - 1 ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
                <?php else: ?>
                    <span class="page-link disabled"><i class="fas fa-chevron-left"></i></span>
                <?php endif; ?>

                <?php 
                $start = max(1, $pagination['current_page'] - 2);
                $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                
                if($start > 1) echo '<span class="page-link disabled">...</span>';
                
                for ($i = $start; $i <= $end; $i++): 
                ?>
                    <a href="?<?= $queryString ?>&page_no=<?= $i ?>" class="page-link <?= ($i == $pagination['current_page']) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if($end < $pagination['total_pages']) echo '<span class="page-link disabled">...</span>'; ?>

                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="?<?= $queryString ?>&page_no=<?= $pagination['current_page'] + 1 ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
                <?php else: ?>
                    <span class="page-link disabled"><i class="fas fa-chevron-right"></i></span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        </div> 
    </div> 
  </div>
</div>

<div id="offlineBookingModal" class="modal-backdrop">
    <div class="modal-content">
        <form id="formOfflineBooking" action="<?= BASEURL; ?>/BookingMitra/tambahOffline" method="POST">
            <div id="modalStep1" class="modal-step active">
                <div class="modal-header">
                    <h3><i class="fas fa-calendar-plus"></i> Tambah Booking (1/2)</h3>
                    <button type="button" class="modal-close btn-close-icon">&times;</button>
                </div>
                <div class="modal-body">
                    <fieldset style="border: 2px dashed #eee; border-radius: 12px; padding: 15px; margin-bottom: 20px;">
                        <legend style="color: var(--primary-orange); font-weight: 700; padding: 0 5px;">👤 Pelanggan</legend>
                        <div class="form-group"><label>Nama</label><input type="text" name="nama_lengkap" class="search-input" style="width:100%" required></div>
                        <div class="form-group"><label>No. Telp</label><input type="tel" name="no_telp" class="search-input" style="width:100%"></div>
                    </fieldset>
                    <fieldset style="border: 2px dashed #eee; border-radius: 12px; padding: 15px;">
                        <legend style="color: var(--primary-orange); font-weight: 700; padding: 0 5px;">📅 Jadwal</legend>
                        <div class="form-grid-2">
                            <div class="form-group"><label>Mulai</label><input type="date" name="tgl_mulai" id="tgl_mulai" class="search-input" style="width:100%" required></div>
                            <div class="form-group"><label>Selesai</label><input type="date" name="tgl_selesai" id="tgl_selesai" class="search-input" style="width:100%" required></div>
                        </div>
                        <div class="form-group">
                            <label>Paket</label>
                            <select name="paket" id="paket" class="filter-select" style="width:100%" required>
                                <option value="" data-harga="0">-- Pilih --</option>
                                <?php if (!empty($paket_mitra)): ?>
                                    <?php foreach ($paket_mitra as $pkt): ?>
                                        <option value="<?= htmlspecialchars($pkt['nama_paket']); ?>" data-harga="<?= htmlspecialchars($pkt['harga']); ?>">
                                            <?= htmlspecialchars($pkt['nama_paket']); ?> - Rp <?= number_format($pkt['harga'], 0, ',', '.'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Total Estimasi</label>
                            <input type="text" id="total_harga_display" class="search-input" readonly style="background:#f8f9fa; font-weight:bold; color:var(--primary-orange);" value="Rp 0">
                            <input type="hidden" name="total_harga" id="total_harga">
                            <small class="text-muted" id="rincian-harga"></small>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary modal-close">Batal</button>
                    <button type="button" id="btnGoToStep2" class="btn-primary">Lanjut <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <div id="modalStep2" class="modal-step">
                <div class="modal-header">
                    <h3><i class="fas fa-cat"></i> Data Kucing (2/2)</h3>
                    <button type="button" class="modal-close btn-close-icon">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="cat-forms-container"></div>
                    <button type="button" id="btnAddCat" class="btn-action-base" style="background: #f0f0f0; color: #555; margin-top:10px; border:1px dashed #aaa;">
                        <i class="fas fa-plus"></i> Tambah Kucing
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnGoToStep1" class="btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</button>
                    <button type="submit" class="btn-primary">Simpan Booking</button>
                </div>
            </div>
        </form>
    </div>
</div>

<template id="cat-form-template">
    <div class="cat-form-instance" style="border:1px solid #eee; padding:20px; border-radius:12px; margin-bottom:15px; background:#fff; box-shadow: 0 2px 5px rgba(0,0,0,0.03);">
        
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; border-bottom:1px dashed #f0f0f0; padding-bottom:10px;">
            <h5 style="margin:0; color:var(--primary-orange); font-weight:700; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-cat"></i> Kucing #INDEX
            </h5>
            <button type="button" class="btnRemoveCat" title="Hapus Kucing">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Nama Kucing</label>
                <input type="text" name="kucing[INDEX][nama]" placeholder="Nama Kucing" class="search-input" style="width:100%;" required>
            </div>
            <div class="form-group">
                <label>Ras</label>
                <input type="text" name="kucing[INDEX][ras]" placeholder="Contoh: Persia, Domestik" class="search-input" style="width:100%;">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="kucing[INDEX][jenis_kelamin]" class="filter-select" style="width:100%; height: 48px;">
                    <option value="Jantan">Jantan</option>
                    <option value="Betina">Betina</option>
                </select>
            </div>

            <div class="form-group">
                <label>Umur</label> 
                <div style="display: flex; gap: 10px;">
                    <input type="number" name="kucing[INDEX][umur_angka]" placeholder="0" class="search-input" style="flex: 1; height: 48px;" min="0" required>
                    
                    <select name="kucing[INDEX][umur_satuan]" class="filter-select" style="width: 110px; height: 48px;">
                        <option value="Tahun">Tahun</option>
                        <option value="Bulan">Bulan</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Catatan Khusus</label>
            <textarea name="kucing[INDEX][keterangan]" placeholder="Contoh: Alergi ayam, galak, butuh obat mata..." class="search-input" style="width:100%; height: 80px; padding: 10px; resize: vertical; font-family: inherit;"></textarea>
        </div>
    </div>
</template>

<div id="detailBookingModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <h3 id="header_nama_pelanggan">Memuat...</h3>
                <p id="header_id_booking" style="margin:0; color:#888; font-size:0.9rem;">ID: -</p>
            </div>
            <button type="button" class="modal-close btn-close-icon">&times;</button>
        </div>
        <div class="modal-body">
            <div id="detailLoading" style="text-align:center; padding:30px;"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
            <div id="detailContent" style="display: none;">
                <div style="background:var(--primary-orange-light); padding:15px; border-radius:12px; margin-bottom:15px; display:flex; justify-content:space-between;">
                    <span>Paket: <strong id="d_paket">-</strong></span>
                    <span style="color:var(--primary-orange-dark); font-weight:800;" id="d_total">-</span>
                </div>
                <h4 style="margin-bottom:10px; border-bottom:1px solid #eee;">Data Kucing</h4>
                <div id="listKucingContainer"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary modal-close">Tutup</button>
        </div>
    </div>
</div>

<div id="modalCheckDP" class="modal-backdrop">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <h3><i class="fas fa-file-invoice-dollar"></i> Cek Bukti Transfer</h3>
            <button type="button" class="modal-close btn-close-icon">&times;</button>
        </div>
        <div class="modal-body">
            <div id="dpLoading" style="text-align:center; padding:20px;"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
            <div id="dpContent" style="display:none;">
                <p>Nama: <strong id="dp_nama">-</strong> | Total: <strong id="dp_total" style="color:var(--primary-orange)">-</strong></p>
                <div style="background:#f9f9f9; padding:10px; border-radius:10px; text-align:center; min-height:150px; margin-bottom:20px;">
                    <img id="dp_image_preview" src="" style="max-width:100%; max-height:300px; display:none; border-radius:8px;">
                    <p id="dp_no_image" style="color:#aaa; display:none;">Tidak ada bukti foto</p>
                </div>
                <div style="display:flex; gap:10px;">
                    <a href="#" id="btnTolakDP" class="btn-action-base btn-act-tolak">Tolak</a>
                    <a href="#" id="btnTerimaDP" class="btn-action-base btn-act-terima">Terima</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- HELPERS ---
        function showModal(id) { document.getElementById(id).classList.add('show'); }
        function hideModal(el) { if (typeof el === 'string') el = document.getElementById(el); if(el) el.classList.remove('show'); }

        // FUNGSI GET DATE HARI INI (YYYY-MM-DD)
        function getTodayString() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // CLOSE BUTTON LOGIC
        document.querySelectorAll('.modal-close').forEach(btn => {
            btn.addEventListener('click', (e) => { hideModal(e.target.closest('.modal-backdrop')); });
        });
        window.onclick = function(event) { if (event.target.classList.contains('modal-backdrop')) { hideModal(event.target); } }

        // --- 1. TABS FILTRASI ---
        const tabs = document.querySelectorAll('.tab-item');
        const rows = document.querySelectorAll('#reservasi-body tr');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const statusFilter = tab.getAttribute('data-status');
                rows.forEach(row => {
                    const rowStatus = row.getAttribute('data-status');
                    row.style.display = (statusFilter === 'Semua' || statusFilter.includes(rowStatus)) ? '' : 'none';
                });
            });
        });

        // --- 2. MODAL BOOKING OFFLINE (PERBAIKAN TANGGAL DISINI) ---
        const btnTambah = document.getElementById('btnTambahOffline');
        const catContainer = document.getElementById('cat-forms-container');
        const template = document.getElementById('cat-form-template');
        
        // Elemen Input Tanggal
        const elMulai = document.getElementById('tgl_mulai');
        const elSelesai = document.getElementById('tgl_selesai');

        if(btnTambah) {
            btnTambah.addEventListener('click', () => {
                showModal('offlineBookingModal');
                document.getElementById('modalStep1').classList.add('active');
                document.getElementById('modalStep2').classList.remove('active');
                if(catContainer.children.length === 0) addCatForm();

                // --- LOGIKA TANGGAL SESUAI PERMINTAAN ---
                const today = getTodayString();
                
                // 1. Tanggal Mulai: Set Hari Ini & KUNCI TOTAL (Readonly + Gak bisa diklik)
                elMulai.value = today;
                elMulai.setAttribute('readonly', true); // Supaya data tetap terkirim tapi ga bisa diketik
                elMulai.style.backgroundColor = '#e9ecef'; // Visual abu-abu (disabled look)
                elMulai.style.pointerEvents = 'none'; // Supaya kalender tidak muncul saat diklik

                // 2. Tanggal Selesai: Kosongkan (biar muncul yyyymmdd) TAPI block masa lalu
                elSelesai.value = ""; // Kosongkan value
                elSelesai.min = today; // Set minimal hari ini (masa lalu gabisa dipilih)
                
                // Reset perhitungan harga karena tgl selesai kosong
                document.getElementById('total_harga').value = 0;
                document.getElementById('total_harga_display').value = 'Rp 0';
                document.getElementById('rincian-harga').textContent = '';
            });
        }

        // Event Listener: Validasi Tanggal Selesai
        if(elSelesai) {
            elSelesai.addEventListener('change', function() {
                // Pastikan user tidak mengakali input manual
                if(this.value < elMulai.value) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tanggal Tidak Valid',
                        text: 'Tanggal selesai tidak boleh sebelum tanggal mulai (hari ini).'
                    });
                    this.value = ""; // Reset jika salah
                }
                calculateTotal();
            });
        }

        // --- STEP NAVIGATION ---
        document.getElementById('btnGoToStep2').addEventListener('click', () => {
            const nama = document.querySelector('input[name="nama_lengkap"]').value;
            const tglM = document.getElementById('tgl_mulai').value;
            const tglS = document.getElementById('tgl_selesai').value;
            const pkt = document.getElementById('paket').value;
            
            if(!nama || !tglM || !tglS || !pkt) {
                Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Harap isi Nama, Tanggal Selesai, dan Paket.' });
                return;
            }
            document.getElementById('modalStep1').classList.remove('active');
            document.getElementById('modalStep2').classList.add('active');
        });

        document.getElementById('btnGoToStep1').addEventListener('click', () => {
            document.getElementById('modalStep2').classList.remove('active');
            document.getElementById('modalStep1').classList.add('active');
        });

        // --- TAMBAH KUCING ---
        let catIndex = 0;
        function addCatForm() {
            const clone = template.content.cloneNode(true);
            clone.querySelectorAll('[name*="INDEX"]').forEach(input => { input.name = input.name.replace('INDEX', catIndex); });
            
            const titleHtml = clone.querySelector('h5').innerHTML;
            clone.querySelector('h5').innerHTML = titleHtml.replace('INDEX', catIndex + 1);

            clone.querySelector('.btnRemoveCat').addEventListener('click', function() {
                this.closest('.cat-form-instance').remove(); calculateTotal();
            });
            catContainer.appendChild(clone);
            catIndex++;
            calculateTotal();
        }
        if(document.getElementById('btnAddCat')) document.getElementById('btnAddCat').addEventListener('click', addCatForm);

        // --- HITUNG TOTAL ---
        const elPaket = document.getElementById('paket');
        const elTotal = document.getElementById('total_harga');
        const elTotalDisplay = document.getElementById('total_harga_display');
        const elRincian = document.getElementById('rincian-harga');

        function calculateTotal() {
            // Cek jika tgl selesai masih kosong
            if(!elMulai.value || !elSelesai.value) {
                elTotal.value = 0; elTotalDisplay.value = 'Rp 0'; 
                if(elRincian) elRincian.textContent = '';
                return;
            }

            const start = new Date(elMulai.value);
            const end = new Date(elSelesai.value);
            const hargaPaket = parseInt(elPaket.options[elPaket.selectedIndex]?.dataset.harga || 0);
            const jumlahKucing = catContainer.children.length;

            if (start && end && end >= start && hargaPaket > 0 && jumlahKucing > 0) {
                let diffDays = Math.ceil(Math.abs(end - start) / (1000 * 60 * 60 * 24));
                if(diffDays === 0) diffDays = 1; // Minimal 1 hari
                const total = diffDays * hargaPaket * jumlahKucing;
                elTotal.value = total;
                elTotalDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                if(elRincian) elRincian.textContent = `${diffDays} hari x Rp${new Intl.NumberFormat('id-ID').format(hargaPaket)} x ${jumlahKucing} ekor`;
            } else {
                elTotal.value = 0; elTotalDisplay.value = 'Rp 0'; if(elRincian) elRincian.textContent = '';
            }
        }
        [elMulai, elSelesai, elPaket, document.getElementById('btnAddCat')].forEach(el => {
            if(el) { el.addEventListener('change', calculateTotal); if(el.id === 'btnAddCat') el.addEventListener('click', calculateTotal); }
        });

        // --- FETCH DETAIL & DP (Sama seperti sebelumnya) ---
        const tableBody = document.querySelector('.data-table tbody');
        if(tableBody) {
            tableBody.addEventListener('click', function(e) {
                const btnDetail = e.target.closest('.btn-detail-view');
                if(btnDetail) {
                    showModal('detailBookingModal');
                    document.getElementById('detailLoading').style.display = 'block';
                    document.getElementById('detailContent').style.display = 'none';
                    fetch('<?= BASEURL ?>/BookingMitra/getDetailJson/' + btnDetail.dataset.id)
                        .then(r => r.json()).then(res => {
                            if(res.status === 'success') {
                                const d = res.data.booking;
                                document.getElementById('header_nama_pelanggan').innerText = d.nama_lengkap;
                                document.getElementById('header_id_booking').innerText = 'ID: ' + d.id_booking;
                                document.getElementById('d_paket').innerText = d.paket;
                                document.getElementById('d_total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(d.total_harga);
                                const list = document.getElementById('listKucingContainer');
                                list.innerHTML = '';
                                res.data.kucing.forEach(c => {
                                    list.innerHTML += `<div style="background:#fff; border:1px solid #eee; padding:10px; border-radius:10px; margin-bottom:5px;">
                                        <strong><i class="fas fa-paw"></i> ${c.nama_kucing}</strong> (${c.jenis_kelamin}, ${c.umur} thn)<br>
                                        <small class="text-muted">Ras: ${c.ras || '-'}</small>
                                    </div>`;
                                });
                                document.getElementById('detailLoading').style.display = 'none';
                                document.getElementById('detailContent').style.display = 'block';
                            }
                        });
                }
                
                const btnCheckDP = e.target.closest('.btn-act-check-dp');
                if(btnCheckDP) {
                    showModal('modalCheckDP');
                    document.getElementById('dpLoading').style.display = 'block';
                    document.getElementById('dpContent').style.display = 'none';
                    fetch('<?= BASEURL ?>/BookingMitra/getDpJson/' + btnCheckDP.dataset.id)
                        .then(r => r.json()).then(res => {
                            if(res.status === 'success') {
                                const d = res.data;
                                document.getElementById('dp_nama').innerText = d.nama;
                                document.getElementById('dp_total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(d.total);
                                const img = document.getElementById('dp_image_preview');
                                if(d.foto_url) { img.src = d.foto_url; img.style.display = 'block'; document.getElementById('dp_no_image').style.display='none'; }
                                else { img.style.display = 'none'; document.getElementById('dp_no_image').style.display='block'; }
                                document.getElementById('btnTerimaDP').href = '<?= BASEURL ?>/BookingMitra/verifikasi_dp/' + d.id_booking + '/terima';
                                document.getElementById('btnTolakDP').href = '<?= BASEURL ?>/BookingMitra/verifikasi_dp/' + d.id_booking + '/tolak';
                                document.getElementById('dpLoading').style.display = 'none';
                                document.getElementById('dpContent').style.display = 'block';
                            }
                        });
                }
            });
        }

        window.konfirmasiBayar = function(e, url) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi', text: "Tandai sudah lunas?", icon: 'question',
                showCancelButton: true, confirmButtonColor: '#198754', cancelButtonColor: '#d33', confirmButtonText: 'Ya'
            }).then((result) => { if (result.isConfirmed) window.location.href = url; });
        }
    });
</script>
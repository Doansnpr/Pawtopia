<?php

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

$reservations = $reservations ?? [];
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
<style>

    .reservasi-content {
        padding-bottom: 10px;
    }

    .reservasi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 30px 30px 0 30px;
    }

    .reservasi-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .tab-container {
        display: flex;
        gap: 10px;
        border-bottom: 2px solid var(--border-color);
        padding: 0 30px;
    }

    .tab-item {
        padding: 10px 15px;
        cursor: pointer;
        font-weight: 500;
        color: var(--text-gray);
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        margin-bottom: -2px;
    }

    .tab-item.active {
        color: var(--primary-blue);
        border-bottom-color: var(--primary-blue);
    }

    .data-card {
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin: 20px 30px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .data-table th,
    .data-table td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.95rem;
    }

    .data-table th {
        color: var(--text-dark);
        font-weight: 600;
        background-color: var(--light-bg);
    }

    .action-links {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .action-links a {
        text-decoration: none;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 5px;
        transition: all 0.2s ease-in-out;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        text-align: center;
        display: inline-block;
        min-width: 80px;
        color: #fff;
        background-color: #6c757d;
    }

    .action-links a:hover {
        filter: brightness(90%);
    }

    .action-links a[href*="terima_booking"] {
        background-color: #28a745;
    }

    .action-links a[href*="terima_booking"]:hover {
        background-color: #218838;
    }

    .action-links a[href*="tolak_booking"] {
        background-color: #dc3545;
    }

    .action-links a[href*="tolak_booking"]:hover {
        background-color: #c82333;
    }

    .action-links a[href*="check_dp"] {
        background-color: #0056b3; /* Biru Tua Dasar */
    }

    .action-links a[href*="check_dp"]:hover {
        background-color: #003d80; /* Biru Lebih Tua saat di-hover */
    }

    .action-links .btn-detail-view {
        background-color: #17a2b8 !important; /* Saya beri warna Teal/Biru Muda agar beda dengan tombol abu-abu biasa */
        color: white !important;
    }
    .action-links .btn-detail-view:hover {
        background-color: #138496 !important;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
        font-size: 0.85rem;
        background-color: #f3f3f3;
        color: #444;
    }

    .status-menunggu-konfirmasi,
    .status-menunggu-dp {
        background-color: #faf4d3ff;
        color: #ffc400ff;
    }

    .status-verifikasi-dp {
        background-color: #ebf2f3ff;
        color: #2666c5ff;
    }

    .status-aktif {
        background-color: #e2ffe9ff;
        color: #00cf30ff;
    }

    .status-selesai {
        background-color: #d3d3d3ff;
        color: #47494bff;
    }

    .status-dibatalkan {
        background-color: #ffe2e5ff;
        color: #cf0217ff;
    }

    .btn-primary {
        background-color: var(--primary-blue, #ffa600ff);
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #d36a07ff; /* Hover Asli Kembali */
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.95rem;
        transition: background-color 0.2s;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 0.85rem;
    }

    /* --- MODAL STYLES (Dipakai Bersama & Diperbarui) --- */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        display: none;
        overflow-y: auto;
        padding: 30px 0;
        backdrop-filter: blur(2px); /* Efek blur untuk modern look */
    }

    .modal-content {
        background-color: #fff;
        border-radius: 12px; /* Sudut lebih lembut */
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15); /* Bayangan lebih dalam */
        width: 90%;
        max-width: 600px;
        margin: auto;
        display: flex;
        flex-direction: column;
        animation: fadeIn 0.3s ease-in-out; /* Animasi masuk */
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color, #dee2e6);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #ffffff, #f8f9fa); /* Gradient header */
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: var(--text-dark, #212529);
        font-weight: 700;
    }

    .modal-close {
        border: none;
        background: transparent;
        font-size: 1.5rem;
        font-weight: 700;
        color: #888;
        cursor: pointer;
        transition: color 0.2s;
        padding: 5px 10px;
        border-radius: 50%;
    }

    .modal-close:hover {
        color: #dc3545;
        background-color: #f8d7da;
    }

    .modal-step {
        display: none;
    }

    .modal-step.active {
        display: block;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #495057;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        font-size: 0.95rem;
        border: 1px solid #ced4da;
        border-radius: 8px; /* Sudut input lebih lembut */
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
        background-color: #fff;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #ffa600;
        box-shadow: 0 0 0 3px rgba(255, 166, 0, 0.1);
        outline: none;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .modal-footer {
        padding: 20px 25px;
        border-top: 1px solid var(--border-color, #dee2e6);
        display: flex;
        justify-content: space-between;
        background-color: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }

    .modal-body {
        padding: 20px 25px;
        flex-grow: 1;
    }

    /* --- STYLING KHUSUS UNTUK OFFLINE BOOKING MODAL --- */
    #offlineBookingModal .modal-header {
        background: linear-gradient(135deg, #ffffff, #fdfdfd);
    }

    #offlineBookingModal fieldset {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #fff;
    }

    #offlineBookingModal legend {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        padding: 0 10px;
        color: #495057;
        background: #f8f9fa;
        border-radius: 6px;
    }

    #offlineBookingModal hr {
        margin: 20px 0;
        border: 0;
        border-top: 1px solid #e9ecef;
    }

    #offlineBookingModal .btn-primary {
        background-color: #ffa600;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(255, 166, 0, 0.2);
    }

    #offlineBookingModal .btn-primary:hover {
        background-color: #e69500;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(255, 166, 0, 0.3);
    }

    #offlineBookingModal .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    #offlineBookingModal .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-1px);
    }

    #offlineBookingModal .btn-danger {
        background-color: #dc3545;
        color: #fff;
        padding: 5px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    #offlineBookingModal .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-1px);
    }

    /* --- STYLING KHUSUS UNTUK CAT FORM TEMPLATE --- */
    .cat-form-instance {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
        background-color: #fff;
        transition: box-shadow 0.2s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .cat-form-instance:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .cat-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .cat-form-header h5 {
        margin: 0;
        font-size: 1.1rem;
        color: #495057;
        font-weight: 600;
    }

    .cat-form-instance .btnRemoveCat {
        background: none;
        border: none;
        color: #dc3545;
        font-size: 1.4rem;
        font-weight: bold;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
        margin: -5px 0;
    }

    .cat-form-instance .btnRemoveCat:hover {
        background-color: rgba(220, 53, 69, 0.1);
        color: #c82333;
        transform: scale(1.1);
    }

    #cat-forms-container {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }

    #btnAddCat {
        width: 100%;
        margin-top: 15px;
        padding: 10px 15px;
        font-size: 0.95rem;
        border-radius: 8px;
        background-color: #ffa600;
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(255, 166, 0, 0.2);
    }

    #btnAddCat:hover {
        background-color: #e69500;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(255, 166, 0, 0.3);
    }

    /* --- STYLING KHUSUS UNTUK DETAIL BOOKING MODAL --- */
    #detailBookingModal .modal-header {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
    }

    #detailBookingModal .modal-header .detail-header-info h3 {
        font-size: 1.3rem;
        margin: 0 0 5px 0;
        color: #212529;
        font-weight: 700;
    }

    #detailBookingModal .modal-header .detail-header-info p {
        margin: 0;
        color: #868e96;
        font-size: 0.9rem;
    }

    #detailBookingModal .modal-body {
        padding: 20px 25px;
    }

    #detailBookingModal #detailLoading {
        text-align: center;
        padding: 30px;
        color: #666;
        font-size: 0.95rem;
    }

    #detailBookingModal #detailContent {
        display: none;
    }

    #detailBookingModal .detail-header-info {
        display: flex;
        flex-direction: column;
    }

    #detailBookingModal .detail-paket-total {
        display: flex;
        justify-content: space-between;
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 0.9rem;
        border: 1px solid #dee2e6;
    }

    #detailBookingModal .detail-paket-total span strong {
        color: #495057;
        font-weight: 600;
    }

    #detailBookingModal .detail-paket-total span#d_total {
        color: #28a745;
        font-weight: bold;
    }

    #detailBookingModal h4 {
        margin: 0 0 10px 0;
        font-size: 1rem;
        color: #555;
        font-weight: 600;
    }

    #detailBookingModal #listKucingContainer {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    #detailBookingModal .kucing-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.2s;
    }

    #detailBookingModal .kucing-card:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    #detailBookingModal .kucing-card h5 {
        margin: 0 0 10px 0;
        font-size: 1.05rem;
        color: #495057;
        font-weight: 600;
    }

    #detailBookingModal .kucing-card .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 0.9rem;
        color: #495057;
    }

    #detailBookingModal .kucing-card .info-row strong {
        font-weight: 600;
    }

    #detailBookingModal .gender-badge {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
        margin-left: 5px;
    }

    #detailBookingModal .gender-jantan {
        background-color: #e7f5ff;
        color: #1c7ed6;
    }

    #detailBookingModal .gender-betina {
        background-color: #fff0f6;
        color: #d6336c;
    }

    #detailBookingModal .modal-footer {
        padding: 20px 25px;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: flex-end;
        background-color: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }

    #detailBookingModal .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    #detailBookingModal .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-1px);
    }

    /* --- STYLING KHUSUS UNTUK CHECK DP MODAL --- */
    #modalCheckDP .modal-content {
        max-width: 500px;
    }

    #modalCheckDP .modal-header {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
    }

    #modalCheckDP .modal-body {
        padding: 20px 25px;
        text-align: center;
    }

    #modalCheckDP #dpLoading {
        padding: 20px;
        color: #666;
        font-size: 0.95rem;
    }

    #modalCheckDP #dpContent {
        display: none;
    }

    #modalCheckDP .dp-info-box {
        background: #f1f3f5;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: left;
        border: 1px solid #dee2e6;
    }

    #modalCheckDP .dp-info-box p {
        margin: 0 0 5px 0;
        font-size: 0.9rem;
        color: #666;
    }

    #modalCheckDP .dp-info-box h4 {
        margin: 0;
        font-size: 1.1rem;
        color: #333;
        font-weight: 600;
    }

    #modalCheckDP .dp-info-box hr {
        border: 0;
        border-top: 1px solid #ddd;
        margin: 10px 0;
    }

    #modalCheckDP .dp-info-box strong {
        color: #28a745;
    }

    #modalCheckDP .bukti-transfer-container {
        border: 2px dashed #ddd;
        padding: 10px;
        border-radius: 8px;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fafafa;
        transition: border-color 0.2s;
    }

    #modalCheckDP .bukti-transfer-container:hover {
        border-color: #ffa600;
    }

    #modalCheckDP #dp_image_preview {
        max-width: 100%;
        max-height: 400px;
        border-radius: 4px;
        display: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    #modalCheckDP #dp_no_image {
        color: #999;
        display: none;
    }

    #modalCheckDP .modal-footer {
        padding: 20px 25px;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        background-color: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }

    #modalCheckDP .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    #modalCheckDP .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-1px);
    }

    #modalCheckDP #btnTolakDP {
        background-color: #dc3545;
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        font-size: 0.95rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(220, 53, 69, 0.2);
    }

    #modalCheckDP #btnTolakDP:hover {
        background-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(220, 53, 69, 0.3);
    }

    #modalCheckDP #btnTerimaDP {
        background-color: #28a745;
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        font-size: 0.95rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(40, 167, 69, 0.2);
    }

    #modalCheckDP #btnTerimaDP:hover {
        background-color: #218838;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(40, 167, 69, 0.3);
    }

</style>

<div class="reservasi-content">
    <div class="reservasi-header">
        <h1><?= htmlspecialchars($title ?? 'Manajemen Booking'); ?></h1>
        <button id="btnTambahOffline" class="btn-primary">+ Tambah Booking Offline</button>
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
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Paket</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="reservasi-body">
                <?php
                if (!empty($reservations)):
                    foreach ($reservations as $res):
                ?>
                        <tr data-status="<?= htmlspecialchars($res['status'] ?? ''); ?>" data-id="<?= htmlspecialchars($res['id_booking'] ?? ''); ?>">
                            <td><?= htmlspecialchars($res['nama_lengkap'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['tgl_booking'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['tgl_mulai'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['tgl_selesai'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['paket'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['total_harga'] ?? ''); ?></td>
                            <td>
                                <?php
                                $statusText = htmlspecialchars($res['status'] ?? '');
                                $statusClass = strtolower(str_replace(' ', '-', $statusText));
                                ?>
                                <span class="status-badge status-<?= $statusClass; ?>">
                                    <?= $statusText; ?>
                                </span>
                            </td>
                            <td class="action-links">
                                <?php
                                $id = htmlspecialchars($res['id_booking'] ?? '');
                                $status = htmlspecialchars($res['status'] ?? '');

                                if (!empty($id)):
                                    if ($status === 'Menunggu Konfirmasi'):
                                    ?>
                                        <a href="<?= BASEURL; ?>/BookingMitra/terima_booking/<?= $id; ?>">Terima</a>
                                        <a href="<?= BASEURL; ?>/BookingMitra/tolak_booking/<?= $id; ?>">Tolak</a>
                                    <?php
                                    elseif ($status === 'Verifikasi DP'):
                                    ?>
                                        <a href="<?= BASEURL; ?>/BookingMitra/check_dp/<?= $id; ?>">Check DP</a>
                                    <?php
                                    else:
                                    ?>
                                        <a href="javascript:void(0);" class="btn-detail-view"  data-id="<?= $id; ?>">Detail</a>
                                <?php
                                    endif;
                                endif;
                                ?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Tidak ada data reservasi.</td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
    </div>

</div>

<div id="offlineBookingModal" class="modal-backdrop">
    <div class="modal-content">
        <form id="formOfflineBooking" action="<?= BASEURL; ?>/BookingMitra/tambahOffline" method="POST">

            <div id="modalStep1" class="modal-step active">
                <div class="modal-header">
                    <h3>Tambah Booking Offline (1/2)</h3>
                    <button type="button" class="modal-close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <legend>Data Pelanggan</legend>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap Pelanggan</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" >
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No. Telepon</label>
                            <input type="tel" name="no_telp" id="no_telp">
                        </div>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <legend>Data Booking</legend>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="tgl_mulai">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" id="tgl_mulai" >
                            </div>
                            <div class="form-group">
                                <label for="tgl_selesai">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" id="tgl_selesai" >
                            </div>
                        </div>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="paket">Paket</label>
                                <select name="paket" id="paket" >
                                    <option value="" data-harga="0">-- Pilih Paket --</option>
                                    <?php if (!empty($paket_mitra)): ?>
                                        <?php foreach ($paket_mitra as $pkt): ?>
                                            <option value="<?= htmlspecialchars($pkt['nama_paket']); ?>" 
                                                    data-harga="<?= htmlspecialchars($pkt['harga']); ?>">
                                                <?= htmlspecialchars($pkt['nama_paket']); ?> - Rp <?= number_format($pkt['harga'], 0, ',', '.'); ?> /hari
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>Belum ada paket tersedia</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="total_harga">Total Harga</label>
                                <input type="number" name="total_harga" id="total_harga" required readonly style="background-color: #f0f0f0;">
                                <small class="text-muted" id="rincian-harga" style="font-size: 0.8rem; color: #666;"></small>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="btnGoToStep2" class="btn-primary">Lanjut ke Data Kucing &rarr;</button>
                </div>
            </div>

            <div id="modalStep2" class="modal-step">
                <div class="modal-header">
                    <h3>Data Kucing (2/2)</h3>
                    <button type="button" class="modal-close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="cat-forms-container">
                        <!-- Form kucing akan ditambahkan di sini -->
                    </div>
                    <button type="button" id="btnAddCat" class="btn-primary">+ Tambah Kucing Lagi</button>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnGoToStep1" class="btn-secondary">&larr; Kembali</button>
                    <button type="submit" class="btn-primary">Simpan Booking</button>
                </div>
            </div>

        </form>
    </div>
</div>

<template id="cat-form-template">
    <div class="cat-form-instance">
        <div class="cat-form-header">
            <h5>Data Kucing</h5>
            <button type="button" class="btnRemoveCat">×</button>
        </div>
        <div class="form-grid-2">
            <div class="form-group">
                <label>Nama Kucing</label>
                <input type="text" name="kucing[INDEX][nama]" >
            </div>
            <div class="form-group">
                <label>Ras</label>
                <input type="text" name="kucing[INDEX][ras]" >
            </div>
        </div>
        <div class="form-grid-2">
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="kucing[INDEX][jenis_kelamin]" >
                    <option value="">Pilih</option>
                    <option value="Jantan">Jantan</option>
                    <option value="Betina">Betina</option>
                </select>
            </div>
            <div class="form-group">
                <label>Umur (Tahun)</label>
                <input type="number" name="kucing[INDEX][umur]" min="0" >
            </div>
        </div>
        
        <div class="form-group" style="margin-top: 10px;">
            <label>Keterangan/Catatan Khusus (Opsional)</label>
            <input type="text" name="kucing[INDEX][keterangan]">
        </div>
    </div>
</template>

<div id="detailBookingModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <div class="detail-header-info">
                <h3 id="header_nama_pelanggan">Memuat...</h3>
                <p id="header_id_booking">ID: -</p>
            </div>
            <button type="button" class="modal-close" onclick="closeDetailModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <div id="detailLoading">
                <p>Mengambil data lengkap...</p>
            </div>

            <div id="detailContent" style="display: none;">
                <div class="detail-paket-total">
                    <span><strong>Paket:</strong> <span id="d_paket">-</span></span>
                    <span><strong>Total:</strong> <span id="d_total" style="color: #28a745; font-weight: bold;">-</span></span>
                </div>

                <h4>Data Kucing</h4>
                <div id="listKucingContainer"></div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeDetailModal()">Tutup</button>
        </div>
    </div>
</div>

<div id="modalCheckDP" class="modal-backdrop" style="display: none;">
    <div class="modal-content">
        
        <div class="modal-header">
            <h3>Verifikasi Pembayaran DP</h3>
            <button type="button" class="modal-close" onclick="closeCheckDpModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div id="dpLoading">
                <p>Memuat Bukti Transfer...</p>
            </div>

            <div id="dpContent" style="display: none;">
                <div class="dp-info-box">
                    <p>Nama Pengirim:</p>
                    <h4 id="dp_nama_customer">-</h4>
                    <hr>
                    <p>Tagihan DP: <strong>Rp <span id="dp_total">0</span></strong></p>
                </div>

                <p style="font-weight: 600; margin-bottom: 10px;">Bukti Transfer:</p>
                <div class="bukti-transfer-container">
                    <img id="dp_image_preview" src="" alt="Bukti DP" style="max-width: 100%; max-height: 400px; border-radius: 4px; display: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <p id="dp_no_image">Belum ada bukti upload.</p>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeCheckDpModal()">Tutup</button>
            
            <div style="display: flex; gap: 10px;">
                <a href="#" id="btnTolakDP" class="btn-danger" onclick="return confirm('Yakin tolak pembayaran ini?');">
                    Tolak
                </a>
                <a href="#" id="btnTerimaDP" class="btn-primary" onclick="return confirm('Yakin bukti valid? Status akan menjadi Aktif.');">
                    Verifikasi Valid
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tab-item');
        const tableBody = document.getElementById('reservasi-body');
        const rows = tableBody ? tableBody.querySelectorAll('tr') : [];

        // Modal Offline
        const modalOffline = document.getElementById('offlineBookingModal');
        const btnOpenOffline = document.getElementById('btnTambahOffline');
        const step1 = document.getElementById('modalStep1');
        const step2 = document.getElementById('modalStep2');

        // Form Kucing
        const btnAddCat = document.getElementById('btnAddCat');
        const catFormsContainer = document.getElementById('cat-forms-container');
        const catFormTemplate = document.getElementById('cat-form-template');
        let catFormIndex = 0;

        // Harga
        const tglMulaiInput = document.getElementById('tgl_mulai');
        const tglSelesaiInput = document.getElementById('tgl_selesai');
        const paketSelect = document.getElementById('paket');
        const totalHargaInput = document.getElementById('total_harga');
        const rincianHargaText = document.getElementById('rincian-harga');
        const formBooking = document.getElementById('formOfflineBooking');

        // --- 1. Filter Tab ---
        function filterReservations(status) {
            // Logika filterReservations yang sudah diperbaiki
            const targetStatuses = status.split(','); 
            let hasVisibleRow = false;
            
            rows.forEach(row => {
                // ... (Logika filter) ...
                const rowStatus = row.getAttribute('data-status');
                const isNoDataRow = row.querySelector('td[colspan="9"]');
                if (isNoDataRow) {
                    row.style.display = 'none';
                    return;
                }
                if (status === 'Semua' || targetStatuses.includes(rowStatus)) {
                    row.style.display = '';
                    hasVisibleRow = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const noDataRow = tableBody.querySelector('td[colspan="9"]');
            if (noDataRow && !hasVisibleRow) {
                noDataRow.parentElement.style.display = '';
            }
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                filterReservations(this.getAttribute('data-status'));
            });
        });

        // PASTIKAN HANYA ADA SATU DEKLARASI allTab DI SINI
        const allTab = document.querySelector('.tab-item[data-status="Semua"]');
        if (allTab) allTab.click();

        // --- 2. Modal Offline Actions ---
        if (btnOpenOffline) {
            btnOpenOffline.addEventListener('click', function () {
                modalOffline.style.display = 'block';
                if (formBooking) formBooking.reset();
                if (catFormsContainer) {
                    catFormsContainer.innerHTML = '';
                    catFormIndex = 0;
                    addNewCatForm();
                }
                hitungTotalHarga();
                goToStep(1);
            });
        }

        // Close logic untuk SEMUA modal
        document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                if (modalOffline) modalOffline.style.display = 'none';
                closeDetailModal();
            });
        });

        // Step Navigation
        const btnGoToStep2 = document.getElementById('btnGoToStep2');
        const btnGoToStep1 = document.getElementById('btnGoToStep1');
        if (btnGoToStep2) btnGoToStep2.addEventListener('click', () => goToStep(2));
        if (btnGoToStep1) btnGoToStep1.addEventListener('click', () => goToStep(1));

        function goToStep(stepNumber) {
            if (step1) step1.classList.toggle('active', stepNumber === 1);
            if (step2) step2.classList.toggle('active', stepNumber === 2);
        }

        function addNewCatForm() {
            if (!catFormTemplate || !catFormsContainer) return;
            const templateContent = catFormTemplate.content.cloneNode(true);
            const newForm = templateContent.querySelector('.cat-form-instance');
            newForm.innerHTML = newForm.innerHTML.replace(/\[INDEX\]/g, `[${catFormIndex}]`);
            newForm.querySelector('.btnRemoveCat').addEventListener('click', function () {
                if (catFormsContainer.querySelectorAll('.cat-form-instance').length > 1) {
                    this.closest('.cat-form-instance').remove();
                    hitungTotalHarga();
                } else {
                    alert('Minimal 1 kucing.');
                }
            });
            catFormsContainer.appendChild(newForm);
            catFormIndex++;
            hitungTotalHarga();
        }

        if (btnAddCat) btnAddCat.addEventListener('click', addNewCatForm);

        // Hitung Harga
        function hitungTotalHarga() {
            if (!tglMulaiInput || !tglSelesaiInput || !paketSelect || !totalHargaInput) return;
            const tglMulai = new Date(tglMulaiInput.value);
            const tglSelesai = new Date(tglSelesaiInput.value);
            if (isNaN(tglMulai.getTime()) || isNaN(tglSelesai.getTime())) {
                totalHargaInput.value = '';
                if (rincianHargaText) rincianHargaText.textContent = '';
                return;
            }
            let diffDays = Math.ceil((tglSelesai - tglMulai) / (1000 * 60 * 60 * 24));
            if (diffDays < 1) diffDays = 1;
            const selectedOption = paketSelect.options[paketSelect.selectedIndex];
            const hargaPaket = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
            let jumlahKucing = 1;
            if (catFormsContainer) {
                const forms = catFormsContainer.querySelectorAll('.cat-form-instance');
                if (forms.length > 0) jumlahKucing = forms.length;
            }
            const total = hargaPaket * diffDays * jumlahKucing;
            totalHargaInput.value = total;
            if (rincianHargaText) {
                rincianHargaText.textContent = hargaPaket > 0
                    ? `${diffDays} Hari x ${jumlahKucing} Kucing x Rp ${hargaPaket.toLocaleString('id-ID')}`
                    : 'Pilih paket';
            }
        }

        if (tglMulaiInput) tglMulaiInput.addEventListener('change', hitungTotalHarga);
        if (tglSelesaiInput) tglSelesaiInput.addEventListener('change', hitungTotalHarga);
        if (paketSelect) paketSelect.addEventListener('change', hitungTotalHarga);

        const observer = new MutationObserver(() => hitungTotalHarga());
        if (catFormsContainer) observer.observe(catFormsContainer, { childList: true });
        hitungTotalHarga(); // Init

        // --- 3. Detail Booking Modal Logic (Card-Based) ---
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('btn-detail-view')) {
                e.preventDefault();
                const idBooking = e.target.getAttribute('data-id');
                bukaDetailModal(idBooking);
            }
        });

        window.bukaDetailModal = function (id) {
            const modalDetail = document.getElementById('detailBookingModal');
            const loadingDiv = document.getElementById('detailLoading');
            const contentDiv = document.getElementById('detailContent');

            if (!modalDetail) {
                console.error('HTML Modal belum ada!');
                return;
            }

            modalDetail.style.display = 'block';
            loadingDiv.style.display = 'block';
            contentDiv.style.display = 'none';

            document.getElementById('header_nama_pelanggan').textContent = 'Memuat...';
            document.getElementById('header_id_booking').textContent = 'ID: ' + id;

            fetch('<?= BASEURL; ?>/BookingMitra/getDetailJson/' + id)
                .then(response => response.json())
                .then(res => {
                    if (res.status === 'success') {
                        const b = res.data.booking;
                        const cats = res.data.kucing;

                        document.getElementById('header_nama_pelanggan').textContent = b.nama_lengkap;
                        document.getElementById('header_id_booking').textContent =
                            'ID: ' + b.id_booking + ' • ' + b.status;
                        document.getElementById('d_paket').textContent = b.paket;
                        document.getElementById('d_total').textContent =
                            'Rp ' + parseInt(b.total_harga).toLocaleString('id-ID');

                        const listContainer = document.getElementById('listKucingContainer');
                        listContainer.innerHTML = '';

                        if (cats.length > 0) {
                            cats.forEach(c => {
                                const genderClass =
                                    c.jenis_kelamin === 'Jantan' ? 'gender-jantan' : 'gender-betina';

                                const cardHtml = `
                                    <div class="kucing-card">
                                        <h5>${c.nama_kucing}</h5>
                                        <div class="info-row">
                                            <span><strong>Ras:</strong> ${c.ras}</span>
                                            <span><strong>Umur:</strong> ${c.umur} tahun</span>
                                        </div>
                                        <div class="info-row">
                                            <span><strong>Jenis Kelamin:</strong> 
                                                <span class="gender-badge ${genderClass}">${c.jenis_kelamin}</span>
                                            </span>
                                        </div>
                                        ${c.keterangan ? `<div class="info-row"><span><strong>Catatan:</strong> ${c.keterangan}</span></div>` : ''}
                                    </div>
                                `;
                                listContainer.insertAdjacentHTML('beforeend', cardHtml);
                            });
                        } else {
                            listContainer.innerHTML =
                                '<p style="text-align:center; padding:20px; color:#999;">Tidak ada data kucing.</p>';
                        }

                        loadingDiv.style.display = 'none';
                        contentDiv.style.display = 'block';
                    } else {
                        alert(res.message);
                        closeDetailModal();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal memuat data.');
                    closeDetailModal();
                });
        };

        window.closeDetailModal = function () {
            const modalDetail = document.getElementById('detailBookingModal');
            if (modalDetail) modalDetail.style.display = 'none';
        };

        window.onclick = function (event) {
            const modalDetail = document.getElementById('detailBookingModal');
            if (event.target === modalDetail) {
                closeDetailModal();
            }
        };

        
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // --- A. INISIALISASI ELEMEN ---
        const modalCheckDP = document.getElementById('modalCheckDP');
        const dpLoading = document.getElementById('dpLoading');
        const dpContent = document.getElementById('dpContent');
        const dpImagePreview = document.getElementById('dp_image_preview');
        const dpNoImage = document.getElementById('dp_no_image');
        const txtNamaDP = document.getElementById('dp_nama_customer');
        const txtTotalDP = document.getElementById('dp_total');
        
        // Tombol Aksi
        const btnTerimaDP = document.getElementById('btnTerimaDP');
        const btnTolakDP = document.getElementById('btnTolakDP');
        const btnCloseList = document.querySelectorAll('[data-dismiss="modal"], .close-modal-btn');

        // --- B. FUNGSI UTAMA ---

        // 1. Tutup Modal
        window.closeCheckDpModal = function() {
            if(modalCheckDP) modalCheckDP.style.display = 'none';
        }

        // 2. Buka Modal & Ambil Data
        window.bukaModalCheckDP = function(id) {
            if(!modalCheckDP) return;

            // Reset Tampilan
            modalCheckDP.style.display = 'block';
            dpLoading.style.display = 'block';
            dpContent.style.display = 'none';
            
            // Reset Gambar
            dpImagePreview.style.display = 'none';
            dpNoImage.style.display = 'none';
            dpImagePreview.src = '';

            // SET LINK TOMBOL AKSI (PENTING: Ini yang menghubungkan ke Controller)
            // Menggunakan SweetAlert confirm (opsional) atau langsung link
            btnTerimaDP.href = `<?= BASEURL; ?>/BookingMitra/verifikasi_dp/${id}/terima`;
            btnTolakDP.href = `<?= BASEURL; ?>/BookingMitra/verifikasi_dp/${id}/tolak`;

            // Fetch Data JSON
            fetch(`<?= BASEURL; ?>/BookingMitra/getDpJson/${id}`)
                .then(res => res.json())
                .then(response => {
                    if (response.status === 'success') {
                        const d = response.data;

                        // Isi Teks
                        txtNamaDP.textContent = d.nama || '-';
                        txtTotalDP.textContent = d.total || 'Rp 0';

                        // Cek Gambar
                        if (d.foto_url) {
                            dpImagePreview.src = d.foto_url;
                            dpImagePreview.style.display = 'block';
                            // Klik gambar untuk zoom (tab baru)
                            dpImagePreview.onclick = () => window.open(d.foto_url, '_blank');
                            dpImagePreview.style.cursor = 'zoom-in';
                        } else {
                            dpNoImage.textContent = 'Bukti transfer belum diupload.';
                            dpNoImage.style.display = 'block';
                        }

                        // Tampilkan Konten
                        dpLoading.style.display = 'none';
                        dpContent.style.display = 'block';
                    } else {
                        alert(response.message || 'Gagal mengambil data.');
                        closeCheckDpModal();
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert("Terjadi kesalahan koneksi.");
                    closeCheckDpModal();
                });
        }

        // --- C. EVENT LISTENERS ---

        // 1. Delegasi Klik Tombol "Check DP" di Tabel
        document.addEventListener('click', function(e) {
            // Cari elemen <a> terdekat yang diklik
            const target = e.target.closest('a'); 
            
            // Pastikan elemen ada dan memiliki href yang mengandung 'check_dp'
            // (Sesuaikan logic ini dengan tombol di tabel Anda)
            if (target && target.getAttribute('href') && target.getAttribute('href').includes('check_dp')) {
                e.preventDefault(); // Mencegah pindah halaman
                
                // Ambil ID dari URL (misal: .../check_dp/123)
                const segments = target.getAttribute('href').split('/');
                const idBooking = segments[segments.length - 1]; // Ambil segmen terakhir
                
                bukaModalCheckDP(idBooking);
            }
        });

        // 2. Tombol Close
        btnCloseList.forEach(btn => {
            btn.addEventListener('click', closeCheckDpModal);
        });

        // 3. Klik di luar modal (Overlay)
        window.onclick = function(event) {
            if (event.target == modalCheckDP) {
                closeCheckDpModal();
            }
        };
    });
</script>
<?php 
$reservations = $reservations ?? []; 
$statusCounts = $statusCounts ?? [
    'Menunggu Konfirmasi' => 0, 
    'Menunggu DP' => 0, 
    'Verifikasi DP' => 0, 
    'Aktif' => 0, 
    'Selesai' => 0,
    'Dibatalkan' => 0
];
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
.data-table th, .data-table td {
    padding: 15px 20px;
    text-align: left;
    border-bottom: 1px solid var(--border-color); /* Tetap ada */
    font-size: 0.95rem;
}

.data-table th { 
    color: var(--text-dark);
    font-weight: 600;
    background-color: var(--light-bg);
}

/* KODE ACTION LINKS (DIUBAH JADI BUTTON): */
.action-links {
    display: flex; 
    flex-direction: column;
    align-items: flex-start; 
    gap: 5px; /* Jarak antar tombol */
}

.action-links a {
    text-decoration: none;
    font-weight: 500;
    padding: 6px 12px; /* Padding lebih besar untuk tombol */
    border-radius: 5px;
    transition: all 0.2s ease-in-out;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
    text-align: center;
    display: inline-block;
    min-width: 80px; /* Lebar minimum agar tombol seragam */
    color: #fff; /* Teks putih */
    background-color: #6c757d; /* Default: Abu-abu (Detail) */
}

/* Hover umum (jika tidak ada yg spesifik) */
.action-links a:hover {
    filter: brightness(90%);
}

/* Tombol Terima (Success) */
.action-links a[href*="terima_booking"] {
    background-color: #28a745; /* Hijau */
}
.action-links a[href*="terima_booking"]:hover {
    background-color: #218838;
}

.action-links a[href*="tolak_Boking"] {
    background-color: #dc3545; /* Merah */
}
.action-links a[href*="tolak_Boking"]:hover {
    background-color: #c82333;
}

.action-links a[href*="check_dp"] {
    background-color: var(--primary-blue, #007bff); /* Biru */
}
.action-links a[href*="check_dp"]:hover {
    background-color: #0056b3;
}

/* Tombol Detail (Secondary - default) */
.action-links a[href*="detail_booking"] {
    background-color: #6c757d; /* Abu-abu */
}
.action-links a[href*="detail_booking"]:hover {
    background-color: #5a6268;
}

/* KODE LAINNYA */
.status-badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 600; 
    display: inline-block;
    font-size: 0.85rem; 
    /* Default */
    background-color: #f3f3f3; 
    color: #444; 
}
.status-menunggu-konfirmasi, .status-menunggu-dp {
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
</style>

<div class="reservasi-content"> 
    <div class="reservasi-header">
        <h1><?= $title ?? 'Reservasi'; ?></h1>  
    </div>
    
    <div class="tab-container">
    <div class="tab-item active" data-status="Semua">Semua (<?= array_sum($statusCounts); ?>)</div>
    <div class="tab-item" data-status="Menunggu Konfirmasi">Menunggu Konfirmasi (<?= $statusCounts['Menunggu Konfirmasi'] ?? 0; ?>)</div>
    
    <div class="tab-item" data-status="Menunggu DP">Menunggu DP (<?= $statusCounts['Menunggu DP'] ?? 0; ?>)</div> 
    
    <div class="tab-item" data-status="Verifikasi DP">Verifikasi DP (<?= $statusCounts['Verifikasi DP'] ?? 0; ?>)</div>
    
    <div class="tab-item" data-status="Aktif">Aktif (<?= $statusCounts['Aktif'] ?? 0; ?>)</div>

    <div class="tab-item" data-status="Selesai">Selesai (<?= $statusCounts['Selesai'] ?? 0; ?>)</div>
    <div class="tab-item" data-status="Dibatalkan">Dibatalkan (<?= $statusCounts['Dibatalkan'] ?? 0; ?>)</div>

    </div>
    
    <div class="data-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Jumlah Kucing</th>
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
                    <td><?= htmlspecialchars($res['jumlah_kucing'] ?? ''); ?></td>
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
                            if ($status === 'Menunggu Konfirmasi'): ?>
                                <a href="<?= BASEURL; ?>/DashboardMitra/terima_booking/<?= $id; ?>">Terima</a>
                                <a href="<?= BASEURL; ?>/DashboardMitra/tolak_Boking/<?= $id; ?>">Tolak</a>
                            <?php elseif ($status === 'Verifikasi DP'): ?>
                                <a href="<?= BASEURL; ?>/DashboardMitra/check_dp/<?= $id; ?>">Check DP</a>
                            <?php else:  
                                ?>
                                <a href="<?= BASEURL; ?>/DashboardMitra/detail_booking/<?= $id; ?>">Detail</a>
                            <?php endif; 
                        endif; ?>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-item');
        const tableBody = document.getElementById('reservasi-body'); 
        
        const rows = tableBody ? tableBody.querySelectorAll('tr') : [];
        
        function filterReservations(status) { 
            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
    
                if (row.querySelector('td[colspan="9"]')) {
                    let hasVisibleRow = false;
                    for (const otherRow of rows) {
                        if (otherRow === row) continue;
                        if (otherRow.getAttribute('data-status') === status || status === 'Semua') {
                            hasVisibleRow = true;
                            break;
                        }
                    }
                    
                    row.style.display = hasVisibleRow ? 'none' : '';
                    return;
                }

                if (status === 'Semua' || rowStatus === status) {
                    row.style.display = '';

                } else {
                    row.style.display = 'none';
                }
            }); 
        }
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const status = this.getAttribute('data-status');
                filterReservations(status);
            }); 
        });
        
        const allTab = document.querySelector('.tab-item[data-status="Semua"]');
        if (allTab) {
             allTab.click(); 
        } 
    });
</script>
<?php 
$reservations = $reservations ?? []; 
$statusCounts = $statusCounts ?? ['Menunggu' => 0, 'Terkonfirmasi' => 0, 'Selesai' => 0, 'Dibatalkan' => 0];
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
    padding: 15px 30px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
    font-size: 0.95rem;
}
.data-table th { 
    color: var(--text-dark);
    font-weight: 600;
    background-color: var(--light-bg);
}
.status-badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 500;
    background-color: #f3f3f3;
    color: var(--text-dark);
    display: inline-block;
}
.action-links a {
    text-decoration: none;
    color: var(--primary-blue);
    margin-right: 10px;
    font-weight: 500;
}
</style>

<div class="reservasi-content"> 
    <div class="reservasi-header">
        <h1><?= $title ?? 'Reservasi'; ?></h1> 
        <!-- <div style="width: 80px; height: 30px; font-size: 1.5rem; font-weight: bold; color: var(--text-dark);">
            P T.
        </div> -->
    </div>
    
    <div class="tab-container">
        <div class="tab-item active" data-status="Menunggu">Menunggu (<?= $statusCounts['Menunggu'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Terkonfirmasi">Terkonfirmasi (<?= $statusCounts['Terkonfirmasi'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Selesai">Selesai (<?= $statusCounts['Selesai'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Dibatalkan">Dibatalkan (<?= $statusCounts['Dibatalkan'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Semua">Semua (<?= array_sum($statusCounts); ?>)</div> </div>
    
    <div class="data-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Check-in</th>
                    <th>Tanggal Check-out</th>
                    <th>Jumlah Kucing</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody> 
                <?php 
                if (!empty($reservations)): 
                    foreach ($reservations as $res): 
                ?>
                <tr data-status="<?= htmlspecialchars($res['status'] ?? ''); ?>" data-id="<?= htmlspecialchars($res['id_booking'] ?? ''); ?>">
                    <td><?= htmlspecialchars($res['name'] ?? ''); ?></td>
                    <td><?= htmlspecialchars($res['check_in'] ?? ''); ?></td>
                    <td><?= htmlspecialchars($res['check_out'] ?? ''); ?></td>
                    <td><?= htmlspecialchars($res['cats'] ?? 0); ?></td>
                    <td>
                        <span class="status-badge"><?= htmlspecialchars($res['status'] ?? ''); ?></span>
                    </td>
                    <td class="action-links">
                        <?php 
                        $id = htmlspecialchars($res['id_booking'] ?? ''); 
                        $status = htmlspecialchars($res['status'] ?? '');

                        // Logic link aksi Anda:
                        if (!empty($id)): 
                            if ($status === 'Menunggu'): ?>
                                <a href="<?= BASEURL; ?>/DashboardMitra/terima_reservasi/<?= $id; ?>">Terima</a>, 
                                <a href="<?= BASEURL; ?>/DashboardMitra/tolak_reservasi/<?= $id; ?>">Tolak</a>
                            <?php elseif ($status === 'Terkonfirmasi'): ?>
                                <a href="<?= BASEURL; ?>/DashboardMitra/detail_reservasi/<?= $id; ?>">Lihat Detail</a>, 
                                <a href="<?= BASEURL; ?>/DashboardMitra/batalkan_reservasi/<?= $id; ?>">Batalkan</a>
                            <?php else: 
                                // Jika status 'diproses', 'Selesai', 'Dibatalkan', dll.
                                ?>
                                <a href="<?= BASEURL; ?>/DashboardMitra/arsip_reservasi/<?= $id; ?>">Arsipkan</a>
                            <?php endif; 
                        endif; ?>
                    </td>
                </tr>
                <?php 
                    endforeach; 
                else: 
                ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data reservasi.</td>
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
        // ID reservasi-body sudah dipastikan ada di tbody di atas
        const tableBody = document.getElementById('reservasi-body'); 
        const rows = tableBody ? tableBody.querySelectorAll('tr') : [];
        const initialStatus = 'Menunggu'; // Status aktif awal

        function filterReservations(status) {
            let foundMatch = false;
            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                
                // Tambahkan pengecekan untuk baris 'Tidak ada data'
                if (row.querySelector('td[colspan="6"]')) {
                    row.style.display = '';
                    return; // Lewati baris "Tidak ada data"
                }

                // Logika filter: tampilkan jika status 'Semua' atau status baris cocok
                if (status === 'Semua' || rowStatus === status) {
                    row.style.display = '';
                    if (rowStatus !== 'Tidak ada data') {
                         foundMatch = true;
                    }
                } else {
                    row.style.display = 'none';
                }
            });
            // Opsional: tampilkan pesan "Tidak ada data" jika tidak ada yang cocok.
        }
        
        // Setup event listeners
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const status = this.getAttribute('data-status');
                filterReservations(status);

                // DEBUG: console.log('Filter data berdasarkan status: ' + status);
            });
        });
        
        // 🚨 Panggil filter awal
        // Jika status aktif awal adalah 'Menunggu' dan data yang muncul 'diproses',
        // maka data 'diproses' akan tersembunyi. Kita harus memanggil filter 'Semua' secara default.
        
        // Cari tab 'Semua' dan klik jika ada data
        const allTab = document.querySelector('.tab-item[data-status="Semua"]');
        if (rows.length > 1 && allTab) { // rows.length > 1 menandakan ada data
             allTab.click(); // Klik tab 'Semua' untuk menampilkan semua data di awal
        } else {
             // Jika tidak ada data atau hanya satu baris 'Tidak ada data', tetap aktifkan 'Menunggu'
             filterReservations(initialStatus);
        }
    });
</script>
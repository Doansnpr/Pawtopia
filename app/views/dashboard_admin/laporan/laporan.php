<style>
    /* Default: Warna Badge di Layar Monitor (Cerah) */
    .badge-selesai { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .badge-batal { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .badge-menunggu { background-color: #FFF2E3; color: #E67E22; border: 1px solid #FFE0B2; }
    .badge-aktif { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    .badge-default { background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6; }

    /* SETTINGAN KHUSUS SAAT PRINT (CETAK) */
   @media print {
        /* 1. Sembunyikan elemen bawaan */
        body * { visibility: hidden; }
        
        /* 2. Pastikan elemen dengan class no-print BENAR-BENAR HILANG (Layoutnya dihapus) */
        .no-print, .btn, .input-group, .dashboard-header, .pagination-container {
            display: none !important;
            height: 0 !important;
            width: 0 !important;
            opacity: 0 !important;
        }

        /* 3. Tampilkan area laporan */
        #printableArea, #printableArea * { visibility: visible; }

        /* 4. Atur posisi kertas */
        #printableArea {
            position: absolute; left: 0; top: 0; width: 100%;
        }

        /* 5. PERBAIKAN PENTING: Matikan Scroll pada tabel saat print */
        .table-responsive {
            overflow: visible !important; /* Biar tabel tdk terpotong */
            display: block !important;
        }
        
        /* Cegah tabel terpotong di tengah baris */
        tr { page-break-inside: avoid; }
    }
</style>

<div id="printableArea">
    
    <div class="d-none d-print-block text-center mb-4">
        <h3 style="font-weight: bold; margin-bottom: 5px;">LAPORAN KEUANGAN & BOOKING PAWTOPIA</h3>
        <p style="margin:0;">Laporan Resmi Transaksi dan Pendapatan Aplikasi</p>
        <p style="font-size: 0.9rem;">Tanggal Cetak: <?= date('d F Y H:i'); ?></p>
        <hr style="border-top: 2px solid black; opacity: 1;">
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-sm p-4 h-100 border-start border-4 border-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success no-print">
                        <i class="fas fa-hand-holding-usd fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold text-uppercase">Pendapatan Bersih Admin</h6>
                        <h3 class="mb-0 fw-bold text-dark">Rp <?= number_format($data['stats']['pendapatan_admin'], 0, ',', '.'); ?></h3>
                        <small class="text-success fw-bold" style="font-size: 0.75rem;">
                            Dari <?= $data['stats']['jml_mitra_bayar']; ?> Mitra Terdaftar
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-sm p-4 h-100 border-start border-4 border-primary">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary no-print">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold text-uppercase">Total Perputaran Uang (GMV)</h6>
                        <h3 class="mb-0 fw-bold text-dark">Rp <?= number_format($data['stats']['gmv_total'], 0, ',', '.'); ?></h3>
                        <small class="text-secondary" style="font-size: 0.75rem;">Transaksi Customer ke Mitra</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-sm p-4 h-100 border-start border-4 border-info">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info no-print">
                        <i class="fas fa-check-double fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 small fw-bold text-uppercase">Booking Sukses</h6>
                        <h3 class="mb-0 fw-bold text-dark"><?= $data['stats']['booking_selesai']; ?> <span class="fs-6 fw-normal text-muted">Pesanan</span></h3>
                        <small class="text-secondary" style="font-size: 0.75rem;">Diselesaikan Mitra</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-4 shadow-sm p-4 mb-4 no-print">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="input-group shadow-sm" style="max-width: 400px; width: 100%;">
                <span class="input-group-text bg-white border-end-0 ps-3">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchLaporan" class="form-control border-start-0 py-2" placeholder="Cari ID Booking atau Nama Mitra...">
            </div>
            <div>
                <a href="?page=laporan&print_all=true" target="_blank" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-print me-2"></i> Cetak Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-4 shadow-sm p-4">
        <div class="d-flex align-items-center mb-4">
            <h5 class="fw-bold m-0 text-dark">
                <i class="fas fa-list-alt me-2 text-warning no-print"></i>
                Data Riwayat Transaksi Booking
            </h5>
        </div>
        
        <div class="table-responsive rounded-3 border">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #FFF2E3;">
                    <tr>
                        <th class="py-3 ps-4 text-uppercase text-secondary" style="font-size: 0.8rem;">ID Booking</th>
                        <th class="py-3 text-uppercase text-secondary" style="font-size: 0.8rem;">Tanggal</th>
                        <th class="py-3 text-uppercase text-secondary" style="font-size: 0.8rem;">Mitra</th>
                        <th class="py-3 text-uppercase text-secondary" style="font-size: 0.8rem;">Customer</th>
                        <th class="py-3 text-uppercase text-secondary" style="font-size: 0.8rem;">Nominal</th>
                        <th class="py-3 text-center text-uppercase text-secondary" style="font-size: 0.8rem;">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php if (!empty($data['riwayat'])) : ?>
                        <?php foreach ($data['riwayat'] as $row) : ?>
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#<?= $row['id_booking']; ?></span>
                            </td>
                            <td>
                                <span class="small fw-semibold text-secondary"><?= date('d M Y', strtotime($row['tgl_booking'])); ?></span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark"><?= htmlspecialchars($row['nama_petshop']); ?></span>
                            </td>
                            <td>
                                <span class="small text-secondary fw-semibold"><?= htmlspecialchars($row['nama_customer']); ?></span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">
                                    Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php 
                                    // LOGIKA WARNA (Kembali dipasang)
                                    // Tentukan class CSS berdasarkan status
                                    $statusClass = match($row['status']) {
                                        'Selesai' => 'badge-selesai',
                                        'Dibatalkan', 'Booking Ditolak', 'DP Ditolak' => 'badge-batal',
                                        'Menunggu Konfirmasi', 'Menunggu DP' => 'badge-menunggu',
                                        'Verifikasi DP', 'Aktif' => 'badge-aktif',
                                        default => 'badge-default'
                                    };
                                ?>
                                <span class="badge rounded-pill px-3 py-2 fw-semibold <?= $statusClass; ?>">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <span class="text-muted fw-semibold">Belum ada riwayat transaksi.</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($data['pagination']['total_pages'] > 1): ?>
    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top no-print pagination-container">
        <div class="text-muted small">
            Halaman <span class="fw-bold text-dark"><?= $data['pagination']['current_page']; ?></span> 
            dari <span class="fw-bold text-dark"><?= $data['pagination']['total_pages']; ?></span>
        </div>
        
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0 gap-1">
                
                <li class="page-item <?= !$data['pagination']['has_prev'] ? 'disabled' : ''; ?>">
                    <a class="page-link rounded-3 border-0 px-3 py-2 d-flex align-items-center gap-1" 
                       href="?page=laporan&page_no=<?= $data['pagination']['current_page'] - 1; ?>"
                       style="background-color: #f8f9fa; color: #6c757d;">
                        <i class="fas fa-chevron-left small"></i> Prev
                    </a>
                </li>

                <?php 
                $start = max(1, $data['pagination']['current_page'] - 1);
                $end = min($data['pagination']['total_pages'], $data['pagination']['current_page'] + 1);
                
                if($start > 1) { echo '<li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>'; }

                for ($i = $start; $i <= $end; $i++): 
                    $isActive = ($i == $data['pagination']['current_page']);
                    $bgStyle = $isActive ? 'background-color: #FF9F43; color: white; box-shadow: 0 4px 10px rgba(255, 159, 67, 0.3);' : 'background-color: #fff; color: #6c757d; border: 1px solid #dee2e6;';
                ?>
                    <li class="page-item">
                        <a class="page-link rounded-3 px-3 py-2 fw-bold" 
                           href="?page=laporan&page_no=<?= $i; ?>" 
                           style="<?= $bgStyle; ?>">
                           <?= $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if($end < $data['pagination']['total_pages']) { echo '<li class="page-item disabled"><span class="page-link border-0 bg-transparent">...</span></li>'; } ?>

                <li class="page-item <?= !$data['pagination']['has_next'] ? 'disabled' : ''; ?>">
                    <a class="page-link rounded-3 border-0 px-3 py-2 d-flex align-items-center gap-1" 
                       href="?page=laporan&page_no=<?= $data['pagination']['current_page'] + 1; ?>"
                       style="background-color: #f8f9fa; color: #6c757d;">
                        Next <i class="fas fa-chevron-right small"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
    </div>

</div> 
<script>
    // Fitur Search Sederhana
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchLaporan');
        if(searchInput) {
            searchInput.addEventListener('keyup', function() {
                let val = this.value.toLowerCase();
                let rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(val) ? '' : 'none';
                });
            });
        }
    });
</script>
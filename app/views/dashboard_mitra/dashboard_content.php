
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* --- INTEGRASI GLOBAL THEME (Sesuai Referensi) --- */
    :root {
        --primary-orange: #FF9F43;
        --primary-orange-dark: #EE801E;
        --primary-orange-light: #FFF2E3;
        --text-dark: #2D3436;
        --text-grey: #636E72;
        --bg-color: #F8F9FD;
        --white: #FFFFFF;
        --shadow-soft: 0 5px 15px rgba(0, 0, 0, 0.05);
        --shadow-hover: 0 8px 25px rgba(255, 159, 67, 0.25);
        
        /* Warna Status Badge */
        --success-bg: #e0f9f4; --success-green: #00b894;
        --info-bg: #e7f5ff; --info-blue: #0984e3;
        --warning-bg: #fff3cd; --warning-text: #856404;
        --danger-bg: #ffecec; --danger-text: #d63031;
    }

    .dashboard-content {
        margin: 25px 30px 25px 30px;
     }
    /* --- DASHBOARD HEADER (MENIRU .reservasi-header) --- */
    .dashboard-header {
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin-bottom: 25px;
        background: var(--white);
        padding: 25px 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        border: 1px solid transparent;
        transition: all 0.3s;
    }
    
    .dashboard-header h2 { 
        font-size: 1.6rem; font-weight: 700; margin: 0 0 5px 0; 
        color: var(--text-dark); 
    }
    .dashboard-header p { 
        margin: 0; color: var(--text-grey); font-size: 0.95rem; 
    }

    /* --- STATS GRID --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    /* STAT CARD (MENIRU .cat-card) */
    .stat-card {
        background: var(--white);
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow-soft);
        display: flex; align-items: center; gap: 20px;
        border: 1px solid transparent;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange-light);
    }

    .stat-icon {
        width: 60px; height: 60px;
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
    }
    /* Warna Icon */
    .icon-orange { background: var(--primary-orange-light); color: var(--primary-orange); }
    .icon-blue { background: var(--info-bg); color: var(--info-blue); }
    .icon-green { background: var(--success-bg); color: var(--success-green); }
    .icon-red { background: var(--danger-bg); color: var(--danger-text); }

    .stat-info h3 { margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-dark); }
    .stat-info p { margin: 0; font-size: 0.85rem; color: var(--text-grey); font-weight: 500; }

    /* --- CHART SECTION --- */
    .chart-section {
        background: var(--white);
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--shadow-soft);
        margin-bottom: 30px;
    }
    .chart-header { margin-bottom: 20px; font-weight: 700; font-size: 1.1rem; color: var(--text-dark); }

    /* --- CONTENT GRID (Split Layout) --- */
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
    }

    /* --- CARD GENERIC --- */
    .card-box {
        background: var(--white);
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow-soft);
        height: 100%;
        border: 1px solid transparent;
        transition: 0.3s;
    }
    .card-box:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.08); }

    .card-header-simple {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px;
    }
    .card-title { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); }
    .btn-link { 
        font-size: 0.85rem; color: var(--primary-orange); 
        text-decoration: none; font-weight: 600; 
        background: var(--primary-orange-light); padding: 5px 12px; border-radius: 10px;
        transition: 0.3s;
    }
    .btn-link:hover { background: var(--primary-orange); color: var(--white); }

    /* --- TABLE STYLE --- */
    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th { 
        text-align: left; padding: 15px; 
        color: var(--text-grey); font-size: 0.85rem; font-weight: 600; 
        border-bottom: 2px solid #f1f2f6; 
    }
    .custom-table td { 
        padding: 15px; font-size: 0.9rem; color: var(--text-dark); 
        border-bottom: 1px solid #f9f9f9; vertical-align: middle; 
    }
    .custom-table tr:last-child td { border-bottom: none; }
    
    /* BADGES */
    .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
    .badge-warning { background: var(--warning-bg); color: var(--warning-text); }
    .badge-success { background: var(--success-bg); color: var(--success-green); }
    .badge-info { background: var(--info-bg); color: var(--info-blue); }
    .badge-danger { background: var(--danger-bg); color: var(--danger-text); }

    /* --- ACTION LIST (SIDEBAR) --- */
    .action-list { display: flex; flex-direction: column; gap: 15px; }
    .action-item {
        display: flex; align-items: center; gap: 15px;
        padding: 15px;
        background: #fdfdfd; border: 1px solid #f0f0f0;
        border-radius: 15px; text-decoration: none;
        color: var(--text-dark); font-weight: 600; font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .action-item:hover { 
        background: var(--primary-orange-light); 
        border-color: var(--primary-orange); 
        color: var(--primary-orange-dark); 
        transform: translateX(5px);
    }
    .action-icon { 
        width: 40px; height: 40px; border-radius: 12px; 
        background: var(--white); display: flex; align-items: center; justify-content: center; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.05); font-size: 1rem;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
        .content-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr; }
        .dashboard-header { padding: 20px; }
    }
</style>
<div class="dashboard-content">
<div class="dashboard-header">
    <h2>Halo, <?= $data['mitra_profile']['nama_pemilik'] ?? 'Mitra'; ?>! 👋</h2>
    <p>Selamat datang kembali di Dashboard <b><?= $data['mitra_profile']['nama_petshop'] ?? 'Petshop'; ?></b>.</p>
</div>


<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-green"><i class="fas fa-wallet"></i></div>
        <div class="stat-info">
            <h3>Rp <?= number_format($data['stats']['pendapatan'] ?? 0, 0, ',', '.') ?></h3> 
            <p>Pendapatan Bulan Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-orange"><i class="fas fa-user-clock"></i></div>
        <div class="stat-info">
            <h3><?= $data['stats']['menunggu'] ?? 0 ?></h3>
            <p>Menunggu Konfirmasi</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-blue"><i class="fas fa-cat"></i></div>
        <div class="stat-info">
            <h3><?= $data['stats']['aktif'] ?? 0 ?></h3>
            <p>Kucing Dirawat</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-red"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <h3><?= $data['stats']['selesai'] ?? 0 ?></h3>
            <p>Booking Selesai</p>
        </div>
    </div>
</div>

<div class="chart-section">
    <div class="chart-header">
        📈 Tren Transaksi Booking (Tahun <?= date('Y') ?>)
    </div>
    <div style="height: 320px; width: 100%;">
        <canvas id="bookingChart"></canvas>
    </div>
</div>

<div class="content-grid">
    
    <div class="card-box">
        <div class="card-header-simple">
            <span class="card-title">📅 Booking Terbaru</span>
            <a href="?page=reservasi" class="btn-link">Lihat Semua</a>
        </div>

        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['recent_bookings'])): ?>
                        <?php foreach ($data['recent_bookings'] as $row): ?>
                            <?php 
                                $badgeClass = 'badge-info';
                                $status = strtolower($row['status']);
                                if($status == 'menunggu konfirmasi') $badgeClass = 'badge-warning';
                                else if($status == 'selesai') $badgeClass = 'badge-success';
                                else if($status == 'dibatalkan') $badgeClass = 'badge-danger';
                            ?>
                            <tr>
                                <td style="font-weight: 700; color:var(--primary-orange);">#<?= $row['id_booking'] ?></td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:30px; height:30px; background:#f0f0f0; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                            <i class="fas fa-user" style="color:#ccc; font-size:0.8rem;"></i>
                                        </div>
                                        <span style="font-weight:600;"><?= htmlspecialchars($row['nama_lengkap']) ?></span>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($row['paket']) ?></td>
                                <td><span class="badge <?= $badgeClass ?>"><?= $row['status'] ?></span></td>
                                <td style="color:var(--text-grey); font-size:0.85rem;"><?= date('d M Y', strtotime($row['tgl_booking'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 40px; color:var(--text-grey);">
                                <i class="far fa-folder-open" style="font-size: 2.5rem; margin-bottom: 15px; opacity:0.5;"></i><br>
                                Belum ada booking terbaru.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-box">
        <div class="card-header-simple">
            <span class="card-title">⚡ Aksi Cepat</span>
        </div>
        
        <div class="action-list">
            <a href="?page=profil" class="action-item">
                <div class="action-icon"><i class="fas fa-edit" style="color:var(--primary-orange);"></i></div>
                <span>Edit Profil Petshop</span>
            </a>
            <a href="?page=reservasi" class="action-item">
                <div class="action-icon"><i class="fas fa-check-double" style="color:var(--info-blue);"></i></div>
                <span>Konfirmasi Pesanan</span>
            </a>
            <a href="?page=laporan" class="action-item">
                <div class="action-icon"><i class="fas fa-file-invoice-dollar" style="color:var(--success-green);"></i></div>
                <span>Laporan Keuangan</span>
            </a>
        </div>

        <div style="margin-top: 30px; background: var(--primary-orange-light); padding: 20px; border-radius: 20px; text-align: center;">
            <p style="font-size: 0.9rem; color: var(--primary-orange-dark); font-weight: 700; margin: 0 0 10px 0;">Butuh Bantuan?</p>
            <button style="background: var(--primary-orange); color: white; border: none; padding: 12px 25px; border-radius: 12px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 10px rgba(255, 159, 67, 0.4); transition:0.3s;">Hubungi Admin</button>
        </div>
    </div>

</div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartDataPHP = <?= json_encode($data['chart_data'] ?? [0,0,0,0,0,0,0,0,0,0,0,0]); ?>;
        const ctx = document.getElementById('bookingChart').getContext('2d');
        
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(255, 159, 67, 0.5)'); 
        gradient.addColorStop(1, 'rgba(255, 159, 67, 0.0)');

        new Chart(ctx, {
            type: 'line', 
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Booking',
                    data: chartDataPHP,
                    borderColor: '#FF9F43', 
                    backgroundColor: gradient, 
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#FF9F43',
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true, 
                    tension: 0.4 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }, 
                    tooltip: {
                        backgroundColor: '#2D3436',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f0f0f0' },
                        ticks: { precision: 0 } 
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>

<!-- ========================================================== -->
<!-- SCRIPT LOGIKA POP-UP UPLOAD BERANTAI & LOGIN SUKSES        -->
<!-- ========================================================== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Ambil Data Flash dari PHP Session
    <?php 
    $flash = $_SESSION['flash'] ?? null; 
    // Hapus session flash agar tidak muncul berulang saat refresh
    if ($flash) unset($_SESSION['flash']); 
    ?>

    const flashData = <?= json_encode($flash); ?>;

    if (flashData) {

        // ============================================================
        // SKENARIO 1: MITRA BARU LOGIN -> STATUS MENUNGGU PEMBAYARAN
        // ============================================================
        if (flashData.aksi === 'force_upload') {
            
            Swal.fire({
                title: 'Silahkan upload bukti pembayaran terlebih dahulu',
                icon: 'info',
                confirmButtonText: 'Oke',
                allowOutsideClick: false, 
                allowEscapeKey: false    
            }).then((result) => {
                
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Instruksi Pembayaran',
                        html: `
                            <div style="text-align: left; font-size: 14px; color: #555;">
                                <p style="margin-bottom:5px;">Silahkan transfer <b>Rp 50.000</b> ke:</p>
                                <div style="background:#f0f8ff; border:1px solid #cce5ff; padding:10px; border-radius:5px; margin-bottom:15px; text-align:center; font-weight:bold; color: #004085;">
                                    BCA: 123-456-7890 (a.n Pawtopia)
                                </div>
                                <p style="margin-bottom:5px;">Upload foto bukti pembayaran disini:</p>
                            </div>
                            
                            <form id="formUploadBukti" action="<?= BASEURL; ?>/DashboardMitra/uploadBuktiBayar" method="POST" enctype="multipart/form-data">
                                <input type="file" name="bukti_bayar" id="fileBukti" class="swal2-input" accept="image/*" style="width: 80%; margin: 10px auto;">
                            </form>
                        `,
                        icon: 'warning',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: 'Kirim Bukti Pembayaran',
                        preConfirm: () => {
                            const fileInput = document.getElementById('fileBukti');
                            if (fileInput.files.length === 0) {
                                Swal.showValidationMessage('Foto bukti pembayaran belum dipilih!');
                                return false; 
                            }
                            document.getElementById('formUploadBukti').submit();
                        }
                    });
                }
            });

        } 
        
        // ============================================================
        // SKENARIO 2: SETELAH BERHASIL UPLOAD -> LOGOUT OTOMATIS
        // ============================================================
        else if (flashData.tipe === 'success_logout') {
            
            Swal.fire({
                title: 'Bukti Terkirim!',
                text: 'Tunggu email selanjutnya dari pawtopia457@gmail.com apakah akun anda terverifikasi atau ditolak verifikasi',
                icon: 'success',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'OK, Mengerti'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASEURL; ?>/auth/logout'; 
                }
            });

        }

        // ============================================================
        // SKENARIO 3: MITRA LOGIN SAAT MASIH MENUNGGU VERIF ADMIN
        // ============================================================
        else if (flashData.pesan === 'Pembayaran Sedang Diproses') {
             Swal.fire({
                title: 'Sedang Diverifikasi',
                text: flashData.aksi,
                icon: 'info',
                confirmButtonText: 'OK',
                allowOutsideClick: false
            });
        }
        
        // ============================================================
        // SKENARIO 4: LOGIN BERHASIL (PERBAIKAN: ADA TOMBOL OK)
        // ============================================================
        else if (flashData.tipe === 'success') {
            Swal.fire({
                title: flashData.pesan,       // "Login Berhasil!"
                text: flashData.aksi,         // "Selamat datang, [Nama]"
                icon: 'success',
                confirmButtonText: 'OK',      // Tombol OK dimunculkan kembali
                confirmButtonColor: '#ff990f' // Warna Orange sesuai tema
            });
        }
        
        // ============================================================
        // SKENARIO 5: ERROR
        // ============================================================
        else if (flashData.tipe === 'error') {
            Swal.fire({
                title: 'Gagal',
                text: flashData.pesan + ' ' + flashData.aksi,
                icon: 'error'
            });
        }
    }
});
</script>
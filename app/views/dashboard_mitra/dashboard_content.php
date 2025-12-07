<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* --- INTEGRASI GLOBAL THEME --- */
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

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-color);
        color: var(--text-dark);
        margin: 0px; padding: 0; /* Reset margin body agar full width di HP */
        overflow-x: hidden; 
    }

    /* Container Utama */
    .dashboard-content {
        margin: 25px 30px; /* Desktop Margin */
        padding-bottom: 50px;
    }

    /* --- DASHBOARD HEADER --- */
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

    /* STAT CARD */
    .stat-card {
        background: var(--white);
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow-soft);
        display: flex; align-items: center; gap: 20px;
        border: 1px solid transparent;
        transition: all 0.3s;
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

    /* --- LAYOUT GRID CONTENT (CHART & ACTION) --- */
    .content-grid {
        display: grid;
        /* Desktop: Chart (2 bagian) - Aksi Cepat (1 bagian) */
        grid-template-columns: 2fr 1fr; 
        gap: 25px;
        align-items: start; /* Supaya tinggi tidak memaksa sama */
    }

    /* --- CHART SECTION --- */
    .chart-section {
        background: var(--white);
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow-soft);
        width: 100%;
    }
    .chart-header { margin-bottom: 20px; font-weight: 700; font-size: 1.1rem; color: var(--text-dark); }
    
    /* Wrapper Canvas PENTING untuk Responsif Chart.js */
    .chart-wrapper {
        position: relative;
        width: 100%;
        height: 350px; /* Tinggi Default Desktop */
    }

    /* --- CARD GENERIC (AKSI CEPAT) --- */
    .card-box {
        background: var(--white);
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow-soft);
        height: auto;
        border: 1px solid transparent;
    }

    .card-header-simple {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px;
    }
    .card-title { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); }
    
    /* --- ACTION LIST (SIDEBAR) --- */
    .action-list { display: flex; flex-direction: column; gap: 15px; }
    .action-item {
        display: flex; align-items: center; gap: 15px;
        padding: 15px;
        background: #fdfdfd; border: 1px solid #f0f0f0;
        border-radius: 15px; text-decoration: none;
        color: var(--text-dark); font-weight: 600; font-size: 0.9rem;
        transition: all 0.3s;
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
        box-shadow: 0 4px 10px rgba(0,0,0,0.05); font-size: 1rem; flex-shrink: 0;
    }

    /* --- MEDIA QUERIES (RESPONSIVE) --- */
    
    /* Tablet & Laptop Kecil */
    @media (max-width: 992px) {
        .content-grid {
            grid-template-columns: 1fr; /* Ubah jadi 1 kolom (tumpuk ke bawah) */
        }
    }

    /* HP (Mobile) */
    @media (max-width: 600px) {
        .dashboard-content { margin: 15px; } /* Margin lebih tipis */
        .dashboard-header { padding: 20px; }
        .dashboard-header h2 { font-size: 1.3rem; }
        
        /* Stats jadi 1 kolom */
        .stats-grid { grid-template-columns: 1fr; gap: 15px; }
        .stat-card { padding: 15px; }
        
        /* Chart wrapper lebih pendek di HP agar tidak makan tempat */
        .chart-wrapper { height: 250px; } 
        .chart-section { padding: 15px; }
        
        /* Card Aksi Cepat */
        .card-box { padding: 15px; }
        .action-item { font-size: 0.85rem; padding: 12px; }
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
                <p>Pendapatan</p>
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

    <div class="content-grid">
        
        <div class="chart-section">
            <div class="chart-header">
                📈 Tren Transaksi Booking (<?= date('Y') ?>)
            </div>
            <div class="chart-wrapper">
                <canvas id="bookingChart"></canvas>
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
                <button style="width: 100%; background: var(--primary-orange); color: white; border: none; padding: 12px 25px; border-radius: 12px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 10px rgba(255, 159, 67, 0.4); transition:0.3s;">Hubungi Admin</button>
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
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true, 
                    tension: 0.4 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // PENTING: Agar chart mengikuti tinggi .chart-wrapper CSS
                plugins: {
                    legend: { display: false }, 
                    tooltip: {
                        backgroundColor: '#2D3436',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f0f0f0' },
                        ticks: { precision: 0, font: { size: 10 } } // Font axis lebih kecil
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>

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

        // SKENARIO 1: MITRA BARU LOGIN -> STATUS MENUNGGU PEMBAYARAN
        if (flashData.aksi === 'force_upload') {
            
            Swal.fire({
                title: 'Silahkan upload bukti pembayaran',
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
        
        // SKENARIO 2: SETELAH BERHASIL UPLOAD -> LOGOUT OTOMATIS
        else if (flashData.tipe === 'success_logout') {
            
            Swal.fire({
                title: 'Bukti Terkirim!',
                text: 'Tunggu email verifikasi dari admin.',
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

        // SKENARIO 3: MENUNGGU VERIF ADMIN
        else if (flashData.pesan === 'Pembayaran Sedang Diproses') {
             Swal.fire({
                title: 'Sedang Diverifikasi',
                text: flashData.aksi,
                icon: 'info',
                confirmButtonText: 'OK',
                allowOutsideClick: false
            });
        }
        
        // SKENARIO 4: LOGIN BERHASIL
        else if (flashData.tipe === 'success') {
            Swal.fire({
                title: flashData.pesan,       
                text: flashData.aksi,         
                icon: 'success',
                confirmButtonText: 'OK',      
                confirmButtonColor: '#ff990f' 
            });
        }
        
        // SKENARIO 5: ERROR
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
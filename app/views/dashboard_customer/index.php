<style>
    /* Styling Header */
    .welcome-header { margin-bottom: 1.5rem; }
    .welcome-header h2 { font-size: 1.5rem; color: #333; margin: 0; font-weight: 700; }
    .welcome-header p { color: #666; font-size: 0.9rem; margin-top: 5px; }

    /* GRID SYSTEM: 3 Kolom ketat */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* Memaksa bagi 3 rata */
        gap: 1.5rem;
        margin-bottom: 2rem;
        width: 100%;
    }

    /* Card Box Styles */
    .card-box {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
        height: 200px; /* Tinggi fix biar semua sejajar & kecil */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }

    /* 1. Stat Card Style */
    .stat-card {
        background: linear-gradient(135deg, #f3b83f 0%, #ff9f43 100%);
        color: white;
        border: none;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .stat-card .bg-icon {
        position: absolute; right: -10px; bottom: -10px;
        font-size: 6rem; opacity: 0.2; color: white; transform: rotate(-20deg);
    }
    .stat-card h2 { font-size: 3rem; margin: 0; font-weight: 700; line-height: 1; }
    .stat-card p { margin: 0 0 5px 0; font-size: 0.9rem; font-weight: 500; opacity: 0.9; }

    /* 2. Chart Header (Judul & Select) */
    .chart-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 5px;
    }
    .chart-title { font-size: 0.95rem; color: #333; font-weight: 600; margin: 0; }
    .year-select {
        border: 1px solid #f3b83f; color: #f3b83f; border-radius: 6px;
        font-size: 0.75rem; padding: 2px 5px; background: white; outline: none;
    }

    /* 3. Canvas Container (PENTING AGAR TIDAK MELEBAR) */
    .chart-container {
        position: relative;
        flex-grow: 1; /* Isi sisa ruang di card */
        width: 100%;
        overflow: hidden;
    }

    /* Legend Custom (Biar irit tempat) */
    .mini-legend {
        display: flex; justify-content: center; gap: 8px; font-size: 0.7rem; margin-top: 5px;
    }
    .legend-item { display: flex; align-items: center; gap: 3px; color: #666; }
    .dot { width: 6px; height: 6px; border-radius: 50%; }

    /* List Booking Style */
    .booking-section { margin-top: 1rem; }
    .booking-item {
        background: white; border-radius: 12px; padding: 0.8rem 1.2rem;
        display: flex; justify-content: space-between; align-items: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02); margin-bottom: 10px; border: 1px solid #f9f9f9;
    }
    .b-status {
        font-size: 0.75rem; padding: 4px 12px; border-radius: 20px; font-weight: 600;
    }
    .status-pending { background: #fff8e1; color: #ff9800; }
    .status-active { background: #e8f5e9; color: #4caf50; }
    
    /* Responsive Tablet (Biar gak maksa 3 kolom kalau layar sempit) */
    @media (max-width: 1100px) {
        .dashboard-grid { grid-template-columns: 1fr 1fr; } /* Jadi 2 kolom */
        .stat-card { grid-column: span 2; height: 120px; flex-direction: row; gap: 20px;} 
    }
</style>

<div class="welcome-header">
    <h2>Halo, <?= htmlspecialchars($data['nama_pengguna']); ?>! <i class="fa-solid fa-paw" style="color:#f3b83f;"></i></h2>
    <p>Selamat datang di <b>Pawtopia</b>.</p>
</div>

<div class="dashboard-grid">
    
    <div class="card-box stat-card">
        <i class="fa-solid fa-cat bg-icon"></i>
        <div>
            <p>Kucing Dititipkan</p>
            <h2><?= $data['jumlah_kucing']; ?></h2>
            <small style="font-size:0.75rem; opacity:0.8;">Sedang dirawat</small>
        </div>
    </div>

    <div class="card-box">
        <div class="chart-header">
            <h3 class="chart-title"><i class="fa-solid fa-chart-line"></i> Statistik</h3>
            <form method="GET" action="<?= BASEURL; ?>/DashboardCustomer">
                <select name="tahun" onchange="this.form.submit()" class="year-select">
                    <?php 
                        $yearNow = date("Y");
                        for ($i = $yearNow; $i >= $yearNow - 5; $i--): 
                    ?>
                        <option value="<?= $i ?>" <?= $data['tahun_pilih'] == $i ? "selected" : "" ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>
        <div class="chart-container">
            <canvas id="chartBooking"></canvas>
        </div>
    </div>

    <div class="card-box">
        <div class="chart-header">
            <h3 class="chart-title"><i class="fa-solid fa-star"></i> Rating Mitra</h3>
        </div>
        <div class="chart-container" style="display:flex; justify-content:center;">
            <canvas id="ratingChart"></canvas>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                <span style="font-size: 0.9rem; font-weight: 700; color: #333;">Avg</span>
            </div>
        </div>
        <div class="mini-legend">
            <?php 
            $colors = ['#f3b83f', '#ffca28', '#ffe082'];
            $idx = 0;
            // Ambil 3 data teratas saja biar ga penuh
            $topRatings = array_slice($data['rating_mitra'] ?? [], 0, 3, true);
            foreach($topRatings as $id => $rating): 
                $c = $colors[$idx % 3]; $idx++;
            ?>
            <div class="legend-item">
                <span class="dot" style="background:<?= $c ?>;"></span>
                <?= htmlspecialchars(substr($data['mitra_list'][$id] ?? 'Mitra', 0, 8)); ?>..
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="booking-section">
    <h3 class="chart-title" style="margin-bottom:1rem; font-size:1.1rem;">Daftar Booking Terbaru</h3>
    
    <?php if (!empty($data['bookings'])): ?>
        <?php foreach ($data['bookings'] as $b): 
             $statusClass = (strpos(strtolower($b['status']), 'selesai') !== false) ? 'status-active' : 'status-pending';
        ?>
        <div class="booking-item">
            <div>
                <b style="font-size:0.9rem; color:#333; display:block;"><?= htmlspecialchars($b['tempat_penitipan']); ?></b>
                <span style="font-size:0.8rem; color:#888;"><i class="fa-solid fa-cat"></i> <?= htmlspecialchars($b['nama_kucing']); ?> &bull; <?= htmlspecialchars($b['tgl_booking']); ?></span>
            </div>
            <div class="<?= $statusClass; ?> b-status">
                <?= htmlspecialchars($b['status']); ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="text-align:center; padding:1.5rem; background:white; border-radius:12px; color:#999; border:1px dashed #ddd; font-size:0.9rem;">
            Belum ada booking aktif.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.addEventListener('load', function() {
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.font.size = 10; 

    // Chart Line (Kecil)
    new Chart(document.getElementById('chartBooking'), {
        type: 'line',
        data: {
            labels: <?= json_encode($data['bulan_nama']); ?>,
            datasets: [{
                label: 'Data',
                data: <?= json_encode($data['chart_data']); ?>,
                borderColor: '#f3b83f',
                backgroundColor: 'rgba(243, 184, 63, 0.1)',
                borderWidth: 2,
                pointRadius: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, /* KUNCI AGAR TIDAK MELEBAR */
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false }, /* Sembunyikan sumbu Y biar bersih */
                x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 6 } }
            }
        }
    });

    // Chart Donut (Kecil)
    new Chart(document.getElementById('ratingChart'), {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: <?= json_encode(array_values($data['rating_mitra'])); ?>,
                backgroundColor: ['#f3b83f', '#ffca28', '#ffe082'],
                borderWidth: 0,
                hoverOffset: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: { legend: { display: false } }
        }
    });
});
</script>
<?php
// === KONEKSI DATABASE ===
$koneksi = new mysqli("localhost", "root", "", "pawtopia");
if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);

// === DATA USER LOGIN ===
if (session_status() === PHP_SESSION_NONE) session_start();
$id_user = $_SESSION['user']['id_users'] ?? 1;

// === DATA USER ===
$query_user = $koneksi->query("SELECT nama_lengkap FROM users WHERE id_users = '$id_user'");
$data_user = $query_user->fetch_assoc();
$nama_pengguna = $data_user['nama_lengkap'] ?? 'Pengguna';

// Ambil nama kucing berdasarkan id_kucing
$kucingList = [];
$resultKucing = $koneksi->query("SELECT id_kucing, nama_kucing FROM kucing WHERE id_users = '$id_user'");
while ($k = $resultKucing->fetch_assoc()) {
    $kucingList[$k['id_kucing']] = $k['nama_kucing'];
}
// Ambil semua nama mitra
$mitra_nama = [];
$result = $koneksi->query("SELECT id_mitra, nama_mitra FROM mitra");
while($row = $result->fetch_assoc()){
    $mitra_nama[$row['id_mitra']] = $row['nama_mitra'];
}
    // === FILTER TAHUN ===
    $tahun = $_GET['tahun'] ?? date("Y");

    // === INISIALISASI 12 BULAN JAN–DES ===
    $bulanNama = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    $dataBooking = array_fill(1, 12, 0);

    // === DATA BOOKING BERDASARKAN TAHUN ===
    $query_chart = $koneksi->query("
        SELECT MONTH(tgl_booking) AS bulan, COUNT(*) AS total_booking
        FROM booking
        WHERE id_users = '$id_user'
          AND YEAR(tgl_booking) = '$tahun'
        GROUP BY MONTH(tgl_booking)
    ");
    while ($row = $query_chart->fetch_assoc()) {
        $dataBooking[(int)$row['bulan']] = (int)$row['total_booking'];
    }
    $bulan = $bulanNama;
    $total_booking = array_values($dataBooking);

// === DATA BOOKING USER (HANYA YANG AKTIF) ===
$bookings = [];
$query_booking = $koneksi->query("
    SELECT b.id_booking, b.id_mitra, m.nama_mitra AS tempat_penitipan, 
           b.id_kucing, b.tgl_booking, b.status 
    FROM booking b
    JOIN mitra m ON b.id_mitra = m.id_mitra
    WHERE b.id_users = '$id_user'
      AND TRIM(LOWER(b.status)) NOT IN ('selesai', 'dibatalkan')
    ORDER BY b.tgl_booking DESC
");
if ($query_booking && $query_booking->num_rows > 0) {
    while ($row = $query_booking->fetch_assoc()) {
        $bookings[] = $row;
    }
}

// === JUMLAH KUCING AKTIF ===
$query_jumlah_kucing = $koneksi->query("
    SELECT COUNT(*) AS jumlah
    FROM booking
    WHERE id_users = '$id_user'
      AND TRIM(LOWER(status)) NOT IN ('selesai','dibatalkan')
");
$jumlah_kucing = 0;
if ($query_jumlah_kucing && $query_jumlah_kucing->num_rows > 0) {
    $countRow = $query_jumlah_kucing->fetch_assoc();
    $jumlah_kucing = (int)($countRow['jumlah'] ?? 0);
}

// === RATA-RATA RATING MITRA PER USER ===
$query_rating = $koneksi->query("
    SELECT m.id_mitra, m.nama_mitra, AVG(u.rating) AS rata_rating
    FROM ulasan u
    JOIN booking b ON u.id_booking = b.id_booking
    JOIN mitra m ON b.id_mitra = m.id_mitra
    WHERE b.id_users = '$id_user'
    GROUP BY m.id_mitra
");
$rating_mitra = [];
while ($r = $query_rating->fetch_assoc()) {
    $rating_mitra[$r['id_mitra']] = round($r['rata_rating'], 1);
}
?>

<h2 style="margin-bottom:0.5rem;">Halo, <?= htmlspecialchars($nama_pengguna); ?>!</h2>
<p style="margin-top:0;">Selamat datang di Pawtopia, tempat nyaman untuk titip si bulu kesayanganmu.</p>

<div class="dashboard-cards">
    <!-- Kucing yang Dititipkan -->
<div class="dashboard-card" style="background:#f3b83f;color:#fff; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center;">
    <p style="margin:0;font-weight:bold;">Kucing yang Dititipkan</p>
    <h2 style="margin:0.5rem 0;font-size:2rem;"><?= $jumlah_kucing; ?></h2>
</div>
    <!-- Aktivitas Booking -->
    <div class="dashboard-card chart-container" style="border:2px solid #f3b83f;background:#fff;">
        <h3 style="color:#f3b83f;margin-bottom:1rem;">Aktivitas Booking</h3>
        <div style="margin:0.8rem 0;">
            <form method="GET" style="display:flex;justify-content:center;">
                <select name="tahun" onchange="this.form.submit()"
                    style="padding:6px 12px;border-radius:8px;border:1px solid #f3b83f;color:#333;">
                    <?php 
                        $yearNow = date("Y");
                        for ($i = $yearNow; $i >= $yearNow - 5; $i--): 
                    ?>
                        <option value="<?= $i ?>" <?= $tahun == $i ? "selected" : "" ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>

        <canvas id="chartBooking" style="width:100%;height:250px;"></canvas>
    </div>

    <!-- Rata-Rata Rating Mitra -->
    <div class="dashboard-card chart-container" style="border:2px solid #f3b83f;background:#fff;">
        <h3 style="color:#f3b83f;margin-bottom:1rem;">Rata-Rata Rating Mitra</h3>
        <canvas id="ratingChart" width="230" height="230"></canvas>
        <div style="margin-top:10px;display:flex;justify-content:center;gap:15px;font-size:13px;">
          <?php foreach($rating_mitra as $id => $rating): ?>
            <div>
            <span style="display:inline-block;width:12px;height:12px;background:#ffb300;border-radius:3px;margin-right:5px;"></span>
            <?= $mitra_nama[$id] ?? 'Mitra '.$id; ?> (<?= $rating; ?>⭐)
            </div>
          <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Informasi Booking -->
<div style="margin:3rem auto;max-width:1100px;text-align:center;">
  <h3 style="color:#f3b83f;margin-bottom:1.5rem;font-size:1.4rem;">Informasi Booking</h3>

  <?php if (count($bookings) > 0): ?>
    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;justify-content:center;">
      <?php foreach ($bookings as $b): ?>
        <div style="background:#fff;border:2px solid #f3b83f;border-radius:1rem;padding:1.5rem 2rem;width:100%;max-width:750px;box-shadow:0 3px 6px rgba(0,0,0,0.05);">
          <h4 style="text-align:center;margin:0 0 1.2rem 0;color:#f3b83f;font-size:1.2rem;">
            <?= htmlspecialchars($b['tempat_penitipan']); ?>
          </h4>
          <div style="display:grid;grid-template-columns:200px auto 200px;align-items:center;row-gap:12px;font-size:1rem;">
            <div style="text-align:right;">Tempat Penitipan</div>
            <div style="text-align:center;font-weight:bold;">:</div>
            <div><?= htmlspecialchars($b['tempat_penitipan']); ?></div>

            <div style="text-align:right;">Nama Kucing</div>
            <div style="text-align:center;font-weight:bold;">:</div>
            <div><?= htmlspecialchars($kucingList[$b['id_kucing']] ?? "Tidak ditemukan"); ?></div>

            <div style="text-align:right;">Tanggal Penitipan</div>
            <div style="text-align:center;font-weight:bold;">:</div>
            <div><?= htmlspecialchars($b['tgl_booking']); ?></div>

            <div style="text-align:right;">Status</div>
            <div style="text-align:center;font-weight:bold;">:</div>
            <div><?= htmlspecialchars($b['status']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p style="text-align:center;color:#777;">Belum ada booking kucing yang aktif.</p>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.addEventListener('load', function() {

  // === Chart Booking ===
  const ctx = document.getElementById('chartBooking').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($bulan); ?>,
      datasets: [{
        label: 'Jumlah Booking Tahun <?= $tahun ?>',
        data: <?= json_encode($total_booking); ?>,
        borderColor: '#f3b83f',
        backgroundColor: 'rgba(243,184,63,0.3)',
        borderWidth: 2,
        fill: true,
        responsive: true,   // ✅ chart responsive
    maintainAspectRatio: false,
        tension: 0.3,
        pointRadius: 5,
        pointBackgroundColor: '#f3b83f'
      }]
    },
    options: {
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
      plugins: { legend: { display: true } }
    }
  });

  // Chart Rating Mitra
  const ratingData = <?= json_encode(array_values($rating_mitra)); ?>;
  const ratingLabels = <?= json_encode(array_map(fn($id) => "Mitra $id", array_keys($rating_mitra))); ?>;

  const ratingCtx = document.getElementById('ratingChart').getContext('2d');
  new Chart(ratingCtx, {
    type: 'doughnut',
    data: {
      labels: ratingLabels,
      datasets: [{
        data: ratingData.length ? ratingData : [1],
        backgroundColor: ratingData.length ? ['#ffb300','#ffe082'] : ['#ddd'],
        borderWidth: 0
      }]
    },
    options: {
      cutout: '70%',
      plugins: { legend: { display: false } }
    }
  });
});
</script>
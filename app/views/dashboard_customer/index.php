<?php
// === KONEKSI DATABASE ===
$koneksi = new mysqli("localhost", "root", "", "pawtopia");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// === AMBIL DATA USER LOGIN ===
session_start();
$id_user = $_SESSION['id_users'] ?? 1; // fallback id 1

// === DATA USER ===
$query_user = $koneksi->query("SELECT nama_lengkap FROM users WHERE id_users = '$id_user'");
$data_user = $query_user->fetch_assoc();

// === DATA BOOKING UNTUK CHART (per bulan) ===
$query_chart = $koneksi->query("
    SELECT 
        MONTH(tgl_booking) AS bulan, 
        COUNT(*) AS total_booking 
    FROM booking 
    WHERE id_users = '$id_user'
    GROUP BY MONTH(tgl_booking)
    ORDER BY bulan
");

$bulan = [];
$total_booking = [];
while ($row = $query_chart->fetch_assoc()) {
    $bulan[] = date("M", mktime(0, 0, 0, $row['bulan'], 1)); 
    $total_booking[] = $row['total_booking'];
}

// === DATA BOOKING USER (semua yang aktif atau terbaru) ===
$query_booking = $koneksi->query("
    SELECT m.nama_mitra AS tempat_penitipan, b.id_kucing, b.tgl_booking, b.status 
    FROM booking b
    JOIN mitra m ON b.id_mitra = m.id_mitra
    WHERE b.id_users = '$id_user'
    ORDER BY b.tgl_booking DESC
");

$bookings = [];
while ($row = $query_booking->fetch_assoc()) {
    $bookings[] = $row;
}

// === GABUNGKAN DATA ===
$nama_pengguna = $data_user['nama_lengkap'] ?? 'Pengguna';
?>

<h2 style="margin-bottom:0.5rem;">Halo, <?= htmlspecialchars($nama_pengguna); ?>!</h2>
<p style="margin-top:0;">Selamat datang di Pawtopia, tempat nyaman untuk titip si bulu kesayanganmu.</p>

<!-- CARD SECTION -->
<div style="display:flex;flex-wrap:wrap;gap:1.25rem;margin:1.5rem 0;justify-content:center;">
  
  <!-- Kucing yang Dititipkan -->
  <div style="background:#f3b83f;color:#fff;border-radius:1rem;padding:1.25rem;
              flex:1 1 250px;max-width:320px;text-align:center;box-shadow:0 3px 6px rgba(0,0,0,0.1);">
    <p style="margin:0;font-weight:500;">Kucing yang Dititipkan</p>
    <h2 style="margin:0.5rem 0;font-size:2rem;"><?= count($bookings); ?></h2>
    <p style="margin:0;font-weight:500;">Tempat Penitipan</p>
  </div>

  <!-- Aktivitas Booking (Line Chart) -->
  <div style="background:#fff;border:2px solid #f3b83f;border-radius:1rem;padding:1.25rem;
              flex:1 1 250px;max-width:320px;text-align:center;box-shadow:0 3px 6px rgba(0,0,0,0.05);">
    <p style="margin:0;font-weight:500;">Aktivitas Booking</p>
    <h3 style="color:#f3b83f;">User <?= htmlspecialchars($nama_pengguna); ?></h3>
    <canvas id="chartBooking" style="width:100%;height:180px;"></canvas>
  </div>

</div>

<!-- INFORMASI BOOKING -->
<div style="margin-top:2rem;">
  <h3 style="text-align:center;color:#f3b83f;margin-bottom:1.5rem;font-size:1.4rem;">Informasi Booking</h3>

  <?php if (count($bookings) > 0): ?>
    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;justify-content:center;">
      <?php foreach ($bookings as $b): ?>
        <div style="background:#fff;border:2px solid #f3b83f;border-radius:1rem;padding:1.5rem;
                    box-shadow:0 3px 6px rgba(0,0,0,0.05);width:330px;">
          <h4 style="text-align:center;margin-top:0;color:#f3b83f;"><?= htmlspecialchars($b['tempat_penitipan']); ?></h4>
          <div style="display:grid;grid-template-columns:1fr auto 1fr;row-gap:0.5rem;align-items:center;font-size:1rem;line-height:1.5;">
            <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">Nama Kucing</div>
            <div style="text-align:center;color:#555;">:</div>
            <div style="text-align:left;padding-left:1rem;"><?= htmlspecialchars($b['nama_kucing']); ?></div>

            <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">Tanggal</div>
            <div style="text-align:center;color:#555;">:</div>
            <div style="text-align:left;padding-left:1rem;"><?= htmlspecialchars($b['tanggal_penitipan']); ?></div>

            <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">Status</div>
            <div style="text-align:center;color:#555;">:</div>
            <div style="text-align:left;padding-left:1rem;"><?= htmlspecialchars($b['status']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p style="text-align:center;color:#777;">Belum ada booking kucing yang aktif.</p>
  <?php endif; ?>
</div>

<!-- CHART.JS SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartBooking').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($bulan); ?>,
        datasets: [{
            label: 'Jumlah Booking',
            data: <?= json_encode($total_booking); ?>,
            borderColor: '#f3b83f',
            backgroundColor: 'rgba(243, 184, 63, 0.3)',
            borderWidth: 2,
            fill: true,
            tension: 0.3,
            pointRadius: 5,
            pointBackgroundColor: '#f3b83f'
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

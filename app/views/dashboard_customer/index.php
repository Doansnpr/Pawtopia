<?php
// === KONEKSI DATABASE ===
$koneksi = new mysqli("localhost", "root", "", "pawtopia");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// === DATA USER LOGIN ===
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_user = $_SESSION['user']['id_users'] ?? 1;

// === DATA USER ===
$query_user = $koneksi->query("SELECT nama_lengkap FROM users WHERE id_users = '$id_user'");
$data_user = $query_user->fetch_assoc();
$nama_pengguna = $data_user['nama_lengkap'] ?? 'Pengguna';

// === DATA BOOKING UNTUK CHART ===
$query_chart = $koneksi->query("
    SELECT MONTH(tgl_booking) AS bulan, COUNT(*) AS total_booking 
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

// === DATA BOOKING USER ===
$bookings = [];
$query_booking = $koneksi->query("
    SELECT b.id_booking, b.id_mitra, m.nama_mitra AS tempat_penitipan, 
           b.id_kucing, b.tgl_booking, b.status 
    FROM booking b
    JOIN mitra m ON b.id_mitra = m.id_mitra
    WHERE b.id_users = '$id_user'
    ORDER BY b.tgl_booking DESC
");
if ($query_booking && $query_booking->num_rows > 0) {
    while ($row = $query_booking->fetch_assoc()) {
        $bookings[] = $row;
    }
}

// === RATA-RATA RATING PER MITRA ===
$query_rating = $koneksi->query("
    SELECT m.id_mitra, m.nama_mitra, AVG(u.rating) AS rata_rating
    FROM ulasan u
    JOIN booking b ON u.id_booking = b.id_booking
    JOIN mitra m ON b.id_mitra = m.id_mitra
    GROUP BY m.id_mitra
");

$rating_mitra = [];
while ($r = $query_rating->fetch_assoc()) {
    $rating_mitra[$r['id_mitra']] = round($r['rata_rating'], 1);
}

// === fallback kalau belum ada rating ===
$rating_mitra[1] = $rating_mitra[1] ?? 0;
$rating_mitra[2] = $rating_mitra[2] ?? 0;
?>

<h2 style="margin-bottom:0.5rem;">Halo, <?= htmlspecialchars($nama_pengguna); ?>!</h2>
<p style="margin-top:0;">Selamat datang di Pawtopia, tempat nyaman untuk titip si bulu kesayanganmu.</p>

<!-- === BAGIAN ATAS: 3 KOTAK SEJAJAR === -->
<div style="
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  gap: 1rem;
  margin: 1.5rem auto;
  width: 100%;
  max-width: 1200px;
  box-sizing: border-box;
">

  <!-- 🐱 Kucing yang Dititipkan -->
  <div style="
    background: #f3b83f;
    color: #fff;
    border-radius: 1rem;
    padding: 1.2rem;
    flex: 0 0 25%;
    text-align: center;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    justify-content: center;
  ">
    <p style="margin: 0; font-weight: 500;">Kucing yang Dititipkan</p>
    <h2 style="margin: 0.5rem 0; font-size: 2rem;"><?= count($bookings); ?></h2>
    <p style="margin: 0; font-weight: 500;">Tempat Penitipan</p>
  </div>

  <!-- 📊 Aktivitas Booking -->
  <div style="
    background: #fff;
    border: 2px solid #f3b83f;
    border-radius: 1rem;
    padding: 1.2rem;
    flex: 0 0 40%;
    box-shadow: 0 3px 6px rgba(0,0,0,0.05);
  ">
    <p style="margin: 0; font-weight: 500;">Aktivitas Booking</p>
    <h3 style="color: #f3b83f; margin: 0.5rem 0 1rem 0;"> <?= htmlspecialchars($nama_pengguna); ?></h3>
    <canvas id="chartBooking" style="width: 100%; height: 200px;"></canvas>
  </div>

  <!-- 🍩 Rata-Rata Rating Mitra -->
  <div style="
    background: #fff;
    border: 2px solid #f3b83f;
    border-radius: 1rem;
    padding: 1.2rem;
    flex: 0 0 30%;
    text-align: center;
    box-shadow: 0 3px 6px rgba(0,0,0,0.05);
  ">
    <h3 style="color:#f3b83f;margin-bottom:1rem;">Rata-Rata Rating Mitra</h3>
    <canvas id="ratingChart" width="230" height="230"></canvas>
    <div style="margin-top:10px;display:flex;justify-content:center;gap:15px;font-size:13px;">
      <div><span style="display:inline-block;width:12px;height:12px;background:#ffb300;border-radius:3px;margin-right:5px;"></span>Mitra 1 (<?= $rating_mitra[1]; ?>⭐)</div>
      <div><span style="display:inline-block;width:12px;height:12px;background:#ffe082;border-radius:3px;margin-right:5px;"></span>Mitra 2 (<?= $rating_mitra[2]; ?>⭐)</div>
    </div>
  </div>
</div>

<!-- === INFORMASI BOOKING === -->
<div style="margin:3rem auto;max-width:1100px;text-align:center;">
  <h3 style="color:#f3b83f;margin-bottom:1.5rem;font-size:1.4rem;">Informasi Booking</h3>

  <?php if (count($bookings) > 0): ?>
    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;justify-content:center;">
      <?php foreach ($bookings as $b): ?>
        <div style="
          background:#fff;
          border:2px solid #f3b83f;
          border-radius:1rem;
          padding:1.25rem;
          box-shadow:0 3px 6px rgba(0,0,0,0.05);
          width:320px;
        ">
          <h4 style="text-align:center;margin-top:0;color:#f3b83f;">
            <?= htmlspecialchars($b['tempat_penitipan']); ?>
          </h4>
          <div style="display:grid;grid-template-columns:1fr auto 1fr;row-gap:0.5rem;align-items:center;">
            <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">ID Kucing</div><div>:</div><div><?= htmlspecialchars($b['id_kucing']); ?></div>
            <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">Tanggal</div><div>:</div><div><?= htmlspecialchars($b['tgl_booking']); ?></div>
            <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">Status</div><div>:</div><div><?= htmlspecialchars($b['status']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p style="text-align:center;color:#777;">Belum ada booking kucing yang aktif.</p>
  <?php endif; ?>
</div>

<!-- === CHART.JS === -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.addEventListener('load', function() {
  // === CHART BOOKING ===
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
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
      plugins: { legend: { display: false } }
    }
  });

  // === CHART RATING MITRA ===
  const rating1 = <?= $rating_mitra[1]; ?>;
  const rating2 = <?= $rating_mitra[2]; ?>;
  const ratingCtx = document.getElementById('ratingChart').getContext('2d');
  new Chart(ratingCtx, {
    type: 'doughnut',
    data: {
      labels: ['Mitra 1', 'Mitra 2'],
      datasets: [{
        data: rating1 === 0 && rating2 === 0 ? [1] : [rating1, rating2],
        backgroundColor: rating1 === 0 && rating2 === 0 ? ['#ddd'] : ['#ffb300', '#ffe082'],
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

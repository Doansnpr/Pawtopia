
<h2 style="margin-bottom:0.5rem;">Halo, <?= htmlspecialchars($data['nama_pengguna']); ?>!</h2>
<p style="margin-top:0;">Selamat datang di Pawtopia, tempat nyaman untuk titip si bulu kesayanganmu.</p>

<div class="dashboard-cards">
    <div class="dashboard-card" style="background:#f3b83f;color:#fff; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center;">
        <p style="margin:0;font-weight:bold;">Kucing yang Dititipkan</p>
        <h2 style="margin:0.5rem 0;font-size:2rem;"><?= $data['jumlah_kucing']; ?></h2>
    </div>

    <div class="dashboard-card chart-container" style="border:2px solid #f3b83f;background:#fff;">
        <h3 style="color:#f3b83f;margin-bottom:1rem;">Aktivitas Booking</h3>
        <div style="margin:0.8rem 0;">
            <form method="GET" action="<?= BASEURL; ?>/DashboardCustomer" style="display:flex;justify-content:center;">
                <select name="tahun" onchange="this.form.submit()"
                    style="padding:6px 12px;border-radius:8px;border:1px solid #f3b83f;color:#333;">
                    <?php 
                        $yearNow = date("Y");
                        for ($i = $yearNow; $i >= $yearNow - 5; $i--): 
                    ?>
                        <option value="<?= $i ?>" <?= $data['tahun_pilih'] == $i ? "selected" : "" ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>
        <canvas id="chartBooking" style="width:100%;height:250px;"></canvas>
    </div>

    <div class="dashboard-card chart-container" style="border:2px solid #f3b83f;background:#fff;">
        <h3 style="color:#f3b83f;margin-bottom:1rem;">Rata-Rata Rating Mitra</h3>
        <canvas id="ratingChart" width="230" height="230"></canvas>
        
        <div style="margin-top:10px;display:flex;justify-content:center;gap:15px;font-size:13px;flex-wrap:wrap;">
          <?php foreach($data['rating_mitra'] as $id => $rating): ?>
            <div>
            <span style="display:inline-block;width:12px;height:12px;background:#ffb300;border-radius:3px;margin-right:5px;"></span>
            <?= htmlspecialchars($data['mitra_list'][$id] ?? 'Mitra '.$id); ?> (<?= $rating; ?>⭐)
            </div>
          <?php endforeach; ?>
        </div>
    </div>
</div>

<div style="margin:3rem auto;max-width:1100px;text-align:center;">
  <h3 style="color:#f3b83f;margin-bottom:1.5rem;font-size:1.4rem;">Informasi Booking</h3>

  <?php if (!empty($data['bookings'])): ?>
    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;justify-content:center;">
      <?php foreach ($data['bookings'] as $b): ?>
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
                
              <div><?= htmlspecialchars($b['nama_kucing'] ?? "Tidak ditemukan"); ?></div> 

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
      labels: <?= json_encode($data['bulan_nama']); ?>,
      datasets: [{
        label: 'Jumlah Booking Tahun <?= $data['tahun_pilih'] ?>',
        data: <?= json_encode($data['chart_data']); ?>,
        borderColor: '#f3b83f',
        backgroundColor: 'rgba(243,184,63,0.3)',
        borderWidth: 2,
        fill: true,
        responsive: true, 
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

  // === Chart Rating Mitra ===
  // Siapkan data rating dari PHP ke JS
  const ratingData = <?= json_encode(array_values($data['rating_mitra'])); ?>;
  const ratingKeys = <?= json_encode(array_keys($data['rating_mitra'])); ?>;
  const mitraNames = <?= json_encode($data['mitra_list']); ?>;
  
  // Mapping ID Mitra ke Nama untuk Labels Chart
  const ratingLabels = ratingKeys.map(id => mitraNames[id] ? mitraNames[id] : "Mitra " + id);

  const ratingCtx = document.getElementById('ratingChart').getContext('2d');
  new Chart(ratingCtx, {
    type: 'doughnut',
    data: {
      labels: ratingLabels,
      datasets: [{
        data: ratingData.length ? ratingData : [1],
        backgroundColor: ratingData.length ? ['#ffb300','#ffe082','#ffca28', '#ffd54f'] : ['#ddd'],
        borderWidth: 0
      }]
    },
    options: {
      cutout: '70%',
      plugins: { legend: { display: false } } // Legend dimatikan karena sudah ada custom legend di HTML
    }
  });
});
</script>
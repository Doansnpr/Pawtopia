<h2 style="margin-bottom:0.5rem;">Halo, <?= $data['nama_pengguna']; ?>!</h2>
<p style="margin-top:0;">Selamat datang di Pawtopia, tempat nyaman untuk titip si Pus kesayanganmu.</p>

<!-- CARD SECTION -->
<div style="display:flex;flex-wrap:wrap;gap:1.25rem;margin:1.5rem 0;justify-content:center;">
  
  <!-- Kucing yang Dititipkan -->
  <div style="background:#f3b83f;color:#fff;border-radius:1rem;padding:1.25rem;flex:1 1 250px;max-width:320px;text-align:center;box-shadow:0 3px 6px rgba(0,0,0,0.1);">
    <p style="margin:0;font-weight:500;">Kucing yang Dititipkan</p>
    <h2 style="margin:0.5rem 0;font-size:2rem;">2</h2>
    <p style="margin:0;font-weight:500;">Kicau Petshop</p>
  </div>

  <!-- Pengeluaran Bulan Ini -->
<div style="background:#fff;border:2px solid #f3b83f;border-radius:1rem;padding:1.25rem;flex:1 1 250px;max-width:320px;text-align:center;box-shadow:0 3px 6px rgba(0,0,0,0.05);">
  <p style="margin:0;font-weight:500;">Pengeluaran Bulan Ini</p>
  <h2 style="margin:0.5rem 0;color:#f3b83f;"><?= $data['pengeluaran']; ?></h2>
  <div style="position:relative;width:100%;max-width:280px;margin:auto;">
    <canvas id="chartPengeluaran" style="width:100%;aspect-ratio:1.6/1;"></canvas>
  </div>
</div>

<!-- Rata-Rata Rating Mitra -->
<div style="background:#fff;border:2px solid #f3b83f;border-radius:1rem;padding:1.25rem;flex:1 1 250px;max-width:320px;text-align:center;box-shadow:0 3px 6px rgba(0,0,0,0.05);">
  <p style="margin:0;font-weight:500;">Rata-Rata Rating Mitra</p>
  <h2 style="margin:0.5rem 0;color:#f3b83f;"><?= $data['rating']; ?></h2>
  <div style="position:relative;width:100%;max-width:280px;margin:auto;">
    <canvas id="chartRating" style="width:100%;aspect-ratio:1/1;"></canvas>
  </div>
</div>

<!-- INFO BOX -->
<div style="background:#fff;border:2px solid #f3b83f;border-radius:1rem;padding:2rem;margin-top:1.5rem;
            box-shadow:0 3px 6px rgba(0,0,0,0.05);width:95%;max-width:1200px;margin-inline:auto;">
  <h3 style="text-align:center;color:#f3b83f;margin:0 0 1.5rem 0;font-size:1.4rem;">Informasi Booking</h3>

  <div style="display:grid;grid-template-columns:1fr auto 1fr;row-gap:0.75rem;align-items:center;font-size:1rem;line-height:1.6;">
    <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">Tempat Penitipan</div>
    <div style="text-align:center;color:#555;">:</div>
    <div style="text-align:left;padding-left:1rem;"><?= $data['tempat_penitipan']; ?></div>

    <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">Nama Kucing</div>
    <div style="text-align:center;color:#555;">:</div>
    <div style="text-align:left;padding-left:1rem;"><?= $data['nama_kucing']; ?></div>

    <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">Tanggal Penitipan</div>
    <div style="text-align:center;color:#555;">:</div>
    <div style="text-align:left;padding-left:1rem;"><?= $data['tanggal_penitipan']; ?></div>

    <div style="font-weight:600;color:#555;text-align:right;padding-right:1rem;">Status</div>
    <div style="text-align:center;color:#555;">:</div>
    <div style="text-align:left;padding-left:1rem;"><?= $data['status']; ?></div>
  </div>
  </div>

<!-- CTA (di luar box) -->
<div style="text-align:center;margin-top:2rem;">
  <p style="margin-bottom:0.75rem;font-size:1rem;">Butuh tempat penitipan baru? Yuk, cari hotel kucing terbaik 💛</p>
  <button style="background:#fff;border:2px solid #f3b83f;border-radius:0.75rem;padding:0.7rem 1.5rem;
                 font-weight:600;color:#333;cursor:pointer;transition:0.2s;font-size:1rem;">
    ➕ Buat Booking Baru
  </button>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Chart pengeluaran (bar)
  const pengeluaranCtx = document.getElementById('chartPengeluaran');
  new Chart(pengeluaranCtx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov','Des'],
      datasets: [{
        label: 'Pengeluaran',
        data: [2000000, 1500000, 3000000, 2500000, 1800000, 2800000],
        backgroundColor: '#f3b83f'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      aspectRatio: 1.6,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });

  // Chart rating (donat)
  const ratingCtx = document.getElementById('chartRating');
  new Chart(ratingCtx, {
    type: 'doughnut',
    data: {
      labels: ['5⭐', '4⭐', '3⭐'],
      datasets: [{
        data: [70, 20, 10],
        backgroundColor: ['#f3b83f', '#f7d77b', '#fceec5']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      aspectRatio: 1,
      plugins: { legend: { position: 'bottom' } }
    }
  });
</script>


<!-- MEDIA QUERY (buat mobile) -->
<style>
@media (max-width: 768px) {
  h2 { font-size: 1.4rem !important; }
  .cards { flex-direction: column !important; }
  table td { display: block; width: 100%; }
  table tr td:first-child { font-weight: bold; }
}
</style>

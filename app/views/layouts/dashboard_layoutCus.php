<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($data['title'] ?? 'Dashboard'); ?></title>

<title><?= $data['title']; ?></title>

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- ✅ Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>
body {
  font-family: "Poppins", sans-serif;
  margin: 0;
  background-color: #fffaf0;
  display: flex;
  height: 100vh;
  color: #333;
}

/* SIDEBAR */
.sidebar {
  width: 200px;
  min-width: 150px;
  position: fixed;
  left: 0;
  top: 0;
  bottom: 0;
  background-color: #fff;
  border-right: 2px solid #f3b83f;
  padding: 20px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.3s ease;
  z-index: 1000;
}

.sidebar .profile {
  text-align: center;
  margin-bottom: 20px;
}

.sidebar .profile img {
  width: 90px;
  height: auto;
  object-fit: contain;
  margin-bottom: 10px;
}

/* MENU */
.menu a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 15px;
  color: #444;
  text-decoration: none;
  border-radius: 10px;
  margin-bottom: 10px;
  font-weight: 500;
  width: 100%;
  box-sizing: border-box;
}

.menu a:hover,
.menu a.active {
  background-color: #f3b83f;
  color: white;
}

/* LOGOUT */
.logout {
  text-align: center;
  padding: 10px;
  border-top: 1px solid #eee;
}

.logout a {
  display: inline-block;
  color: #f39c12;
  font-weight: bold;
  text-decoration: none;
  padding: 5px 10px;
  border-radius: 10px;
}

/* MAIN CONTENT */
.main {
  margin-left: 200px;
  padding: 20px;
  flex: 1;
  overflow-x: hidden; /* cegah horizontal scroll */
  transition: all 0.3s ease;
  box-sizing: border-box;
}

/* DASHBOARD CARDS */
.dashboard-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  width: 100%;
  max-width: 1200px;
  margin: 1.5rem auto;
}

/* DASHBOARD CARD */
.dashboard-card {
  background: #fff;
  border: 2px solid #f3b83f;
  border-radius: 1rem;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 3px 6px rgba(0,0,0,0.05);
  box-sizing: border-box;
}

/* Set kartu agar responsif */
.dashboard-card {
  flex: 1 1 250px; /* minimum 250px, fleksibel */
  min-width: 250px;
  border-radius: 1rem;
  padding: 1.2rem;
  box-sizing: border-box;
  text-align: center;
  box-shadow: 0 3px 6px rgba(0,0,0,0.05);
}

/* INFORMASI BOOKING */
.booking-card {
  background:#fff;
  border:2px solid #f3b83f;
  border-radius:1rem;
  padding:1.5rem;
  box-shadow:0 3px 6px rgba(0,0,0,0.05);
  width: 100%;
  max-width: 750px;
  box-sizing: border-box;
}

.booking-grid {
  display: grid;
  grid-template-columns: 1fr 30px 1fr;
  row-gap: 12px;
  align-items: center;
}

.booking-grid div {
  word-break: break-word; /* agar teks panjang tidak pecah layout */
}

/* Container chart */
.chart-container {
  flex: 1 1 350px;
  min-width: 300px;
}

/* RESPONSIVE */
@media (max-width: 992px) {
  .sidebar { width: 150px; }
  .main { margin-left: 150px; }
}

@media (max-width: 768px) {
  .sidebar {
    position: relative;
    width: 100%;
    height: auto;
    display: flex;
    flex-direction: row;
    padding: 10px;
    border-right: none;
    border-bottom: 2px solid #f3b83f;
  }
  .main { margin-left: 0; padding: 15px; }
  .dashboard-cards { grid-template-columns: 1fr; }
  .booking-grid { grid-template-columns: 1fr 20px 1fr; }
}

</style>
</head>
<body>

<div class="sidebar">
  <div>
    <div class="profile">
      <img src="<?= BASEURL; ?>/images/logo_paw.png" alt="logo">
    </div>

    <div class="menu">
      <a href="<?= BASEURL; ?>/DashboardCustomer" class="<?= ($data['title'] ?? '') === 'Dashboard' ? 'active' : ''; ?>"> 
        <i class="fas fa-home"></i>Dashboard</a>
      <a href="#"><i class="fa-solid fa-user"></i>Profil</a>
      <a href="#"><i class="fa-solid fa-magnifying-glass-location"></i>Cari Penitipan</a>
      <a href="#"><i class="fa-solid fa-receipt"></i>Booking</a>
      <a href="<?= BASEURL; ?>/DashboardCustomer/status_penitipan" class="<?= ($data['title'] ?? '') === 'Status' ? 'active' : ''; ?>"><i class="fa-solid fa-map-pin"></i>Status</a>
      <a href="<?= BASEURL; ?>/DashboardCustomer/ulasan" class="<?= ($data['title'] ?? '') === 'Beri Ulasan' ? 'active' : ''; ?>">
        <i class="fa-solid fa-comment-dots"></i>Beri Ulasan</a>
    </div>
  </div>

  <div class="logout">
    <a href="<?= BASEURL; ?>/home"><i class="fa-solid fa-arrow-up-right-from-square"></i> Keluar</a>
  </div>
</div>

<div class="main">
  <div class="pawtopia-logo"></div>

  <?php
  // ✅ Cek dan include view yang sesuai
  $pathFile = __DIR__ . '/../' . $data['content'] . '.php';
  if (!file_exists($pathFile)) {
      echo "<pre style='color:red;font-weight:bold;'>⚠️ File tidak ditemukan di: $pathFile</pre>";
  } else {
      include $pathFile;
  }
  ?>
</div>

<!-- ✅ Flash Message SweetAlert -->
<?php if (isset($_SESSION['flash'])): ?>
<script>
Swal.fire({
    title: "<?= $_SESSION['flash']['pesan']; ?>",
    text: "<?= $_SESSION['flash']['aksi']; ?>",
    icon: "<?= $_SESSION['flash']['tipe']; ?>",
    confirmButtonColor: "#f3b83f"
});
</script>
<?php unset($_SESSION['flash']); endif; ?>

</body>
</html>

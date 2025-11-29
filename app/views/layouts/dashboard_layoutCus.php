<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($data['title'] ?? 'Dashboard'); ?></title>

<title><?= $data['title']; ?></title>

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
  background-color: #fff;
  border-right: 2px solid #f3b83f;
  padding: 20px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
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
  flex: 1;
  padding: 30px 40px;
  margin-left: 200px;
  overflow-y: auto;
  transition: all 0.3s ease;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .sidebar {
    width: 150px;
  }

  .main {
    margin-left: 150px;
    padding: 20px;
  }

  .menu a {
    font-size: 0.9rem;
    padding: 8px 10px;
  }

  

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
      <a href="<?= BASEURL; ?>/DashboardCustomer" class="<?= ($data['title'] ?? '') === 'Dashboard' ? 'active' : ''; ?>">Dashboard</a>
      <a href="#">Profil</a>
      <a href="<?= BASEURL; ?>/DashboardCustomer/Penitipan" class="<?= ($data['title'] ?? '') === 'Cari Penitipan' ? 'active' : ''; ?>">Cari Penitipan</a>
      <a href="<?= BASEURL; ?>/DashboardCustomer/Booking" class="<?= ($data['title'] ?? '') === 'Booking' ? 'active' : ''; ?>">Booking</a>
      <a href="#">Status</a>
      <a href="<?= BASEURL; ?>/DashboardCustomer/ulasan" class="<?= ($data['title'] ?? '') === 'Beri Ulasan' ? 'active' : ''; ?>">Beri Ulasan</a>

    </div>
  </div>

  <div class="logout">
    <a href="#">← Keluar</a>
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

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $data['title']; ?></title>
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
  justify-content: space-between; /* tombol logout tetap di bawah */
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
  height: auto;      /* biar proporsional sesuai gambar */
  border-radius: 0;  /* hapus lingkaran */
  object-fit: contain; /* tampilkan seluruh gambar tanpa crop */
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

.menu a img {
  width: 20px;
  height: 20px;
  object-fit: contain;
}

.menu a img.icon-booking {
  width: 22px;
  height: 22px;
}

.menu a:hover,
.menu a.active {
  background-color: #f3b83f;
  color: white;
}

.menu a:hover img,
.menu a.active img {
  filter: brightness(0) invert(1);
}

.logout {
  text-align: center;  /* pastikan container tengah */
  padding: 10px;
  border-top: 1px solid #eee;
}

.logout a {
  display: inline-block;   /* biar bisa diatur text-align center */
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
  margin-left: 200px; /* sesuai lebar sidebar */
  overflow-y: auto;
  transition: all 0.3s ease;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .sidebar {
    width: 150px; /* lebih kecil di HP */
  }

  .main {
    margin-left: 150px; /* sesuaikan lebar sidebar */
    padding: 20px;
  }

  .menu a {
    font-size: 0.9rem;
    padding: 8px 10px;
  }

  .menu a img {
    width: 18px;
    height: 18px;
  }

  .menu a img.icon-booking {
    width: 20px;
    height: 20px;
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
      <a href="#" class="active">
        <!-- <img src="<?= BASEURL; ?>/images/icon_dashboard.png" alt="Dashboard"> -->
        Dashboard
      </a>
      <a href="#">
        <!-- <img src="<?= BASEURL; ?>/images/icon_status.png" alt="Status Penitipan"> -->
        Profil
      </a>
      <a href="#">
        <!-- <img src="<?= BASEURL; ?>/images/icon_cari.png" alt="Cari Penitipan"> -->
        Cari Penitipan
      </a>
      <a href="#">
        <!-- <img src="<?= BASEURL; ?>/images/icon_booking.png" alt="Booking Penitipan" class="icon-booking"> -->
        Booking
      </a>
      <a href="#">
        <!-- <img src="<?= BASEURL; ?>/images/icon_status.png" alt="Status Penitipan"> -->
        Status
</a>
<a href="#">
        <!-- <img src="<?= BASEURL; ?>/images/icon_status.png" alt="Status Penitipan"> -->
        Beri ulasan
</a>
    </div>
  </div>

  <div class="logout">
    <a href="#">← Keluar</a>
  </div>
</div>

<div class="main">
  <div class="pawtopia-logo"></div>
  <?php include __DIR__ . '/../' . $data['content'] . '.php'; ?>
</div>

</body>
</html>
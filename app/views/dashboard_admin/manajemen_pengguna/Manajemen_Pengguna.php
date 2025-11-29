<?php
// BYPASS DEV CEPAT (hapus nanti pas produksi)
session_start();
$_SESSION['login_admin'] = true;

// tambahkan 2 baris ini
$nama_user = "Elyazid";
$foto_user = "https://via.placeholder.com/50/fd7e14/white?text=User";

// koneksi database
require_once __DIR__ . '/../../../core/Database.php';

$db   = new Database();
$conn = $db->getConnection();

$sql    = "SELECT * FROM users";
$result = $conn->query($sql);
if (!$result) {
    die("Query error: " . $conn->error);
}
?>


<!DOCTYPE html>
<html lang="id">
      
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Pengguna (Admin) - PawTopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100%; width: 260px;
            background: linear-gradient(180deg, #f1f3f5 0%, #e9ecef 100%);
            padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); z-index: 1000;
        } 
        .sidebar .nav-link  { 
            color: #49 5057; padding: 14px 25px; margin: 8px 15px;
            border-radius: 12px; font-weight: 500; display: flex; align-items: center;
        } 
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #ced4da; color: #212529;
        }
        .sidebar .nav-link img { width: 24px; height: 24px; margin-right: 15px; }
        .main-content { margin-left: 260px; padding: 30px; }
        .badge-role {
            font-size: 0.8rem; padding: 6px 16px; border-radius: 50px; font-weight: bold;
        }
        .aksi-btn { font-size: 0.9rem; text-decoration: none; margin: 0 8px; }
        .aksi-btn i { margin-right: 4px; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark">PawTopia</h2>
        </div>

        <div class="d-flex align-items-center mx-4 mb-4 p-3 bg-white-rounded-3 shadow-sm">
            <img src="<?= $foto_user ?>" class="rounded-circle me-3" width="50" height="50" alt="User">
            <div>
                <strong class="d-block"><?= $nama_user ?></strong>
                <small class="text-muted">Admin</small>
            </div>
        </div>

        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <img src="/pawtopia/public/images/icon_dashboard.png" alt="Dashboard">
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <img src="/pawtopia/public/image/manajemen_pengguna.png" alt="Pengguna">
                    Manajemen Pengguna
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <img src="/Pawtopia/public/images/monitoring_transaksi.png"alt="Transaksi">
                    Monitoring Transaksi
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <img src="/pawtopia/public/images/laporan_sistem.png" alt="Laporan">
                    Laporan Sistem
                </a>
            </li>
        </ul>
    </div>

   
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-muted fw-bold">MANAJEMEN PENGGUNA </h3>
        <img src="/pawtopia/public/images/logo_paw.png" alt="PawTopia Logo" height="80">
    </div>

        <div class="bg-white rounded-4 shadow p-4">
            <div class="mb-4">
                <label class="form-label fw-bold">Cari pengguna :</label>
                <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Masukkan nama / email / no HP">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tbody>
<?php
$no = 1;
if (!empty($result)) {
    foreach ($result as $row) {
?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
        <td><?= htmlspecialchars($row['role']); ?></td>
        <td><?= htmlspecialchars($row['email']); ?></td>
        <td><?= htmlspecialchars($row['no_hp']); ?></td>
        <td>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                        type="button" data-bs-toggle="dropdown">
                    Aksi
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Dikonfirmasi 1</a></li>
                    <li><a class="dropdown-item" href="#">Dikonfirmasi 2</a></li>
                    <li><a class="dropdown-item" href="#">Dikonfirmasi 3</a></li>
                    <li><a class="dropdown-item" href="#">Dikonfirmasi 4</a></li>
                </ul>
            </div>
        </td>
    </tr>
<?php
    }
} else {
?>
    <tr>
        <td colspan="6" class="text-center text-muted py-5">
            Belum ada data pengguna. Silakan tambah pengguna baru.
        </td>
    </tr>
<?php } ?>
</tbody>

                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada data pengguna. Silakan tambah pengguna baru.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-center my-5">
                <a href="Tambah_Pengguna.php" class="btn btn-warning text-white px-2 py-1,5 fw-bold">
                    <i class="fas fa-plus me-2"></i> Tambah Pengguna
                </a>
            </div>

            <div class="mt-4">
                <a href="#" class="text-decoration-none text-secondary">
                    ← Keluar
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>
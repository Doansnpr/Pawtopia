<?php
// BYPASS DEV CEPAT (hapus nanti pas produksi)
session_start();
$_SESSION['login_admin'] = true;
$nama_user = "Elyazid";
$foto_user = "https://via.placeholder.com/50/fd7e14/white?text=User"; // ganti foto asli kalau ada
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Pengguna (Admin) - PawTopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100%; width: 260px;
            background: linear-gradient(180deg, #f1f3f5 0%, #e9ecef 100%);
            padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); z-index: 1000;
        }
        .sidebar .nav-link {
            color: #495057; padding: 14px 25px; margin: 8px 15px;
            border-radius: 12px; font-weight: 500; display: flex; align-items: center;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #ced4da; color: #212529;
        }
        .sidebar .nav-link img { width: 24px; height: 24px; margin-right: 15px; }
        .main-content { margin-left: 260px; padding: 30px; }
        .btn-simpan {
            background-color: #fd7e14; color: white; border: none;
            padding: 12px 40px; border-radius: 50px; font-weight: bold;
        }
        .btn-simpan:hover { background-color: #e96d00; }
        .form-control { border-radius: 12px; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark">PawTopia</h2>
        </div>

        <div class="d-flex align-items-center mx-4 mb-4 p-3 bg-white rounded-3 shadow-sm">
            <img src="\public\images\logo_paw.png" class="rounded-circle me-3" width="50" height="50" alt="User">
            <div>
                <strong class="d-block"><?= $nama_user ?></strong>
                <small class="text-muted">Admin</small>
            </div>
        </div>

        <ul class="nav flex-column px-2">
            <li class="nav-item"><a href="#" class="nav-link"><img src="\public\images\icon_dashboard.png" alt="Dashboard"> Dashboard</a></li>
            <li class="nav-item"><a href="Manajemen_Pengguna.php" class="nav-link active"><img src="\public\images\manajemen_pengguna.png" alt="Pengguna"> Manajemen Pengguna</a></li>
            <li class="nav-item"><a href="#" class="nav-link"><img src="\public\images\monitoring_transaksi.png" alt="Transaksi"> Monitoring Transaksi</a></li>
            <li class="nav-item"><a href="#" class="nav-link"><img src="\public\images\laporan_sistem.png" alt="Laporan"> Laporan Sistem</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Isi Data Dulu Yaa</h2>
            <img src="\public\images\logo_paw.png" alt="PawTopia Logo" height="70">
        </div>

        <div class="bg-white rounded-4 shadow p-5">
            <div class="row g-5">
                <!-- Form Data Pemilip Kucing (Customer) -->
                <div class="col-lg-6">
                    <h4 class="mb-4 text-dark fw-bold">Data Pemilik Kucing</h4>
                    <form action="proses_tambah_customer.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PassWord</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" class="form-control" name="no_hp" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="customer">Customer</option>
                                <option value="mitra">Mitra</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Daftar</label>
                            <input type="date" class="form-control" name="tgl_daftar" value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-simpan btn-lg">Simpan Data Customer</button>
                        </div>
                    </form>
                </div>

                <!-- Form Data Mitra -->
                <div class="col-lg-6">
                    <h4 class="mb-4 text-dark fw-bold">Data Mitra</h4>
                    <form action="proses_tambah_mitra.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nama Mitra</label>
                            <input type="text" class="form-control" name="nama_mitra" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" rows="3" name="alamat" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" class="form-control" name="no_hp" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" rows="3" name="deskripsi"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" class="form-control" name="kapasitas" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga per Hari</label>
                            <input type="number" class="form-control" name="harga_per_hari" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" name="foto_profil" accept="image/*">
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-simpan btn-lg">Simpan Data Mitra</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-5">
                <a href="Manajemen_Pengguna.php" class="text-decoration-none text-secondary">
                    ← Keluar
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
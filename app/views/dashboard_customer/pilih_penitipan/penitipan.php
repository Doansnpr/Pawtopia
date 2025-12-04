<?php
// ⚠️ Ganti dengan kredensial database Anda
$host = 'localhost';
$db   = 'pawtopia'; // Ganti dengan nama database Anda
$user = 'root'; // Ganti dengan username database Anda
$pass = ''; // Ganti dengan password database Anda
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$penitipan = []; // Inisialisasi array untuk menyimpan data

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // 1. Definisikan Query SQL untuk mengambil data dari tabel 'mitra'
     // Kita hanya mengambil kolom 'nama_petshop' dan 'id_mitra'
     $sql = "SELECT id_mitra, nama_petshop FROM mitra";
     
     // 2. Eksekusi Query
     $stmt = $pdo->query($sql);
     
     // 3. Ambil semua hasil dan simpan ke variabel $penitipan
     $penitipan = $stmt->fetchAll();
     
} catch (\PDOException $e) {
     // Menangani error koneksi atau query
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
     // Anda bisa mengganti ini dengan die("Gagal mengambil data dari database.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Penitipan</title>

    <style>
        body {
            background: #e7f0fa;
            font-family: Poppins, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container { width: 90%; margin: auto; padding: 20px; }
        .card { background: white; padding: 20px; border-radius: 12px; border: 2px solid #f1b56c; display: flex; justify-content: space-between; }
        .card img { width: 200px; height: 150px; border-radius: 10px; object-fit: cover; }
        .btn-next {
            background: #ffa726;
            padding: 14px;
            color: white;
            border: none;
            width: 100%;
            margin-top: 20px;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
        }
        .filter-box { display: flex; gap: 15px; margin-bottom: 20px; }
        select {
            padding: 12px;
            border-radius: 8px;
            border: 2px solid #ffb245;
            width: 50%;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Pilih Penitipan Kucing</h2>
    <p>Temukan penitipan terbaik untuk si meow-mu 🐾</p>

    <form method="GET">
        <div class="filter-box">
            <select name="lokasi">
                <option value="">Cari Tempat Penitipan</option>
                <?php foreach ($penitipan as $p): ?>
                    <option value="<?= $p['nama_petshop'] ?>"><?= $p['nama_petshop'] ?></option>
                <?php endforeach; ?>
            </select>

            <select name="fasilitas">
                <option value="">Pilih Fasilitas</option>
                <option value="AC">Ruangan AC</option>
                <option value="CCTV">CCTV</option>
                <option value="Full Service">Full Service</option>
            </select>
        </div>

        <button type="submit" class="btn-next">Filter</button>
    </form>

    <br>

    <?php foreach ($penitipan as $p): ?>
        <div class="card">
            <div>
                <h3><?= $p['nama_petshop'] ?></h3>
                <p><?= $p['deskripsi'] ?></p>

                <p>Harga : <?= number_format($p['harga'], 0, ',', '.') ?></p>
                <p>Tersedia : <?= $p['kapasitas'] ?> Kandang</p>
                <p>Owner : <?= $p['owner'] ?></p>
            </div>

            <img src="/public/images/<?= $p['foto'] ?>">
        </div>

        <br>
    <?php endforeach; ?>

    <button class="btn-next">Simpan dan Lanjut ke Halaman Berikutnya</button>

</div>

</body>
</html>

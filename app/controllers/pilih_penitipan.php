<?php
// ===================
// KONEKSI DATABASE
// ===================
$host = 'localhost';
$db   = 'pawtopia';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Gagal koneksi database: " . $e->getMessage());
}


// ===================
// AMBIL DATA MITRA
// ===================
$sqlMitra = "SELECT nama_petshop, deskripsi, kapasitas, foto_profil FROM mitra";
$stmt = $pdo->query($sqlMitra);
$penitipan = $stmt->fetchAll(PDO::FETCH_ASSOC);



// =========================
// AMBIL DATA PAKET DARI MITRA_PAKET
// =========================
$sqlPaket = "SELECT id_mitra, nama_paket, harga 
             FROM mitra_paket";
$stmt2 = $pdo->query($sqlPaket);
$paketData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Kelompokkan paket berdasarkan id_mitra
$paketByMitra = [];
foreach ($paketData as $pk) {
    $paketByMitra[$pk['id_mitra']][] = $pk; 
}



// ===================
// GABUNGKAN PAKET KE MITRA
// ===================
foreach ($penitipan as &$m) {
    $m['paket'] = [];

    foreach ($paket as $p) {
        if ($p['id_mitra'] == $m['id_mitra']) {
            $m['paket'][] = $p;
        }
    }
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
        .card { 
            background: white; 
            padding: 20px; 
            border-radius: 12px; 
            border: 2px solid #f1b56c; 
            display: flex; 
            justify-content: space-between; 
        }
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
    </style>
</head>

<body>

<div class="container">

    <h2>Pilih Penitipan Kucing</h2>
    <p>Temukan penitipan terbaik untuk si meow-mu 🐾</p>
    <br>

    <?php foreach ($penitipan as $p): ?>
    <div class="card">
        <div>
            <h3><?= $p['nama_petshop'] ?></h3>
            <p><?= $p['deskripsi'] ?></p>

            <p><strong>Daftar Harga:</strong></p>

            <?php if (!empty($paketByMitra[$p['id_mitra']])): ?>
                <ul>
                    <?php foreach ($paketByMitra[$p['id_mitra']] as $pkt): ?>
                        <li><?= $pkt['nama_paket'] ?> — Rp <?= number_format($pkt['harga'], 0, ',', '.') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Tidak ada paket</p>
            <?php endif; ?>

            <p>Tersedia: <?= $p['kapasitas'] ?> Kandang</p>
        </div>

        <img src="/public/images/<?= $p['foto_profil'] ?>">
    </div>
<?php endforeach; ?>


    <button class="btn-next">Simpan dan Lanjut ke Halaman Berikutnya</button>

</div>

</body>
</html>

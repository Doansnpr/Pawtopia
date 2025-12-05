<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Resmi - Pawtopia</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- RESET & BASIC SETUP --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background-color: #525659; /* Background gelap di layar monitor */
            font-family: 'Poppins', sans-serif;
            color: #333;
            font-size: 12px; /* Ukuran font standar dokumen */
        }

        /* --- SETTING KERTAS A4 --- */
        .page-a4 {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 20px auto;
            padding: 15mm 20mm; /* Margin Kertas: Atas-Bawah 15mm, Kiri-Kanan 20mm */
            position: relative;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }

        /* --- KOP SURAT (HEADER) --- */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #FF9F43; /* Garis Oranye */
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .logo {
            width: 80px;
            margin-right: 20px;
        }
        .company-info h1 {
            font-size: 22px;
            color: #2D3436;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .company-info p {
            font-size: 10px;
            color: #636E72;
            line-height: 1.4;
        }

        /* --- JUDUL LAPORAN --- */
        .report-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .report-title h2 {
            font-size: 18px;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .report-title span {
            font-size: 11px;
            color: #666;
        }

        /* --- KARTU RINGKASAN (STATS) --- */
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 15px;
        }
        .stat-box {
            flex: 1;
            border: 1px solid #eee;
            background-color: #fffbf5; /* Background oranye sangat muda */
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-box h3 {
            font-size: 10px;
            text-transform: uppercase;
            color: #FF9F43;
            margin-bottom: 5px;
        }
        .stat-box p {
            font-size: 16px;
            font-weight: 700;
            color: #2D3436;
        }

        /* --- TABEL DATA --- */
        .table-container {
            width: 100%;
            margin-bottom: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        thead th {
            background-color: #FF9F43;
            color: white;
            text-align: left;
            padding: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        tbody td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            color: #444;
        }
        /* Zebra Striping */
        tbody tr:nth-child(even) {
            background-color: #fcfcfc;
        }
        
        /* Footer Total Table */
        tfoot td {
            padding: 15px 10px;
            font-weight: bold;
            background-color: #f9f9f9;
            border-top: 2px solid #ddd;
        }

        /* --- TANDA TANGAN --- */
        .signature-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 50px;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-box p { margin-bottom: 60px; font-weight: 600; }
        .signature-line { border-top: 1px solid #333; margin-top: 5px; }

        /* --- SETTING KHUSUS PRINTER --- */
        @media print {
            body { background: none; -webkit-print-color-adjust: exact; }
            .page-a4 { 
                margin: 0; 
                width: 100%; 
                box-shadow: none; 
                padding: 10mm; /* Sesuaikan margin printer */
                min-height: auto;
            }
            /* Hilangkan header/footer browser (URL, Page Number) */
            @page { margin: 0; size: A4; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="page-a4">
        
        <div class="header">
            <img src="<?= BASEURL ?>/img/logo-pawtopia.png" alt="Logo" class="logo" onerror="this.style.display='none'">
            <div class="company-info">
                <h1><?= $data['mitra_profile']['nama_petshop'] ?? 'Mitra Pawtopia' ?></h1>
                <p>
                    <?= $data['mitra_profile']['alamat'] ?? 'Alamat Petshop Belum Diatur' ?><br>
                    Telp: <?= $data['mitra_profile']['no_hp'] ?? '-' ?> | Email: <?= $data['mitra_profile']['email'] ?? '-' ?><br>
                    <i>Laporan Resmi Mitra Pawtopia</i>
                </p>
            </div>
        </div>

        <div class="report-title">
            <h2>LAPORAN PENDAPATAN & TRANSAKSI</h2>
            <span>Periode: 
                <?= date('d M Y', strtotime($data['laporan']['start_date'])) ?> s/d 
                <?= date('d M Y', strtotime($data['laporan']['end_date'])) ?>
            </span>
        </div>

        <div class="stats-container">
            <div class="stat-box">
                <h3>Total Pendapatan</h3>
                <p>Rp <?= number_format($data['laporan']['financial']['pendapatan'], 0, ',', '.') ?></p>
            </div>
            <div class="stat-box">
                <h3>Transaksi Sukses</h3>
                <p><?= $data['laporan']['financial']['booking_selesai'] ?> Pesanan</p>
            </div>
            <div class="stat-box">
                <h3>Rata-rata Okupansi</h3>
                <p><?= $data['laporan']['occupancy']['rate'] ?>%</p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Tgl Masuk</th>
                        <th style="width: 15%;">ID Booking</th>
                        <th style="width: 20%;">Pelanggan</th>
                        <th style="width: 20%;">Paket</th>
                        <th style="width: 10%; text-align:center;">Jml Hewan</th>
                        <th style="width: 15%; text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['laporan']['history'])): ?>
                        <tr><td colspan="7" style="text-align:center;">Tidak ada data pada periode ini.</td></tr>
                    <?php else: ?>
                        <?php $no=1; foreach($data['laporan']['history'] as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tgl_mulai'])) ?></td>
                            <td>#<?= $row['id_booking'] ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($row['paket']) ?></td>
                            <td style="text-align:center;"><?= $row['jumlah_kucing'] ?></td>
                            <td style="text-align:right; font-weight:600;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align:right;">TOTAL PENDAPATAN</td>
                        <td style="text-align:right; color: #FF9F43;">Rp <?= number_format($data['laporan']['financial']['pendapatan'], 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>
                    <?= $data['mitra_profile']['kota'] ?? 'Kota Anda' ?>, <?= date('d F Y') ?><br>
                    Pemilik Petshop
                </p>
                <br><br>
                <div class="signature-line"></div>
                <span><?= $data['mitra_profile']['nama_pemilik'] ?? 'Admin' ?></span>
            </div>
        </div>

    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Resmi - Admin Pawtopia</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- 1. RESET & BASE STYLES --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background-color: #525659; /* Abu-abu gelap di layar monitor */
            font-family: 'Poppins', sans-serif;
            color: #2D3436;
            font-size: 11px; /* Ukuran font standar dokumen presisi */
            -webkit-print-color-adjust: exact; /* Paksa cetak warna */
        }

        /* --- 2. KERTAS A4 SETUP --- */
        .page-a4 {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 30px auto;
            padding: 15mm 20mm; /* Margin Kertas: Atas-Bawah 15mm, Kiri-Kanan 20mm */
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        /* --- 3. HEADER / KOP SURAT --- */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #FF9F43; /* Garis Oranye Pawtopia */
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header-left { display: flex; align-items: center; gap: 15px; }
        .logo-img { width: 70px; height: auto; }
        
        .company-details h1 { font-size: 20px; font-weight: 700; color: #2D3436; margin-bottom: 2px; letter-spacing: 0.5px; }
        .company-details p { font-size: 10px; color: #636E72; line-height: 1.3; }

        .header-right { text-align: right; }
        .report-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #b2bec3; margin-bottom: 5px; font-weight: 600; }
        .report-date { font-size: 12px; font-weight: 600; color: #2D3436; }

        /* --- 4. JUDUL LAPORAN --- */
        .report-title-box {
            text-align: center;
            margin-bottom: 30px;
        }
        .report-title-box h2 {
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2D3436;
            margin-bottom: 5px;
        }
        .report-title-box span {
            display: inline-block;
            padding: 5px 15px;
            background-color: #f1f2f6;
            border-radius: 20px;
            font-size: 10px;
            color: #636E72;
        }

        /* --- 5. STATS CARDS (PREMIUM LOOK) --- */
        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 35px;
        }
        .stat-card {
            flex: 1;
            padding: 15px 20px;
            border-radius: 12px;
            background-color: #fff;
            border: 1px solid #dfe6e9;
            border-left: 5px solid #FF9F43; /* Aksen Oranye */
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }
        .stat-card.blue { border-left-color: #0984e3; }
        .stat-card.green { border-left-color: #00b894; }

        .stat-label { font-size: 9px; text-transform: uppercase; color: #636E72; font-weight: 600; letter-spacing: 0.5px; display: block; margin-bottom: 5px; }
        .stat-value { font-size: 18px; font-weight: 700; color: #2D3436; }
        .stat-sub { font-size: 9px; color: #b2bec3; margin-top: 3px; display: block; }

        /* --- 6. TABEL DATA (CLEAN) --- */
        .table-container { width: 100%; margin-bottom: 40px; }
        
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        
        thead th {
            background-color: #2D3436; /* Header Gelap */
            color: #fff;
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 1px;
        }
        thead th:first-child { border-top-left-radius: 6px; }
        thead th:last-child { border-top-right-radius: 6px; }

        tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f2f6;
            color: #2D3436;
            vertical-align: middle;
        }
        /* Zebra Striping */
        tbody tr:nth-child(even) { background-color: #fcfcfc; }
        
        .col-id { color: #FF9F43; font-weight: 600; }
        .col-money { font-family: 'Consolas', monospace; font-weight: 600; text-align: right; }
        .col-status { text-align: center; }

        /* Status Badge (Simulated for Print) */
        .status-dot {
            height: 8px; width: 8px;
            background-color: #ccc;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .status-text { font-weight: 500; font-size: 10px; }

        /* --- 7. FOOTER TANDA TANGAN --- */
        .footer-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 220px;
            text-align: center;
        }
        .sign-city { margin-bottom: 40px; font-size: 11px; color: #636E72; }
        .sign-line {
            border-top: 1px solid #2D3436;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .sign-name { font-weight: 700; font-size: 12px; text-transform: uppercase; }
        .sign-role { font-size: 10px; color: #636E72; }

        /* --- 8. PRINT MEDIA QUERY --- */
        @media print {
            body { background: none; }
            .page-a4 {
                width: 100%; margin: 0; padding: 0;
                box-shadow: none; border: none;
            }
            @page { size: A4; margin: 15mm; }
            html { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="page-a4">
        
        <div class="header-section">
            <div class="header-left">
                <img src="<?= BASEURL; ?>/images/logo_pawtopia.png" alt="Pawtopia" class="logo-img" onerror="this.style.display='none'">
                <div class="company-details">
                    <h1>PAWTOPIA INDONESIA</h1>
                    <p>Jalan Kucing Dalam No. 88, Jakarta Selatan<br>
                    support@pawtopia.com | (021) 555-8888</p>
                </div>
            </div>
            <div class="header-right">
                <div class="report-label">Generated On</div>
                <div class="report-date"><?= date('d F Y, H:i'); ?> WIB</div>
            </div>
        </div>

        <div class="report-title-box">
            <h2>Laporan Keuangan & Transaksi</h2>
            <span>Data Laporan Menyeluruh - Admin Dashboard</span>
        </div>

        <div class="stats-container">
            <div class="stat-card green">
                <span class="stat-label">Total Pendapatan Admin</span>
                <div class="stat-value">Rp <?= number_format($data['stats']['pendapatan_admin'], 0, ',', '.'); ?></div>
                <span class="stat-sub">Pendapatan bersih platform</span>
            </div>

            <div class="stat-card blue">
                <span class="stat-label">Gross Merchandise Value (GMV)</span>
                <div class="stat-value">Rp <?= number_format($data['stats']['gmv_total'], 0, ',', '.'); ?></div>
                <span class="stat-sub">Total perputaran uang transaksi</span>
            </div>

            <div class="stat-card">
                <span class="stat-label">Booking Selesai</span>
                <div class="stat-value"><?= $data['stats']['booking_selesai']; ?> <small style="font-size:10px; font-weight:400;">Trx</small></div>
                <span class="stat-sub">Total layanan berhasil</span>
            </div>
        </div>

        <h5 style="font-size: 11px; font-weight: 600; margin-bottom: 10px; text-transform:uppercase; color:#636E72;">Rincian Transaksi Terbaru</h5>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Tgl Booking</th>
                        <th style="width: 12%;">ID Booking</th>
                        <th style="width: 20%;">Mitra Petshop</th>
                        <th style="width: 18%;">Customer</th>
                        <th style="width: 15%; text-align: right;">Nominal</th>
                        <th style="width: 15%; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['riwayat'])): ?>
                        <tr><td colspan="7" style="text-align:center; padding: 30px;">Tidak ada data transaksi.</td></tr>
                    <?php else: ?>
                        <?php $no=1; foreach ($data['riwayat'] as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tgl_booking'])); ?></td>
                            <td class="col-id">#<?= $row['id_booking']; ?></td>
                            <td style="font-weight: 500;"><?= htmlspecialchars($row['nama_petshop']); ?></td>
                            <td style="color: #636E72;"><?= htmlspecialchars($row['nama_customer']); ?></td>
                            <td class="col-money">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            <td class="col-status">
                                <?php 
                                    $dotColor = match($row['status']) {
                                        'Selesai' => '#00b894', // Hijau
                                        'Dibatalkan', 'Ditolak' => '#ff7675', // Merah
                                        'Menunggu Konfirmasi' => '#fdcb6e', // Kuning
                                        default => '#b2bec3' // Abu
                                    };
                                ?>
                                <span class="status-dot" style="background-color: <?= $dotColor ?>;"></span>
                                <span class="status-text"><?= $row['status']; ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer-section">
            <div class="signature-box">
                <div class="sign-city">Jakarta, <?= date('d F Y'); ?></div>
                <br><br><br> <div class="sign-line"></div>
                <div class="sign-name">Administrator Pawtopia</div>
                <div class="sign-role">ID: <?= $_SESSION['user']['id_users'] ?? 'ADM-SYS'; ?></div>
            </div>
        </div>

    </div>

</body>
</html>
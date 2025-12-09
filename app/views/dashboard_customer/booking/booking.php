<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- CSS DASAR --- */
    :root {
        --primary-orange: #FF9F1C;
        --light-orange: #fff8e1;
        --primary-hover: #e68a00;
        --text-dark: #2d3436;
        --bg-light: #f5f6fa;
        --white: #ffffff;
        --border-color: #dee2e6;
    }

    .container { max-width: 100%; width: 100%; margin: 0 auto; }

    /* Perbaikan Container Utama */
    .reservasi-content { 
        padding: 15px; /* Padding lebih kecil */
        font-family: 'Poppins', sans-serif;
        max-width: 100%;
        overflow-x: hidden; /* Mencegah scrollbar ganda pada body */
    }

    /* --- COMPACT HEADER CARD STYLE --- */
    .header-card {
        background: linear-gradient(135deg, #FF9F1C 0%, #ffb347 100%);
        padding: 20px 25px; /* Diperkecil dari 30px */
        border-radius: 15px; /* Radius sedikit diperkecil */
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 8px 20px rgba(255, 159, 28, 0.2);
        margin-bottom: 20px; /* Jarak bawah dikurangi */
        color: white;
        position: relative;
        overflow: hidden;
        flex-wrap: wrap; /* Agar responsif di HP */
        gap: 15px;
    }
    
    /* Hiasan background tetap sama */
    .header-card::before {
        content: ''; position: absolute; top: -50px; right: -50px;
        width: 150px; height: 150px; background: rgba(255,255,255,0.1);
        border-radius: 50%; pointer-events: none;
    }
    .header-card::after {
        content: ''; position: absolute; bottom: -30px; left: 20px;
        width: 80px; height: 80px; background: rgba(255,255,255,0.1);
        border-radius: 50%; pointer-events: none;
    }

    .header-title h1 { 
        font-size: 1.6rem; /* Font size diperkecil */
        font-weight: 800;
        margin: 0; letter-spacing: 0.5px; 
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .header-title p { 
        margin: 3px 0 0;
        opacity: 0.95; font-size: 0.85rem; /* Font size diperkecil */
    }

    /* Tombol Tambah Compact */
    .btn-add-new {
        background: white;
        color: #FF9F1C; border: none;
        padding: 8px 20px; /* Padding tombol diperkecil */
        border-radius: 50px; cursor: pointer;
        font-weight: 700; font-size: 0.85rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease; display: flex; align-items: center; gap: 6px;
        white-space: nowrap; /* Teks tidak turun baris */
    }
    .btn-add-new:hover { 
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15); 
        color: #e68a00;
    }

    /* --- COMPACT TABLE CARD STYLE --- */
    .table-card {
        background: white;
        padding: 0; /* Padding dihapus agar tabel full width di dalam card */
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        border: 1px solid #fff;
        overflow: hidden; /* Penting untuk border radius */
    }

    /* WRAPPER RESPONSIVE TABEL (PENTING AGAR TIDAK MELEBAR) */
    .table-responsive {
        width: 100%;
        overflow-x: auto; /* Scroll samping otomatis */
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        width: 100%; 
        border-collapse: collapse; /* Ubah ke collapse agar lebih rapi */
        min-width: 800px; /* Lebar minimum agar tabel tidak hancur di HP */
    }
    
    .data-table th { 
        background-color: #fcfcfc; 
        padding: 12px 15px; /* Padding diperkecil */
        text-align: left; 
        color: #888; font-weight: 700; font-size: 0.8rem; /* Font size header kecil */
        text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 1px solid #eee;
        white-space: nowrap; /* Header satu baris */
    }
    
    .data-table tbody tr {
        background-color: #fff;
        transition: background-color 0.2s;
        border-bottom: 1px solid #f5f5f5;
    }
    .data-table tbody tr:hover {
        background-color: #fdfdfd;
    }
    .data-table tbody tr:last-child {
        border-bottom: none;
    }

    /* Cell Styling Compact */
    .data-table td { 
        padding: 10px 15px; /* Padding cell diperkecil */
        vertical-align: middle; 
        font-size: 0.85rem; /* Font isi tabel diperkecil */
        color: #444;
    }

    /* Other Components Compacted */
    .file-list { list-style: none; padding: 0; margin: 0; }
    .file-list li { 
        font-size: 0.75rem; color: #666;
        margin-bottom: 3px; 
        background: #fdfdfd; padding: 2px 8px; border-radius: 4px; border: 1px solid #eee;
        display: inline-block;
    }

    .badge { 
        padding: 5px 10px; /* Badge lebih kecil */
        border-radius: 20px; 
        font-size: 0.7rem; 
        font-weight: bold; 
        text-transform: uppercase; 
        display: inline-block;
        letter-spacing: 0.5px; 
        white-space: nowrap;
    }
    /* Warna Status Tetap */
    .st-menunggu { background: #fff8e1; color: #ffc107; border: 1px solid #ffe69c; }
    .st-aktif { background: #e3f9e5; color: #28a745; border: 1px solid #c3e6cb; }
    .st-batal { background: #ffebee; color: #dc3545; border: 1px solid #f5c6cb; }
    .st-verif { background: #e3f2fd; color: #0d6efd; border: 1px solid #b6d4fe; }
    .st-dp-ditolak { background: #f8d7da; color: #842029; border: 1px solid #f5c6cb; }

    .btn-action { 
        padding: 6px 10px; border-radius: 6px;
        font-size: 0.8rem; border: none; cursor: pointer; 
        margin-right: 3px; color: white; transition: 0.2s; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-action:hover { transform: translateY(-2px); }
    .btn-edit { background-color: #ffc107; color: #333; }
    .btn-cancel { background-color: #ff6b6b; }
    .btn-upload { background-color: #4cd137; }

    /* MEDIA QUERY UNTUK RESPONSIVE (HP) */
    @media (max-width: 768px) {
        .header-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
        }
        .btn-add-new {
            width: 100%;
            justify-content: center;
        }
        .header-title h1 { font-size: 1.4rem; }
        
        /* Tabel tetap bisa di-scroll horizontal tanpa merusak layout */
        .table-responsive {
            border: 1px solid #eee; /* Batas visual di HP */
            border-radius: 8px;
        }
    }

    /* =========================================
       MODAL STYLES (TETAP SESUAI REQUEST)
       ========================================= */
    .modal-overlay {
        display: none;
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.6); z-index: 2000; align-items: center; justify-content: center;
        backdrop-filter: blur(3px);
    }
    
    .modal-content {
        background: white;
        width: 650px; border-radius: 12px; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.25);
        height: 90vh; display: flex; flex-direction: column; 
        overflow: hidden;
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp { from {transform:translateY(20px); opacity:0;} to {transform:translateY(0); opacity:1;} }

    #formBooking { display: flex; flex-direction: column; height: 100%; }

    .modal-header { padding: 15px 25px; background: #f8f9fa; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
    .modal-header h3 { margin: 0; color: #333; font-size: 1.25rem; }

    .modal-body { padding: 25px; overflow-y: auto; flex: 1; background-color: #fff; }

    .modal-footer { padding: 15px 25px; border-top: 1px solid #eee; background: #fff; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; box-shadow: 0 -4px 10px rgba(0,0,0,0.05); z-index: 10; }

    .step-section { display: none; }
    .step-section.active { display: block; animation: fadeIn 0.4s; }
    @keyframes fadeIn { from {opacity:0;} to {opacity:1;} }

    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #555; font-size: 0.9rem; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box; }
    .form-control:focus { border-color: var(--primary-orange); outline: none; }
    .form-control[readonly] { background-color: #e9ecef; cursor: not-allowed; font-weight: bold; color: #495057; }

    .age-group { display: flex; gap: 10px; }
    .age-group input { width: 60%; }
    .age-group select { width: 40%; }

    .cat-row { background: #fdfdfd; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 20px; position: relative; border-left: 4px solid var(--primary-orange); }
    .cat-header { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px dashed #ddd; padding-bottom: 5px; color: var(--primary-orange); font-weight: bold; font-size: 0.9rem; }
    
    .btn-remove { color: #dc3545; background: none; border: none; cursor: pointer; font-size: 0.85rem; text-decoration: underline; }
    .btn-add-cat { width: 100%; padding: 12px; background: #fff3cd; color: #856404; border: 1px dashed #ffeeba; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.2s; margin-bottom: 20px; }
    
    .btn-primary { background-color: var(--primary-orange); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; }

    .price-display small { display: block; font-size: 0.8rem; color: #777; margin-bottom: 2px; }
    .price-display .price-val { font-size: 1.4rem; font-weight: 800; color: var(--primary-orange); }

    .bank-info-box { background: linear-gradient(135deg, #0056b3, #004494); color: white; padding: 20px; border-radius: 10px; text-align: center; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,86,179,0.3); }
    .bank-code { font-size: 1.5rem; font-weight: 800; letter-spacing: 2px; margin: 10px 0; display: block; font-family: monospace; background: rgba(255,255,255,0.1); padding: 5px; border-radius: 5px;}
    
    .payment-options { display: flex; gap: 15px; margin-bottom: 20px; }
    .radio-card { flex: 1; border: 2px solid #eee; border-radius: 8px; padding: 15px; cursor: pointer; transition: 0.2s; position: relative; }
    .radio-card input { position: absolute; opacity: 0; }
    .radio-card:hover { border-color: var(--primary-orange); background: #fffbf0; }
    .radio-card.selected { border-color: var(--primary-orange); background: #fffbf0; box-shadow: 0 0 0 1px var(--primary-orange); }
    .radio-card strong { display: block; font-size: 1rem; color: #333; margin-bottom: 5px; }
    .radio-card span { font-size: 0.9rem; color: #666; }

    .total-display-compact { text-align: center; padding: 10px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px dashed #ccc; font-size: 0.9rem; }
    .total-display-compact strong { font-size: 1.1rem; color: var(--primary-orange); margin-left: 5px; }

    .reject-alert { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.85rem; display: none; animation: fadeIn 0.5s; }
    .reject-alert strong { display: block; margin-bottom: 2px; }

    .btn-batal-bayar { background: #dc3545; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; width: 48%; }
    .btn-kirim-bayar { background: #28a745; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; width: 48%; font-weight: bold;}
    .btn-group-bayar { display: flex; justify-content: space-between; margin-top: 10px; }

    .close-modal { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #aaa; transition: 0.2s; }
    .close-modal:hover { color: #dc3545; }
</style>

<div class="reservasi-content">
    
    <div class="header-card">
        <div class="header-title">
            <h1>Booking Saya</h1>
            <p>Kelola penitipan kucing kesayanganmu</p>
        </div>
        <button class="btn-add-new" onclick="openModal('tambah')">
            <span>+</span> Booking Baru
        </button>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="min-width: 110px;">Mitra</th> 
                        <th style="min-width: 120px;">Foto Kucing</th> 
                        <th style="min-width: 90px;">Jadwal</th>
                        <th style="min-width: 100px;">Paket</th>
                        <th style="min-width: 80px;">Biaya</th>
                        <th style="min-width: 100px;">Status</th>
                        <th style="min-width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $pendingPaymentId = null; 
                        $pendingPaymentTotal = 0;
                        $pendingPaymentStatus = '';
                    ?>
                    <?php if (empty($bookings)): ?>
                        <tr><td colspan="8" style="text-align:center; padding:40px; color:#999; font-style:italic;">Belum ada riwayat booking.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($bookings as $row): ?>
                            <?php 
                                if (in_array($row['status'], ['Menunggu Pembayaran', 'Menunggu DP', 'DP Ditolak']) && $pendingPaymentId === null) {
                                    $pendingPaymentId = $row['id_booking'];
                                    $pendingPaymentTotal = $row['total_harga'];
                                    $pendingPaymentStatus = $row['status'];
                                }
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong style="color:var(--text-dark);"><?= htmlspecialchars($row['nama_mitra']) ?></strong><br>
                                    <small style="color:#888; font-size:0.75rem; display:block; max-width:200px; white-space:normal; line-height:1.2; margin-top:3px;"><?= htmlspecialchars($row['alamat_mitra']) ?></small>
                                </td>
                                <td>
                                    <ul class="file-list">
                                        <?php 
                                            $fotos = !empty($row['foto_kucing_list']) ? explode(',', $row['foto_kucing_list']) : [];
                                            if(!empty($fotos)) {
                                                foreach($fotos as $f) {
                                                    $fname = !empty($f) ? $f : '-';
                                                    // Potong nama file jika terlalu panjang
                                                    $dispName = (strlen($fname) > 12) ? substr($fname, 0, 9).'...' : $fname;
                                                    echo "<li title='".htmlspecialchars($fname)."'>📄 " . htmlspecialchars($dispName) . "</li>";
                                                }
                                            } else {
                                                echo "<li>-</li>";
                                            }
                                        ?>
                                    </ul>
                                </td>
                                <td>
                                    <div style="font-size:0.8rem; line-height:1.4;">
                                        <?= date('d/m/y', strtotime($row['tgl_mulai'])) ?> <br>
                                        <span style="color:#aaa; font-size:0.7rem;">s/d</span> <br>
                                        <?= date('d/m/y', strtotime($row['tgl_selesai'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight:500; font-size:0.85rem;"><?= htmlspecialchars($row['paket']) ?></span>
                                </td>
                                <td>
                                    <strong style="color:#28a745;">Rp <?= number_format($row['total_harga'],0,',','.') ?></strong>
                                </td>
                                <td>
                                    <?php 
                                        $s = $row['status'];
                                        $cls = (strpos($s,'Menunggu')!==false)?'st-menunggu':((strpos($s,'Verifikasi')!==false)?'st-verif':((strpos($s,'Aktif')!==false)?'st-aktif':((strpos($s,'Ditolak')!==false)?'st-dp-ditolak':'st-batal')));
                                    ?>
                                    <span class="badge <?= $cls ?>"><?= htmlspecialchars($s) ?></span>
                                </td>
                                <td>
                                    <?php if (in_array($s, ['Menunggu Konfirmasi', 'Menunggu DP', 'DP Ditolak'])): ?>
                                        <button class="btn-action btn-edit" title="Edit" onclick="editBooking('<?= $row['id_booking'] ?>')">✏️</button>
                                        <button class="btn-action btn-cancel" title="Batal" onclick="confirmCancel(event, '<?= BASEURL ?>/BookingCustomer/batalkan/<?= $row['id_booking'] ?>')">❌</button>
                                    <?php endif; ?>

                                    <?php if (in_array($s, ['Menunggu Pembayaran', 'Menunggu DP', 'DP Ditolak'])): ?>
                                        <button class="btn-action btn-upload" title="Upload Bukti" onclick="openPaymentModal('<?= $row['id_booking'] ?>', '<?= $row['total_harga'] ?>', '<?= $s ?>')">📂</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <form action="<?= BASEURL ?>/BookingCustomer/simpan" method="POST" id="formBooking" enctype="multipart/form-data">
            
            <input type="hidden" name="mode" id="formMode" value="tambah">
            <input type="hidden" name="id_booking" id="editIdBooking">
            <input type="hidden" name="total_harga" id="input_total_harga">

            <div class="modal-header">
                <h3 id="modalTitle">Booking Baru</h3>
                <button type="button" class="close-modal" onclick="closeAddModal()">&times;</button>
            </div>

            <div class="modal-body">
                <div id="step1" class="step-section active">
                    <h4 style="margin-top:0; color:var(--primary-orange); border-bottom:1px solid #eee; padding-bottom:10px;">Langkah 1: Layanan</h4>
                    
                    <div class="form-group">
                        <label>Mitra Petshop</label>
                        <div id="div_pilih_mitra">
                            <select name="id_mitra" id="id_mitra" class="form-control" onchange="loadPackages(this.value)">
                                <option value="">-- Pilih --</option>
                                <?php foreach ($mitras as $m): ?>
                                    <option value="<?= $m['id_mitra'] ?>"><?= $m['nama_petshop'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="div_baca_mitra" style="display:none;">
                            <input type="text" id="read_nama_mitra" class="form-control" readonly>
                            <input type="hidden" name="id_mitra_edit" id="id_mitra_edit">
                        </div>
                    </div>

                    <input type="hidden" name="paket_nama" id="paket_nama_input">

                    <div class="form-group">
                        <label>Pilih Paket</label>
                        <select id="paket_select" class="form-control" required onchange="updatePriceCalculation()"></select>
                    </div>

                    <div class="form-group" style="display:flex; gap:10px;">
                        <div style="flex:1;">
                            <label>Check-In (Readonly)</label>
                            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" readonly>
                        </div>
                        <div style="flex:1;">
                            <label>Check-Out</label>
                            <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" required onchange="updatePriceCalculation()">
                        </div>
                    </div>
                </div>

                <div id="step2" class="step-section">
                    <h4 style="margin-top:0; color:var(--primary-orange); border-bottom:1px solid #eee; padding-bottom:10px;">Langkah 2: Data Kucing</h4>
                    <div id="catContainer"></div>
                    <button type="button" class="btn-add-cat" onclick="addCatRow()">+ Tambah Kucing Lain</button>
                </div>
            </div>

            <div class="modal-footer">
                <div class="price-display">
                    <small>Total Estimasi:</small>
                    <span class="price-val" id="view_total_harga">Rp 0</span>
                </div>
                <div>
                    <button type="button" id="btnBack" class="btn-primary" style="background:#6c757d; display:none;" onclick="goToStep(1)">Kembali</button>
                    <button type="button" id="btnNext" class="btn-primary" onclick="goToStep(2)">Lanjut</button>
                    <button type="submit" id="btnSubmit" class="btn-primary" style="background:#28a745; display:none;">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="paymentModal" class="modal-overlay">
    <div class="modal-content" style="width:500px;">
        <form action="<?= BASEURL ?>/BookingCustomer/upload_bukti" method="POST" enctype="multipart/form-data" id="formPayment">
            
            <div class="modal-header">
                <h3>Selesaikan Pembayaran</h3>
                <button type="button" onclick="redirectToDashboard()" class="close-modal" title="Tutup">&times;</button>
            </div>
            
            <div class="modal-body">
                <input type="hidden" name="id_booking" id="pay_id_booking">

                <div id="reject_alert" class="reject-alert">
                    <strong>⚠️ Pembayaran Ditolak!</strong> Bukti sebelumnya tidak valid. Silakan upload ulang.
                </div>

                <div class="bank-info-box">
                    <small style="opacity:0.8;">Transfer ke BCA a.n Pawtopia</small>
                    <span class="bank-code">8210-2293-1928</span>
                    <small>Kode Unik: <?= rand(100,999) ?></small>
                </div>

                <label style="font-weight:bold; margin-bottom:8px; display:block; font-size:0.9rem;">Pilih Tipe:</label>
                <div class="payment-options">
                    <label class="radio-card selected" onclick="selectPayment('DP')">
                        <input type="radio" name="jenis_pembayaran" value="DP" checked>
                        <strong>Bayar DP</strong>
                        <span>Rp 15.000</span>
                    </label>
                    <label class="radio-card" onclick="selectPayment('Pelunasan')">
                        <input type="radio" name="jenis_pembayaran" value="Pelunasan">
                        <strong>Lunas</strong>
                        <span>Full Amount</span>
                    </label>
                </div>

                <div class="total-display-compact">
                    Bayar Sebesar: <strong id="display_nominal">Rp 15.000</strong>
                    <input type="hidden" name="nominal_bayar" id="input_nominal" value="15000">
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label>Upload Bukti Transfer</label>
                    <input type="file" name="bukti_bayar" class="form-control" accept="image/*" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-primary" style="background:#dc3545; width:45%;" onclick="redirectToDashboard()">Batal</button>
                <button type="submit" class="btn-primary" style="background:#28a745; width:45%;">Kirim</button>
            </div>
        </form>
    </div>
</div>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '<?= $_SESSION['flash']['tipe'] == 'error' ? 'error' : 'success'; ?>',
            title: '<?= $_SESSION['flash']['pesan']; ?>',
            text: '<?= $_SESSION['flash']['aksi'] ?? ''; ?>',
            timer: 3000, showConfirmButton: false
        });
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectMitra = document.getElementById('id_mitra');
    const infoText = document.getElementById('info_kapasitas');

    selectMitra.addEventListener('change', function() {
        const idMitra = this.value;

        if (idMitra) {
            // Tampilkan loading text
            infoText.innerText = "Mengecek ketersediaan...";
            infoText.className = "text-info";

            // Kirim request ke Controller cek_kapasitas
            fetch('<?= BASEURL; ?>/BookingCustomer/cek_kapasitas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_mitra: idMitra })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'penuh') {
                    // 1. Tampilkan Alert / Pop Up
                    alert("Maaf, Kapasitas untuk " + data.nama + " sudah PENUH (0 slot). Silakan pilih mitra lain.");
                    
                    // 2. Reset Pilihan Dropdown agar user tidak bisa lanjut
                    selectMitra.value = "";
                    
                    // 3. Update text info
                    infoText.innerText = "Kapasitas Penuh!";
                    infoText.className = "text-danger font-weight-bold";
                    
                } else if (data.status === 'tersedia') {
                    // Jika tersedia
                    infoText.innerText = "Kapasitas Tersedia: " + data.kapasitas + " slot.";
                    infoText.className = "text-success";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                infoText.innerText = "";
            });
        } else {
            infoText.innerText = "";
        }
    });
});
</script>
<script>
    let globalTotalBooking = 0; 

    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($pendingPaymentId !== null): ?>
            openPaymentModal('<?= $pendingPaymentId ?>', '<?= $pendingPaymentTotal ?>', '<?= $pendingPaymentStatus ?>');
        <?php endif; ?>
    });

    function openPaymentModal(id, totalAsli, status = '') {
        document.getElementById('pay_id_booking').value = id;
        globalTotalBooking = parseInt(totalAsli); 
        
        if(status === 'DP Ditolak') document.getElementById('reject_alert').style.display = 'block';
        else document.getElementById('reject_alert').style.display = 'none';

        selectPayment('DP'); 
        document.getElementById('paymentModal').style.display = 'flex';
    }

    function selectPayment(type) {
        document.querySelectorAll('.radio-card').forEach(el => el.classList.remove('selected'));
        const radio = document.querySelector(`input[value="${type}"]`);
        if(radio) { radio.checked = true; radio.parentElement.classList.add('selected'); }

        let nominal = (type === 'DP') ? 15000 : globalTotalBooking;
        document.getElementById('display_nominal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(nominal);
        document.getElementById('input_nominal').value = nominal;
    }

    function redirectToDashboard() { window.location.href = '<?= BASEURL ?>/DashboardCustomer'; }

    let currentStep = 1;
    let catCount = 0;

    function confirmCancel(event, url) {
        event.preventDefault();
        Swal.fire({
            title: 'Batalkan?', text: "Pesanan tidak bisa dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya'
        }).then((result) => { if (result.isConfirmed) window.location.href = url; })
    }

    function openModal(mode) {
        document.getElementById('addModal').style.display = 'flex';
        document.getElementById('formMode').value = mode;

        const date = new Date();
        const today = date.getFullYear() + '-' + 
                    String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                    String(date.getDate()).padStart(2, '0');
        
        // Set minimal tanggal agar user tidak bisa pilih tanggal kemarin
        document.getElementById('tgl_mulai').min = today;
        document.getElementById('tgl_selesai').min = today;
        
        if(mode == 'tambah'){
            document.getElementById('modalTitle').innerText = "Booking Baru";
            document.getElementById('formBooking').reset();
            
            // Tgl Mulai tetap hari ini
            document.getElementById('tgl_mulai').value = today; 
            
            // --- PERUBAHAN DI SINI ---
            // Kosongkan value agar tampilannya default (dd/mm/yyyy)
            document.getElementById('tgl_selesai').value = ""; 
            // -------------------------

            document.getElementById('div_pilih_mitra').style.display = 'block';
            document.getElementById('div_baca_mitra').style.display = 'none';
            document.getElementById('paket_select').innerHTML = '<option value="" data-harga="0">-- Pilih Mitra --</option>';
            document.getElementById('catContainer').innerHTML = '';
            document.getElementById('view_total_harga').innerText = "Rp 0";
            document.getElementById('input_total_harga').value = "0";
            catCount = 0; addCatRow(); goToStep(1);
        } else {
            document.getElementById('modalTitle').innerText = "Edit Booking";
            document.getElementById('div_pilih_mitra').style.display = 'none';
            document.getElementById('div_baca_mitra').style.display = 'block';
        }
    }

    function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }

    async function editBooking(id) {
        openModal('edit');
        document.getElementById('editIdBooking').value = id;
        document.getElementById('read_nama_mitra').value = "Loading...";

        const response = await fetch('<?= BASEURL ?>/BookingCustomer/get_detail_booking', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id_booking: id })
        });
        const json = await response.json();
        const b = json.booking;
        const cats = json.cats;

        document.getElementById('read_nama_mitra').value = b.nama_petshop;
        document.getElementById('id_mitra_edit').value = b.id_mitra;
        await loadPackages(b.id_mitra);

        const pkgSel = document.getElementById('paket_select');
        let found = false;
        for(let i=0; i<pkgSel.options.length; i++) {
            if(pkgSel.options[i].value == b.paket) { pkgSel.selectedIndex = i; found = true; break; }
        }
        if(!found) {
            const opt = new Option(b.paket + " (Lama)", b.paket, true, true);
            opt.setAttribute('data-harga', 0); pkgSel.add(opt);
        }

        document.getElementById('tgl_mulai').value = b.tgl_mulai;
        document.getElementById('tgl_selesai').value = b.tgl_selesai;
        validateDates();
        
        document.getElementById('catContainer').innerHTML = '';
        catCount = 0;
        if(cats.length > 0) cats.forEach(c => addCatRow(c));
        else addCatRow();

        updatePriceCalculation();
    }

    function addCatRow(data = null) {
        const i = catCount;
        const container = document.getElementById('catContainer');
        let ua = '', us = 'Tahun';
        if(data && data.umur) {
            const parts = data.umur.split(' ');
            ua = parts[0]; if(parts[1]) us = parts[1];
        }

        const html = `
            <div class="cat-row" id="row_${i}">
                <div class="cat-header">
                    <span>Data Kucing #${i+1}</span>
                    ${i>0 ? `<button type="button" onclick="removeRow(${i})" class="btn-remove">Hapus</button>` : ''}
                </div>
                <input type="hidden" name="cats[${i}][id_kucing]" value="${data ? data.id_kucing : ''}">
                <div class="form-group"><label>Nama</label><input type="text" name="cats[${i}][nama]" class="form-control" value="${data?data.nama_kucing:''}" required></div>
                <div style="display:flex; gap:10px;">
                    <div style="flex:1;"><label>Ras</label><input type="text" name="cats[${i}][ras]" class="form-control" value="${data?data.ras:''}"></div>
                    <div style="flex:1;"><label>Gender</label><select name="cats[${i}][gender]" class="form-control"><option value="Jantan">Jantan</option><option value="Betina">Betina</option></select></div>
                </div>
                <div class="form-group"><label>Umur</label><div class="age-group"><input type="number" name="cats[${i}][umur_angka]" class="form-control" value="${ua}" required><select name="cats[${i}][umur_satuan]" class="form-control"><option value="Tahun" ${us=='Tahun'?'selected':''}>Tahun</option><option value="Bulan" ${us=='Bulan'?'selected':''}>Bulan</option></select></div></div>
                <div class="form-group"><label>Foto</label><input type="file" name="cats[${i}][foto]" class="form-control" accept="image/*"></div>
                <div class="form-group"><label>Catatan</label><input type="text" name="cats[${i}][keterangan]" class="form-control" value="${data?data.keterangan:''}"></div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        catCount++;
        updatePriceCalculation();
    }

    function removeRow(i) { document.getElementById('row_'+i).remove(); updatePriceCalculation(); }

    function validateDates() {
        const t1 = document.getElementById('tgl_mulai');
        const t2 = document.getElementById('tgl_selesai');
        t2.min = t1.value;
        if(t2.value < t1.value) t2.value = t1.value;
        updatePriceCalculation();
    }

    async function loadPackages(id) {
        const sel = document.getElementById('paket_select');
        sel.innerHTML = '<option>Loading...</option>';
        try {
            const res = await fetch('<?= BASEURL ?>/BookingCustomer/get_paket_mitra', {method:'POST', body:JSON.stringify({id_mitra:id})});
            if(!res.ok) throw new Error();
            const pkgs = await res.json();
            sel.innerHTML = '<option value="" data-harga="0">-- Pilih --</option>';
            if(Array.isArray(pkgs)) {
                pkgs.forEach(p => {
                    const rp = new Intl.NumberFormat('id-ID').format(p.harga);
                    sel.innerHTML += `<option value="${p.nama_paket}" data-harga="${p.harga}">${p.nama_paket} - Rp ${rp}</option>`;
                });
            } else { sel.innerHTML = '<option value="" data-harga="0">Tidak ada paket</option>'; }
        } catch(e){ sel.innerHTML = '<option>Gagal memuat</option>'; }
        updatePriceCalculation();
    }

    function updatePriceCalculation() {
        const t1 = document.getElementById('tgl_mulai').value;
        const t2 = document.getElementById('tgl_selesai').value;
        const sel = document.getElementById('paket_select');
        const price = parseInt(sel.options[sel.selectedIndex]?.getAttribute('data-harga')) || 0;
        document.getElementById('paket_nama_input').value = sel.value;
        let days = 0;
        if(t1 && t2) {
            days = Math.ceil((new Date(t2)-new Date(t1))/(86400000));
            if(days < 1) days = 1; 
        }
        
        const cats = document.querySelectorAll('.cat-row').length;
        const total = days * price * cats;
        document.getElementById('view_total_harga').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
        document.getElementById('input_total_harga').value = total;
    }

    function goToStep(step) {
        if(step == 2) {
            const m = document.getElementById('formMode').value == 'tambah' ? document.getElementById('id_mitra').value : document.getElementById('id_mitra_edit').value;
            const p = document.getElementById('paket_select').value;
            if(!m || !p) { alert('Lengkapi data layanan!'); return; }
        }
        document.querySelectorAll('.step-section').forEach(el => el.classList.remove('active'));
        document.getElementById('step'+step).classList.add('active');
        document.getElementById('btnBack').style.display = step==2?'block':'none';
        document.getElementById('btnNext').style.display = step==1?'block':'none';
        document.getElementById('btnSubmit').style.display = step==2?'block':'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('paymentModal')) redirectToDashboard();
        if (event.target == document.getElementById('addModal')) closeAddModal();
    }
</script>
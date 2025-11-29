<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Konfirmasi Pemesanan - PawTopia</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #eef4fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            /* max-width: 800px; */ /* DIHAPUS untuk memperlebar container */
            margin: 30px auto;
        }

        .card {
            background: #fff;
            padding: 40px; /* Ditingkatkan agar lebih lega */
            border-radius: 15px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        .title {
            text-align: center;
            font-size: 26px;
            margin-bottom: 10px;
            font-weight: 700;
            color: #333;
        }

        p {
            line-height: 1.6;
            color: #555;
            margin-bottom: 25px;
        }

        .status-box {
            margin-top: 15px;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
            background: #fdfdfd;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
            padding: 12px;
            border-radius: 8px;
            background: #f7f7f7;
            font-size: 15px;
            border-left: 4px solid #ffa726; /* Penanda status */
        }
        
        .status-item span { /* Pastikan span yang berisi teks status bisa menyusut */
            flex-grow: 1; /* Memberi ruang agar teks bisa memenuhi sisa ruang */
            flex-shrink: 1;
        }

        .status-item button { /* Gaya untuk tombol kecil di status-item */
            background: #ffa726;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            margin-left: 10px;
            font-size: 13px;
            cursor: pointer;
            font-weight: 500;
            flex-shrink: 0; /* PENTING: Mencegah tombol menyusut dan memotong teks */
            white-space: nowrap; /* Mencegah teks tombol patah baris */
        }

        .btn-row {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        .btn {
            flex-grow: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            color: #fff;
            font-weight: 600;
        }

        .btn-cancel { background: #f26b6b; }
        .btn-confirm { background: #ffa726; }

        /* POPUP */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup {
            width: 90%;
            max-width: 480px; /* Batasi lebar */
            background: #fff;
            padding: 30px; /* NAIKKAN padding di popup agar tidak mepet ke tepi */
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        .popup h3 {
            text-align: center;
            margin-bottom: 25px; /* Jarak yang cukup setelah judul */
            font-size: 22px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 20px; /* NAIKKAN jarak antar grup form */
        }

        .form-group label {
            font-weight: 600;
            font-size: 14px;
            display: block; /* Agar label mengambil satu baris penuh */
            margin-bottom: 5px; /* Jarak antara label dan input */
        }

        .form-group input {
            width: 100%;
            padding: 12px; /* Padding input ditingkatkan */
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 0; /* Pastikan margin-top 0 karena jarak sudah diatur oleh label margin-bottom */
            box-sizing: border-box; /* PENTING: Agar padding tidak merusak lebar 100% */
            background: #fefefe;
        }

        .btn-group-popup { /* Gaya baru untuk menampung tombol Kirim dan Tutup */
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-submit {
            flex-grow: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .btn-submit-confirm {
            background: #ffa726; 
        }

        .btn-submit-cancel {
            background: #90a4ae; 
        }

    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="title">Konfirmasi Pemesanan 🐾</div>
        <p style="text-align:center;">Kamu sudah siap untuk menitipkan kucing kesayanganmu di PawTopia.<br> Kamu akan menerima **invoice dan email konfirmasi** dari pihak kami.</p>

        <div class="status-box">
            <div class="status-item">
                <span style="color:#5cb85c; font-weight:600;">✔️ Menunggu Konfirmasi – Pesananmu sedang diproses oleh tim Pawtopia.
            </div>
            <div class="status-item">
                <span style="color:#f0ad4e; font-weight:600;">🟢 Dikonfirmasi – Pesanan disetujui. Silakan lakukan pembayaran DP. 
                </span><button onclick="openPopup()">Bayar dari sini</button>
            </div>
            <div class="status-item">
                <span style="color:#337ab7; font-weight:600;">⏳ Menunggu Konfirmasi DP – Kami sedang memverifikasi pembayaran DP kamu.
            </div>
            <div class="status-item">
                <span style="color:#5cb85c; font-weight:600;">✅ DP Terkonfirmasi – Pembayaran DP kamu telah diterima. Pesananmu resmi terdaftar!
            </div>
            <div class="status-item">
                <span style="color:#d9534f; font-weight:600;">❌ DP Ditolak – Silakan cek kembali dan kirim bukti pembayaran.
            </div>
        </div>

        <div class="btn-row">
            <button class="btn btn-cancel">Batal Booking</button>
            <button class="btn btn-confirm" onclick="openPopup()">Konfirmasi Pembayaran DP</button>
        </div>
    </div>
</div>

<div class="overlay" id="dpPopup">
    <div class="popup">
        <h3>Form Pembayaran DP</h3>
        <form method="POST" action="proses_dp.php" enctype="multipart/form-data"> <div class="form-group">
                <label>Nama Lengkap Pemesanan <span style="color:red;">*</span></label>
                <input type="text" name="nama_pemesan" required/> 
            </div>

            <div class="form-group">
                <label>Nomor Pemesanan <span style="color:red;">*</span></label>
                <input type="text" name="nomor_pesanan" required/>
            </div>

            <div class="form-group">
                <label>Jumlah DP yang Dibayarkan <span style="color:red;">*</span></label>
                <input type="number" name="jumlah_dp" required/>
            </div>

            <div class="form-group">
                <label>Metode Pembayaran (Contoh: Transfer BCA) <span style="color:red;">*</span></label>
                <input type="text" name="metode_bayar" required/>
            </div>

            <div class="form-group">
                <label>Tanggal Pembayaran <span style="color:red;">*</span></label>
                <input type="date" name="tgl_bayar" required/>
            </div>

            <div class="form-group">
                <label>Unggah Bukti Pembayaran (Max 2MB) <span style="color:red;">*</span></label>
                <input type="file" name="bukti_bayar" accept="image/*" required/>
            </div>
            
            <div class="btn-group-popup">
                <button type="button" class="btn-submit btn-submit-cancel" onclick="closePopup()">Tutup</button>
                <button type="submit" class="btn-submit btn-submit-confirm">Kirim Bukti Pembayaran</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPopup() {
    document.getElementById("dpPopup").style.display = "flex";
}
function closePopup() {
    document.getElementById("dpPopup").style.display = "none";
}

window.onclick = function(e) {
    const popup = document.getElementById("dpPopup");
    if (e.target === popup) {
        popup.style.display = "none";
    }
};
</script>

</body>
</html>
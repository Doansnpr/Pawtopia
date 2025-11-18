<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Account | PawTopia</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@700&family=Poppins&display=swap');

    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: url('<?= BASEURL ?>/images/logo.png') no-repeat center center fixed;
      background-size: 850px auto;
      background-color: #d4f2ff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .register-container {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      padding: 40px;
      width: 400px;
      text-align: center;
      transition: 0.3s ease;
      overflow-y: auto;
      max-height: 95vh;
    }

    h2 {
      font-family: 'Comic Neue', cursive;
      color: #d68c00;
      font-size: 26px;
      margin-bottom: 25px;
    }

    .input-wrapper {
      position: relative;
      margin-bottom: 15px;
      width: 100%;
    }

    input, select, textarea {
      width: 100%;
      padding: 10px 50px 10px 15px;
      border: 2px solid orange;
      border-radius: 15px;
      outline: none;
      font-size: 15px;
      box-sizing: border-box;
      background-color: white;
    }

    select {
      appearance: none;
      background-image: url("data:image/svg+xml;utf8,<svg fill='orange' height='18' viewBox='0 0 24 24' width='18' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
      background-repeat: no-repeat;
      background-position: right 18px center;
      background-size: 18px;
      cursor: pointer;
    }

    select:hover {
      border-color: #ffb300;
      box-shadow: 0 0 5px rgba(255,179,0,0.3);
    }

    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      font-size: 18px;
      color: #d68c00;
      padding: 2px;
    }

    button[type="submit"] {
      background: orange;
      color: white;
      border: none;
      border-radius: 20px;
      padding: 12px;
      width: 100%;
      font-weight: bold;
      cursor: pointer;
      font-size: 15px;
      margin-top: 10px;
      transition: 0.2s;
    }

    button[type="submit"]:hover {
      background: #e69500;
    }

    .login-link {
      display: block;
      margin-top: 15px;
      color: orange;
      text-decoration: underline;
      font-weight: 500;
      font-size: 14px;
    }

    /* 🔹 Form tambahan Mitra */
    .mitra-extra {
      display: none;
      margin-top: 20px;
      padding-top: 15px;
      border-top: 2px dashed orange;
      text-align: left;
    }

    .mitra-extra label {
      font-weight: 600;
      color: #d68c00;
      display: block;
      margin-bottom: 5px;
    }

    .paket-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    /* Atur ulang lebar input di dalam .paket-row */
.paket-row input[type="checkbox"] {
  width: auto; /* Mengembalikan ukuran checkbox ke normal */
  flex-shrink: 0; /* Mencegah checkbox-nya 'gepeng' */
}

.paket-row input[type="number"] {
  width: auto; /* Membatalkan width: 100% */
  flex-grow: 1;  /* Membuat input harga mengisi sisa ruang */
}
  

    /* 🔹 Modal Peta */
    #map-modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    #map-container {
      background: white;
      width: 90%;
      max-width: 700px;
      height: 500px;
      border-radius: 15px;
      position: relative;
      overflow: hidden;
      padding: 10px;
      box-sizing: border-box;
    }

    #map {
      width: 100%;
      height: calc(100% - 60px);
      border-radius: 10px;
    }

    #close-map {
      position: absolute;
      top: 10px; right: 10px;
      background: red;
      color: white;
      border: none;
      border-radius: 50%;
      width: 30px; height: 30px;
      cursor: pointer;
      font-weight: bold;
    }

    /* tombol bawah modal */
    .map-actions {
      display: flex;
      gap: 10px;
      margin-top: 8px;
      justify-content: flex-end;
      align-items: center;
    }
    .map-actions button {
      padding: 8px 12px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
    }
    .btn-use { background:#2b9cff; color:white; }
    .btn-save { background:#28a745; color:white; }

    @media (max-width: 900px) {
      .register-container {
        width: 80%;
        padding: 30px;
      }
    }
    @media (max-width: 600px) {
      .register-container {
        width: 90%;
        padding: 25px;
      }
      h2 { font-size: 22px; }
      input, select, button[type="submit"] {
        font-size: 14px;
        padding: 10px 40px 10px 12px;
      }
    }
  </style>
</head>
<body>
  <div class="register-container">
    <h2>Create Account</h2>
    <form method="POST" action="<?= BASEURL; ?>/auth/register" enctype="multipart/form-data">
      <div class="input-wrapper">
        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
      </div>
      <div class="input-wrapper">
        <input type="text" name="no_hp" placeholder="Nomor Telepon" required>
      </div>
      <div class="input-wrapper">
        <input type="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-wrapper">
        <input id="reg-password" type="password" name="password" placeholder="Password" required>
        <button type="button" class="toggle-password" data-target="reg-password">👁️‍🗨️</button>
      </div>
      <div class="input-wrapper">
        <select name="role" id="role" required>
          <option value="">-- Create as --</option>
          <option value="customer">Customer</option>
          <option value="mitra">Mitra</option>
        </select>
      </div>

      <!-- 🔹 Form tambahan Mitra -->
      <div class="mitra-extra" id="mitra-extra">
        <h3 style="color:#d68c00;text-align:center;">Data Pet Shop</h3>

        <label>Nama Pet Shop</label>
        <input type="text" name="nama_petshop" placeholder="Nama Petshop">

        <label>Alamat Pet Shop</label>
        <input type="text" name="alamat_petshop" placeholder="Alamat lengkap">
        <button type="button" id="btn-lokasi" style="margin-top:5px;background:#ffa31a;color:white;border:none;padding:8px;border-radius:8px;cursor:pointer;">📍 Tentukan Titik Lokasi</button>

        <!-- Tambahan hidden input untuk menyimpan koordinat -->
        <input type="hidden" id="lokasi_lat" name="lokasi_lat">
        <input type="hidden" id="lokasi_lng" name="lokasi_lng">

        <label style="margin-top:10px;">Nomor HP Pet Shop</label>
        <input type="text" name="no_hp_petshop" placeholder="Nomor HP Pet Shop">

        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="3" placeholder="Ceritakan tentang Pet Shop Anda"></textarea>

        <label>Kapasitas</label>
        <input type="number" name="kapasitas" placeholder="Jumlah Hewan yang Bisa Ditampung">

        <label>Harga per Hari</label>
        <div class="paket-row">
  <input type="checkbox" name="paket[]" value="paket1" id="cb-paket1">
  <label for="cb-paket1" style="white-space: nowrap;">Paket 1</label>
  <input type="number" name="harga_paket1" placeholder="Harga Rp">
</div>

<div class="paket-row">
  <input type="checkbox" name="paket[]" value="paket2" id="cb-paket2">
  <label for="cb-paket2" style="white-space: nowrap;">Paket 2</label>
  <input type="number" name="harga_paket2" placeholder="Harga Rp">
</div>

<div class="paket-row">
  <input type="checkbox" name="paket[]" value="paket3" id="cb-paket3">
  <label for="cb-paket3" style="white-space: nowrap;">Paket 3</label>
  <input type="number" name="harga_paket3" placeholder="Harga Rp">
</div>

        <label>Foto Pet Shop</label>
        <input type="file" name="foto_petshop" accept="image/*">
      </div>

      <button type="submit">CREATE</button>
    </form>

    <a href="<?= BASEURL; ?>/auth/login" class="login-link">Sudah punya akun?</a>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  </div>

  <!-- 🔹 Modal untuk Map -->
  <div id="map-modal">
    <div id="map-container">
      <button id="close-map">X</button>
      <div id="map"></div>
      <div class="map-actions">
        <button id="use-my-loc" class="btn-use" type="button">Gunakan Lokasi Saya</button>
        <button id="save-loc" class="btn-save" type="button">Simpan Lokasi</button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    // toggle password
    document.querySelectorAll('.toggle-password').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = document.getElementById(btn.getAttribute('data-target'));
        input.type = (input.type === 'password') ? 'text' : 'password';
      });
    });

    // tampilkan form mitra
    const roleSelect = document.getElementById('role');
    const mitraExtra = document.getElementById('mitra-extra');
    roleSelect.addEventListener('change', () => {
      mitraExtra.style.display = (roleSelect.value === 'mitra') ? 'block' : 'none';
    });

    // 🔹 Modal Map (pakai Leaflet) - minimal perubahan terhadap HTML-mu
    const mapModal = document.getElementById("map-modal");
    const closeMap = document.getElementById("close-map");
    const btnLokasi = document.getElementById("btn-lokasi");
    const useMyLoc = document.getElementById("use-my-loc");
    const saveLoc = document.getElementById("save-loc");
    let map, marker, tempLatLng;

    // safety: pastikan elemen ada
    if (btnLokasi) {
      btnLokasi.addEventListener('click', () => {
        mapModal.style.display = "flex";
        // init with a tiny delay so modal CSS applied
        setTimeout(initLeaflet, 200);
      });
    }

    if (closeMap) {
      closeMap.addEventListener('click', () => {
        mapModal.style.display = "none";
      });
    }

    // gunakan lokasi sekarang (geolocation)
    if (useMyLoc) {
      useMyLoc.addEventListener('click', () => {
        if (!map) return;
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Anda sekarang").openPopup();
            map.setView([lat, lng], 15);
            tempLatLng = { lat, lng };
            Swal.fire({ icon: 'success', title: 'Lokasi ditemukan', text: `${lat.toFixed(5)}, ${lng.toFixed(5)}`, timer: 1200, showConfirmButton: false });
          }, err => {
            Swal.fire({ icon: 'error', title: 'Gagal dapatkan lokasi', text: 'Pastikan izin lokasi sudah diizinkan.' });
          }, { timeout: 8000 });
        } else {
          Swal.fire({ icon: 'info', title: 'Tidak tersedia', text: 'Browser tidak mendukung geolocation.' });
        }
      });
    }

    // simpan lokasi ke input hidden dan tutup modal
    if (saveLoc) {
      saveLoc.addEventListener('click', () => {
        if (!tempLatLng) {
          Swal.fire({ icon: 'warning', title: 'Belum memilih lokasi', text: 'Silakan klik peta atau gunakan lokasi saya dulu.' });
          return;
        }
        document.getElementById("lokasi_lat").value = tempLatLng.lat;
        document.getElementById("lokasi_lng").value = tempLatLng.lng;
        Swal.fire({ icon: 'success', title: 'Lokasi disimpan', text: `${tempLatLng.lat.toFixed(5)}, ${tempLatLng.lng.toFixed(5)}`, timer: 1000, showConfirmButton: false });
        setTimeout(() => mapModal.style.display = "none", 1100);
      });
    }

    function initLeaflet() {
      if (map) {
        map.invalidateSize();
        return;
      }
      // default view (misal Jember) kalau geolocation gagal
      map = L.map('map').setView([-8.1722, 113.7007], 13);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }).addTo(map);

      // jika browser izinkan, fokus ke lokasi user saat modal dibuka
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;
          map.setView([lat, lng], 15);
          // biarkan user memilih sendiri, tapi kita set marker awal kecil
          if (marker) map.removeLayer(marker);
          marker = L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Anda (ketuk peta untuk ganti)").openPopup();
          tempLatLng = { lat, lng };
        }, () => {
          // kalau ditolak/eror, tetap di default (Jember)
          console.log('geolocation tidak diizinkan atau gagal, fokus ke default');
        }, { timeout: 8000 });
      }

      // klik peta -> pilih titik (mengupdate tempLatLng)
      map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        if (marker) {
          map.removeLayer(marker);
        }

        marker = L.marker([lat, lng]).addTo(map)
          .bindPopup(`Koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}`).openPopup();

        tempLatLng = { lat, lng };

        // beri notifikasi agar user tahu menyimpan harus klik "Simpan Lokasi"
        Swal.fire({
          icon: "info",
          title: "Lokasi dipilih",
          text: `Klik "Simpan Lokasi" untuk menyimpan titik ini.`,
          timer: 1400,
          showConfirmButton: false
        });
      });
    }

    <?php if (isset($_SESSION['flash'])) : ?>
    Swal.fire({
      icon: '<?= $_SESSION['flash']['tipe']; ?>',
      title: '<?= $_SESSION['flash']['pesan']; ?>',
      text: '<?= $_SESSION['flash']['aksi']; ?>',
      confirmButtonColor: '#f39c12'
    });
    <?php unset($_SESSION['flash']); endif; ?>
  </script>
</body>
</html>
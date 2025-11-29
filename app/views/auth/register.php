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
      border-radius: 25px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
      padding: 25px;
      width: 350px;
      text-align: center;
      overflow-y: auto;
      max-height: 95vh;
    }

    h2 {
      font-family: 'Comic Neue', cursive;
      color: #d68c00;
      font-size: 22px;
      margin-bottom: 15px;
    }

    .input-wrapper {
      margin-bottom: 12px;
      width: 100%;
      text-align: left;
    }

    .input-wrapper input[type="password"] {
      padding-right: 40px;
    }

    input, select, textarea {
      width: 100%;
      padding: 8px 12px;
      border: 2px solid orange;
      border-radius: 12px;
      outline: none;
      font-size: 14px;
      background-color: white;
      box-sizing: border-box;
    }

    label {
      font-size: 14px;
      font-weight: 600;
      color: #d68c00;
      margin-bottom: 5px;
      display: block;
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      top: 42px;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      font-size: 18px;
      color: #d68c00;
    }

    .mitra-extra {
      display: none;
      margin-top: 20px;
      padding-top: 15px;
      border-top: 2px dashed orange;
      text-align: left;
    }

    .paket-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 12px;
    }

    .paket-row input[type="text"],
    .paket-row input[type="number"] {
      width: 45%;
      padding: 8px 12px;
    }

    .paket-row button {
      background: red;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 12px;
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
      opacity: 0.5;
      pointer-events: none;
      transition: 0.2s;
    }

    #fee-check {
      margin-top: 10px;
    }

    .login-link {
      display: block;
      margin-top: 15px;
      color: orange;
      text-decoration: underline;
      font-weight: 500;
    }

    /* MAP MODAL */
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
      padding: 10px;
      box-sizing: border-box;
      position: relative;
    }

    #map {
      width: 100%;
      height: calc(100% - 60px);
      border-radius: 10px;
    }

    #close-map {
      position: absolute;
      right: 15px;
      top: 10px;
      background: red;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 5px;
      z-index: 999;
    }

    .map-actions {
      margin-top: 10px;
      display: flex;
      justify-content: space-between;
    }

    .btn-use,
    .btn-save {
      background: orange;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 10px;
      cursor: pointer;
    }
  </style>
</head>

<body>

<div class="register-container">
  <h2>Create Account</h2>

  <form method="POST" action="<?= BASEURL; ?>/auth/register" enctype="multipart/form-data">

    <div class="input-wrapper">
      <label>Nama Lengkap</label>
      <input type="text" name="nama_lengkap" required>
    </div>

    <div class="input-wrapper">
      <label>Nomor Telepon</label>
      <input type="text" name="no_hp" required>
    </div>

    <div class="input-wrapper">
      <label>Email</label>
      <input type="email" name="email" required>
    </div>

    <div class="input-wrapper" style="position:relative;">
      <label>Password</label>
      <input id="reg-password" type="password" name="password" required>
      <button type="button" class="toggle-password" data-target="reg-password">👁️‍🗨️</button>
    </div>

    <div class="input-wrapper">
      <label>Daftar Sebagai</label>
      <select name="role" id="role" required>
        <option value="">-- Pilih --</option>
        <option value="customer">Customer</option>
        <option value="mitra">Mitra</option>
      </select>
    </div>

    <div class="mitra-extra" id="mitra-extra">
      <h3 style="text-align:center;color:#d68c00;">Data Pet Shop</h3>

      <label>Nama Pet Shop</label>
      <input type="text" name="nama_petshop">

      <label>Alamat Pet Shop</label>
      <input type="text" name="alamat_petshop">

      <label>Tentukan Titik Lokasi</label>
      <button type="button" id="btn-lokasi"
              style="margin-top:5px;background:#ffa31a;color:white;border:none;padding:8px;border-radius:8px;cursor:pointer;">
        📍 Pilih Titik Lokasi
      </button>

      <input type="hidden" id="lokasi_lat" name="lokasi_lat">
      <input type="hidden" id="lokasi_lng" name="lokasi_lng">

      <label>Nomor HP Pet Shop</label>
      <input type="text" name="no_hp_petshop">

      <label>Deskripsi</label>
      <textarea name="deskripsi" rows="3"></textarea>

      <label>Kapasitas</label>
      <input type="number" name="kapasitas">

      <label>Paket Harga</label>
      <div id="paket-container">
        <div class="paket-row">
          <input type="text" name="nama_paket1" placeholder="Nama Paket">
          <input type="number" name="harga_paket1" placeholder="Harga">
          <button type="button" class="remove-paket">X</button>
        </div>
      </div>

      <button type="button" id="add-paket"
              style="margin-top:5px;background:green;color:white;padding:8px;border-radius:8px;cursor:pointer;">
        + Tambah Paket
      </button>

      <label>Foto Pet Shop</label>
      <input type="file" name="foto_petshop" accept="image/*">

      <label>Foto KTP</label>
      <input type="file" name="foto_ktp" accept="image/*">

      <label style="display:flex;align-items:center;gap:8px;margin-top:15px;">
        <input type="checkbox" name="agree_fee" id="fee-check" value="1"> Saya setuju biaya pendaftaran Rp 50.000
      </label>

    </div>

    <button type="submit" id="submit-btn">CREATE</button>
  </form>

  <a href="<?= BASEURL; ?>/auth/login" class="login-link">Sudah punya akun?</a>
</div>


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

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

// Toggle password
document.querySelectorAll('.toggle-password').forEach(btn => {
  btn.addEventListener('click', () => {
    const inp = document.getElementById(btn.dataset.target);
    inp.type = inp.type === "password" ? "text" : "password";
  });
});

const role = document.getElementById("role");
const mitraExtra = document.getElementById("mitra-extra");
const feeCheck = document.getElementById("fee-check");
const submitBtn = document.getElementById("submit-btn");

// Default tombol disable sampai role dipilih
submitBtn.style.opacity = "0.5";
submitBtn.style.pointerEvents = "none";

// Fungsi Cek Status Tombol Submit
function checkSubmitStatus() {
  if (role.value === "mitra") {
    mitraExtra.style.display = "block";
    // Kalau mitra, harus centang fee
    if (feeCheck.checked) {
        submitBtn.style.opacity = "1";
        submitBtn.style.pointerEvents = "auto";
    } else {
        submitBtn.style.opacity = "0.5";
        submitBtn.style.pointerEvents = "none";
    }
  } else if (role.value === "customer") {
    mitraExtra.style.display = "none";
    submitBtn.style.opacity = "1";
    submitBtn.style.pointerEvents = "auto";
  } else {
    // Belum pilih role
    mitraExtra.style.display = "none";
    submitBtn.style.opacity = "0.5";
    submitBtn.style.pointerEvents = "none";
  }
}

role.addEventListener("change", checkSubmitStatus);
feeCheck.addEventListener("change", checkSubmitStatus);


// ========================
// MAP
// ========================
const mapModal = document.getElementById("map-modal");
const btnLokasi = document.getElementById("btn-lokasi");
const closeMap = document.getElementById("close-map");
const useMyLoc = document.getElementById("use-my-loc");
const saveLoc = document.getElementById("save-loc");

let map, marker, tempLat, tempLng;

// buka modal map
btnLokasi.addEventListener("click", () => {
  mapModal.style.display = "flex";
  setTimeout(initMap, 200);
});

// tutup map
closeMap.addEventListener("click", () => mapModal.style.display = "none");

// load map
function initMap() {
  if (map) {
    map.invalidateSize();
    return;
  }

  map = L.map('map').setView([-8.1722, 113.7007], 14);

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

  map.on("click", e => {
    tempLat = e.latlng.lat;
    tempLng = e.latlng.lng;

    if (marker) map.removeLayer(marker);
    marker = L.marker([tempLat, tempLng]).addTo(map);
  });
}

// Lokasi saya (akurat)
useMyLoc.addEventListener("click", () => {
  navigator.geolocation.getCurrentPosition(pos => {

    tempLat = pos.coords.latitude;
    tempLng = pos.coords.longitude;

    if (marker) map.removeLayer(marker);
    marker = L.marker([tempLat, tempLng]).addTo(map);

    map.setView([tempLat, tempLng], 18);

  }, console.error, {
    enableHighAccuracy: true,
    maximumAge: 0,
    timeout: 15000
  });
});

// simpan lokasi
saveLoc.addEventListener("click", () => {
  document.getElementById("lokasi_lat").value = tempLat;
  document.getElementById("lokasi_lng").value = tempLng;
  mapModal.style.display = "none";
});

// tambah paket
document.getElementById("add-paket").addEventListener("click", () => {
  const container = document.getElementById("paket-container");
  const id = container.children.length + 1;

  const row = document.createElement("div");
  row.classList.add("paket-row");

  row.innerHTML = `
    <input type="text" name="nama_paket${id}" placeholder="Nama Paket">
    <input type="number" name="harga_paket${id}" placeholder="Harga">
    <button type="button" class="remove-paket">X</button>
  `;

  container.appendChild(row);
});

// hapus row paket
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("remove-paket")) {
    e.target.parentNode.remove();
  }
});
</script>

</body>
</html>
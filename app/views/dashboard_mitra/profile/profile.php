<style>
    /* CSS STYLE (TIDAK BERUBAH) */
    .profile-page { width: 100%; background-color: #f5f5f5; margin: -30px; padding-bottom: 40px; font-family: 'Poppins', sans-serif; }
    
    /* Header Banner: Pakai path absolut manual agar tidak error */
    .header-banner { width: 100%; height: 280px; background: linear-gradient(135deg, #bdb107ff 0%, #be4f0bff 100%); position: relative; overflow: hidden; }
    .header-banner::before { content: ''; position: absolute; width: 100%; height: 100%; background: url("/pawtopia/public/images/petshop_header.jpg") center/cover no-repeat; opacity: 0.25; filter: blur(3px); }
    
    .profile-info { position: relative; margin-top: -140px; z-index: 50; display: flex; justify-content: center; width: 100%; padding: 0 20px; }
    .profile-picture-wrapper { position: relative; width: 160px; height: 160px; }
    
    /* Style Foto Profil: Pastikan object-fit cover agar rapi */
    .profile-picture { width: 160px; height: 160px; border-radius: 50%; border: 6px solid white; object-fit: cover; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); background: #e2e8f0; display: block; }
    
    .profile-content-wrapper { max-width: 900px; margin: 30px auto 0; padding: 0 20px 40px; }
    .profile-card { background: white; border-radius: 16px; padding: 30px; margin-bottom: 25px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); }
    .profile-card h3 { margin-bottom: 20px; font-size: 22px; font-weight: 600; color: #2d3748; border-bottom: 2px solid #f7fafc; padding-bottom: 10px; }
    .profile-detail { margin-bottom: 15px; display: flex; align-items: flex-start; padding: 8px 0; }
    .profile-detail span { font-weight: 600; display: inline-block; min-width: 160px; flex-shrink: 0; color: #4a5568; }
    .edit-btn { background: linear-gradient(135deg, #ff7f50 0%, #ff6347 100%); color: white; border: none; padding: 12px 24px; border-radius: 10px; cursor: pointer; font-size: 15px; font-weight: 600; margin-top: 15px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(255, 127, 80, 0.3); }
    .edit-btn:hover { background: linear-gradient(135deg, #ff6347 0%, #ff4500 100%); transform: translateY(-2px); }

    .modal-bg { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 9999; overflow-y: auto; padding: 20px; }
    .modal-box { background: white; width: 100%; max-width: 650px; border-radius: 16px; padding: 30px; margin: 20px auto; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); }
    .form-group { margin-bottom: 18px; }
    .form-group label { font-weight: 600; display: block; margin-bottom: 8px; font-size: 14px; color: #4a5568; }
    .form-group input, .form-group textarea { width: 100%; padding: 12px 14px; border-radius: 10px; border: 2px solid #e2e8f0; font-size: 14px; box-sizing: border-box; }
    .btn-group { margin-top: 25px; display: flex; gap: 10px; }
    .save-btn { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; padding: 12px 28px; border-radius: 10px; cursor: pointer; font-weight: 600; }
    .cancel-btn { background: #6c757d; color: white; border: none; padding: 12px 28px; border-radius: 10px; cursor: pointer; font-weight: 600; }
    .btn-lokasi { width: 100%; padding: 12px; background: #f6ad55; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; text-align: left; display: flex; align-items: center; gap: 10px; }
    .btn-lokasi:hover { background: #ed8936; }
    
    .paket-container { border: 2px dashed #e2e8f0; padding: 15px; border-radius: 10px; background: #f8fafc; }
    .paket-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
    .paket-row input { flex: 1; }
    .btn-remove { background: #fc8181; color: white; border: none; border-radius: 8px; width: 40px; height: 40px; cursor: pointer; font-weight: bold; }
    .btn-add-paket { background: #68d391; color: white; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; margin-top: 5px; }
    #map-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); justify-content: center; align-items: center; z-index: 10000; }
    #map-container-popup { background: white; width: 90%; max-width: 700px; height: 500px; border-radius: 15px; padding: 10px; position: relative; }
    #map { width: 100%; height: calc(100% - 60px); border-radius: 10px; margin-top: 10px; }
    .map-actions { margin-top: 10px; display: flex; justify-content: space-between; }
    .btn-use, .btn-save-loc { background: #ed8936; color: white; border: none; padding: 8px 12px; border-radius: 10px; cursor: pointer; }
    #close-map { position: absolute; right: 15px; top: 10px; background: red; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; z-index: 999; }
</style>

<div class="profile-page">
    <div class="header-banner"></div>
    <div class="profile-info">
        <div class="profile-picture-wrapper">
            <?php 
            // === LOGIKA JALUR PASTI ===
            // Kita gunakan alamat absolut web server: /pawtopia/public/...
            // Ini akan selalu benar asalkan folder projectmu bernama 'pawtopia'
            
            // 1. Ambil nama file dari database
            $fotoName = $data['mitra']['foto_profil'] ?? ''; 
            
            // 2. Tentukan Path URL Gambar Mitra & Default
            // CATATAN: Pastikan folder di htdocs kamu namanya 'pawtopia'
            $pathMitra   = '/pawtopia/public/uploads/mitra/';
            $pathDefault = '/pawtopia/public/images/default_petshop.jpg';

            // 3. Cek apakah ada nama file dari database
            if (!empty($fotoName)) {
                $fotoUrl = $pathMitra . htmlspecialchars($fotoName);
            } else {
                $fotoUrl = $pathDefault;
            }

            // 4. Tambahkan '?v=time()' untuk memaksa browser refresh gambar baru
            $finalUrl = $fotoUrl . '?v=' . time(); 
            ?>
            
            <img 
                class="profile-picture" 
                src="<?= $finalUrl ?>" 
                alt="Foto Profil" 
                onerror="this.onerror=null; this.src='<?= $pathDefault ?>';"
            >
        </div>
    </div>

    <div class="profile-content-wrapper">
        <div class="profile-card">
             <?php if(isset($_SESSION['success'])): ?>
                <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <h3>📋 Informasi Profil</h3>

            <div class="profile-detail">
                <span>🏪 Nama Petshop:</span>
                <div><?= htmlspecialchars($data['mitra']['nama_petshop'] ?? '-') ?></div>
            </div>
            <div class="profile-detail">
                <span>📱 No. HP:</span>
                <div><?= htmlspecialchars($data['mitra']['no_hp'] ?? '-') ?></div>
            </div>
            <div class="profile-detail">
                <span>📍 Alamat:</span>
                <div><?= htmlspecialchars($data['mitra']['alamat'] ?? '-') ?></div>
            </div>
            <div class="profile-detail">
                <span>📝 Deskripsi:</span>
                <div><?= !empty($data['mitra']['deskripsi']) ? htmlspecialchars($data['mitra']['deskripsi']) : '-' ?></div>
            </div>
            <div class="profile-detail">
                <span>🐾 Kapasitas:</span>
                <div><?= intval($data['mitra']['kapasitas'] ?? 0) ?> hewan</div>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

            <h3>📦 Paket Penitipan</h3>
            <?php if (!empty($data['paket'])): ?>
                <?php foreach ($data['paket'] as $p): ?>
                <div class="profile-detail">
                    <span>🐾 <?= htmlspecialchars($p['nama_paket']) ?>:</span>
                    <div><strong>Rp <?= number_format($p['harga'], 0, ',', '.') ?></strong></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada paket.</p>
            <?php endif; ?>

            <button class="edit-btn" type="button">Edit Profil</button>
        </div>
    </div>
</div>

<div id="editModal" class="modal-bg">
    <div class="modal-box">
        <h2>✏️ Edit Profil</h2>

        <form action="<?= BASEURL ?>/DashboardMitra/updateProfile" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="id_mitra" value="<?= $data['mitra']['id_mitra'] ?>">

            <div class="form-group">
                <label>🏪 Nama Petshop</label>
                <input type="text" name="nama_petshop" value="<?= htmlspecialchars($data['mitra']['nama_petshop'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>📱 No HP/Telepon</label>
                <input type="text" name="no_hp" value="<?= htmlspecialchars($data['mitra']['no_hp'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>📍 Alamat</label>
                <textarea name="alamat" required><?= htmlspecialchars($data['mitra']['alamat'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>🗺️ Titik Lokasi</label>
                <button type="button" class="btn-lokasi" id="btn-open-map">
                    📍 Lihat / Ubah Lokasi di Peta
                </button>
                <input type="hidden" id="lokasi_lat" name="lokasi_lat" value="<?= htmlspecialchars($data['mitra']['lokasi_lat'] ?? '') ?>">
                <input type="hidden" id="lokasi_lng" name="lokasi_lng" value="<?= htmlspecialchars($data['mitra']['lokasi_lng'] ?? '') ?>">
                <small id="text-koordinat" style="display:block; margin-top:5px; color:#718096; font-weight:bold;">
                    <?= !empty($data['mitra']['lokasi_lat']) ? "Lokasi Tersimpan: " . htmlspecialchars($data['mitra']['lokasi_lat']) . ", " . htmlspecialchars($data['mitra']['lokasi_lng']) : "Belum ada lokasi." ?>
                </small>
            </div>

            <div class="form-group">
                <label>📝 Deskripsi Petshop</label>
                <textarea name="deskripsi"><?= htmlspecialchars($data['mitra']['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>🐾 Kapasitas</label>
                <input type="number" name="kapasitas" value="<?= htmlspecialchars($data['mitra']['kapasitas'] ?? 0) ?>" min="0">
            </div>

            <div class="form-group">
                <label>💰 Paket Harga</label>
                <div class="paket-container" id="paket-wrapper">
                    <?php if (!empty($data['paket'])): ?>
                        <?php foreach ($data['paket'] as $pkt): ?>
                        <div class="paket-row">
                            <input type="text" name="nama_paket[]" value="<?= htmlspecialchars($pkt['nama_paket']) ?>" placeholder="Nama Paket">
                            <input type="number" name="harga_paket[]" value="<?= $pkt['harga'] ?>" placeholder="Harga">
                            <button type="button" class="btn-remove">X</button>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="paket-row">
                            <input type="text" name="nama_paket[]" placeholder="Nama Paket">
                            <input type="number" name="harga_paket[]" placeholder="Harga">
                            <button type="button" class="btn-remove">X</button>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" id="add-paket" class="btn-add-paket">+ Tambah Paket</button>
            </div>

            <div class="form-group">
                <label>📷 Ganti Foto Profil</label>
                <input type="file" id="file-input-foto" name="foto_petshop" accept="image/*">
                <img id="modal-preview-img" src="" style="display:none; width: 100px; height: 100px; border-radius: 50%; margin-top: 10px; object-fit: cover; border: 2px solid #ddd;">
            </div>

            <div class="btn-group">
                <button class="save-btn" type="submit">💾 Simpan Perubahan</button>
                <button class="cancel-btn" type="button">❌ Batal</button>
            </div>

        </form>
    </div>
</div>

<div id="map-modal">
    <div id="map-container-popup">
        <button id="close-map">X</button>
        <h4 style="margin:0; text-align:center;">Pilih Lokasi Petshop</h4>
        <div id="map"></div>

        <div class="map-actions">
            <button id="use-my-loc" class="btn-use" type="button">Lokasi Saya (GPS)</button>
            <button id="save-loc" class="btn-save-loc" type="button">✅ Simpan Lokasi Ini</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- 1. MODAL EDIT ---
        const editModal = document.getElementById("editModal");
        const btnEdit = document.querySelector(".edit-btn");
        const btnCancel = document.querySelector(".cancel-btn");

        function openEditModal() {
            if (editModal) {
                editModal.style.display = "flex";
                document.body.style.overflow = "hidden";
            }
        }

        function closeEditModal() {
            if (editModal) {
                editModal.style.display = "none";
                document.body.style.overflow = "auto";
            }
        }
        if (btnEdit) btnEdit.addEventListener("click", openEditModal);
        if (btnCancel) btnCancel.addEventListener("click", closeEditModal);
        if (editModal) editModal.addEventListener("click", (e) => {
            if (e.target === editModal) closeEditModal();
        });

        // --- 2. PAKET DYNAMIC (DIPERBAIKI) ---
        const containerPaket = document.getElementById("paket-wrapper");
        const btnAddPaket = document.getElementById("add-paket");

        if (btnAddPaket && containerPaket) {
            btnAddPaket.addEventListener("click", () => {
                const row = document.createElement("div");
                row.classList.add("paket-row");
                
                // PERBAIKAN: Menggunakan backtick (`) untuk string HTML
                row.innerHTML = `
                    <input type="text" name="nama_paket[]" placeholder="Nama Paket" required>
                    <input type="number" name="harga_paket[]" placeholder="Harga" required>
                    <button type="button" class="btn-remove">X</button>
                `;
                
                containerPaket.appendChild(row);
            });
            containerPaket.addEventListener("click", (e) => {
                if (e.target.classList.contains("btn-remove")) e.target.parentElement.remove();
            });
        }

        // --- 3. PREVIEW FOTO ---
        const fileInput = document.getElementById("file-input-foto");
        const previewImg = document.getElementById("modal-preview-img");

        if (fileInput && previewImg) {
            fileInput.addEventListener("change", function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = "block";
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.style.display = "none";
                }
            });
        }

        // --- 4. MAP LEAFLET (DIPERBAIKI) ---
        const mapModal = document.getElementById("map-modal");
        const btnOpenMap = document.getElementById("btn-open-map");
        const btnCloseMap = document.getElementById("close-map");
        const btnUseMyLoc = document.getElementById("use-my-loc");
        const btnSaveLoc = document.getElementById("save-loc");
        const inputLat = document.getElementById("lokasi_lat");
        const inputLng = document.getElementById("lokasi_lng");
        const textKoordinat = document.getElementById("text-koordinat");
        let map, marker, tempLat, tempLng;

        if (btnOpenMap) {
            btnOpenMap.addEventListener("click", () => {
                mapModal.style.display = "flex";
                setTimeout(initMap, 200);
            });
        }
        if (btnCloseMap) btnCloseMap.addEventListener("click", () => mapModal.style.display = "none");

        function initMap() {
            let curLat = parseFloat(inputLat.value);
            let curLng = parseFloat(inputLng.value);

            // PENTING: Gunakan data dari input hidden jika ada
            let startLat = (curLat) ? curLat : -8.1724;
            let startLng = (curLng) ? curLng : 113.6995;

            if (!map) {
                map = L.map('map').setView([startLat, startLng], 15);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);
                map.on("click", (e) => updateMarker(e.latlng.lat, e.latlng.lng));
            } else {
                map.invalidateSize();
                map.setView([startLat, startLng], 15);
            }
            updateMarker(startLat, startLng);
        }

        function updateMarker(lat, lng) {
            tempLat = lat;
            tempLng = lng;
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map);
        }

        if (btnUseMyLoc) {
            btnUseMyLoc.addEventListener("click", () => {
                if (navigator.geolocation) {
                    btnUseMyLoc.innerText = "Mencari...";
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            updateMarker(pos.coords.latitude, pos.coords.longitude);
                            map.setView([pos.coords.latitude, pos.coords.longitude], 18);
                            btnUseMyLoc.innerText = "Lokasi Saya (GPS)";
                        },
                        (err) => {
                            alert("Gagal deteksi lokasi.");
                            btnUseMyLoc.innerText = "Lokasi Saya (GPS)";
                        }, {
                            enableHighAccuracy: true
                        }
                    );
                } else {
                    alert("Browser tidak support GPS.");
                }
            });
        }

        if (btnSaveLoc) {
            btnSaveLoc.addEventListener("click", () => {
                if (tempLat && tempLng) {
                    inputLat.value = tempLat;
                    inputLng.value = tempLng;

                    // PERBAIKAN: Menggunakan backtick (`) karena ada variabel ${...}
                    textKoordinat.innerHTML = `Lokasi terpilih: <span style="color:green; font-weight:bold;">${tempLat.toFixed(6)}, ${tempLng.toFixed(6)}</span>`;
                    
                    mapModal.style.display = "none";
                } else {
                    alert("Silakan pilih titik lokasi di peta terlebih dahulu.");
                }
            });
        }
    });
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    /* --- TEMA GLOBAL (MATCHING WITH DASHBOARD) --- */
    :root {
        --primary-orange: #FF9F43;
        --primary-orange-dark: #e67e22;
        --primary-orange-light: #FFF2E3;
        --text-dark: #2D3436;
        --text-grey: #636E72;
        --bg-color: #F8F9FD;
        --white: #FFFFFF;
        --danger: #ff7675;
        --success: #00b894;
        --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.05);
        --radius-card: 20px;
        --radius-btn: 12px;
    }

    .profile-page {
        width: 100%;
        background-color: var(--bg-color);
        font-family: 'Poppins', sans-serif;
        padding-bottom: 60px;
    }

    /* --- HEADER BANNER --- */
    .header-banner {
        width: 100%;
        height: 250px;
        background: linear-gradient(135deg, var(--primary-orange), #ff7f50);
        position: relative;
        border-radius: 0 0 30px 30px;
        overflow: hidden;
    }
    .header-banner::before {
        content: ''; position: absolute; width: 100%; height: 100%;
        background: url("/pawtopia/public/images/petshop_header.jpg") center/cover no-repeat;
        opacity: 0.15; filter: blur(2px);
    }

    /* --- PROFILE PHOTO --- */
    .profile-info {
        position: relative;
        margin-top: -100px;
        display: flex;
        justify-content: center;
        width: 100%;
        padding: 0 20px;
        z-index: 10;
    }
    .profile-picture-wrapper {
        position: relative;
        width: 180px; height: 180px;
    }
    .profile-picture {
        width: 100%; height: 100%;
        border-radius: 50%;
        border: 6px solid var(--white);
        object-fit: cover;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        background: var(--white);
    }

    /* --- CONTENT CARD --- */
    .profile-content-wrapper {
        max-width: 900px;
        margin: 20px auto 0;
        padding: 0 20px;
    }
    .profile-card {
        background: var(--white);
        border-radius: var(--radius-card);
        padding: 40px;
        box-shadow: var(--shadow-soft);
        position: relative;
    }

    /* TYPOGRAPHY */
    .profile-header-text { text-align: center; margin-bottom: 30px; }
    .petshop-name { font-size: 1.8rem; font-weight: 700; color: var(--text-dark); margin-bottom: 5px; }
    .petshop-owner { font-size: 1rem; color: var(--text-grey); font-weight: 500; }

    /* GRID INFO */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    .info-item {
        display: flex; gap: 15px;
        padding: 15px;
        background: #fdfdfd;
        border: 1px solid #f0f0f0;
        border-radius: 15px;
        transition: 0.3s;
    }
    .info-item:hover { border-color: var(--primary-orange-light); background: var(--primary-orange-light); }
    
    .info-icon {
        width: 45px; height: 45px;
        background: var(--primary-orange-light);
        color: var(--primary-orange);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .info-text label { display: block; font-size: 0.8rem; color: var(--text-grey); font-weight: 600; margin-bottom: 2px; }
    .info-text p { margin: 0; font-size: 1rem; font-weight: 600; color: var(--text-dark); }

    /* PACKAGES BADGES */
    .section-title { font-size: 1.2rem; font-weight: 700; color: var(--text-dark); margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
    .section-title::before { content: ''; display: block; width: 5px; height: 20px; background: var(--primary-orange); border-radius: 5px; }

    .package-grid { display: flex; flex-wrap: wrap; gap: 15px; }
    .package-badge {
        background: var(--white);
        border: 1px solid #eee;
        padding: 10px 20px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: space-between;
        gap: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .pkt-name { font-weight: 600; color: var(--text-dark); }
    .pkt-price { color: var(--primary-orange); font-weight: 700; }

    /* ACTION BUTTON */
    .edit-btn {
        background: linear-gradient(135deg, var(--primary-orange), var(--primary-orange-dark));
        color: white; border: none;
        padding: 15px 40px; border-radius: 50px;
        font-size: 1rem; font-weight: 600;
        cursor: pointer; transition: 0.3s;
        box-shadow: 0 5px 20px rgba(255, 159, 67, 0.4);
        display: block; margin: 40px auto 0;
        width: fit-content;
    }
    .edit-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(255, 159, 67, 0.5); }

    /* --- MODAL EDIT --- */
    .modal-bg {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(45, 52, 54, 0.6); backdrop-filter: blur(5px);
        justify-content: center; align-items: center; z-index: 9999;
        padding: 20px;
    }
    .modal-box {
        background: white; width: 100%; max-width: 700px;
        border-radius: var(--radius-card);
        padding: 40px; max-height: 90vh; overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease-out;
    }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    .form-group { margin-bottom: 20px; }
    .form-group label { font-weight: 600; display: block; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-dark); }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%; padding: 12px 15px;
        border-radius: var(--radius-btn);
        border: 2px solid #eee;
        font-size: 0.95rem; font-family: 'Poppins', sans-serif;
        transition: 0.3s;
    }
    .form-group input:focus, .form-group textarea:focus {
        border-color: var(--primary-orange); outline: none;
        box-shadow: 0 0 0 4px var(--primary-orange-light);
    }

    /* BUTTON GROUP IN MODAL */
    .btn-group { margin-top: 30px; display: flex; gap: 15px; justify-content: flex-end; }
    .btn-action { padding: 12px 30px; border-radius: var(--radius-btn); font-weight: 600; cursor: pointer; border: none; transition: 0.3s; }
    .btn-save { background: var(--success); color: white; }
    .btn-save:hover { background: #00a383; }
    .btn-cancel { background: #f1f2f6; color: var(--text-grey); }
    .btn-cancel:hover { background: #e2e6ea; }

    /* MAP BUTTON */
    .btn-lokasi {
        width: 100%; padding: 12px;
        background: var(--primary-orange-light);
        color: var(--primary-orange-dark);
        border: 2px dashed var(--primary-orange);
        border-radius: var(--radius-btn);
        cursor: pointer; font-weight: 600;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: 0.3s;
    }
    .btn-lokasi:hover { background: var(--primary-orange); color: white; border-style: solid; }

    /* PAKET DYNAMIC */
    .paket-container { background: #f9f9f9; padding: 20px; border-radius: 15px; border: 1px solid #eee; }
    .paket-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
    .btn-remove { background: #ffecec; color: var(--danger); border: none; border-radius: 8px; width: 45px; height: 45px; cursor: pointer; transition: 0.3s; }
    .btn-remove:hover { background: var(--danger); color: white; }
    .btn-add-paket { background: var(--text-dark); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.85rem; margin-top: 5px; }

    /* MAP POPUP */
    #map-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); justify-content: center; align-items: center; z-index: 10000; }
    #map-container-popup { background: white; width: 90%; max-width: 800px; height: 600px; border-radius: 20px; padding: 20px; position: relative; }
    #map { width: 100%; height: calc(100% - 60px); border-radius: 15px; margin-top: 15px; box-shadow: inset 0 0 10px rgba(0,0,0,0.1); }
    #close-map { position: absolute; right: 20px; top: 20px; background: white; border: 2px solid #eee; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-weight: bold; color: var(--text-dark); z-index: 999; }
    .map-actions { position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; z-index: 1000; }
    .btn-map-act { padding: 10px 25px; border-radius: 30px; border: none; font-weight: 600; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
    .btn-use-gps { background: white; color: var(--text-dark); }
    .btn-save-loc { background: var(--primary-orange); color: white; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .profile-picture-wrapper { width: 140px; height: 140px; }
        .profile-card { padding: 25px; }
        .info-grid { grid-template-columns: 1fr; }
        .paket-row { flex-direction: column; align-items: stretch; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .btn-remove { width: 100%; height: 35px; margin-top: 5px; }
    }
</style>

<div class="profile-page">
    <div class="header-banner"></div>
    
    <div class="profile-info">
        <div class="profile-picture-wrapper">
            <?php 
            $fotoName = $data['mitra']['foto_profil'] ?? ''; 
            $pathMitra = '/pawtopia/public/uploads/mitra/';
            $pathDefault = '/pawtopia/public/images/default_petshop.jpg';

            if (!empty($fotoName)) {
                $fotoUrl = $pathMitra . htmlspecialchars($fotoName);
            } else {
                $fotoUrl = $pathDefault;
            }
            $finalUrl = $fotoUrl . '?v=' . time(); 
            ?>
            <img class="profile-picture" src="<?= $finalUrl ?>" alt="Foto Profil" onerror="this.onerror=null; this.src='<?= $pathDefault ?>';">
        </div>
    </div>

    <div class="profile-content-wrapper">
        <div class="profile-card">
             <?php if(isset($_SESSION['success'])): ?>
                <div style="background:#d4edda; color:#155724; padding:15px; border-radius:12px; margin-bottom:25px; display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="profile-header-text">
                <div class="petshop-name"><?= htmlspecialchars($data['mitra']['nama_petshop'] ?? '-') ?></div>
                <div class="petshop-owner"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($data['mitra']['nama_pemilik'] ?? 'Mitra Pawtopia') ?></div>
            </div>

            <div class="section-title">Informasi Detail</div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="info-text">
                        <label>Nomor Telepon</label>
                        <p><?= htmlspecialchars($data['mitra']['no_hp'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info-text">
                        <label>Alamat</label>
                        <p><?= htmlspecialchars($data['mitra']['alamat'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-paw"></i></div>
                    <div class="info-text">
                        <label>Kapasitas</label>
                        <p><?= intval($data['mitra']['kapasitas'] ?? 0) ?> Hewan</p>
                    </div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-icon"><i class="fas fa-align-left"></i></div>
                    <div class="info-text">
                        <label>Deskripsi</label>
                        <p style="font-weight: 400; line-height: 1.6;"><?= !empty($data['mitra']['deskripsi']) ? nl2br(htmlspecialchars($data['mitra']['deskripsi'])) : '-' ?></p>
                    </div>
                </div>
            </div>

            <div class="section-title">Paket Layanan</div>
            <div class="package-grid">
                <?php if (!empty($data['paket'])): ?>
                    <?php foreach ($data['paket'] as $p): ?>
                    <div class="package-badge">
                        <span class="pkt-name"><i class="fas fa-box-open" style="color:var(--text-grey); margin-right:8px;"></i> <?= htmlspecialchars($p['nama_paket']) ?></span>
                        <span class="pkt-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color:var(--text-grey); font-style:italic;">Belum ada paket yang ditambahkan.</p>
                <?php endif; ?>
            </div>

            <button class="edit-btn" type="button"><i class="fas fa-edit"></i> Edit Profil</button>
        </div>
    </div>
</div>

<div id="editModal" class="modal-bg">
    <div class="modal-box">
        <h2 style="margin-bottom:20px; font-weight:700; color:var(--text-dark);">✏ Edit Profil</h2>

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
                <label>📍 Alamat Lengkap</label>
                <textarea name="alamat" rows="3" required><?= htmlspecialchars($data['mitra']['alamat'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>🗺 Titik Lokasi (Maps)</label>
                <button type="button" class="btn-lokasi" id="btn-open-map">
                    <i class="fas fa-map-marked-alt"></i> Atur Lokasi di Peta
                </button>
                <input type="hidden" id="lokasi_lat" name="lokasi_lat" value="<?= htmlspecialchars($data['mitra']['lokasi_lat'] ?? '') ?>">
                <input type="hidden" id="lokasi_lng" name="lokasi_lng" value="<?= htmlspecialchars($data['mitra']['lokasi_lng'] ?? '') ?>">
                <small id="text-koordinat" style="display:block; margin-top:8px; color:var(--text-grey); font-size:0.85rem;">
                    <?= !empty($data['mitra']['lokasi_lat']) ? '<i class="fas fa-check-circle" style="color:green"></i> Lokasi tersimpan' : '<i class="fas fa-info-circle"></i> Belum ada lokasi' ?>
                </small>
            </div>

            <div class="form-group">
                <label>📝 Deskripsi Petshop</label>
                <textarea name="deskripsi" rows="4"><?= htmlspecialchars($data['mitra']['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>🐾 Kapasitas (Ekor)</label>
                <input type="number" name="kapasitas" value="<?= htmlspecialchars($data['mitra']['kapasitas'] ?? 0) ?>" min="0">
            </div>

            <div class="form-group">
                <label>💰 Atur Paket Harga</label>
                <div class="paket-container" id="paket-wrapper">
                    <?php if (!empty($data['paket'])): ?>
                        <?php foreach ($data['paket'] as $pkt): ?>
                        <div class="paket-row">
                            <input type="text" name="nama_paket[]" value="<?= htmlspecialchars($pkt['nama_paket']) ?>" placeholder="Contoh: Paket Full Service">
                            <input type="number" name="harga_paket[]" value="<?= $pkt['harga'] ?>" placeholder="Harga (Rp)">
                            <button type="button" class="btn-remove" title="Hapus Paket"><i class="fas fa-trash-alt"></i></button>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="paket-row">
                            <input type="text" name="nama_paket[]" placeholder="Contoh: Paket Regular">
                            <input type="number" name="harga_paket[]" placeholder="Harga (Rp)">
                            <button type="button" class="btn-remove"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" id="add-paket" class="btn-add-paket"><i class="fas fa-plus"></i> Tambah Paket Baru</button>
            </div>

            <div class="form-group">
                <label>📷 Ganti Foto Profil</label>
                <input type="file" id="file-input-foto" name="foto_petshop" accept="image/*">
                <img id="modal-preview-img" src="" style="display:none; width: 100px; height: 100px; border-radius: 50%; margin-top: 15px; object-fit: cover; border: 3px solid var(--primary-orange);">
            </div>

            <div class="btn-group">
                <button class="btn-action btn-cancel" type="button">Batal</button>
                <button class="btn-action btn-save" type="submit">Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>

<div id="map-modal">
    <div id="map-container-popup">
        <button id="close-map"><i class="fas fa-times"></i></button>
        <h3 style="margin:0 0 10px 0; text-align:center; color:var(--text-dark);">Pilih Lokasi Petshop</h3>
        <div id="map"></div>

        <div class="map-actions">
            <button id="use-my-loc" class="btn-map-act btn-use-gps" type="button"><i class="fas fa-crosshairs"></i> GPS Saya</button>
            <button id="save-loc" class="btn-map-act btn-save-loc" type="button"><i class="fas fa-check"></i> Gunakan Lokasi Ini</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- 1. MODAL EDIT ---
        const editModal = document.getElementById("editModal");
        const btnEdit = document.querySelector(".edit-btn");
        const btnCancel = document.querySelector(".btn-cancel");

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

        // --- 2. PAKET DYNAMIC ---
        const containerPaket = document.getElementById("paket-wrapper");
        const btnAddPaket = document.getElementById("add-paket");

        if (btnAddPaket && containerPaket) {
            btnAddPaket.addEventListener("click", () => {
                const row = document.createElement("div");
                row.classList.add("paket-row");
                row.innerHTML = `
                    <input type="text" name="nama_paket[]" placeholder="Nama Paket" required>
                    <input type="number" name="harga_paket[]" placeholder="Harga (Rp)" required>
                    <button type="button" class="btn-remove"><i class="fas fa-trash-alt"></i></button>
                `;
                containerPaket.appendChild(row);
            });
            containerPaket.addEventListener("click", (e) => {
                // Handle klik pada tombol hapus (termasuk ikon di dalamnya)
                if (e.target.classList.contains("btn-remove") || e.target.closest(".btn-remove")) {
                    const btn = e.target.classList.contains("btn-remove") ? e.target : e.target.closest(".btn-remove");
                    btn.parentElement.remove();
                }
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

        // --- 4. MAP LEAFLET ---
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

            let startLat = (curLat) ? curLat : -8.1724; // Default Jember
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
                    btnUseMyLoc.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            updateMarker(pos.coords.latitude, pos.coords.longitude);
                            map.setView([pos.coords.latitude, pos.coords.longitude], 18);
                            btnUseMyLoc.innerHTML = '<i class="fas fa-crosshairs"></i> GPS Saya';
                        },
                        (err) => {
                            alert("Gagal deteksi lokasi. Pastikan GPS aktif.");
                            btnUseMyLoc.innerHTML = '<i class="fas fa-crosshairs"></i> GPS Saya';
                        }, { enableHighAccuracy: true }
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
                    textKoordinat.innerHTML = `<i class="fas fa-check-circle" style="color:green"></i> Lokasi tersimpan: <b>${tempLat.toFixed(5)}, ${tempLng.toFixed(5)}</b>`;
                    mapModal.style.display = "none";
                } else {
                    alert("Silakan pilih titik lokasi di peta terlebih dahulu.");
                }
            });
        }
    });
</script>
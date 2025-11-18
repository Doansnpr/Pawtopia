<!-- LEAFLET CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    /* Profile page styles - NO body, html tags needed! */
    .profile-page {
        width: 100%;
        background-color: #f5f5f5;
        margin: -30px; /* Negate content-wrapper padding */
        padding-bottom: 40px;
    }

    .header-banner {
        width: 100%;
        height: 280px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }

    .header-banner::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: url("<?= BASEURL ?>/public/images/petshop_header.jpg") center/cover no-repeat;
        opacity: 0.25;
        filter: blur(3px);
    }

    .header-banner::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 120px;
        background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.15));
    }

    .profile-info {
        position: relative;
        margin-top: -140px;
        z-index: 50;
        display: flex;
        justify-content: center;
        width: 100%;
        padding: 0 20px;
    }

    .profile-picture-wrapper {
        position: relative;
        width: 160px;
        height: 160px;
    }

    .profile-picture-wrapper::before {
        content: '';
        position: absolute;
        top: -12px;
        left: -12px;
        right: -12px;
        bottom: -12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        filter: blur(20px);
        opacity: 0.5;
        z-index: -1;
    }

    .profile-picture {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        border: 6px solid white;
        object-fit: cover;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        background: #e2e8f0;
        display: block;
    }

    .profile-content-wrapper {
        max-width: 900px;
        margin: 30px auto 0;
        padding: 0 20px 40px;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.3s ease;
    }

    .profile-card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }

    .profile-card h3 {
        margin-bottom: 20px;
        font-size: 22px;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 2px solid #f7fafc;
        padding-bottom: 10px;
    }

    .profile-detail { 
        margin-bottom: 15px;
        display: flex;
        align-items: flex-start;
        padding: 8px 0;
    }

    .profile-detail span {
        font-weight: 600;
        display: inline-block;
        min-width: 160px;
        flex-shrink: 0;
        color: #4a5568;
    }

    .profile-detail div {
        color: #2d3748;
        line-height: 1.6;
    }

    .edit-btn {
        background: linear-gradient(135deg, #ff7f50 0%, #ff6347 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        margin-top: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 127, 80, 0.3);
        font-family: 'Poppins', 'Inter', sans-serif;
    }

    .edit-btn:hover {
        background: linear-gradient(135deg, #ff6347 0%, #ff4500 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 127, 80, 0.4);
    }

    /* Modal */
    .modal-bg {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        justify-content: center;
        align-items: center;
        z-index: 9999;
        overflow-y: auto;
        padding: 20px;
    }

    .modal-box {
        background: white;
        width: 100%;
        max-width: 650px;
        border-radius: 16px;
        padding: 30px;
        margin: 20px auto;
        max-height: 90vh;
        overflow-y: auto;
        animation: fadeInModal .3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    @keyframes fadeInModal {
        from { opacity: 0; transform: scale(.95) translateY(-20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-box h2 {
        margin-top: 0;
        margin-bottom: 25px;
        font-weight: 600;
        font-size: 24px;
        color: #2d3748;
        border-bottom: 2px solid #f7fafc;
        padding-bottom: 12px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #4a5568;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 14px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        font-size: 14px;
        box-sizing: border-box;
        font-family: 'Poppins', 'Inter', sans-serif;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
    }

    .form-group textarea {
        min-height: 90px;
        resize: vertical;
    }

    .form-group small {
        display: block;
        margin-top: 6px;
        color: #718096;
        font-size: 13px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    #map-container {
        width: 100%;
        height: 320px;
        border-radius: 12px;
        margin-top: 10px;
        border: 2px solid #e2e8f0;
        overflow: hidden;
    }

    .save-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        font-family: 'Poppins', 'Inter', sans-serif;
    }

    .save-btn:hover {
        background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
    }

    .cancel-btn {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        margin-left: 10px;
        transition: all 0.3s ease;
        font-family: 'Poppins', 'Inter', sans-serif;
    }

    .cancel-btn:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .btn-group {
        margin-top: 25px;
        display: flex;
        gap: 10px;
    }

    .price-label {
        font-size: 12px;
        color: #718096;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .modal-box::-webkit-scrollbar {
        width: 8px;
    }

    .modal-box::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .modal-box::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .modal-box::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .profile-detail {
            flex-direction: column;
        }

        .profile-detail span {
            margin-bottom: 5px;
        }
    }
</style>

<div class="profile-page">
    <!-- HEADER BANNER -->
    <div class="header-banner"></div>

    <!-- FOTO PROFIL -->
    <div class="profile-info">
        <div class="profile-picture-wrapper">
            <?php 
            $foto_path = BASEURL . '/public/uploads/mitra/';
            $foto_file = !empty($data['mitra']['foto_profil']) ? $data['mitra']['foto_profil'] : 'default_petshop.jpg';
            $full_path = $foto_path . htmlspecialchars($foto_file);
            ?>
            <img class="profile-picture"
            src="<?= $full_path ?>"
            alt="Foto Profil Petshop"
            onerror="this.src='<?= BASEURL ?>/public/images/default_petshop.jpg';">
        </div>
    </div>

    <!-- CONTENT -->
    <div class="profile-content-wrapper">
        <!-- INFORMASI PROFIL -->
        <div class="profile-card">
            <h3>📋 Informasi Profil</h3>

            <div class="profile-detail">
                <span>🏪 Nama Petshop:</span>
                <div><?= htmlspecialchars($data['mitra']['nama_petshop'] ?? '-'); ?></div>
            </div>

            <div class="profile-detail">
                <span>📱 No. HP:</span>
                <div><?= htmlspecialchars($data['mitra']['no_hp'] ?? '-'); ?></div>
            </div>

            <div class="profile-detail">
                <span>📍 Alamat:</span>
                <div><?= htmlspecialchars($data['mitra']['alamat'] ?? '-'); ?></div>
            </div>

            <div class="profile-detail">
                <span>📝 Deskripsi:</span>
                <div><?= !empty($data['mitra']['deskripsi']) ? htmlspecialchars($data['mitra']['deskripsi']) : '-'; ?></div>
            </div>

            <div class="profile-detail">
                <span>🐾 Kapasitas:</span>
                <div><?= htmlspecialchars($data['mitra']['kapasitas'] ?? '0'); ?> hewan</div>
            </div>

            <div class="profile-detail">
                <span>💰 Harga Paket 1:</span>
                <div>Rp <?= number_format($data['mitra']['harga_paket1'] ?? 0, 0, ',', '.'); ?></div>
            </div>

            <div class="profile-detail">
                <span>💰 Harga Paket 2:</span>
                <div>Rp <?= number_format($data['mitra']['harga_paket2'] ?? 0, 0, ',', '.'); ?></div>
            </div>

            <div class="profile-detail">
                <span>💰 Harga Paket 3:</span>
                <div>Rp <?= number_format($data['mitra']['harga_paket3'] ?? 0, 0, ',', '.'); ?></div>
            </div>

            <button class="edit-btn" onclick="openModal()">✏️ Edit Profil</button>
        </div>
    </div>
</div>

<!-- MODAL UPDATE PROFIL -->
<div id="editModal" class="modal-bg" onclick="closeModalOnOutside(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
        <h2>✏️ Edit Profil</h2>

        <form action="<?= BASEURL ?>/DashboardMitra/updateProfile" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>🏪 Nama Petshop</label>
                <input type="text" name="nama_petshop" value="<?= htmlspecialchars($data['mitra']['nama_petshop'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>📱 No HP/Telepon</label>
                <input type="text" name="no_hp" value="<?= htmlspecialchars($data['mitra']['no_hp'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>📍 Alamat</label>
                <textarea name="alamat" required><?= htmlspecialchars($data['mitra']['alamat'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label>📝 Deskripsi Petshop</label>
                <textarea name="deskripsi" placeholder="Ceritakan tentang petshop Anda..."><?= htmlspecialchars($data['mitra']['deskripsi'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label>🐾 Kapasitas (Jumlah Hewan)</label>
                <input type="number" name="kapasitas" value="<?= htmlspecialchars($data['mitra']['kapasitas'] ?? '0'); ?>" min="0" required>
            </div>

            <div class="form-group">
                <label>💰 Harga Paket Penitipan</label>
                <div class="form-row">
                    <div>
                        <label class="price-label">Paket 1 (Rp)</label>
                        <input type="number" name="harga_paket1" value="<?= htmlspecialchars($data['mitra']['harga_paket1'] ?? '0'); ?>" min="0" step="1000">
                    </div>
                    <div>
                        <label class="price-label">Paket 2 (Rp)</label>
                        <input type="number" name="harga_paket2" value="<?= htmlspecialchars($data['mitra']['harga_paket2'] ?? '0'); ?>" min="0" step="1000">
                    </div>
                    <div>
                        <label class="price-label">Paket 3 (Rp)</label>
                        <input type="number" name="harga_paket3" value="<?= htmlspecialchars($data['mitra']['harga_paket3'] ?? '0'); ?>" min="0" step="1000">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>📷 Foto Profil</label>
                <input type="file" name="foto_petshop" accept="image/jpeg,image/jpg,image/png,image/gif">
                <?php if(!empty($data['mitra']['foto_profil'])): ?>
                    <small>📁 Foto saat ini: <strong><?= htmlspecialchars($data['mitra']['foto_profil']); ?></strong></small>
                <?php endif; ?>
            </div>

            <!-- MAP -->
            <div class="form-group">
                <label>📍 Lokasi Petshop (Drag pin untuk memilih)</label>
                <div id="map-container"></div>
                <input type="hidden" id="latitude" name="latitude" value="<?= htmlspecialchars($data['mitra']['lokasi_lat'] ?? ''); ?>">
                <input type="hidden" id="longitude" name="longitude" value="<?= htmlspecialchars($data['mitra']['lokasi_lng'] ?? ''); ?>">
                <small>Klik dan drag marker merah untuk memilih lokasi yang tepat</small>
            </div>

            <div class="btn-group">
                <button class="save-btn" type="submit">💾 Simpan Perubahan</button>
                <button class="cancel-btn" type="button" onclick="closeModal()">❌ Batal</button>
            </div>

        </form>
    </div>
</div>

<!-- LEAFLET JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;

    function openModal() {
        document.getElementById("editModal").style.display = "flex";
        document.body.style.overflow = "hidden";
        setTimeout(() => initMap(), 350);
    }

    function closeModal() {
        document.getElementById("editModal").style.display = "none";
        document.body.style.overflow = "auto";
    }

    function closeModalOnOutside(event) {
        if (event.target.id === 'editModal') {
            closeModal();
        }
    }

    function initMap() {
        let lat = parseFloat(document.getElementById("latitude").value) || -8.160844;
        let lng = parseFloat(document.getElementById("longitude").value) || 113.706651;

        if (!map) {
            map = L.map('map-container').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            marker = L.marker([lat, lng], { 
                draggable: true,
                title: 'Drag saya untuk mengubah lokasi'
            }).addTo(map);

            marker.on('dragend', function(e) {
                let c = e.target.getLatLng();
                document.getElementById("latitude").value = c.lat.toFixed(6);
                document.getElementById("longitude").value = c.lng.toFixed(6);
            });
        } else {
            map.setView([lat, lng], 15);
            marker.setLatLng([lat, lng]);
        }

        setTimeout(() => map.invalidateSize(), 200);
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById("editModal").style.display === 'flex') {
            closeModal();
        }
    });
</script>
<?php
// View: app/views/dashboard_customer/pilih_penitipan/penitipan.php
// Versi ini sudah diperbaiki: semua if/foreach ditutup, HTML rapi.
?>
<style>
    :root {
        --primary: #f3b83f;
        --secondary: #ff9f43;
        --accent: #ff6347;
        --light: #F8F9FA;
        --dark: #212529;
        --success: #28a745;
        --info: #17a2b8;
        --warning: #ffc107;
        --danger: #dc3545;
        --gray: #6c757d;
        --border: #dee2e6;
        --shadow: 0 4px 15px rgba(243, 184, 63, 0.2);
        --bg-light: #fff8e1;
        --bg-card: #ffffff;
        --text-primary: #212529;
        --text-secondary: #6c757d;
    }

    .container { max-width: 100%; width: 100%; margin: 0 auto; }

    .page-header { margin-bottom: 1.5rem; }
    .page-header h2 { font-size: 1.5rem; color: #333; margin: 0; font-weight: 700; }
    .page-header p { color: #666; font-size: 0.9rem; margin-top: 5px; }

    .search-hero {
        background: linear-gradient(135deg, #f3b83f 0%, #ff9f43 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        box-shadow: 0 4px 15px rgba(243, 184, 63, 0.3);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
    }
    .search-hero .bg-icon { position: absolute; right: -20px; bottom: -30px; font-size: 10rem; opacity: 0.15; color: white; transform: rotate(-15deg); pointer-events: none; }

    .search-content { position: relative; z-index: 2; text-align: center; max-width: 700px; margin: 0 auto; }
    .search-input-wrapper { position: relative; width: 100%; margin-top: 1.5rem; }
    .search-input { width: 100%; padding: 15px 55px 15px 25px; border-radius: 50px; border: none; outline: none; font-size: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1); box-sizing: border-box; }
    .search-btn-circle { position: absolute; right: 8px; top: 8px; height: 40px; width: 40px; background: #f3b83f; border: none; border-radius: 50%; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
    .search-btn-circle:hover { background: #d99f28; transform: scale(1.05); }

    .slider-section { margin-bottom: 2.5rem; width: 100%; position: relative; padding: 0 5px; box-sizing: border-box; }
    .slider-outer-wrapper { position: relative; width: 100%; overflow: hidden; }
    .slider-container { display: flex; overflow-x: auto; gap: 1.2rem; padding: 5px 0 20px 0; scroll-behavior: smooth; -ms-overflow-style: none; scrollbar-width: none; width: 100%; box-sizing: border-box; }
    .slider-container::-webkit-scrollbar { display: none; }

    .mitra-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; width: 100%; box-sizing: border-box; }

    .mitra-card { background: white; border-radius: 16px; padding: 1.2rem; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; display: flex; flex-direction: column; text-decoration: none; color: inherit; transition: transform 0.2s, box-shadow 0.2s; height: 100%; position: relative; }
    .mitra-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); border-color: #f3b83f; }
    .card-img { width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 1rem; border: 1px solid #f9f9f9; }
    .card-title { font-size: 1.1rem; font-weight: 700; color: #333; margin: 0 0 5px 0; }
    .card-loc { font-size: 0.85rem; color: #888; display: flex; align-items: center; gap: 5px; margin-bottom: 15px; }

    .price-section { margin-top: auto; padding-top: 15px; border-top: 1px dashed #eee; display: flex; justify-content: space-between; align-items: center; }
    .price-tag { color: #f3b83f; font-weight: 700; font-size: 1rem; }
    .rating-tag { background:#fff8e1; color:#ffb300; padding:4px 8px; border-radius:6px; font-size:0.8rem; font-weight:600; }

    .badge-new { position: absolute; top: 15px; left: 15px; background: #ff4757; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 2px 5px rgba(255,71,87,0.4); z-index: 5; }

    /* Modal styles kept as in your code */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s; }
    .modal-overlay.open { display: flex; opacity: 1; }
    .modal-content { background: white; border-radius: 16px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; padding: 2rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); transform: scale(0.9); transition: transform 0.3s ease-out; }
    .modal-overlay.open .modal-content { transform: scale(1); }
    .modal-header { border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1.5rem; }
    .modal-body-detail { display: flex; gap: 1.5rem; margin-bottom: 1.5rem; }
    .mitra-photo { width: 120px; height: 120px; object-fit: cover; border-radius: 12px; flex-shrink: 0; border: 2px solid var(--primary); }
    .paket-item { padding: 10px; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; background: var(--bg-light); }
    .ulasan-container { max-height: 200px; overflow-y: auto; padding-right: 10px; }
    .ulasan-item { margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px dotted #eee; }

    @media (max-width: 768px) {
        .search-hero { padding: 1.5rem 1rem; }
        .bg-icon { display: none; }
        .mitra-grid { grid-template-columns: 1fr; }
        .slider-section { padding: 0; }
        .modal-body-detail { flex-direction: column; text-align: center; }
        .modal-body-detail .mitra-photo { margin: 0 auto; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
    .btn-primary-paw { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.2s; }
    .btn-primary-paw:hover { background: #d99f28; }
    .btn-secondary-paw { background: var(--gray); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; transition: background 0.2s; }
    .btn-secondary-paw:hover { background: #5a6268; }
    .modal-description { margin-bottom: 1rem; padding: 10px; background: #fff8e1; border-radius: 8px; border: 1px dashed var(--primary); font-size: 0.95rem; }
    .ulasan-item strong { color: var(--primary); }
    .balasan-mitra { background: #f0f0f0; padding: 8px; border-left: 3px solid var(--accent); margin-top: 5px; font-size: 0.85rem; color: var(--text-secondary); }
    }
</style>

<div class="content-wrapper">

    <div class="page-header">
        <h2>Cari Penitipan <i class="fa-solid fa-magnifying-glass-location" style="color:#f3b83f;"></i></h2>
        <p>Temukan mitra terbaik untuk anabul kesayanganmu.</p>
    </div>

    <div class="search-hero">
        <i class="fa-solid fa-cat bg-icon"></i>

        <div class="search-content">
            <h3 style="margin:0; font-size:1.8rem; font-weight:700;">Mau titip di mana hari ini?</h3>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Kami punya banyak mitra terpercaya untukmu.</p>

            <form action="" method="GET">
                <div class="search-input-wrapper">
                    <input type="text" name="q" class="search-input" placeholder="Cari nama petshop, kota, atau daerah..." value="<?= isset($data['keyword']) ? htmlspecialchars($data['keyword']) : '' ?>">
                    <button type="submit" class="search-btn-circle"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($data['keyword']) && !empty($data['hotArrivals'])): ?>
    <div class="slider-section">
        <h3 style="font-size:1.2rem; color:#333; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">
            <i class="fa-solid fa-fire" style="color:#ff4757;"></i> Pendatang Baru
        </h3>

        <div class="slider-outer-wrapper">
            <div class="slider-container" id="hotSlider">
                <?php foreach ($data['hotArrivals'] as $new): ?>
                    <div class="mitra-card" style="min-width: 250px; max-width: 250px; cursor:pointer;"
                         onclick="openDetailModal('<?= htmlspecialchars($new['id_mitra']) ?>', '<?= BASEURL ?>')">

                        <?php if (!empty($new['is_new'])): ?>
                            <div class="badge-new">NEW</div>
                        <?php endif; ?>

                        <?php if (!empty($new['foto_profil'])): ?>
                            <img src="<?= BASEURL ?>/images/mitra/<?= htmlspecialchars($new['foto_profil']) ?>" class="card-img" style="height:140px;" alt="<?= htmlspecialchars($new['nama_petshop'] ?? '') ?>">
                        <?php endif; ?>

                        <div>
                            <h4 class="card-title" style="font-size:1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?= htmlspecialchars($new['nama_petshop'] ?? '-') ?>
                            </h4>
                        </div>

                        <div class="price-section" style="padding-top:10px; margin-top:5px;">
                            <span class="price-tag" style="font-size:0.9rem;">
                                <?= !empty($new['harga_mulai']) ? 'Rp '.number_format($new['harga_mulai'],0,',','.') : 'Cek Harga' ?>
                            </span>
                            <span class="rating-tag">
                                <i class="fas fa-star"></i> <?= !empty($new['rating_rata']) ? number_format($new['rating_rata'], 1) : 'New' ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div style="margin-top: 2rem;">
        <h3 style="font-size:1.2rem; color:#333; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">
            <?= !empty($data['keyword']) ? '🔍 Hasil Pencarian' : '<i class="fa-solid fa-store" style="color:#f3b83f;"></i> Jelajahi Mitra' ?>
        </h3>

        <div class="mitra-grid">
            <?php if (empty($data['mitraList'])): ?>
                <div style="grid-column: 1/-1; text-align:center; padding:3rem; background:white; border-radius:16px; border:2px dashed #ddd; color:#999;">
                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 10px; display:block; opacity:0.5;"></i>
                    Belum ada data penitipan yang tersedia :(
                </div>
            <?php else: ?>
                <?php foreach ($data['mitraList'] as $mitra): ?>
                    <div class="mitra-card" style="cursor:pointer;" onclick="openDetailModal('<?= htmlspecialchars($mitra['id_mitra']) ?>', '<?= BASEURL ?>')">

                        <?php if (!empty($mitra['foto_profil'])): ?>
                            <img src="<?= BASEURL ?>/images/mitra/<?= htmlspecialchars($mitra['foto_profil']) ?>" class="card-img" alt="<?= htmlspecialchars($mitra['nama_petshop'] ?? '-') ?>">
                        <?php endif; ?>

                        <div>
                            <h4 class="card-title"><?= htmlspecialchars($mitra['nama_petshop'] ?? '-') ?></h4>
                            <div class="card-loc">
                                <i class="fas fa-map-marker-alt" style="color:#f3b83f;"></i>
                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= htmlspecialchars(substr($mitra['alamat'] ?? '-', 0, 30)) ?>...
                                </span>
                            </div>
                        </div>

                        <div class="price-section">
                            <span class="price-tag">
                                <?php if (!empty($mitra['harga_mulai'])): ?>
                                    Rp <?= number_format($mitra['harga_mulai'], 0, ',', '.') ?>
                                <?php else: ?>
                                    Tanya Admin
                                <?php endif; ?>
                            </span>
                            <span class="rating-tag">
                                <i class="fas fa-star"></i> <?= !empty($mitra['rating_rata']) ? number_format($mitra['rating_rata'], 1) : 'New' ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal-overlay" id="mitraModal" onclick="if(event.target.id === 'mitraModal') closeModal()">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Detail Mitra Penitipan</h3>
        </div>
        <div class="modal-body">
            <div class="modal-body-detail">
                <img id="modalPhoto" src="" class="mitra-photo" alt="Foto Mitra">
                <div class="modal-info">
                    <h4 id="modalMitraName">Nama Petshop</h4>
                    <div id="modalRating" class="modal-rating"style="margin-bottom:10px;"><i class="fas fa-star"></i> 4.5 <span>(Rata-rata Ulasan)</span></div>
                    <p style="font-size:0.9rem; margin-bottom:5px;"><i class="fas fa-map-marker-alt" style="color:#f3b83f;"></i> <span id="modalAddress">Alamat Lengkap</span></p>
                    <p style="font-size:0.9rem; margin-bottom:10px;"><i class="fas fa-phone-alt" style="color:#f3b83f;"></i> <span id="modalPhone">No HP</span></p>
                    <a id="modalLocationLink" href="#" target="_blank" style="color:var(--accent); font-size:0.9rem; text-decoration:none;">
                        <i class="fas fa-location-arrow"></i> Lihat Lokasi di Peta
                    </a>
                </div>
            </div>

            <div class="modal-description">
                <p id="modalDescriptionText">Deskripsi Mitra...</p>
            </div>

            <h4 style="border-bottom: 1px solid var(--border); padding-bottom: 5px; color:#333; font-size:1.1rem;">Daftar Paket Penitipan</h4>
            <div class="paket-list" id="modalPaketList"></div>

            <h4 style="border-bottom: 1px solid var(--border); padding-bottom: 5px; color:#333; font-size:1.1rem;">Ulasan Terbaru</h4>
            <div class="ulasan-container" id="modalUlasanList"></div>
        </div>

        <div class="modal-actions">
            <button class="btn-secondary-paw" onclick="closeModal()">Batal</button>
            <a id="btnBooking" class="btn-primary-paw" href="#">Booking Sekarang <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<script>
    // Tutup modal
    function closeModal() {
        document.getElementById('mitraModal').classList.remove('open');
    }

    // Utility: tampilkan pesan error di modal (daripada alert bawaan)
    function showModalError(message) {
        document.getElementById('modalTitle').innerText = 'Terjadi Kesalahan';
        document.getElementById('modalMitraName').innerText = '';
        document.getElementById('modalAddress').innerText = '';
        document.getElementById('modalPhone').innerText = '';
        document.getElementById('modalPhoto').src = '';
        document.getElementById('modalRating').innerHTML = '';
        document.getElementById('modalPaketList').innerHTML = `<p style="text-align:center; color:var(--danger);">${message}</p>`;
        document.getElementById('modalUlasanList').innerHTML = '';
        document.getElementById('modalLocationLink').style.display = 'none'; // Sembunyikan link lokasi saat error
    }

    // Fungsi utama buka modal dan fetch detail
    async function openDetailModal(mitraId, baseUrl) {
        // Pastikan id dikirim sebagai string persis (trim + encode)
        if (typeof mitraId !== 'string') {
            mitraId = String(mitraId);
        }
        mitraId = mitraId.trim();
        if (!mitraId) {
            alert('ID mitra kosong. Gagal memuat detail.');
            return;
        }

        const modal = document.getElementById('mitraModal');

        // Set tampilan loading
        document.getElementById('modalTitle').innerText = 'Memuat Detail...';
        document.getElementById('modalMitraName').innerText = '...';
        document.getElementById('modalAddress').innerText = '...';
        document.getElementById('modalPhone').innerText = '...';
        document.getElementById('modalDescriptionText').innerText = 'Memuat deskripsi...'; // Tambahan
        document.getElementById('modalPhoto').src = '';
        document.getElementById('modalRating').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
        document.getElementById('modalPaketList').innerHTML = '<p style="text-align:center;">Memuat data paket...</p>';
        document.getElementById('modalUlasanList').innerHTML = '<p style="text-align:center;">Memuat data ulasan...</p>';
        document.getElementById('modalLocationLink').style.display = 'none';

        modal.classList.add('open');

        try {
            // Safety: pastikan baseUrl tidak punya trailing slash ganda
            baseUrl = baseUrl ? String(baseUrl).replace(/\/+$/, '') : '';

            // Encode ID agar aman di URL
            const safeId = encodeURIComponent(mitraId);

            // Bangun URL endpoint (sesuaikan path jika baseUrl sudah mengandung /public)
            const url = `${baseUrl}/CariPenitipan/getDetailMitra/${safeId}`;

            console.info('Fetching detail mitra from:', url);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            // Baca response text dulu (untuk debug jika bukan JSON)
            const text = await response.text();

            // Coba parse JSON, tapi jika tidak valid tampilkan isi response untuk debug
            let result;
            try {
                result = JSON.parse(text);
            } catch (e) {
                console.error('Response is not valid JSON. Raw response:', text);
                showModalError('Response dari server tidak valid. Cek console (raw response).');
                return;
            }

            // Jika server mengembalikan success = true, isi modal
            if (result && result.success) {
                const data = result.data;

                document.getElementById('modalTitle').innerText = `Detail ${data.nama_petshop || 'Mitra'}`;
                document.getElementById('modalMitraName').innerText = data.nama_petshop || '-';
                document.getElementById('modalAddress').innerText = data.alamat || '-';
                document.getElementById('modalPhone').innerText = data.no_hp || '-';
                document.getElementById('modalDescriptionText').innerText = data.deskripsi || 'Tidak ada deskripsi tersedia.'; // Tampilkan deskripsi
                document.getElementById('modalPhoto').src = data.foto_profil ? `${baseUrl}/images/mitra/${data.foto_profil}` : '';
                
                const rating = (data.rating_rata !== null && data.rating_rata !== undefined) ? parseFloat(data.rating_rata).toFixed(1) : 'Belum Ada';
                document.getElementById('modalRating').innerHTML = `<i class="fas fa-star"></i> ${rating} <span>(Rata-rata Ulasan)</span>`;

                document.getElementById('btnBooking').href = `${baseUrl}/Booking/form/${encodeURIComponent(data.id_mitra)}`;

                // Link Lokasi
                if (data.lokasi_lat && data.lokasi_lng) {
                    document.getElementById('modalLocationLink').style.display = 'inline';
                } else {
                     document.getElementById('modalLocationLink').style.display = 'none';
                }

                // Paket
                const paketListEl = document.getElementById('modalPaketList');
                paketListEl.innerHTML = '';
                if (Array.isArray(data.paket) && data.paket.length > 0) {
                    data.paket.forEach(paket => {
                        const hargaNum = parseFloat(paket.harga) || 0;
                        const harga = 'Rp ' + hargaNum.toLocaleString('id-ID', { minimumFractionDigits: 0 });
                        const nama = paket.nama_paket || 'Paket';
                        paketListEl.innerHTML += `
                            <div class="paket-item">
                                <strong>${nama}</strong>
                                <span>${harga}</span>
                            </div>
                        `;
                    });
                } else {
                    paketListEl.innerHTML = '<p style="color:var(--gray); font-size:0.9rem;">Belum ada paket yang terdaftar.</p>';
                }

                // Ulasan
                const ulasanListEl = document.getElementById('modalUlasanList');
                ulasanListEl.innerHTML = '';
                if (Array.isArray(data.ulasan) && data.ulasan.length > 0) {
                    data.ulasan.forEach(ulasan => {
                        const r = parseInt(ulasan.rating) || 0;
                        const starHtml = '<i class="fas fa-star" style="color:var(--warning);"></i>'.repeat(r);
                        let balasanHtml = '';
                        if (ulasan.balasan_mitra) {
                            balasanHtml = `<div class="balasan-mitra">Balasan Mitra: ${ulasan.balasan_mitra}</div>`;
                        }

                        ulasanListEl.innerHTML += `
                            <div class="ulasan-item">
                                <strong>${ulasan.nama_lengkap || 'Anonim'}</strong>
                                <span style="float:right;">${starHtml}</span>
                                <p style="margin:0.5rem 0 0 0; font-style:italic;">"${ulasan.komentar || ''}"</p>
                                ${balasanHtml}
                                </div>
                        `;
                    });
                } else {
                    ulasanListEl.innerHTML = '<p style="color:var(--gray); font-size:0.9rem; text-align:center;">Belum ada ulasan untuk mitra ini.</p>';
                }

            } else {
                // Jika server mengembalikan success:false, tampilkan pesan dari server ke user
                const serverMsg = (result && result.message) ? result.message : 'Gagal memuat detail mitra.';
                console.warn('Server returned error:', serverMsg, result);
                showModalError(serverMsg);
                // optional: tutup modal otomatis setelah 2.5s
                // setTimeout(closeModal, 2500);
            }

        } catch (error) {
            console.error('Error fetching detail:', error);
            showModalError('Terjadi kesalahan saat memuat data. Periksa koneksi atau console untuk detail.');
        }
    }
</script>

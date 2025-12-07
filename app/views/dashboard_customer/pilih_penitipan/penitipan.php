<?php
// View: app/views/dashboard_customer/pilih_penitipan/penitipan.php
?>
<style>
    /* CSS BAWAAN KAMU */
    :root {
        --primary: #f3b83f; --secondary: #ff9f43; --accent: #ff6347;
        --light: #F8F9FA; --dark: #212529; --success: #28a745;
        --info: #17a2b8; --warning: #ffc107; --danger: #dc3545;
        --gray: #6c757d; --border: #dee2e6;
        --shadow: 0 4px 15px rgba(243, 184, 63, 0.2);
        --bg-light: #fff8e1; --bg-card: #ffffff;
    }
    .container { max-width: 100%; width: 100%; margin: 0 auto; }
    .page-header { margin-bottom: 1.5rem; }
    .page-header h2 { font-size: 1.5rem; color: #333; margin: 0; font-weight: 700; }
    .page-header p { color: #666; font-size: 0.9rem; margin-top: 5px; }
    .search-hero { background: linear-gradient(135deg, #f3b83f 0%, #ff9f43 100%); border-radius: 16px; padding: 2rem; color: white; box-shadow: 0 4px 15px rgba(243, 184, 63, 0.3); margin-bottom: 2rem; position: relative; overflow: hidden; width: 100%; box-sizing: border-box; }
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
    .mitra-card { background: white; border-radius: 16px; padding: 1.2rem; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; display: flex; flex-direction: column; text-decoration: none; color: inherit; transition: transform 0.2s, box-shadow 0.2s; height: 100%; position: relative; cursor: pointer;}
    .mitra-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); border-color: #f3b83f; }
    .card-img { width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 1rem; border: 1px solid #f9f9f9; }
    .card-title { font-size: 1.1rem; font-weight: 700; color: #333; margin: 0 0 5px 0; }
    .card-loc { font-size: 0.85rem; color: #888; display: flex; align-items: center; gap: 5px; margin-bottom: 15px; }
    .badge-new { position: absolute; top: 15px; left: 15px; background: #ff4757; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 2px 5px rgba(255,71,87,0.4); z-index: 5; }

    /* Modal Styling */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s; }
    .modal-overlay.open { display: flex; opacity: 1; }
    .modal-content { background: white; border-radius: 16px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; padding: 2rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); transform: scale(0.9); transition: transform 0.3s ease-out; position: relative; }
    .modal-overlay.open .modal-content { transform: scale(1); }
    .modal-header { border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
    .close { font-size: 2rem; cursor: pointer; color: #aaa; }
    .close:hover { color: #333; }
    
    .modal-body-detail { display: flex; gap: 1.5rem; margin-bottom: 1.5rem; }
    .mitra-photo { width: 120px; height: 120px; object-fit: cover; border-radius: 12px; flex-shrink: 0; border: 2px solid var(--primary); }
    .paket-item { padding: 10px; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; background: var(--bg-light); }
    .ulasan-container { max-height: 200px; overflow-y: auto; padding-right: 10px; }
    .ulasan-item { margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px dotted #eee; }
    .modal-description { margin-bottom: 1rem; padding: 10px; background: #fff8e1; border-radius: 8px; border: 1px dashed var(--primary); font-size: 0.95rem; }
    .btn-primary-paw { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; cursor: pointer; display:inline-block; }
    .btn-secondary-paw { background: var(--gray); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; }
    .balasan-mitra { background: #f0f0f0; padding: 8px; border-left: 3px solid var(--accent); margin-top: 5px; font-size: 0.85rem; color: var(--text-secondary); }

    /* Form Booking Styles */
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
    .cat-section { border: 1px dashed #ccc; padding: 15px; border-radius: 8px; margin-bottom: 10px; position: relative; }
    .remove-cat { position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; line-height: 1; }
    
    @media (max-width: 768px) {
        .modal-body-detail { flex-direction: column; text-align: center; }
        .modal-body-detail .mitra-photo { margin: 0 auto; }
        .search-hero { padding: 1.5rem 1rem; }
        .bg-icon { display: none; }
        .mitra-grid { grid-template-columns: 1fr; }
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
                    <input type="text" name="q" class="search-input" placeholder="Cari nama petshop..." value="<?= isset($data['keyword']) ? htmlspecialchars($data['keyword']) : '' ?>">
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
            <div class="slider-container">
            <?php foreach ($data['hotArrivals'] as $new): ?>
                <?php 
                    $fotoName = $new['foto_profil'];
                    $folder = (strpos($fotoName, 'foto_petshop_') !== false) ? 'uploads/mitra/' : 'images/mitra/';
                    $imgUrl = !empty($fotoName) ? BASEURL . '/' . $folder . rawurlencode($fotoName) : BASEURL . '/images/default_petshop.jpg';
                ?>
                <div class="mitra-card" style="min-width: 250px; max-width: 250px; cursor:pointer;"
                     onclick="openDetailModal('<?= htmlspecialchars($new['id_mitra']) ?>')">
                    
                    <?php if (!empty($new['is_new'])): ?>
                        <div class="badge-new">NEW</div>
                    <?php endif; ?>

                    <img src="<?= $imgUrl ?>" class="card-img" alt="Mitra" onerror="this.src='<?= BASEURL ?>/images/default_petshop.jpg'">

                    <div>
                        <h4 class="card-title"><?= htmlspecialchars($new['nama_petshop'] ?? '-') ?></h4>
                        <div class="card-loc" style="font-size: 0.8rem; color: #888; margin-bottom: 5px;">
                            <i class="fas fa-map-marker-alt" style="color:#f3b83f;"></i>
                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display:inline-block; max-width: 200px; vertical-align: middle;">
                                <?= htmlspecialchars($new['alamat'] ?? 'Lokasi tidak tersedia') ?>
                            </span>
                        </div>
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
                    <?php 
                        $fotoName = $mitra['foto_profil'];
                        $folder = (strpos($fotoName, 'foto_petshop_') !== false) ? 'uploads/mitra/' : 'images/mitra/';
                        $imgUrl = !empty($fotoName) ? BASEURL . '/' . $folder . rawurlencode($fotoName) : BASEURL . '/images/default_petshop.jpg';
                    ?>
                    <div class="mitra-card" style="cursor:pointer;" onclick="openDetailModal('<?= htmlspecialchars($mitra['id_mitra']) ?>')">
                        <img src="<?= $imgUrl ?>" class="card-img" alt="Mitra" onerror="this.src='<?= BASEURL ?>/images/default_petshop.jpg'">
                        
                        <div>
                            <h4 class="card-title"><?= htmlspecialchars($mitra['nama_petshop'] ?? '-') ?></h4>
                            <div class="card-loc">
                                <i class="fas fa-map-marker-alt" style="color:#f3b83f;"></i>
                                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars(substr($mitra['alamat'] ?? '-', 0, 30)) ?>...</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal-overlay" id="mitraModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Detail Mitra</h3>
            <span class="close" onclick="closeModal('mitraModal')">&times;</span>
        </div>
        <div class="modal-body">
            <div id="loadingDetail" style="text-align:center; padding:2rem;"><i class="fas fa-spinner fa-spin fa-2x" style="color:var(--primary);"></i></div>

            <div id="contentDetail" style="display:none;">
                <div class="modal-body-detail">
                    <img id="detailFoto" src="" class="mitra-photo" alt="Foto Mitra" onerror="this.src='<?= BASEURL ?>/images/default_petshop.jpg'">
                    <div class="modal-info" style="flex:1; text-align:left;">
                        <h4 id="detailNama" style="color:var(--primary); margin-top:0;"></h4>
                        <p style="font-size:0.9rem; margin-bottom:5px;"><i class="fas fa-map-marker-alt"></i> <span id="detailAlamat"></span></p>
                        <p style="font-size:0.9rem;"><i class="fas fa-phone-alt"></i> <span id="detailHp"></span></p>
                    </div>
                </div>

                <div class="modal-description" id="detailDeskripsiContainer">
                    <p id="detailDeskripsiText" style="margin:0;"></p>
                </div>

                <h4 style="border-bottom: 1px solid var(--border); padding-bottom: 5px; margin-top:15px;">Daftar Paket</h4>
                <div class="paket-list" id="detailPaketList"></div>

                <h4 style="border-bottom: 1px solid var(--border); padding-bottom: 5px; margin-top:15px;">Ulasan</h4>
                <div class="ulasan-container" id="detailUlasanList"></div>
            </div>
        </div>

        <div class="modal-actions" style="margin-top:20px; text-align:right;">
            <button class="btn-secondary-paw" onclick="closeModal('mitraModal')">Tutup</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="bookingModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Buat Booking Baru 🐾</h2>
            <span class="close" onclick="closeModal('bookingModal')">&times;</span>
        </div>
        <form id="bookingForm" action="<?= BASEURL ?>/Booking/proses_booking" method="POST">
            
            <input type="hidden" id="input_id_mitra" name="id_mitra">

            <div class="form-group">
                <label>Pilih Penitipan (Mitra)</label>
                <input type="text" id="display_nama_mitra" readonly style="background:#eee; color:#555;">
            </div>

            <div class="form-group">
                <label for="tgl_mulai">Tanggal Mulai Penitipan</label>
                <input type="date" id="tgl_mulai" name="tgl_mulai" required>
            </div>

            <div class="form-group">
                <label for="tgl_selesai">Tanggal Selesai Penitipan</label>
                <input type="date" id="tgl_selesai" name="tgl_selesai" required>
            </div>

            <div class="form-group">
                <label for="paket">Pilih Paket</label>
                <select id="paket" name="id_paket" required>
                    <option value="">-- Pilih Paket --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="total_harga">Total Harga (Estimasi)</label>
                <input type="text" id="total_harga" readonly placeholder="Pilih paket & tanggal dulu...">
            </div>

            <div class="form-group">
                <label>Kucing yang Akan Dititipkan</label>
                <div id="cat-container">
                    <div class="cat-section" data-cat-id="1">
                        <h3>Kucing 1</h3>
                        <div class="form-group">
                            <label>Nama Kucing</label>
                            <input type="text" name="kucing[1][nama]" required placeholder="Contoh: Mochi">
                        </div>
                        <div class="form-group">
                            <label>Ras</label>
                            <input type="text" name="kucing[1][ras]" required placeholder="Contoh: Persian">
                        </div>
                        <div style="display:flex; gap:10px;">
                            <div style="width:50%;">
                                <label>Umur (Thn)</label>
                                <input type="number" name="kucing[1][umur]" required>
                            </div>
                            <div style="width:50%;">
                                <label>Gender</label>
                                <select name="kucing[1][jenis_kelamin]" required>
                                    <option value="Jantan">Jantan</option>
                                    <option value="Betina">Betina</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:10px;">
                            <label>Keterangan</label>
                            <textarea name="kucing[1][keterangan]" rows="2" placeholder="Makanan khusus, dll"></textarea>
                        </div>
                    </div>
                </div>
                <button type="button" id="addCatBtn" class="btn-secondary-paw" style="font-size:0.8rem; margin-top:5px;">+ Tambah Kucing Lainnya</button>
            </div>

            <div class="modal-actions" style="margin-top:20px; text-align:right;">
                <button type="button" class="btn-secondary-paw" onclick="closeModal('bookingModal')">Batal</button>
                <button type="submit" class="btn-primary-paw">Simpan Booking</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    let currentMitraData = null; 

    function openDetailModal(idMitra) {
        // RESET MODAL
        $('#mitraModal').addClass('open');
        $('#loadingDetail').show();
        $('#contentDetail').hide();
        $('#detailPaketList').empty();
        $('#detailUlasanList').empty();

        $.ajax({
            url: '<?= BASEURL ?>/DashboardCustomer/getMitraDetailJson/' + idMitra,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.error) {
                    alert('Data tidak ditemukan: ' + data.error);
                    closeModal('mitraModal');
                    return;
                }

                currentMitraData = data; 

                // --- DATA SAFEGUARD (Pencegahan Error JS jika data null) ---
                let nama = data.nama_petshop || 'Nama tidak tersedia';
                let alamat = data.alamat || '-';
                let hp = data.no_hp || '-';
                let desc = data.deskripsi || "Tidak ada deskripsi.";
                
                $('#detailNama').text(nama);
                $('#detailAlamat').text(alamat);
                $('#detailHp').text(hp);
                
                // LOGIKA FOTO (JS)
                let fotoName = data.foto_profil;
                let folder = 'images/mitra/';
                if (fotoName && fotoName.indexOf('foto_petshop_') !== -1) {
                    folder = 'uploads/mitra/';
                }
                let imgUrl = '<?= BASEURL ?>/images/default_petshop.jpg';
                if(fotoName) {
                    imgUrl = '<?= BASEURL ?>/' + folder + encodeURIComponent(fotoName);
                }
                $('#detailFoto').attr('src', imgUrl);

                // Logic Deskripsi
                if(desc.length > 100) {
                    let shortDesc = desc.substring(0, 100) + '...';
                    $('#detailDeskripsiText').html(
                        shortDesc + ' <a href="javascript:void(0)" onclick="$(this).parent().text(\'' + desc.replace(/'/g, "\\'") + '\')" style="color:blue;">Lihat Selengkapnya</a>'
                    );
                } else {
                    $('#detailDeskripsiText').text(desc);
                }

                // Logic Paket
                let paketHtml = '';
                if(data.paket && Array.isArray(data.paket) && data.paket.length > 0) {
                    data.paket.forEach(p => {
                        let harga = p.harga ? parseInt(p.harga).toLocaleString('id-ID') : '0';
                        paketHtml += `
                        <div class="paket-item">
                            <strong>${p.nama_paket || 'Paket'}</strong>
                            <span style="color:#f3b83f; font-weight:bold;">Rp ${harga}</span>
                        </div>`;
                    });
                } else {
                    paketHtml = '<p style="text-align:center; color:#888;">Belum ada paket.</p>';
                }
                $('#detailPaketList').html(paketHtml);

                // Logic Ulasan
                let ulasanHtml = '';
                if(data.ulasan && Array.isArray(data.ulasan) && data.ulasan.length > 0) {
                    data.ulasan.forEach(u => {
                        let ratingVal = u.rating ? parseInt(u.rating) : 5;
                        let stars = '<i class="fas fa-star" style="color:#ffc107"></i> '.repeat(ratingVal);
                        let balasan = u.balasan_mitra ? `<div class="balasan-mitra">Balasan: ${u.balasan_mitra}</div>` : '';
                        let namaCust = u.nama_lengkap || 'Customer';
                        
                        ulasanHtml += `
                        <div class="ulasan-item">
                            <div style="display:flex; justify-content:space-between;">
                                <strong>${namaCust}</strong>
                                <span>${stars}</span>
                            </div>
                            <p style="margin:5px 0;">"${u.komentar || ''}"</p>
                            ${balasan}
                        </div>`;
                    });
                } else {
                    ulasanHtml = '<p style="text-align:center; color:#888;">Belum ada ulasan.</p>';
                }
                $('#detailUlasanList').html(ulasanHtml);

                // TAMPILKAN
                $('#loadingDetail').hide();
                $('#contentDetail').fadeIn();
            },
            error: function(xhr, status, error) {
                console.error("RAW RESPONSE:", xhr.responseText);
                alert('Gagal memuat detail. Cek console.');
                closeModal('mitraModal');
            }
        });
    }

    // FUNGSI LANJUT BOOKING
    function lanjutKeBooking() {
        if(!currentMitraData) return;
        closeModal('mitraModal'); 
        $('#bookingModal').addClass('open'); 
        $('#display_nama_mitra').val(currentMitraData.nama_petshop);
        $('#input_id_mitra').val(currentMitraData.id_mitra);
        let selectPaket = $('#paket');
        selectPaket.empty();
        selectPaket.append('<option value="">-- Pilih Paket --</option>');
        if(currentMitraData.paket && Array.isArray(currentMitraData.paket)) {
            currentMitraData.paket.forEach(p => {
                selectPaket.append(`<option value="${p.id_paket}" data-harga="${p.harga}">${p.nama_paket} - Rp ${parseInt(p.harga).toLocaleString('id-ID')}</option>`);
            });
        }
    }

    // HITUNG HARGA
    $('#tgl_mulai, #tgl_selesai, #paket').on('change', function() {
        let tglMulai = new Date($('#tgl_mulai').val());
        let tglSelesai = new Date($('#tgl_selesai').val());
        let hargaPaket = $('#paket option:selected').data('harga');
        if(tglMulai && tglSelesai && hargaPaket && tglSelesai >= tglMulai) {
            let diffTime = Math.abs(tglSelesai - tglMulai);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; 
            let jumlahKucing = $('.cat-section').length;
            let total = diffDays * hargaPaket * jumlahKucing;
            $('#total_harga').val(`Rp ${total.toLocaleString('id-ID')} (${diffDays} hari x ${jumlahKucing} kucing)`);
        } else {
            $('#total_harga').val('');
        }
    });

    // TAMBAH KUCING
    $('#addCatBtn').click(function() {
        let index = $('.cat-section').length + 1;
        let html = `
        <div class="cat-section" data-cat-id="${index}">
            <h3>Kucing ${index}</h3>
            <button type="button" class="remove-cat" onclick="$(this).parent().remove();">×</button>
            <div class="form-group">
                <label>Nama Kucing</label>
                <input type="text" name="kucing[${index}][nama]" required>
            </div>
            <div class="form-group">
                <label>Ras</label>
                <input type="text" name="kucing[${index}][ras]" required>
            </div>
            <div style="display:flex; gap:10px;">
                <div style="width:50%;">
                    <label>Umur</label>
                    <input type="number" name="kucing[${index}][umur]" required>
                </div>
                <div style="width:50%;">
                    <label>Gender</label>
                    <select name="kucing[${index}][jenis_kelamin]">
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
            </div>
            <div class="form-group" style="margin-top:10px;">
                <label>Keterangan</label>
                <textarea name="kucing[${index}][keterangan]" rows="2"></textarea>
            </div>
        </div>`;
        $('#cat-container').append(html);
    });

    function closeModal(id) { $('#' + id).removeClass('open'); }
</script>
<div class="ulasan-wrapper">
    
    <div class="ulasan-top-bar">
        <button id="openFormBtn" class="btn-add-new">
            <i class="fa-solid fa-pen-to-square"></i> Tulis Ulasan
        </button>
    </div>

    <div class="review-grid">
        <?php if (!empty($data['ulasan'])): ?>
            <?php foreach ($data['ulasan'] as $u): ?>
                <div class="review-card">
                    <div class="card-header">
                        <div class="stars-display">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fa-solid fa-star <?= ($i <= $u['rating']) ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="review-label">Ulasan Kamu</span>
                    </div>

                    <div class="card-body">
                        <p class="comment-text">"<?= nl2br(htmlspecialchars($u['komentar'])); ?>"</p>
                    </div>

                    <?php if (!empty($u['balasan_mitra'])): ?>
                        <div class="mitra-reply-box">
                            <div class="reply-header">
                                <span><i class="fa-solid fa-store"></i> Balasan Mitra</span>
                                <?php if (isset($u['tgl_balasan_mitra'])): ?>
                                    <small class="reply-date">
                                        <?= date('d M Y', strtotime($u['tgl_balasan_mitra'])); ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <div class="reply-content">
                                <p class="reply-text"><?= nl2br(htmlspecialchars($u['balasan_mitra'])); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="card-actions">
                        <button class="btn-action btn-edit btnEdit"
                            data-id="<?= $u['id_ulasan']; ?>"
                            data-rating="<?= $u['rating']; ?>"
                            data-komentar="<?= htmlspecialchars($u['komentar']); ?>">
                            <i class="fa-solid fa-pencil"></i> Edit
                        </button>

                        <form method="POST" action="<?= BASEURL; ?>/DashboardCustomer/ulasan" style="flex:1;">
                            <input type="hidden" name="mode" value="hapus">
                            <input type="hidden" name="id_ulasan" value="<?= $u['id_ulasan']; ?>">
                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Hapus ulasan ini?');">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-regular fa-comment-dots"></i>
                <p>Belum ada ulasan yang kamu buat.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="popupUlasan" class="modal-overlay">
    <div class="modal-content">
        <span id="closePopup" class="close-modal">&times;</span>
        
        <div class="modal-header">
            <h3><i class="fa-solid fa-star" style="color:#f3b83f;"></i> Beri Nilai</h3>
            <p>Bagaimana pengalaman penitipanmu?</p>
        </div>

        <form method="POST" action="<?= BASEURL; ?>/DashboardCustomer/ulasan" id="formUlasan">
            <input type="hidden" name="mode" id="modeInput" value="baru">
            <input type="hidden" name="id_ulasan" id="idUlasanInput" value="">
            <input type="hidden" name="rating" id="ratingInput" value="0">

            <div class="star-input-wrapper" id="ratingStars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fa-solid fa-star star-item" data-value="<?= $i; ?>"></i>
                <?php endfor; ?>
            </div>
            <p id="ratingText" style="font-size:0.85rem; color:#888; margin-top:-10px; margin-bottom:15px;">Ketuk bintang untuk menilai</p>

            <textarea name="komentar" id="komentarInput" placeholder="Ceritakan pengalamanmu..."></textarea>

            <button type="submit" class="btn-submit" id="btnSubmit">Kirim Ulasan</button>
        </form>
        
        <?php if(!empty($data['flash'])): ?>
            <div class="flash-msg <?= $data['flash']['tipe'] ?>">
                <?= htmlspecialchars($data['flash']['pesan']); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* === 1. CONTAINER === */
    .ulasan-wrapper { max-width: 1200px; margin: 0 auto; width: 100%; }
    
    .ulasan-top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
    .ulasan-top-bar h2 { margin: 0; color: #333; font-size: 1.5rem; }
    .ulasan-top-bar p { margin: 5px 0 0; color: #666; font-size: 0.9rem; }

    .btn-add-new {
        background-color: #f3b83f; color: white; border: none; padding: 12px 20px;
        border-radius: 50px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;
        transition: 0.3s; box-shadow: 0 4px 10px rgba(243, 184, 63, 0.3);
    }
    .btn-add-new:hover { background-color: #e6a02f; transform: translateY(-2px); }

    /* === 2. GRID SYSTEM === */
    .review-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    /* === 3. CARD STYLE === */
    .review-card {
        background: white; border-radius: 16px; padding: 20px;
        border: 1px solid #eee; box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: flex; flex-direction: column; justify-content: space-between;
        position: relative; overflow: hidden; height: 100%;
    }
    .review-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); border-color: #f3b83f; }

    .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .stars-display { color: #eee; font-size: 1.1rem; }
    .stars-display .active { color: #f3b83f; }
    .review-label { font-size: 0.75rem; background: #fff8e1; color: #f3b83f; padding: 4px 10px; border-radius: 20px; font-weight: 600; }

    .card-body { margin-bottom: 15px; flex-grow: 1; }
    /* Fix Text Overflow pada komentar user */
    .comment-text { 
        font-size: 0.95rem; color: #444; line-height: 1.6; font-style: italic; 
        white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;
    }

    /* === BALASAN MITRA FIX (SOLUSI DISINI) === */
    .mitra-reply-box {
        background: #fdf6e7; /* Warna background lebih soft */
        border-left: 4px solid #f3b83f;
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 15px;
        width: 100%; /* Pastikan lebar penuh container */
        box-sizing: border-box; /* Padding tidak nambah lebar */
    }

    .reply-header {
        display: flex; justify-content: space-between; align-items: center;
        font-size: 0.8rem; font-weight: 700; color: #d35400; margin-bottom: 8px;
        border-bottom: 1px dashed #e6ccb2; padding-bottom: 5px;
    }
    
    .reply-content {
        width: 100%;
        overflow: hidden; /* Safety net */
    }

    .reply-text { 
        font-size: 0.9rem; color: #333; line-height: 1.5; margin: 0;
        /* === INI KUNCI AGAR TEKS TIDAK KELUAR === */
        white-space: pre-wrap;       /* Menjaga enter/baris baru */
        word-wrap: break-word;       /* Memutus kata panjang */
        overflow-wrap: break-word;   /* Standar modern untuk putus kata */
        word-break: break-word;      /* Memastikan kata super panjang diputus */
        max-width: 100%;             /* Tidak boleh lebih dari container */
    }
    
    .reply-date { color: #888; font-weight: normal; font-size: 0.75rem; }

    /* Tombol Aksi */
    .card-actions { display: flex; gap: 10px; border-top: 1px solid #f0f0f0; padding-top: 15px; }
    .btn-action {
        flex: 1; padding: 8px; border-radius: 8px; border: none; cursor: pointer;
        font-weight: 600; font-size: 0.85rem; transition: 0.2s;
        display: flex; justify-content: center; align-items: center; gap: 6px;
    }
    .btn-edit { background: #fff3cd; color: #856404; } .btn-edit:hover { background: #ffeeba; }
    .btn-delete { background: #f8d7da; color: #721c24; } .btn-delete:hover { background: #f5c6cb; }

    .empty-state { grid-column: 1 / -1; text-align: center; padding: 40px; color: #999; border: 2px dashed #ddd; border-radius: 15px; }
    .empty-state i { font-size: 3rem; margin-bottom: 10px; opacity: 0.5; }

    /* === 4. MODAL POPUP === */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; padding: 20px; backdrop-filter: blur(3px); }
    .modal-content { background: white; width: 100%; max-width: 450px; padding: 30px; border-radius: 20px; position: relative; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: popUp 0.3s ease-out; }
    .close-modal { position: absolute; top: 15px; right: 20px; font-size: 1.5rem; cursor: pointer; color: #aaa; }
    .star-input-wrapper { font-size: 2.5rem; color: #ddd; cursor: pointer; margin-bottom: 5px; display: flex; justify-content: center; gap: 10px; }
    .star-input-wrapper .active { color: #f3b83f; transform: scale(1.1); }
    textarea { width: 100%; height: 120px; border: 2px solid #eee; border-radius: 12px; padding: 15px; resize: none; font-family: inherit; font-size: 0.95rem; margin-bottom: 20px; outline: none; box-sizing: border-box; }
    textarea:focus { border-color: #f3b83f; }
    .btn-submit { width: 100%; padding: 12px; background: #f3b83f; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; }
    @keyframes popUp { from {transform: scale(0.8); opacity: 0;} to {transform: scale(1); opacity: 1;} }

    /* === 5. MEDIA QUERIES (MOBILE) === */
    @media (max-width: 600px) {
        .ulasan-top-bar { flex-direction: column; text-align: center; }
        .btn-add-new { width: 100%; justify-content: center; }
        .review-grid { grid-template-columns: 1fr; } 
        .modal-content { padding: 20px; }
        .star-input-wrapper { font-size: 2rem; }
    }
</style>

<script>
    const modal = document.getElementById('popupUlasan');
    const stars = document.querySelectorAll('.star-item');
    const ratingInput = document.getElementById('ratingInput');
    const idField = document.getElementById('idUlasanInput');
    const punyaBookingSelesai = <?= $data['punyaBookingSelesai'] ? 'true' : 'false'; ?>;

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            ratingInput.value = index + 1;
            updateStars(index + 1);
        });
    });

    function updateStars(val) {
        stars.forEach((s, i) => {
            if (i < val) s.classList.add('active'); else s.classList.remove('active');
        });
    }

    document.getElementById('openFormBtn').onclick = () => {
        if (!punyaBookingSelesai) {
            Swal.fire({ icon: 'warning', title: 'Akses Ditolak', text: 'Selesaikan pesanan terlebih dahulu.', confirmButtonColor: '#f3b83f' });
            return;
        }
        openModal('baru');
    };

    const closeModal = () => { modal.style.display = 'none'; };
    document.getElementById('closePopup').onclick = closeModal;
    window.onclick = (e) => { if (e.target == modal) closeModal(); };

    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.onclick = (e) => { e.preventDefault(); openModal('perbarui', btn.dataset); };
    });

    function openModal(mode, data = null) {
        modal.style.display = 'flex';
        document.getElementById('modeInput').value = mode;
        document.getElementById('btnSubmit').innerText = (mode === 'baru') ? 'Kirim Ulasan' : 'Simpan Perubahan';
        if (mode === 'baru') {
            document.getElementById('formUlasan').reset(); idField.value = ""; ratingInput.value = 0; updateStars(0);
        } else {
            idField.value = data.id; document.getElementById('komentarInput').value = data.komentar;
            ratingInput.value = data.rating; updateStars(data.rating);
        }
    }
</script>
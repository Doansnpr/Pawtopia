<?php
    // --- LOGIKA PHP TETAP SAMA (AMAN) ---
    $ulasan_list = $data['list_ulasan'] ?? [];
    $statistik   = $data['statistik'] ?? ['rata_rata' => 0, 'total_ulasan' => 0];
    
    $rating_val  = (float) ($statistik['rata_rata'] ?? 0);
    $rating_avg  = number_format($rating_val, 1);
    
    $debug_id_mitra = $_SESSION['id_mitra'] ?? 'TIDAK TERDETEKSI';
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* --- TEMA SESUAI REFERENSI (MODERN ORANGE) --- */
    :root {
        --primary-orange: #FF9F43;
        --primary-orange-dark: #EE801E;
        --primary-orange-light: #FFF2E3;
        --text-dark: #2D3436;
        --text-grey: #636E72;
        --bg-color: #F8F9FD;
        --white: #FFFFFF;
        --shadow-soft: 0 5px 15px rgba(0, 0, 0, 0.05);
        --shadow-hover: 0 8px 25px rgba(255, 159, 67, 0.25);
        --radius-box: 20px;
        --radius-btn: 12px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-color);
        margin: 30px; padding: 0;
        color: var(--text-dark);
    }

    /* --- LAYOUT UTAMA --- */
    .ulasan-container {
        padding: 20px;
        max-width: 100%;
        margin: auto;
    }



    .rating-big {
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1;
        margin-bottom: 5px;
    }

    /* --- 1. HEADER STATISTIK (MODERN ORANGE BLOCK) --- */
    .card-stat-ulasan {
        /* Background Oren Gradasi */
        background: linear-gradient(135deg, #FF9F43 0%, #FF6B6B 100%);
        padding: 30px;
        border-radius: 25px; /* Lebih membulat */
        box-shadow: 0 10px 30px rgba(255, 159, 67, 0.4); /* Bayangan berwarna oren */
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white; /* Semua teks jadi putih */
    }

    /* Aksen Paw di Background (Putih Transparan) */
    .card-stat-ulasan::after {
        content: '\f1b0';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -35px;
        font-size: 160px;
        color: white;
        opacity: 0.1; /* Transparan halus */
        transform: rotate(-20deg);
        pointer-events: none;
    }

    .rating-big {
        font-size: 3.5rem;
        font-weight: 800;
        color: white; /* Angka Putih */
        line-height: 1;
        margin-bottom: 5px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Bintang di atas Background Oren */
    .star-fill-white { color: #ffeaa7; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1)); } /* Kuning muda terang */
    .star-empty-white { color: rgba(255,255,255,0.4); } /* Putih transparan */

    /* Badge Total Ulasan */
    .badge-total {
        background: white;
        color: #FF6B6B; /* Teks oren kemerahan */
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* --- 2. GRID SYSTEM --- */
    .ulasan-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    /* --- 3. KARTU ULASAN (Style Modern) --- */
    .review-card {
        background: var(--white);
        border-radius: var(--radius-box);
        box-shadow: var(--shadow-soft);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid transparent;
        overflow: hidden;
    }

    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange-light);
    }

    .card-body-custom {
        padding: 25px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Header Profile */
    .header-group {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .avatar-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 50%; /* Lingkaran */
        overflow: hidden;
        border: 2px solid var(--primary-orange-light);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-color);
        flex-shrink: 0;
    }

    .avatar-img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-initial { font-weight: 700; font-size: 1.2rem; color: var(--primary-orange); }

    .user-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        line-height: 1.2;
    }

    .review-date {
        font-size: 0.75rem;
        color: var(--text-grey);
        margin-top: 2px;
    }

    /* Komentar Style */
    .review-comment {
        background-color: #fcfcfc;
        border: 1px dashed #e0e0e0;
        padding: 15px;
        border-radius: 12px;
        font-size: 0.9rem;
        color: #555;
        line-height: 1.6;
        flex-grow: 1;
        margin-bottom: 20px;
        position: relative;
    }
    
    .review-comment i {
        color: var(--primary-orange);
        opacity: 0.5;
        margin-right: 5px;
    }

    /* --- 4. BALASAN & FORM (Modern Pill Buttons) --- */
    
    /* Tombol Utama (Mirip referensi .btn-primary) */
    .btn-orange-gradient {
        background: linear-gradient(135deg, #FF9F43, #EE801E);
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 10px rgba(255, 159, 67, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-orange-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 159, 67, 0.4);
        color: white;
    }

    /* Tombol Aksi Kecil (Edit/Hapus) */
    .btn-action-small {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: 0.2s;
        color: white;
        text-decoration: none;
    }
    .btn-edit { background: #f1c40f; box-shadow: 0 2px 5px rgba(241, 196, 15, 0.3); }
    .btn-delete { background: #ff7675; box-shadow: 0 2px 5px rgba(255, 118, 117, 0.3); }
    
    .btn-action-small:hover { transform: translateY(-2px); filter: brightness(1.1); }

    /* Kotak Balasan */
    .reply-container {
        background: var(--primary-orange-light);
        border-left: 4px solid var(--primary-orange);
        padding: 15px;
        border-radius: 0 12px 12px 0;
        margin-top: auto;
    }
    
    /* Input Form Modern */
    .form-reply-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #f1f2f6;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        transition: 0.3s;
        background: #fcfcfc;
        resize: none;
        margin-bottom: 10px;
    }
    .form-reply-input:focus {
        border-color: var(--primary-orange);
        outline: none;
        box-shadow: 0 0 0 4px var(--primary-orange-light);
    }

    /* Animasi Form */
    .inline-reply-form {
        margin-top: 15px;
        border-top: 1px dashed #eee;
        padding-top: 15px;
    }

    /* Helper Colors */
    .text-orange { color: var(--primary-orange) !important; }
</style>

<div class="ulasan-container">
    
    <div class="card-stat-ulasan">
        <div class="d-flex align-items-center gap-4" style="position: relative; z-index: 2;">
            <div class="text-center">
                <div class="rating-big"><?= $rating_avg; ?></div>
                
                <div style="font-size: 0.9rem;">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fas fa-star <?= $i <= round($rating_val) ? 'star-fill-white' : 'star-empty-white'; ?>"></i>
                    <?php endfor; ?>
                </div>
            </div>

            <div style="border-left: 2px solid rgba(255,255,255,0.3); padding-left: 25px; margin-left: 10px;">
                <h5 style="margin: 0 0 5px 0; font-weight: 700; color: white;">Kepuasan Pelanggan</h5>
                <p class="small mb-3" style="color: rgba(255,255,255,0.9);">Statistik rating dari seluruh layanan.</p>
                
                <div class="badge-total">
                    <i class="fas fa-users"></i> Total: <?= $statistik['total_ulasan'] ?? 0; ?> Ulasan
                </div>
            </div>
        </div>
    </div>

    <div>
        <h4 style="font-weight: 700; color: var(--text-dark); margin-bottom: 5px;">
            <i class="far fa-comments text-orange me-2"></i> Ulasan Terbaru
        </h4>
        <p class="text-muted small mb-4">Apa kata pelanggan tentang Pawtopia?</p>

        <?php if (empty($ulasan_list)) : ?>
            
            <div style="text-align: center; padding: 50px; background: white; border-radius: 20px; border: 2px dashed #eee;">
                <i class="fas fa-comment-slash fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                <h6 class="text-muted">Belum ada ulasan masuk.</h6>
            </div>

        <?php else : ?>
            
            <div class="ulasan-grid-container">
                <?php foreach ($ulasan_list as $row) : 
                    // Data setup
                    $nama_lengkap = $row['nama_lengkap'] ?? 'Pelanggan';
                    $inisial      = strtoupper(substr($nama_lengkap, 0, 1));
                    $rating       = (float) ($row['rating'] ?? 0);
                    $tgl_ulasan   = $row['tgl_ulasan'] ?? date('Y-m-d');
                    $komentar     = $row['komentar'] ?? '-';
                    $balasan      = $row['balasan'] ?? '';
                    $tgl_balasan  = $row['tgl_balasan'] ?? null;
                    $id_ulasan    = $row['id_ulasan'];
                    $foto_profil  = $row['foto_profil'] ?? null;
                    $foto_url     = !empty($foto_profil) ? BASEURL.'/public/uploads/profil/'.$foto_profil : null;
                ?>
                
                <div class="review-card">
                    <div class="card-body-custom">
                        
                        <div class="header-group">
                            <div class="avatar-wrapper">
                                <?php if ($foto_url): ?>
                                    <img src="<?= $foto_url; ?>" class="avatar-img" alt="Foto" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="avatar-initial" style="display: none; width:100%; height:100%; align-items:center; justify-content:center;">
                                        <?= $inisial; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="avatar-initial" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                                        <?= $inisial; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div style="flex-grow: 1;">
                                <h6 class="user-name text-truncate" title="<?= htmlspecialchars($nama_lengkap); ?>">
                                    <?= htmlspecialchars($nama_lengkap); ?>
                                </h6>
                                <div class="review-date"><?= date('d F Y', strtotime($tgl_ulasan)); ?></div>
                            </div>

                            <div style="text-align: right;">
                                <div style="color: #FF9F43; font-size: 0.8rem;">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= round($rating) ? 'star-fill' : 'star-empty'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span style="font-weight: 700; color: var(--text-dark); font-size: 0.8rem;"><?= number_format($rating, 1); ?></span>
                            </div>
                        </div>

                        <div class="review-comment">
                            <i class="fas fa-quote-left"></i>
                            <?= htmlspecialchars($komentar); ?>
                        </div>

                        <?php if (!empty($balasan)): ?>
                            
                            <div id="reply-view-<?= $id_ulasan; ?>" class="reply-container shadow-sm mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong class="text-orange small">
                                            <i class="fas fa-store me-1"></i> Respon Mitra
                                        </strong>
                                        <div class="text-muted" style="font-size: 0.65rem;">
                                            <?= date('d/m/y', strtotime($tgl_balasan)); ?>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn-action-small btn-edit btn-edit-reply" 
                                                data-target="reply-form-<?= $id_ulasan; ?>"
                                                data-view="reply-view-<?= $id_ulasan; ?>"
                                                data-val="<?= htmlspecialchars($balasan); ?>"
                                                title="Edit Balasan">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <a href="<?= BASEURL; ?>/DashboardMitra/hapusBalasan/<?= $id_ulasan; ?>" 
                                           class="btn-action-small btn-delete"
                                           onclick="return confirm('Hapus balasan ini?');"
                                           title="Hapus Balasan">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                <p class="mb-0 text-dark small" style="white-space: pre-line; font-size: 0.85rem;">
                                    <?= htmlspecialchars($balasan); ?>
                                </p>
                            </div>

                        <?php else: ?>
                            
                            <div id="btn-reply-wrapper-<?= $id_ulasan; ?>" class="mt-auto pt-2 text-end">
                                <button type="button" class="btn-orange-gradient btn-toggle-reply" 
                                        data-target="reply-form-<?= $id_ulasan; ?>"
                                        data-wrapper="btn-reply-wrapper-<?= $id_ulasan; ?>">
                                    <i class="fas fa-reply"></i> Balas
                                </button>
                            </div>

                        <?php endif; ?>

                        <div id="reply-form-<?= $id_ulasan; ?>" class="inline-reply-form d-none">
                            <form action="<?= BASEURL; ?>/DashboardMitra/balasUlasan" method="POST">
                                <input type="hidden" name="id_ulasan" value="<?= $id_ulasan; ?>">
                                
                                <textarea name="balasan" class="form-reply-input" rows="3" 
                                          placeholder="Tulis balasan terima kasih atau tanggapan..." required></textarea>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn-orange-gradient">
                                        Kirim <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div> 
                </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. Tombol Balas (Show Form)
    const toggleButtons = document.querySelectorAll('.btn-toggle-reply');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const wrapperId = this.getAttribute('data-wrapper');
            const formContainer = document.getElementById(targetId);
            const btnWrapper = document.getElementById(wrapperId);

            if (btnWrapper) btnWrapper.classList.add('d-none');
            if (formContainer) {
                formContainer.classList.remove('d-none');
                const textarea = formContainer.querySelector('textarea');
                if(textarea) { textarea.value = ''; textarea.focus(); }
            }
        });
    });

    // 2. Tombol Edit (Load Data & Show Form)
    const editButtons = document.querySelectorAll('.btn-edit-reply');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target'); 
            const viewId = this.getAttribute('data-view');     
            const val = this.getAttribute('data-val');         

            const formContainer = document.getElementById(targetId);
            const viewContainer = document.getElementById(viewId);

            if (viewContainer) viewContainer.classList.add('d-none');
            if (formContainer) {
                formContainer.classList.remove('d-none');
                const textarea = formContainer.querySelector('textarea');
                if(textarea) { textarea.value = val; textarea.focus(); }
            }
        });
    });

    // 3. Tombol Batal
    const cancelButtons = document.querySelectorAll('.btn-cancel-reply');
    cancelButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');   
            const wrapperId = this.getAttribute('data-wrapper'); 
            const viewId = this.getAttribute('data-view');       
            
            const formContainer = document.getElementById(targetId);
            if (formContainer) formContainer.classList.add('d-none');

            if (wrapperId) {
                const btnWrapper = document.getElementById(wrapperId);
                if(btnWrapper) btnWrapper.classList.remove('d-none');
            }
            if (viewId) {
                const viewContainer = document.getElementById(viewId);
                if(viewContainer) viewContainer.classList.remove('d-none');
            }
        });
    });
});
</script>
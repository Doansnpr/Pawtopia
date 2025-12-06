<?php
    $ulasan_list = $data['list_ulasan'] ?? [];
    $statistik   = $data['statistik'] ?? ['rata_rata' => 0, 'total_ulasan' => 0];
    
    $rating_val  = (float) ($statistik['rata_rata'] ?? 0);
    $rating_avg  = number_format($rating_val, 1);
    
    $debug_id_mitra = $_SESSION['id_mitra'] ?? 'TIDAK TERDETEKSI';
?>

<style>
    /* CSS GRID SYSTEM */
    .ulasan-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .card-stat-ulasan {
        background: #ffffff;
        border: 1px solid #ffe0b2;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    
    .text-orange { color: #fd7e14 !important; }
    .bg-orange { background-color: #fd7e14 !important; }
    
    .star-fill { color: #ffc107; }
    .star-empty { color: #e9ecef; }
    
    /* Kartu Ulasan */
    .review-card {
        background: #fff;
        border: 1px solid #f1f1f1;
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    
    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(253, 126, 20, 0.1);
        border-color: #ffcc80;
    }

    /* Body Kartu */
    .card-body-custom {
        padding: 20px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Avatar */
    .avatar-wrapper {
        width: 45px; 
        height: 45px;
        flex-shrink: 0;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #ffe0b2;
        background-color: #fff3e0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-initial { font-weight: bold; font-size: 1.1rem; color: #ef6c00; }

    .user-name { font-size: 1rem; font-weight: 700; color: #333; margin-bottom: 2px; }
    .review-date { font-size: 0.75rem; color: #999; display: block; margin-top: 2px;}

    .review-comment {
        background-color: #fcfcfc; 
        border: 1px solid #f0f0f0; 
        padding: 12px;
        border-radius: 8px; 
        font-style: italic; 
        color: #555; 
        margin-top: 15px;
        flex-grow: 1; 
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    /* Kotak Balasan */
    .reply-container {
        background-color: #fff8f0; 
        border-left: 3px solid #fd7e14;
        padding: 12px; 
        border-radius: 0 6px 6px 0; 
        margin-top: auto;
        font-size: 0.85rem;
    }
    
    /* Form Balasan Inline */
    .inline-reply-form {
        margin-top: auto;
        padding-top: 10px;
        border-top: 1px dashed #eee;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .header-group {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    /* --- STYLE TOMBOL CUSTOM (SESUAI PERMINTAAN) --- */
    .btn-yellow {
        background: linear-gradient(90deg, #ffc400, #ffb000);
        border: none;
        color: #ffffff;
        font-weight: 600;
        border-radius: 50px;
        padding: 5px 15px;
        font-size: 0.8rem;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(255, 170, 60, 0.18);
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-yellow:hover { transform: translateY(-2px); color: white; }

    .btn-red {
        background: linear-gradient(90deg, #ff3b3b, #e53935);
        border: none;
        color: #ffffff;
        font-weight: 600;
        border-radius: 50px;
        padding: 5px 10px; /* Sedikit lebih kecil untuk tombol hapus */
        font-size: 0.8rem;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(230, 80, 80, 0.12);
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-red:hover { transform: translateY(-2px); color: white; }
    
    /* Tombol Batal/Simpan di Form */
    .btn-orange-solid { background-color: #fd7e14; color: white; border: none; }
    .btn-orange-solid:hover { background-color: #e36d0d; color: white; }
</style>

<div class="container-fluid px-0">
    
    <!-- 1. HEADER STATISTIK -->
    <div class="card card-stat-ulasan p-4">
        <div class="d-flex align-items-center">
            <div class="text-center me-5">
                <h1 class="fw-bold text-dark mb-0 display-4"><?= $rating_avg; ?></h1>
                <div class="mb-2">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fas fa-star <?= $i <= round($rating_val) ? 'star-fill' : 'star-empty'; ?>"></i>
                    <?php endfor; ?>
                </div>
                <span class="badge bg-orange rounded-pill px-3">
                    <i class="fas fa-users me-1"></i> <?= $statistik['total_ulasan'] ?? 0; ?> Ulasan
                </span>
            </div>
            <div class="border-start ps-4 d-none d-md-block">
                <h5 class="fw-bold text-orange">Ringkasan Kepuasan</h5>
                <p class="text-muted small mb-0" style="max-width: 500px;">
                    Rating ini mencerminkan rata-rata kepuasan pelanggan terhadap layanan petshop Anda.
                </p>
            </div>
        </div>
    </div>

    <!-- 2. DAFTAR ULASAN -->
    <div>
        <h5 class="fw-bold mb-0 ps-3 border-start border-4 border-warning text-secondary">
            Ulasan Terbaru
        </h5>

        <?php if (empty($ulasan_list)) : ?>
            
            <div class="text-center py-5 bg-white rounded-4 shadow-sm border mt-3">
                <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada ulasan masuk.</h6>
            </div>

        <?php else : ?>
            
            <!-- CONTAINER GRID UTAMA -->
            <div class="ulasan-grid-container">
                <?php foreach ($ulasan_list as $row) : 
                    // Persiapan Data
                    $nama_lengkap = $row['nama_lengkap'] ?? 'Pelanggan';
                    $inisial      = strtoupper(substr($nama_lengkap, 0, 1));
                    $rating       = (float) ($row['rating'] ?? 0);
                    $tgl_ulasan   = $row['tgl_ulasan'] ?? date('Y-m-d');
                    $komentar     = $row['komentar'] ?? '-';
                    $balasan      = $row['balasan'] ?? '';
                    $tgl_balasan  = $row['tgl_balasan'] ?? null;
                    $id_ulasan    = $row['id_ulasan'];
                    
                    // Foto Profil
                    $foto_profil  = $row['foto_profil'] ?? null;
                    $foto_url     = !empty($foto_profil) ? BASEURL.'/public/uploads/profil/'.$foto_profil : null;
                ?>
                
                <!-- ITEM KARTU -->
                <div class="review-card">
                    <div class="card-body-custom">
                        
                        <!-- Header Kartu -->
                        <div class="header-group">
                            <!-- Avatar -->
                            <div class="avatar-wrapper shadow-sm">
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

                            <!-- Info User -->
                            <div style="flex-grow: 1;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="user-name text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($nama_lengkap); ?>">
                                        <?= htmlspecialchars($nama_lengkap); ?>
                                    </h6>
                                    <span class="review-date"><?= date('d/m/y', strtotime($tgl_ulasan)); ?></span>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="me-2">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= round($rating) ? 'star-fill' : 'star-empty'; ?>" style="font-size: 0.7rem;"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-muted small fw-bold" style="font-size: 0.75rem;">(<?= number_format($rating, 1); ?>)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Isi Komentar -->
                        <div class="review-comment">
                            "<?= htmlspecialchars($komentar); ?>"
                        </div>

                        <!-- LOGIKA BALASAN -->
                        <?php if (!empty($balasan)): ?>
                            
                            <!-- JIKA SUDAH DIBALAS: Tampilkan Balasan + Tombol Edit/Hapus -->
                            <div id="reply-view-<?= $id_ulasan; ?>" class="reply-container shadow-sm mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong class="text-orange small">
                                            <i class="fas fa-store me-1"></i> Respon Anda
                                        </strong>
                                        <div class="text-muted" style="font-size: 0.65rem;">
                                            <?= date('d/m/y', strtotime($tgl_balasan)); ?>
                                        </div>
                                    </div>
                                    <!-- Tombol Aksi Balasan -->
                                    <div class="d-flex gap-1">
                                        <button class="btn-yellow btn-edit-reply" 
                                                data-target="reply-form-<?= $id_ulasan; ?>"
                                                data-view="reply-view-<?= $id_ulasan; ?>"
                                                data-value="<?= htmlspecialchars($balasan); ?>"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?= BASEURL; ?>/DashboardMitra/hapusBalasan/<?= $id_ulasan; ?>" 
                                           class="btn-red"
                                           onclick="return confirm('Hapus balasan ini?');"
                                           title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                                <p class="mb-0 text-dark small" style="white-space: pre-line; font-size: 0.8rem;">
                                    <?= htmlspecialchars($balasan); ?>
                                </p>
                            </div>

                        <?php else: ?>
                            
                            <!-- JIKA BELUM DIBALAS: Tampilkan Tombol Balas -->
                            <div id="btn-reply-wrapper-<?= $id_ulasan; ?>" class="mt-auto pt-2 text-end">
                                <button type="button" class="btn-yellow btn-toggle-reply" 
                                        data-target="reply-form-<?= $id_ulasan; ?>"
                                        data-wrapper="btn-reply-wrapper-<?= $id_ulasan; ?>">
                                    <i class="fas fa-reply me-1"></i> Balas
                                </button>
                            </div>

                        <?php endif; ?>

                        <!-- FORM BALASAN INLINE (Tersembunyi Default) -->
                        <div id="reply-form-<?= $id_ulasan; ?>" class="inline-reply-form d-none">
                            <form action="<?= BASEURL; ?>/DashboardMitra/balasUlasan" method="POST">
                                <input type="hidden" name="id_ulasan" value="<?= $id_ulasan; ?>">
                                
                                <div class="mb-2">
                                    <textarea name="balasan" class="form-control form-control-sm bg-light" rows="3" 
                                              placeholder="Tulis balasan Anda di sini..." required></textarea>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <!-- Tombol Kirim -->
                                    <button type="submit" class="btn btn-sm btn-orange-solid">
                                        Kirim <i class="fas fa-paper-plane ms-1"></i>
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

    // 1. FUNGSI UNTUK TOMBOL "BALAS" (Munculkan Form)
    const toggleButtons = document.querySelectorAll('.btn-toggle-reply');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const wrapperId = this.getAttribute('data-wrapper');
            
            const formContainer = document.getElementById(targetId);
            const btnWrapper = document.getElementById(wrapperId);

            // Sembunyikan tombol balas
            if (btnWrapper) btnWrapper.classList.add('d-none');

            // Munculkan form
            if (formContainer) {
                formContainer.classList.remove('d-none');
                // Bersihkan textarea (untuk mode balas baru)
                const textarea = formContainer.querySelector('textarea');
                if(textarea) {
                    textarea.value = ''; 
                    textarea.focus();
                }
            }
        });
    });

    // 2. FUNGSI UNTUK TOMBOL "EDIT" (Munculkan Form dengan data lama)
    const editButtons = document.querySelectorAll('.btn-edit-reply');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target'); // ID Form
            const viewId = this.getAttribute('data-view');     // ID Tampilan Balasan
            const val = this.getAttribute('data-val');         // Isi Balasan Lama

            const formContainer = document.getElementById(targetId);
            const viewContainer = document.getElementById(viewId);

            // Sembunyikan tampilan balasan
            if (viewContainer) viewContainer.classList.add('d-none');

            // Munculkan form & isi data
            if (formContainer) {
                formContainer.classList.remove('d-none');
                const textarea = formContainer.querySelector('textarea');
                if(textarea) {
                    textarea.value = val;
                    textarea.focus();
                }
            }
        });
    });

    // 3. FUNGSI TOMBOL "BATAL" (Kembalikan ke kondisi semula)
    const cancelButtons = document.querySelectorAll('.btn-cancel-reply');
    cancelButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');   // ID Form
            const wrapperId = this.getAttribute('data-wrapper'); // ID Tombol Balas (jika mode balas)
            const viewId = this.getAttribute('data-view');       // ID Tampilan Balasan (jika mode edit)
            
            const formContainer = document.getElementById(targetId);
            
            // Sembunyikan form
            if (formContainer) {
                formContainer.classList.add('d-none');
            }

            // Jika mode BALAS BARU -> Munculkan tombol balas
            if (wrapperId) {
                const btnWrapper = document.getElementById(wrapperId);
                if(btnWrapper) btnWrapper.classList.remove('d-none');
            }

            // Jika mode EDIT -> Munculkan tampilan balasan lama
            if (viewId) {
                const viewContainer = document.getElementById(viewId);
                if(viewContainer) viewContainer.classList.remove('d-none');
            }
        });
    });

});
</script>
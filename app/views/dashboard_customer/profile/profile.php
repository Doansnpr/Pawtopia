<style>
    /* KONSEP TEMA OREN LUCU PAWTOPIA */
    :root {
        --paw-orange: #f3b83f;
        --paw-dark: #d99f26;
        --paw-light: #fff5e0;
    }

    /* Container Center */
    .container-fluid {
        padding-right: 15px !important;
        padding-left: 15px !important;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .row {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .col-12 {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 650px;
        margin: 0 auto;
        padding: 157px;
        box-shadow: 0 10px 25px rgba(243, 184, 63, 0.15);
        border: 2px solid var(--paw-light);
        position: relative;
        overflow: hidden;
    }

    /* Hiasan Kucing & Tapak */
    .cat-avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        background: var(--paw-orange);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 7px solid var(--paw-light);
        box-shadow: 0 7px 15px rgba(243, 184, 63, 0.3);
    }
    
    .cat-icon {
        font-size: 70px;
        color: white;
    }

    h1.cat-title {
        color: #555;
        font-weight: 800;
        text-align: center;
        margin-bottom: 5px;
    }

    .cat-subtitle {
        text-align: center;
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 30px;
    }
    
    .cat-subtitle span {
        color: var(--paw-orange);
    }

    /* LAYOUT FORM YANG RAPI - GRID SYSTEM */
    .form-group-custom {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 20px;
        align-items: center;
        margin-bottom: 16px;
    }

    .form-label {
        font-weight: 600;
        color: #666;
        font-size: 0.95rem;
        text-align: right;
        margin: 0;
        padding-right: 10px;
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #eee;
        padding: 11px 15px;
        transition: all 0.3s;
        width: 100%;
        font-size: 0.95rem;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--paw-orange);
        box-shadow: 0 0 0 3px rgba(243, 184, 63, 0.1);
        outline: none;
    }

    .form-control[readonly] {
        background-color: #fafafa;
        color: #aaa;
        cursor: not-allowed;
    }

    .form-control::placeholder {
        color: #bbb;
    }

    /* Tombol dengan Grid Layout */
    .btn-wrapper {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 20px;
        margin-top: 22px;
    }

    .btn-wrapper > div {
        grid-column: 2;
    }

    /* Tombol Lucu */
    .btn-paw {
        background-color: var(--paw-orange);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: bold;
        transition: all 0.2s;
        width: 100%;
        box-shadow: 0 4px 6px rgba(243, 184, 63, 0.2);
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-paw:hover {
        background-color: var(--paw-dark);
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 6px 12px rgba(243, 184, 63, 0.3);
    }

    .btn-paw-danger {
        background-color: #ff6b6b;
        color: white;
        border-radius: 50px;
        width: 100%;
        padding: 12px 30px;
        border: none;
        font-weight: bold;
        transition: all 0.2s;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-paw-danger:hover {
        background-color: #ff5252;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255, 107, 107, 0.3);
    }

    /* Section Header */
    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--paw-orange);
    }

    .section-header h5 {
        margin: 0;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .section-divider {
        margin: 40px 0;
        border: none;
        border-top: 2px dashed #eee;
    }

    /* Alert */
    .paw-alert-container {
        margin-bottom: 20px;
        animation: slideDown 0.5s ease-out;
        width: 100%;
        max-width: 650px;
    }

    .paw-alert {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-left: 10px solid;
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    .paw-alert-success {
        border-color: #00b894;
        background: #f0fff4;
    }
    
    .paw-alert-error {
        border-color: #ff7675;
        background: #fff5f5;
    }

    .paw-alert-icon {
        font-size: 40px;
        margin-right: 20px;
        animation: bounce 1s infinite;
    }

    .paw-alert-content h4 {
        margin: 0;
        font-weight: 800;
        color: #444;
        font-size: 1.1rem;
    }

    .paw-alert-content p {
        margin: 5px 0 0;
        color: #666;
        font-size: 0.95rem;
    }

    /* Animasi */
    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    /* Dekorasi Background */
    .bg-decoration {
        position: absolute;
        top: -20px;
        right: -20px;
        font-size: 150px;
        opacity: 0.05;
        transform: rotate(20deg);
        pointer-events: none;
        color: #000;
    }

    /* Table Styling */
    .table-responsive {
        margin-top: 20px;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background-color: var(--paw-light);
        color: var(--paw-dark);
        font-weight: 700;
        border: none;
        padding: 12px;
    }

    .table td {
        padding: 12px;
        vertical-align: middle;
    }

    /* RESPONSIVE DESIGN */
    
    /* Tablet (768px - 991px) */
    @media (max-width: 991px) {
        .profile-card {
            padding: 35px;
            max-width: 100%;
        }

        .form-group-custom {
            grid-template-columns: 140px 1fr;
            gap: 15px;
        }

        .btn-wrapper {
            grid-template-columns: 140px 1fr;
            gap: 15px;
        }

        .form-label {
            font-size: 0.9rem;
        }

        .form-control {
            font-size: 0.9rem;
            padding: 10px 12px;
        }

        .paw-alert-container {
            max-width: 100%;
        }
    }

    /* Mobile Landscape (576px - 767px) */
    @media (max-width: 767px) {
        .profile-card {
            padding: 25px;
            border-radius: 15px;
        }

        .form-group-custom,
        .btn-wrapper {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .form-label {
            text-align: left;
            padding-right: 0;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .btn-wrapper > div {
            grid-column: 1;
        }

        .cat-avatar-wrapper {
            width: 100px;
            height: 100px;
            border: 5px solid var(--paw-light);
        }

        .cat-icon {
            font-size: 60px;
        }

        h1.cat-title {
            font-size: 1.5rem;
        }

        .cat-subtitle {
            font-size: 0.85rem;
        }

        .section-header {
            margin-bottom: 20px;
        }

        .section-header h5 {
            font-size: 1rem;
        }

        .section-divider {
            margin: 30px 0;
        }

        .paw-alert {
            padding: 15px;
            flex-direction: column;
            text-align: center;
        }

        .paw-alert-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }

        .bg-decoration {
            font-size: 100px;
        }
    }

    /* Mobile Portrait (max 575px) */
    @media (max-width: 575px) {
        .profile-card {
            padding: 20px;
            margin: 0 10px;
        }

        .form-control {
            font-size: 0.875rem;
            padding: 10px 12px;
        }

        .btn-paw,
        .btn-paw-danger {
            padding: 11px 25px;
            font-size: 0.9rem;
        }

        h1.cat-title {
            font-size: 1.3rem;
        }

        .section-header span {
            font-size: 20px !important;
        }

        .paw-alert-content h4 {
            font-size: 1rem;
        }

        .paw-alert-content p {
            font-size: 0.875rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .table th,
        .table td {
            padding: 8px;
            font-size: 0.85rem;
        }

        .badge {
            font-size: 0.75rem;
        }

        .paw-alert-container {
            margin: 0 10px 20px;
        }
    }

    /* Extra Small Mobile (max 400px) */
    @media (max-width: 400px) {
        .profile-card {
            padding: 15px;
            margin: 0 5px;
        }

        .cat-avatar-wrapper {
            width: 90px;
            height: 90px;
        }

        .cat-icon {
            font-size: 50px;
        }

        h1.cat-title {
            font-size: 1.2rem;
        }

        .form-control {
            padding: 9px 10px;
            font-size: 0.85rem;
        }

        .btn-paw,
        .btn-paw-danger {
            padding: 10px 20px;
            font-size: 0.875rem;
        }

        .section-header span {
            font-size: 18px !important;
        }

        .paw-alert-container {
            margin: 0 5px 15px;
        }

        .paw-alert {
            padding: 12px;
        }

        .paw-alert-icon {
            font-size: 35px;
        }
    }
</style>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12"> 

            <?php if (isset($data['flash'])): ?>
                <div class="paw-alert-container">
                    <?php if ($data['flash']['type'] == 'success'): ?>
                        <div class="paw-alert paw-alert-success">
                            <div class="paw-alert-icon">😻</div>
                            <div class="paw-alert-content">
                                <h4>Yey! Berhasil!</h4>
                                <p><?= $data['flash']['msg']; ?></p>
                            </div>
                            <div style="position:absolute; right:-10px; bottom:-10px; font-size:80px; opacity:0.1;">🐾</div>
                        </div>
                    <?php else: ?>
                        <div class="paw-alert paw-alert-error">
                            <div class="paw-alert-icon">😿</div>
                            <div class="paw-alert-content">
                                <h4>Waduh, Gagal...</h4>
                                <p><?= $data['flash']['msg']; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($data['profil']): ?>
            <div class="profile-card">
                <div class="bg-decoration">🐾</div>

                <div class="cat-avatar-wrapper">
                    <div class="cat-icon">🐱</div> 
                </div>

                <h1 class="cat-title">Halo, <?= htmlspecialchars($data['profil']['nama_lengkap']); ?>!</h1>
                <p class="cat-subtitle">Member sejak: <span><?= htmlspecialchars($data['profil']['tgl_daftar']); ?></span></p>

                <!-- Section: Edit Data Diri -->
                <div class="section-header">
                    <span style="font-size: 22px;">📝</span>
                    <h5 style="color: var(--paw-orange);">Edit Data Diri</h5>
                </div>

                <form action="<?= BASEURL; ?>/DashboardCustomer/profil" method="post">
                    <input type="hidden" name="mode" value="update_profile">
                    
                    <!-- Nama Lengkap -->
                    <div class="form-group-custom">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" value="<?= htmlspecialchars($data['profil']['nama_lengkap']); ?>" required>
                    </div>

                    <!-- No. Handphone -->
                    <div class="form-group-custom">
                        <label class="form-label">No. Handphone</label>
                        <input type="text" class="form-control" name="no_hp" value="<?= htmlspecialchars($data['profil']['no_hp']); ?>" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group-custom">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($data['profil']['email']); ?>" required>
                    </div>

                    <!-- Alamat -->
                    <div class="form-group-custom">
                        <label class="form-label">Alamat</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['profil']['alamat']); ?>" readonly placeholder="Belum tersedia">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="form-group-custom">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['profil']['tanggal_lahir']); ?>" readonly>
                    </div>

                    <!-- Button Submit -->
                    <div class="btn-wrapper">
                        <div></div>
                        <div>
                            <button type="submit" class="btn btn-paw">Simpan Perubahan 🐾</button>
                        </div>
                    </div>
                </form>

                <!-- Divider -->
                <hr class="section-divider">

                <!-- Section: Keamanan Akun -->
                <div class="section-header">
                    <span style="font-size: 22px;">🔒</span>
                    <h5 style="color: #ff6b6b;">Keamanan Akun</h5>
                </div>

                <form action="<?= BASEURL; ?>/DashboardCustomer/profil" method="post">
                    <input type="hidden" name="mode" value="update_password">
                    
                    <!-- Password Lama -->
                    <div class="form-group-custom">
                        <label class="form-label">Password Lama</label>
                        <input type="password" class="form-control" name="old_password" required>
                    </div>
                    
                    <!-- Password Baru -->
                    <div class="form-group-custom">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="form-group-custom">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>

                    <!-- Button Submit -->
                    <div class="btn-wrapper">
                        <div></div>
                        <div>
                            <button type="submit" class="btn btn-paw-danger">Ganti Password</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Riwayat Pesanan -->
            <div class="profile-card mt-4">
                <div class="section-header">
                    <span style="font-size: 22px;">📜</span>
                    <h5 style="color: var(--paw-dark);">Riwayat Pesanan Terakhir</h5>
                </div>

                <?php if (!empty($data['riwayat'])): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['riwayat'] as $r): ?>
                                <tr>
                                    <td><?= $r['id_pemesanan'] ?? $r['id_booking'] ?? '-' ?></td>
                                    <td><?= $r['tanggal_pesan'] ?? $r['tgl_booking'] ?? '-' ?></td>
                                    <td>
                                        <span class="badge bg-warning text-dark"><?= $r['status_pesanan'] ?? $r['status'] ?? '-' ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted">Belum ada riwayat pesanan.</p>
                <?php endif; ?>
            </div>

            <?php else: ?>
                <div class="alert alert-danger text-center">Data profil tidak ditemukan. Silakan login ulang.</div>
            <?php endif; ?>

        </div>
    </div>
</div>
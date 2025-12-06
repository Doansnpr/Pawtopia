<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* --- INTEGRASI GLOBAL THEME --- */
    :root {
        --primary-orange: #FF9F43;
        --primary-orange-dark: #EE801E;
        --primary-orange-light: #FFF2E3;
        --primary-orange-super-light: #FFF8F0;
        --text-dark: #2D3436;
        --text-grey: #636E72;
        --bg-color: #F4F7FC;
        --white: #FFFFFF;
        
        --success-bg: #e0f9f4; --success-green: #00b894;
        --info-bg: #e7f5ff; --info-blue: #0984e3;
        --warning-bg: #fff3cd; --warning-text: #856404;
        
        --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.03);
        --shadow-orange: 0 15px 35px rgba(255, 159, 67, 0.2);
        --radius-card: 20px;
    }

    body { 
        font-family: 'Poppins', sans-serif; 
        background-color: var(--bg-color); 
        background-image: radial-gradient(#ffe0b2 1px, transparent 1px);
        background-size: 30px 30px; 
        margin: 0; padding: 0;
        color: var(--text-dark);
        overflow-x: hidden; 
    }

    .reservasi-content { 
        padding: 20px 20px 150px 20px; 
        max-width: 1400px;
        margin: auto;
    }
    
    /* --- HEADER --- */
    .reservasi-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; background: var(--white); padding: 20px 25px;
        border-radius: var(--radius-card); 
        box-shadow: 0 10px 25px rgba(238, 128, 30, 0.05);
        gap: 20px; border: 2px solid #fff; flex-wrap: wrap;
    }

    .reservasi-header h1 {
        font-size: 1.5rem; font-weight: 800; margin: 0;
        display: flex; align-items: center; gap: 12px; color: var(--text-dark);
        white-space: nowrap;
    }

    .header-right-controls { display: flex; gap: 15px; flex: 1; justify-content: flex-end; align-items: center; width: 100%; }
    .search-container { position: relative; flex: 1; max-width: 400px; }
    
    .search-input {
        width: 100%; padding: 12px 45px 12px 20px;
        border: 2px solid #f0f0f0; border-radius: 50px;
        background: #f9f9f9; font-size: 0.9rem; font-family: 'Poppins', sans-serif;
        transition: 0.3s;
    }
    .search-input:focus { outline: none; border-color: var(--primary-orange); background: #fff; box-shadow: var(--shadow-orange); }
    .search-icon-btn { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--primary-orange); background: transparent; border: none; font-size: 1rem; }

    .filter-select {
        padding: 12px 20px; border: 2px solid #f0f0f0; border-radius: 50px;
        background-color: #f9f9f9; font-size: 0.9rem; font-family: 'Poppins', sans-serif; cursor: pointer;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23FF9F43%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat; background-position: right 15px top 50%; background-size: 10px auto;
        padding-right: 35px; min-width: 160px; appearance: none;
    }

    /* --- GRID CARD --- */
    .booking-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; }
    .booking-card {
        background: var(--white); border-radius: var(--radius-card); box-shadow: var(--shadow-soft); 
        border: none; display: flex; flex-direction: column; transition: transform 0.3s; overflow: hidden;
    }
    .booking-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-orange); }

    .booking-header {
        background: linear-gradient(to right, #FFF8F0, #fff); padding: 15px 20px; 
        border-bottom: 1px dashed #ffe0b2; display: flex; justify-content: space-between; align-items: center;
    }
    .booking-info h3 { margin: 0; font-size: 1.1rem; color: var(--text-dark); font-weight: 700; }
    .booking-info span { font-size: 0.8rem; color: var(--primary-orange-dark); font-weight: 600; background: #fff; padding: 2px 8px; border-radius: 6px; border: 1px solid #eee; }
    
    .cat-list-container { padding: 15px; }
    .cat-item-row {
        display: flex; align-items: center; padding: 10px; margin-bottom: 10px;
        background: #fff; border: 1px solid #f0f4f8; border-radius: 12px; transition: 0.2s; cursor: pointer;
    }
    .cat-item-row:hover, .cat-item-row.checked { border-color: var(--primary-orange); background: var(--primary-orange-light); }
    
    .custom-checkbox { width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary-orange); }
    .cat-mini-img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-right: 12px; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    
    .cat-row-info { flex: 1; }
    .cat-row-name { font-weight: 700; font-size: 0.95rem; color: var(--text-dark); }
    .cat-row-ras { font-size: 0.75rem; color: var(--text-grey); }
    
    .btn-manage-row {
        background-color: var(--white); color: var(--primary-orange-dark); border: 1px solid #eee; 
        padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; cursor: pointer; 
        transition: 0.2s; white-space: nowrap;
    }
    .btn-manage-row:hover { background: var(--primary-orange); color: white; border-color: var(--primary-orange); }

    .status-badge { padding: 3px 8px; border-radius: 15px; font-weight: 700; font-size: 0.65rem; display: inline-flex; align-items: center; gap: 4px; margin-top: 3px; }
    .status-badge.rawat { background-color: var(--success-bg); color: var(--success-green); } 
    .status-badge.tunggu { background-color: var(--warning-bg); color: var(--warning-text); } 
    .status-badge.siap { background-color: var(--info-bg); color: var(--info-blue); } 
    .status-badge.selesai { background-color: #f3f4f6; color: #374151; } 

    /* --- BULK ACTION BAR (DESKTOP - DIPERBAIKI) --- */
    .bulk-action-bar {
        position: fixed; 
        bottom: 30px; 
        left: 50%; 
        transform: translateX(-50%) translateY(150%);
        background: rgba(255, 255, 255, 0.98); /* Lebih solid */
        backdrop-filter: blur(12px); 
        border: 2px solid var(--primary-orange); 
        color: var(--text-dark); 
        padding: 12px 30px; /* Padding lebih luas */
        border-radius: 50px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        gap: 20px; 
        box-shadow: 0 15px 40px rgba(255, 120, 0, 0.25); 
        z-index: 2000; 
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        
        /* INI PERBAIKANNYA UTAMANYA: */
        width: fit-content;  /* Lebar menyesuaikan isi */
        min-width: 650px;    /* Lebar minimal agar tidak gepeng */
        max-width: 95vw;     /* Maksimal 95% layar laptop */
        white-space: normal; /* Biar tombol bisa turun kalau mentok */
    }
    .bulk-action-bar.active { transform: translateX(-50%) translateY(0); }
    
    .selected-count { font-weight: 700; font-size: 1rem; color: var(--primary-orange-dark); background: var(--primary-orange-light); padding: 8px 15px; border-radius: 20px; white-space: nowrap;}
    .v-divider { width: 1px; height: 30px; background: #ddd; flex-shrink: 0; }
    
    /* Container Tombol */
    .bulk-buttons { 
        display: flex; 
        gap: 20px; 
        align-items: center; 
        flex-wrap: wrap; /* PENTING: Kalau layar laptop sempit, tombol akan turun ke bawah */
        justify-content: center;
    }
    
    .btn-status-bulk, .btn-act-bulk {
        border: none; padding: 10px 18px; border-radius: 25px; cursor: pointer; 
        font-size: 0.9rem; font-weight: 600; transition: 0.2s; display: flex; align-items: center; gap: 6px; white-space: nowrap; flex-shrink: 0;
    }
    .btn-status-bulk { 
        background: #f8f9fa; color: var(--text-dark); border: 1px solid #eee; 
    }
    .btn-status-bulk:hover { background: var(--primary-orange-light); color: var(--primary-orange); border-color: var(--primary-orange); }
    .btn-act-bulk { background: var(--primary-orange); color: white; box-shadow: 0 4px 10px rgba(255, 159, 67, 0.3); }
    .btn-act-bulk:hover { background: var(--primary-orange-dark); transform: translateY(-2px); }

    /* --- MODAL STYLE --- */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.6); z-index: 3000; backdrop-filter: blur(4px);
        align-items: center; justify-content: center; animation: fadeIn 0.2s;
    }
    .modal-content {
        background: #fff; width: 90%; max-width: 500px; border-radius: 20px; overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3); display: flex; flex-direction: column; 
        max-height: 90vh; animation: slideUp 0.3s;
    }
    .modal-header { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: #fff; }
    .modal-body { padding: 20px; overflow-y: auto; }
    
    .btn-close-icon { 
        background: transparent; border: none; font-size: 1.8rem; color: #b2bec3; 
        cursor: pointer; width: 35px; height: 35px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; padding: 0; 
        line-height: 1; transition: 0.3s; 
    }
    .btn-close-icon:hover { color: #ff7675; background-color: #fff0f0; transform: rotate(90deg); }

    .action-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px; }
    .action-btn { padding: 10px 5px; background: #fdfdfd; border: 1px solid #eee; border-radius: 12px; cursor: pointer; text-align: center; }
    .action-btn:hover { border-color: var(--primary-orange); background: var(--primary-orange-light); }
    .action-btn span { font-size: 1.5rem; display: block; margin-bottom: 5px; }
    .action-btn p { font-size: 0.75rem; margin: 0; font-weight: 600; }

    .timeline-container { border-left: 2px solid #eee; margin-left: 5px; padding-left: 20px; max-height: 200px; overflow-y: auto; }
    .timeline-item { position: relative; margin-bottom: 20px; }
    .timeline-item::before { content: ''; position: absolute; left: -26px; top: 5px; width: 10px; height: 10px; background: #fff; border: 3px solid var(--primary-orange); border-radius: 50%; }

    /* --- RESPONSIVE HP --- */
    @media (max-width: 768px) {
        .reservasi-header { flex-direction: column; align-items: flex-start; gap: 15px; padding: 20px; }
        .header-right-controls { flex-direction: column; width: 100%; gap: 10px; }
        .search-container, .filter-container, .filter-select { width: 100%; max-width: 100%; }
        
        .booking-grid { grid-template-columns: 1fr; }

        /* Bulk Bar Style HP */
        .bulk-action-bar {
            width: 100%; min-width: 100%; border-radius: 20px 20px 0 0;
            bottom: 0; left: 0; transform: translateY(100%);
            flex-direction: column; padding: 20px 15px 30px 15px;
            border: none; border-top: 3px solid var(--primary-orange);
            background: #fff; height: auto; max-height: 70vh;
        }
        .bulk-action-bar.active { transform: translateY(0); }
        .v-divider { display: none; }
        
        .selected-count { width: 100%; text-align: center; margin-bottom: 15px; }

        /* Grid 3 Kolom di HP */
        .bulk-buttons { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; width: 100%; }
        
        .btn-status-bulk, .btn-act-bulk { 
            width: 100%; justify-content: center; font-size: 0.8rem; padding: 12px 5px; 
            flex-direction: column; gap: 5px; 
        }
    }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    .btn-cancel-custom { background: #f1f2f6; color: #636E72; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; }
    .btn-yes-custom { background: var(--primary-orange); color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; }
    .toast { visibility: hidden; background-color: #333; color: #fff; text-align: center; border-radius: 8px; padding: 12px; position: fixed; z-index: 4000; left: 50%; bottom: 30px; transform: translateX(-50%); font-size: 0.9rem; opacity: 0; transition: 0.3s; }
    .toast.show { visibility: visible; opacity: 1; bottom: 80px; }
</style>

<div class="reservasi-content">
    <div class="reservasi-header">
        <h1><i class="fas fa-cat" style="color: var(--primary-orange);"></i> Manajemen Status</h1>
        <div class="header-right-controls">
            <div class="search-container">
                <input type="text" id="searchInput" class="search-input" onkeyup="filterBooking()" placeholder="Cari ID, Nama Pelanggan atau Kucing...">
                <button class="search-icon-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="filter-container">
                <select id="statusFilter" class="filter-select" onchange="filterBooking()">
                    <option value="all">📂 Semua</option>
                    <option value="Menunggu Kedatangan">🕒 Menunggu</option>
                    <option value="Check-In">🏠 Check-In</option>
                    <option value="Siap Dijemput">✅ Siap</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="booking-grid" id="bookingGrid">
        <div id="emptySearch" style="display:none; grid-column:1/-1; text-align:center; padding:50px;">
            <i class="fas fa-search" style="font-size:3rem; color:#eee; margin-bottom:15px;"></i>
            <p style="color:#aaa;">Data tidak ditemukan.</p>
        </div>

        <?php if(empty($data['groupedBookings'])): ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 60px; background: #fff; border-radius: 20px;">
                <h4 style="color: #ccc;">Belum ada tamu hari ini.</h4>
            </div>
        <?php else: ?>
            <?php foreach($data['groupedBookings'] as $booking): ?>
                <?php 
                    $statusList = "";
                    foreach($booking['cats'] as $c) { $statusList .= $c['status_lifecycle'] . ","; }
                ?>
                <div class="booking-card searchable-card" data-statuses="<?= $statusList ?>">
                    <div class="search-payload" style="display:none;">
                        <?= strtolower($booking['id_booking'] . ' ' . $booking['nama_pemilik']); ?>
                        <?php foreach($booking['cats'] as $c) echo strtolower(' ' . $c['nama_kucing']); ?>
                    </div>

                    <div class="booking-header">
                        <div class="booking-info">
                            <h3><?= htmlspecialchars($booking['nama_pemilik']) ?></h3>
                            <span>#<?= $booking['id_booking'] ?></span>
                        </div>
                        <div style="display:flex; align-items:center; gap:5px;">
                            <input type="checkbox" class="custom-checkbox master-checkbox" data-booking="<?= $booking['id_booking'] ?>" onchange="toggleBookingGroup(this)">
                        </div>
                    </div>

                    <div class="cat-list-container">
                        <?php foreach($booking['cats'] as $cat): ?>
                           <?php 
                                $imgUrl = !empty($cat['foto_kucing']) ? BASEURL.'/images/foto_kucing/'.$cat['foto_kucing'] : 'https://placehold.co/100/FFF2E3/EE801E?text=Cat';
                                $uniqueId = $cat['id_booking'].'-'.$cat['id_kucing'];
                                
                                $badgeClass = 'tunggu';
                                if($cat['status_lifecycle'] == 'Check-In') $badgeClass = 'rawat';
                                elseif($cat['status_lifecycle'] == 'Siap Dijemput') $badgeClass = 'siap';
                                elseif($cat['status_lifecycle'] == 'Selesai') $badgeClass = 'selesai';
                            ?>
                            <div class="cat-item-row" id="row-<?= $uniqueId ?>" onclick="triggerCheck('chk-<?= $uniqueId ?>')">
                                <input type="checkbox" class="custom-checkbox cat-checkbox item-chk-<?= $booking['id_booking'] ?>" id="chk-<?= $uniqueId ?>" value="<?= $cat['id_kucing'] ?>" data-booking="<?= $cat['id_booking'] ?>" onclick="event.stopPropagation(); updateBulkState();">
                                
                                <img src="<?= $imgUrl ?>" class="cat-mini-img">
                                
                                <div class="cat-row-info">
                                    <div class="cat-row-name"><?= htmlspecialchars($cat['nama_kucing']) ?></div>
                                    <div class="cat-row-ras"><?= htmlspecialchars($cat['ras']) ?></div>
                                    <span class="status-badge <?= $badgeClass ?>" id="badge-<?= $uniqueId ?>">
                                        <?= $cat['status_lifecycle'] ?>
                                    </span>
                                </div>
                                
                                <button class="btn-manage-row" onclick="event.stopPropagation(); openManageModal(<?= htmlspecialchars(json_encode($cat), ENT_QUOTES, 'UTF-8') ?>)">
                                    <i class="fas fa-cog"></i> Atur
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="bulk-action-bar" id="bulkBar">
    <div class="selected-count"><span id="countSelected">0</span> Dipilih</div>
    <div class="v-divider"></div>
    
    <div class="bulk-buttons">
        <button class="btn-status-bulk" onclick="bulkUpdateStatus('Check-In')">
            <i class="fas fa-home" style="color:var(--success-green)"></i> Check-In
        </button>
        <button class="btn-status-bulk" onclick="bulkUpdateStatus('Siap Dijemput')">
            <i class="fas fa-check-circle" style="color:var(--info-blue)"></i> Siap
        </button>
        <button class="btn-status-bulk" onclick="bulkUpdateStatus('Selesai')">
            <i class="fas fa-flag-checkered" style="color:#555"></i> Selesai
        </button>
        
        <button class="btn-act-bulk" onclick="bulkAction('Makan')"><i class="fas fa-utensils"></i> Makan</button>
        <button class="btn-act-bulk" onclick="bulkAction('Main')"><i class="fas fa-basketball-ball"></i> Main</button>
        <button class="btn-act-bulk" onclick="bulkAction('Grooming')"><i class="fas fa-bath"></i> Mandi</button>
        <button class="btn-act-bulk" onclick="bulkAction('Tidur')"><i class="fas fa-bed"></i> Tidur</button>
    </div>
</div>

<div class="modal-overlay" id="manageModal">
    <div class="modal-content">
        <div class="modal-header">
            <div style="display: flex; align-items: center;">
                <img id="modalCatImage" src="" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; margin-right: 15px; border: 2px solid #eee;">
                <div>
                    <h2 id="modalCatName" style="font-size:1.1rem; margin:0;">Nama</h2>
                    <p id="modalCatRace" style="font-size:0.8rem; margin:0; color:#888;">Ras</p>
                </div>
            </div>
            <button class="btn-close-icon" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div id="customerNoteAlert" style="display:none; background:#fff3cd; color:#856404; padding:10px; border-radius:8px; margin-bottom:15px; font-size:0.85rem;">
                <strong><i class="fas fa-sticky-note"></i> Catatan:</strong> <span id="modalCatNote"></span>
            </div>

            <p style="font-size:0.8rem; font-weight:700; color:#aaa; margin-bottom:5px;">UPDATE STATUS</p>
            <select id="lifecycleStatus" onchange="updateLifecycleStatus()" style="width:100%; padding:10px; border-radius:10px; border:1px solid #ddd; margin-bottom:20px;">
                <option value="Menunggu Kedatangan">🕒 Menunggu Kedatangan</option>
                <option value="Check-In">🏠 Check-In</option>
                <option value="Siap Dijemput">✅ Siap Dijemput</option>
                <option value="Selesai">🏁 Selesai</option>
            </select>

            <p style="font-size:0.8rem; font-weight:700; color:#aaa; margin-bottom:5px;">AKTIVITAS CEPAT</p>
            <div class="action-grid">
                <div class="action-btn" onclick="postActivity('Makan', '')"><span>🍽️</span><p>Makan</p></div>
                <div class="action-btn" onclick="postActivity('Main', '')"><span>🧶</span><p>Main</p></div>
                <div class="action-btn" onclick="postActivity('Tidur', '')"><span>😴</span><p>Tidur</p></div>
                <div class="action-btn" onclick="postActivity('Grooming', '')"><span>🛁</span><p>Mandi</p></div>
            </div>

            <p style="font-size:0.8rem; font-weight:700; color:#aaa; margin-bottom:5px;">RIWAYAT HARI INI</p>
            <div class="timeline-container" id="timelineList">
                <p style="text-align:center; color:#ccc;">Memuat...</p>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="confirmModal" style="z-index: 4000;">
    <div class="modal-content" style="max-width: 350px; text-align: center; border-radius: 20px;">
        <div style="padding: 25px;">
            <div style="background: #fff3e0; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                <i class="fas fa-question" style="font-size: 2rem; color: var(--primary-orange);"></i>
            </div>
            <h3 style="margin: 0 0 10px 0; font-size: 1.2rem;">Konfirmasi</h3>
            <p id="confirmText" style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">Yakin ingin melakukan aksi ini?</p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="btn-cancel-custom" onclick="closeConfirmModal()">Batal</button>
                <button class="btn-yes-custom" onclick="processBulkUpdate()">Ya, Lanjut</button>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast">Aksi berhasil!</div>

<script>
    const BASE_URL = "<?= BASEURL ?>"; 
    let activeBookingId = null;
    let activeCatId = null;
    let pendingBulkStatus = null; // Variabel simpanan status sementara

    const modal = document.getElementById('manageModal');
    const confirmModal = document.getElementById('confirmModal');
    const toast = document.getElementById('toast');

    // --- FUNGSI PENCARIAN & FILTER ---
    function filterBooking() {
        // 1. Ambil nilai input dan filter
        const searchInput = document.getElementById('searchInput');
        const searchText = searchInput.value.toLowerCase().trim();
        
        const statusSelect = document.getElementById('statusFilter');
        const selectedStatus = statusSelect.value;

        // 2. Ambil semua kartu dan elemen empty state
        const cards = document.getElementsByClassName('searchable-card');
        const emptyState = document.getElementById('emptySearch');
        let visibleCount = 0;

        // 3. Loop setiap kartu
        for (let i = 0; i < cards.length; i++) {
            const card = cards[i];
            
            // --- PERBAIKAN LOGIKA SEARCH ---
            // Ambil elemen payload
            const payloadElem = card.querySelector('.search-payload');
            // Pastikan elemen ada, lalu ambil teksnya. Jika tidak ada, anggap string kosong.
            const searchableText = payloadElem ? payloadElem.textContent.toLowerCase() : "";

            // Cek apakah teks pencarian ada di dalam searchableText
            const isTextMatch = searchableText.indexOf(searchText) > -1;

            // --- LOGIKA FILTER STATUS ---
            const cardStatuses = card.getAttribute('data-statuses');
            // Jika pilih 'all', tampilkan semua. Jika tidak, cek apakah status ada di data-statuses
            const isStatusMatch = (selectedStatus === 'all') || (cardStatuses && cardStatuses.indexOf(selectedStatus) > -1);

            // 4. Tentukan Show/Hide
            if (isTextMatch && isStatusMatch) {
                card.style.display = ""; // Reset display (munculkan)
                visibleCount++;
            } else {
                card.style.display = "none"; // Sembunyikan
            }
        }

        // 5. Tampilkan pesan kosong jika tidak ada hasil
        if (visibleCount === 0 && cards.length > 0) {
            emptyState.style.display = "block"; // Munculkan pesan "Data tidak ditemukan"
        } else {
            emptyState.style.display = "none";
        }
    }

    // --- CHECKBOX LOGIC ---
    function toggleBookingGroup(masterChk) {
        const bookingId = masterChk.getAttribute('data-booking');
        const childCheckboxes = document.querySelectorAll(`.item-chk-${bookingId}`);
        childCheckboxes.forEach(chk => chk.checked = masterChk.checked);
        updateBulkState();
    }
    function triggerCheck(chkId) {
        const chk = document.getElementById(chkId);
        if(chk) { chk.checked = !chk.checked; updateBulkState(); }
    }
    function updateBulkState() {
        const allChecked = document.querySelectorAll('.cat-checkbox:checked');
        const count = allChecked.length;
        const bar = document.getElementById('bulkBar');
        document.getElementById('countSelected').textContent = count;
        if (count > 0) bar.classList.add('active'); else bar.classList.remove('active');
    }
    function resetSelection() {
        document.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
        updateBulkState();
    }

    // --- BULK ACTION ---
    async function bulkAction(actionType) {
        const allChecked = document.querySelectorAll('.cat-checkbox:checked');
        if (allChecked.length === 0) return;
        const groups = {}; 
        allChecked.forEach(chk => {
            const bid = chk.getAttribute('data-booking');
            const cid = chk.value;
            if (!groups[bid]) groups[bid] = [];
            groups[bid].push(cid);
        });
        showToast('⏳ Menyimpan aktivitas...');
        for (const [bookingId, catIds] of Object.entries(groups)) {
            try {
                await fetch(`${BASE_URL}/StatusKucing/add_activity`, {
                    method: 'POST', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_booking: bookingId, id_kucing: catIds, jenis: actionType, catatan: 'Bulk update' })
                });
            } catch (e) { console.error(e); }
        }
        showToast(`✅ ${actionType} berhasil dicatat!`);
        resetSelection();
    }

    // --- REVISI: BULK UPDATE DENGAN POP-UP FLASH ---
    function bulkUpdateStatus(newStatus) {
        const allChecked = document.querySelectorAll('.cat-checkbox:checked');
        if (allChecked.length === 0) return;

        // Simpan status yg mau diubah ke variabel global
        pendingBulkStatus = newStatus;

        // Update teks di modal konfirmasi
        document.getElementById('confirmText').textContent = `Ubah status ${allChecked.length} kucing menjadi "${newStatus}"?`;

        // Tampilkan Modal
        confirmModal.style.display = 'flex';
    }

    // Fungsi yang dipanggil saat tombol "Ya, Yakin" ditekan
    async function processBulkUpdate() {
        closeConfirmModal(); // Tutup modal dulu
        
        if (!pendingBulkStatus) return;

        showToast('⏳ Mengubah status...');
        const allChecked = document.querySelectorAll('.cat-checkbox:checked');
        const promises = [];
        
        allChecked.forEach(chk => {
            const bid = chk.getAttribute('data-booking');
            const cid = chk.value;
            const p = fetch(`${BASE_URL}/StatusKucing/update_lifecycle`, {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_booking: bid, id_kucing: cid, status_baru: pendingBulkStatus })
            }).then(() => { updateBadgeUI(bid, cid, pendingBulkStatus); });
            promises.push(p);
        });

        await Promise.all(promises);
        showToast(`✅ Status berhasil diubah!`);
        if(pendingBulkStatus === 'Selesai') setTimeout(() => location.reload(), 1000); 
        resetSelection();
        pendingBulkStatus = null; // Reset
    }

    function closeConfirmModal() {
        confirmModal.style.display = 'none';
    }

    // --- MODAL SINGLE ---
    function openManageModal(catData) {
        activeBookingId = catData.id_booking; activeCatId = catData.id_kucing; 
        document.getElementById('modalCatImage').src = (catData.foto_kucing) ? `${BASE_URL}/images/foto_kucing/${catData.foto_kucing}` : 'https://placehold.co/80';
        document.getElementById('modalCatName').textContent = catData.nama_kucing;
        document.getElementById('modalCatRace').textContent = catData.ras;
        const noteAlert = document.getElementById('customerNoteAlert');
        if (catData.keterangan) { document.getElementById('modalCatNote').textContent = '"' + catData.keterangan + '"'; noteAlert.style.display = 'block'; } 
        else noteAlert.style.display = 'none';
        document.getElementById('lifecycleStatus').value = catData.status_lifecycle; 
        fetchLogs(activeBookingId, activeCatId);
        modal.style.display = 'flex';
    }
    function closeModal() { modal.style.display = 'none'; }
    function getCurrentTime() { const now = new Date(); return `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`; }
    
    function renderLog(log, insertAtTop = false) {
        const timeline = document.getElementById('timelineList');
        const d = document.createElement('div'); d.className = 'timeline-item';
        d.style.animation = "fadeIn 0.5s ease"; d.innerHTML = `<div class="time">${log.jam_format}</div><div class="activity">${log.jenis_aktivitas}</div>`;
        if (insertAtTop) { timeline.prepend(d); timeline.scrollTop = 0; } else { timeline.appendChild(d); }
    }
    
    async function fetchLogs(bid, cid) {
        const timeline = document.getElementById('timelineList'); timeline.innerHTML = '<p style="text-align:center; padding:20px; color:#aaa;">Memuat riwayat...</p>';
        try {
            const res = await fetch(`${BASE_URL}/StatusKucing/get_logs`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id_booking: bid, id_kucing: cid }) });
            const json = await res.json();
            timeline.innerHTML = ''; 
            if(json.data && json.data.length > 0) { json.data.forEach(l => renderLog(l, false)); } 
            else { timeline.innerHTML = '<p style="text-align:center; color:#ccc; padding:20px;">Belum ada aktivitas hari ini.</p>'; }
        } catch(e) { timeline.innerHTML = '<p style="text-align:center; color:red;">Gagal memuat data.</p>'; }
    }

    async function postActivity(type, note) {
        try {
            const timeline = document.getElementById('timelineList');
            if (timeline.querySelector('p')) timeline.innerHTML = '';
            renderLog({ jam_format: getCurrentTime(), jenis_aktivitas: type }, true);
            showToast('✅ Aktivitas disimpan');
            await fetch(`${BASE_URL}/StatusKucing/add_activity`, { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ id_booking: activeBookingId, id_kucing: activeCatId, jenis: type, catatan: note }) });
        } catch(e) { console.error(e); alert('Gagal menyimpan ke server.'); }
    }

    async function updateLifecycleStatus() {
         const newSt = document.getElementById('lifecycleStatus').value;
         try {
             await fetch(`${BASE_URL}/StatusKucing/update_lifecycle`, { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({id_booking: activeBookingId, id_kucing: activeCatId, status_baru: newSt}) });
             showToast('✅ Status Diperbarui'); updateBadgeUI(activeBookingId, activeCatId, newSt);
         } catch(e) { alert('Gagal update status'); }
    }

    function updateBadgeUI(bid, cid, status) {
        const badge = document.getElementById(`badge-${bid}-${cid}`);
        if(badge) {
            badge.textContent = status; badge.className = 'status-badge'; 
            if(status === 'Check-In') badge.classList.add('rawat'); else if(status === 'Siap Dijemput') badge.classList.add('siap');
            else if(status === 'Selesai') badge.classList.add('selesai'); else badge.classList.add('tunggu');
        }
    }
    function showToast(msg) { toast.textContent = msg; toast.className = "toast show"; setTimeout(() => toast.className = toast.className.replace("show", ""), 3000); }
    window.onclick = function(e) { 
        if (e.target == modal) closeModal(); 
        if (e.target == confirmModal) closeConfirmModal();
    }
</script>
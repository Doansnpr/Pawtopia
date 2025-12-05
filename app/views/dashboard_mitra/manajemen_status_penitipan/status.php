<style>
    /* --- INTEGRASI GLOBAL THEME --- */
    :root {
        --primary-orange: #FF9F43;
        --primary-orange-dark: #EE801E;
        --primary-orange-light: #FFF2E3;
        --text-dark: #2D3436;
        --text-grey: #636E72;
        --bg-color: #F8F9FD;
        --white: #FFFFFF;
        --success-bg: #e0f9f4;
        --success-green: #00b894;
        --info-bg: #e7f5ff;
        --info-blue: #0984e3;
        --warning-bg: #fff3cd;
        --warning-text: #856404;
        --shadow-soft: 0 5px 15px rgba(0, 0, 0, 0.05);
        --shadow-hover: 0 8px 25px rgba(255, 159, 67, 0.25);
    }

    body { 
        font-family: 'Poppins', sans-serif; 
        background-color: var(--bg-color); 
        margin: 0; padding: 0; 
        color: var(--text-dark);
    }

    .reservasi-content { padding-bottom: 30px; }
    
    /* Header Container */
    .reservasi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        background: var(--white);
        padding: 20px 30px;
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        margin: 0 30px 25px 30px;
        flex-wrap: wrap; /* Agar responsif */
        gap: 15px;
    }

    .reservasi-header h1 {
        font-size: 1.6rem; font-weight: 700; margin: 0;
        display: flex; align-items: center; gap: 12px; color: var(--text-dark);
    }

    /* Grouping Filter & Search */
    .header-tools {
        display: flex; gap: 15px; align-items: center;
    }

    /* Styling Input & Select (SERAGAM) */
    .search-container { position: relative; width: 250px; }
    
    .custom-input {
        width: 100%;
        padding: 12px 20px;
        border-radius: 12px;
        border: 2px solid #f1f2f6;
        background-color: #fcfcfc;
        outline: none;
        transition: 0.3s;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: var(--text-dark);
    }
    
    .search-input { padding-left: 45px; } /* Khusus search ada padding kiri buat icon */

    .custom-input:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 4px var(--primary-orange-light);
    }

    .search-icon {
        position: absolute; left: 15px; top: 50%;
        transform: translateY(-50%); color: var(--text-grey); font-size: 1rem;
    }

    /* --- GRID KARTU KUCING --- */
    .cat-grid-container { padding: 0 30px; }
    .cat-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 25px;
    }

    .cat-card {
        background: var(--white); border-radius: 20px; padding: 20px;
        border: 1px solid transparent; box-shadow: var(--shadow-soft);
        display: flex; align-items: center; gap: 15px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative; overflow: hidden;
    }
    .cat-card:hover {
        transform: translateY(-5px); box-shadow: var(--shadow-hover);
        border-color: var(--primary-orange-light);
    }

    .cat-img-wrapper {
        width: 80px; height: 80px; border-radius: 15px; overflow: hidden; flex-shrink: 0;
        border: 2px solid #f0f0f0;
    }
    .cat-img { width: 100%; height: 100%; object-fit: cover; }

    .cat-details { flex: 1; min-width: 0; }
    .cat-name { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .cat-race { font-size: 0.85rem; color: var(--text-grey); margin-bottom: 8px; }
    
    .status-badge {
        padding: 6px 15px; border-radius: 30px; font-weight: 700; font-size: 0.75rem;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .status-badge.rawat { background-color: var(--success-bg); color: var(--success-green); } 
    .status-badge.tunggu { background-color: var(--primary-orange-light); color: var(--primary-orange-dark); } 
    .status-badge.siap { background-color: var(--info-bg); color: var(--info-blue); } 

    .btn-manage {
        background: linear-gradient(135deg, #FF9F43, #FF7F50); color: white; border: none; padding: 10px 20px;
        border-radius: 12px; cursor: pointer; font-weight: 600; font-size: 0.85rem; transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(255, 159, 67, 0.3);
    }
    .btn-manage:hover {
        transform: translateY(-2px); box-shadow: 0 6px 15px rgba(255, 159, 67, 0.5); filter: brightness(1.05);
    }

    /* --- MODAL STYLES --- */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.6); z-index: 1040; backdrop-filter: blur(5px);
        align-items: center; justify-content: center; animation: fadeIn 0.2s ease-out;
    }
    .modal-content {
        background: #fff; width: 90%; max-width: 600px; border-radius: 20px; overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3); display: flex; flex-direction: column; max-height: 85vh;
        animation: slideUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { transform: translateY(30px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }

    .modal-header {
        padding: 20px 25px; background: linear-gradient(to right, #FFF2E3, #fff);
        border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;
    }
    .modal-cat-info h2 { margin: 0; font-size: 1.3rem; color: var(--text-dark); font-weight: 700; }
    .modal-cat-info p { margin: 0; font-size: 0.9rem; color: var(--text-grey); }
    
    .btn-close-icon {
        background: transparent; border: none; font-size: 1.8rem; color: #b2bec3; cursor: pointer;
        transition: all 0.3s; width: 35px; height: 35px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; padding: 0; line-height: 1;
    }
    .btn-close-icon:hover { color: #ff7675; background-color: #fff0f0; transform: rotate(90deg); }

    .modal-body { padding: 25px; overflow-y: auto; }
    .section-title { font-size: 0.8rem; font-weight: 700; color: var(--text-grey); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; }

    /* Modal Inputs */
    .status-select {
        width: 100%; padding: 12px 15px; border-radius: 12px;
        border: 2px solid #f1f2f6; font-size: 1rem; font-weight: 600; font-family: 'Poppins', sans-serif;
        color: var(--text-dark); cursor: pointer; transition: 0.2s; background-color: #fcfcfc;
    }
    .status-select:focus { border-color: var(--primary-orange); outline: none; }

    .action-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px; }
    .action-btn {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 15px 10px; background: #fff; border: 1px solid #f0f0f0; border-radius: 15px;
        cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    }
    .action-btn:hover {
        background: var(--primary-orange-light); border-color: var(--primary-orange);
        transform: translateY(-3px); box-shadow: 0 8px 15px rgba(255,166,0,0.15);
    }
    .action-btn span { font-size: 1.8rem; margin-bottom: 5px; }
    .action-btn p { margin: 0; font-size: 0.8rem; font-weight: 600; color: var(--text-dark); }

    .timeline-container {
        border-left: 2px solid #f0f0f0; margin-left: 10px; padding-left: 25px;
        max-height: 250px; overflow-y: auto; padding-right: 10px;
    }
    .timeline-item { position: relative; margin-bottom: 25px; animation: fadeIn 0.3s; }
    .timeline-item::before {
        content: ''; position: absolute; left: -32px; top: 5px; width: 12px; height: 12px; background: var(--white);
        border: 4px solid var(--primary-orange); border-radius: 50%; box-shadow: 0 0 0 3px rgba(255, 159, 67, 0.1);
    }
    .time { font-size: 0.75rem; color: var(--text-grey); margin-bottom: 4px; font-weight: 600; }
    .activity { font-size: 1rem; font-weight: 600; color: var(--text-dark); }
    .note { 
        font-size: 0.9rem; color: #666; margin-top: 5px; line-height: 1.5; 
        background: #f8f9fa; padding: 8px 12px; border-radius: 8px; font-style: italic;
    }

    .toast {
        visibility: hidden; min-width: 300px; background-color: var(--text-dark); color: #fff;
        text-align: center; border-radius: 12px; padding: 16px; position: fixed;
        z-index: 2000; left: 50%; bottom: 30px; transform: translateX(-50%);
        font-size: 0.95rem; opacity: 0; transition: opacity 0.3s, bottom 0.3s; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .toast.show { visibility: visible; opacity: 1; bottom: 50px; }

    .modal-body::-webkit-scrollbar, .timeline-container::-webkit-scrollbar { width: 6px; }
    .modal-body::-webkit-scrollbar-track { background: transparent; }
    .modal-body::-webkit-scrollbar-thumb { background: #dfe6e9; border-radius: 10px; }
    .modal-body::-webkit-scrollbar-thumb:hover { background: #b2bec3; }
</style>

<div class="reservasi-content">
    
    <div class="reservasi-header">
        <h1><i class="fas fa-paw" style="color: var(--primary-orange);"></i> <?= $data['title'] ?></h1>
        
        <div class="header-tools">
            <select id="statusFilter" class="custom-input" style="width: 200px; cursor: pointer;">
                <option value="all">📂 Semua Status</option>
                <option value="menunggu">🕒 Menunggu</option>
                <option value="check-in">🏠 Dalam Perawatan</option>
                <option value="siap">✅ Siap Dijemput</option>
            </select>

            <div class="search-container">
                <span class="search-icon"><i class="fas fa-search"></i></span>
                <input type="text" class="custom-input search-input" id="searchInput" placeholder="Cari nama kucing...">
            </div>
        </div>
    </div>

    <div class="cat-grid-container">
        <div class="cat-grid" id="catContainer">
            <?php if(empty($data['activeCats'])): ?>
                <div id="noDataMessage" style="grid-column: 1/-1; text-align: center; padding: 60px; background: #fff; border-radius: 20px; box-shadow: var(--shadow-soft);">
                    <div style="font-size: 4rem; margin-bottom: 15px; opacity: 0.5;">🐱</div>
                    <h3 style="color: var(--text-dark); margin-bottom: 5px;">Belum ada tamu bulu</h3>
                    <p style="color: var(--text-grey);">Tidak ada kucing yang perlu dirawat saat ini.</p>
                </div>
            <?php else: ?>
                <?php foreach ($data['activeCats'] as $cat): 
                    // Logic Warna Badge
                    $statusLC = $cat['status_lifecycle']; 
                    $badgeClass = 'rawat'; 
                    // Mapping untuk keperluan filtering class di JS nanti
                    $filterClass = 'rawat'; 
                    
                    if (stripos($statusLC, 'Menunggu') !== false) { 
                        $badgeClass = 'tunggu'; 
                        $filterClass = 'menunggu';
                    }
                    else if (stripos($statusLC, 'Siap') !== false) {
                        $badgeClass = 'siap';
                        $filterClass = 'siap';
                    }
                    else if (stripos($statusLC, 'Check-In') !== false || stripos($statusLC, 'Perawatan') !== false) {
                        $filterClass = 'check-in';
                    }
                ?>
                    <div class="cat-card" 
                         data-name="<?= strtolower($cat['nama_kucing']) ?>"
                         data-status="<?= $filterClass ?>"
                         id="card-<?= $cat['id_booking'] . '-' . $cat['id_kucing'] ?>">
                        
                        <div class="cat-img-wrapper">
                            <?php 
                                $fotoName = !empty($cat['foto_kucing']) ? $cat['foto_kucing'] : 'default.png';
                                $imgUrl = BASEURL . '/images/foto_kucing/' . $fotoName;
                            ?>
                            <img src="<?= $imgUrl ?>" alt="Foto" class="cat-img" onerror="this.src='https://placehold.co/80?text=No+Img'">
                        </div>
                        
                        <div class="cat-details">
                            <div class="cat-name"><?= htmlspecialchars($cat['nama_kucing']) ?></div>
                            <div class="cat-race"><?= htmlspecialchars($cat['ras']) ?></div>
                            
                            <span class="status-badge <?= $badgeClass ?>" id="badge-<?= $cat['id_booking'] . '-' . $cat['id_kucing'] ?>">
                                  <?= $statusLC ?>
                            </span>
                        </div>
                        
                        <button class="btn-manage" onclick='openManageModal(<?= json_encode($cat) ?>)'>Kelola</button>
                    </div>
                <?php endforeach; ?>
                
                <div id="noFilterMatch" style="grid-column: 1/-1; text-align: center; padding: 40px; display: none; color: #888;">
                    <i class="fas fa-filter" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
                    <p>Tidak ada kucing yang cocok dengan filter pencarian.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal-overlay" id="manageModal">
    <div class="modal-content">
        <div class="modal-header">
            <div style="display: flex; align-items: center;">
                <img id="modalCatImage" src="" alt="Foto" style="width: 50px; height: 50px; border-radius: 12px; object-fit: cover; margin-right: 15px; border: 2px solid #eee;">
                <div class="modal-cat-info">
                    <h2 id="modalCatName">Nama Kucing</h2>
                    <p id="modalCatRace">Ras Kucing</p>
                </div>
            </div>
            <button class="btn-close-icon" onclick="closeModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div id="customerNoteAlert" style="display: none; background-color: var(--warning-bg); border: 1px solid #ffeeba; color: var(--warning-text); padding: 15px; border-radius: 12px; margin-bottom: 25px; font-size: 0.9rem;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                    <i class="fas fa-sticky-note"></i> <strong style="font-weight: 600;">Catatan Pemilik:</strong>
                </div>
                <span id="modalCatNote" style="display: block; padding-left: 25px; font-style: italic;">-</span>
            </div>

            <div class="section-title">Update Status Penitipan</div>
            <div class="status-select-wrapper">
                <select class="status-select" id="lifecycleStatus" onchange="updateLifecycleStatus()">
                    <option value="Menunggu Kedatangan">🕒 Menunggu Kedatangan</option>
                    <option value="Check-In">🏠 Check-In / Dalam Perawatan</option>
                    <option value="Siap Dijemput">✅ Siap Dijemput</option>
                    <option value="Selesai">🏁 Selesai / Pulang</option>
                </select>
            </div>

            <hr style="border: 0; border-top: 1px solid #f0f0f0; margin: 25px 0;">

            <div class="section-title">Input Aktivitas Harian</div>
            <div class="action-grid">
                <div class="action-btn" onclick="postActivity('Makan', '')"><span>🍽️</span><p>Makan</p></div>
                <div class="action-btn" onclick="postActivity('Main', '')"><span>🧶</span><p>Main</p></div>
                <div class="action-btn" onclick="postActivity('Tidur', '')"><span>😴</span><p>Tidur</p></div>
                <div class="action-btn" onclick="postActivity('Grooming', '')"><span>🛁</span><p>Grooming</p></div>
            </div>

            <div class="section-title">Riwayat Aktivitas Terkini</div>
            <div class="timeline-container" id="timelineList">
                <p style="text-align:center; color:#999; font-size:0.9rem; padding: 20px;">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast">Aktivitas berhasil dicatat!</div>

<script>
    const BASE_URL = "<?= BASEURL ?>"; 
    let activeBookingId = null;
    let activeCatId = null;
    const modal = document.getElementById('manageModal');
    const toast = document.getElementById('toast');

    // --- LOGIK FILTER BARU ---
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const noMatchMsg = document.getElementById('noFilterMatch');

    function applyFilters() {
        const keyword = searchInput.value.toLowerCase();
        const status = statusFilter.value;
        const cards = document.querySelectorAll('.cat-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const cardStatus = card.getAttribute('data-status');
            
            // Cek Search
            const matchName = name.includes(keyword);
            
            // Cek Status
            let matchStatus = true;
            if (status !== 'all') {
                // Khusus untuk 'check-in', kita harus agak longgar karena text aslinya bisa 'Check-In' atau 'Dalam Perawatan'
                if (status === 'check-in') {
                    // Logic di PHP tadi sudah menandai semua yg 'Check-In'/'Perawatan' sbg 'check-in'
                    matchStatus = (cardStatus === 'check-in'); 
                } else {
                    matchStatus = (cardStatus === status);
                }
            }

            if (matchName && matchStatus) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        if (visibleCount === 0 && cards.length > 0) {
            noMatchMsg.style.display = 'block';
        } else {
            noMatchMsg.style.display = 'none';
        }
    }

    // Event Listeners untuk Filter
    searchInput.addEventListener('keyup', applyFilters);
    statusFilter.addEventListener('change', applyFilters);

    // --- MODAL & AJAX FUNCTIONS (SAMA) ---
    function openManageModal(catData) {
        activeBookingId = catData.id_booking;
        activeCatId = catData.id_kucing; 

        const modalImg = document.getElementById('modalCatImage');
        modalImg.src = (catData.foto_kucing) ? `${BASE_URL}/images/foto_kucing/${catData.foto_kucing}` : 'https://placehold.co/80?text=Cat';
        modalImg.onerror = function() { this.src = 'https://placehold.co/80?text=Err'; };

        document.getElementById('modalCatName').textContent = catData.nama_kucing;
        document.getElementById('modalCatRace').textContent = catData.ras;
        
        const noteAlert = document.getElementById('customerNoteAlert');
        if (catData.keterangan && catData.keterangan.trim()) {
            document.getElementById('modalCatNote').textContent = '"' + catData.keterangan + '"';
            noteAlert.style.display = 'block'; 
        } else {
            noteAlert.style.display = 'none'; 
        }
        
        document.getElementById('lifecycleStatus').value = catData.status_lifecycle; 
        fetchLogs(activeBookingId, activeCatId);
        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
        activeBookingId = null;
    }

    async function fetchLogs(bookingId, catId) {
        const timeline = document.getElementById('timelineList');
        timeline.innerHTML = '<div style="text-align:center; padding:20px; color:#aaa;"><i class="fas fa-spinner fa-spin"></i> Sedang memuat...</div>';
        try {
            const res = await fetch(`${BASE_URL}/StatusKucing/get_logs`, {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_booking: bookingId, id_kucing: catId })
            });
            const json = await res.json();
            timeline.innerHTML = ''; 
            if(json.status === 'success' && json.data.length > 0) {
                json.data.forEach(log => renderLogItem(log.jenis_aktivitas, log.catatan, log.jam_format));
            } else {
                timeline.innerHTML = '<div style="text-align:center; color:#ccc;">Belum ada aktivitas.</div>';
            }
        } catch (e) { timeline.innerHTML = '<p style="color:red; text-align:center;">Gagal memuat data.</p>'; }
    }

    function renderLogItem(act, note, time) {
        const item = document.createElement('div');
        item.className = 'timeline-item';
        let noteHtml = (note && note !== 'null') ? `<div class="note">${note}</div>` : '';
        item.innerHTML = `<div class="time">${time}</div><div class="activity">${act}</div>${noteHtml}`;
        document.getElementById('timelineList').appendChild(item);
    }

    async function postActivity(type, note = '') {
        if (!activeBookingId) return;
        const btn = event.currentTarget;
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:1.5rem; color:var(--primary-orange)"></i>';
        btn.style.pointerEvents = 'none';
        
        try {
            const res = await fetch(`${BASE_URL}/StatusKucing/add_activity`, {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_booking: activeBookingId, id_kucing: activeCatId, jenis: type, catatan: note })
            });
            const json = await res.json();
            if (json.status === 'success') {
                const now = new Date();
                const timeStr = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');
                
                // Hapus pesan kosong jika ada
                const emptyMsg = document.getElementById('timelineList').querySelector('div[style*="text-align:center"]');
                if(emptyMsg) emptyMsg.remove();

                const item = document.createElement('div');
                item.className = 'timeline-item';
                let noteHtml = note ? `<div class="note">${note}</div>` : '';
                item.innerHTML = `<div class="time">${timeStr} - Baru saja</div><div class="activity">${type}</div>${noteHtml}`;
                const list = document.getElementById('timelineList');
                list.insertBefore(item, list.firstChild);
                
                showToast(`✅ ${type} disimpan!`);
            } else throw new Error(json.message);
        } catch (e) { showToast("❌ Gagal menyimpan."); }
        finally { btn.innerHTML = original; btn.style.pointerEvents = 'auto'; }
    }

    async function updateLifecycleStatus() {
        const newStatus = document.getElementById('lifecycleStatus').value;
        try {
            const res = await fetch(`${BASE_URL}/StatusKucing/update_lifecycle`, {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_booking: activeBookingId, id_kucing: activeCatId, status_baru: newStatus })
            });
            const json = await res.json();
            if (json.status === 'success') {
                showToast(`Status diubah: ${newStatus}`);
                const badge = document.getElementById(`badge-${activeBookingId}-${activeCatId}`);
                const card = document.getElementById(`card-${activeBookingId}-${activeCatId}`);
                
                if (badge && card) {
                    badge.textContent = newStatus;
                    badge.className = 'status-badge'; 
                    // Update class badge & data-status card untuk filter real-time
                    if (newStatus.includes('Menunggu')) { 
                        badge.classList.add('tunggu'); 
                        card.setAttribute('data-status', 'menunggu');
                    }
                    else if (newStatus.includes('Siap')) { 
                        badge.classList.add('siap'); 
                        card.setAttribute('data-status', 'siap');
                    }
                    else { 
                        badge.classList.add('rawat'); 
                        card.setAttribute('data-status', 'check-in');
                    }
                }
                if (newStatus === 'Selesai') setTimeout(() => location.reload(), 1000);
            } else showToast("❌ Gagal update.");
        } catch (e) { showToast("❌ Error koneksi."); }
    }

    function showToast(msg) {
        toast.textContent = msg; toast.className = "toast show";
        setTimeout(() => toast.className = toast.className.replace("show", ""), 3000);
    }
    window.onclick = function(e) { if (e.target == modal) closeModal(); }
</script>
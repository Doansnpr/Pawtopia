
<style>
        /* --- INTEGRASI STYLE DARI FITUR RESERVASI (GLOBAL THEME) --- */
        :root {
            --primary-orange: #ffa600;
            --primary-hover: #e69500;
            --text-dark: #2d3436;
            --text-gray: #636e72;
            --bg-light: #f5f6fa;
            --white: #ffffff;
            --border-color: #dee2e6;
            --shadow-soft: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-light); margin: 0; padding: 0; }

        /* Layout Utama (Mengikuti Reservasi) */
        .reservasi-content { padding-bottom: 30px; }
        
        .reservasi-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 30px 30px 0 30px;
        }

        .reservasi-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        /* Search Bar Modern */
        .search-container { position: relative; width: 300px; }
        .search-input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            background-color: #fff;
            outline: none;
            transition: all 0.3s;
            font-size: 0.95rem;
            box-shadow: var(--shadow-soft);
        }
        .search-input:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(255, 166, 0, 0.1);
        }
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-gray);
        }

        /* --- GRID KARTU KUCING (MODERN CARD) --- */
        .cat-grid-container { padding: 0 30px; }
        
        .cat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        
        .cat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #f0f0f0; /* Border halus */
            box-shadow: var(--shadow-soft);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .cat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(255, 166, 0, 0.3);
        }

        .cat-img-wrapper {
            width: 70px; height: 70px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .cat-img { width: 100%; height: 100%; object-fit: cover; }

        .cat-details { flex: 1; min-width: 0; }
        .cat-name { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .cat-race { font-size: 0.85rem; color: var(--text-gray); margin-bottom: 8px; }
        
        /* Status Badge (Mengikuti style Reservasi) */
        .status-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-badge.rawat { background-color: #e2ffe9; color: #00cf30; } /* Hijau */
        .status-badge.tunggu { background-color: #faf4d3; color: #ffc400; } /* Kuning */
        .status-badge.siap { background-color: #ebf2f3; color: #2666c5; } /* Biru */

        /* Tombol Kelola (Style Teal/Biru Muda Modern) */
        .btn-manage {
            background-color: #ffa600;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(226, 175, 5, 0.2);
        }
        .btn-manage:hover {
            background-color: #d17409ff;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(143, 109, 16, 0.3);
        }

        /* --- MODAL STYLES (Sesuai Reservasi) --- */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); z-index: 1040;
            backdrop-filter: blur(3px); /* Efek blur modern */
            align-items: center; justify-content: center;
            animation: fadeIn 0.2s ease-out;
        }

        .modal-content {
            background: #fff; width: 90%; max-width: 550px;
            border-radius: 12px; overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            display: flex; flex-direction: column; max-height: 85vh;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-header {
            padding: 20px 25px;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border-bottom: 1px solid var(--border-color);
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-cat-info h2 { margin: 0; font-size: 1.3rem; color: var(--text-dark); font-weight: 700; }
        .modal-cat-info p { margin: 0; font-size: 0.9rem; color: var(--text-gray); }
        
        .close-modal {
            background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #aaa; transition: 0.2s;
        }
        .close-modal:hover { color: #dc3545; transform: rotate(90deg); }

        .modal-body { padding: 25px; overflow-y: auto; }

        /* Form Elements di Modal */
        .section-title {
            font-size: 0.85rem; font-weight: 700; color: #888;
            margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;
        }

        .status-select {
            width: 100%; padding: 12px 15px; border-radius: 8px;
            border: 1px solid #ced4da; font-size: 1rem; font-weight: 600;
            color: var(--text-dark); cursor: pointer; transition: 0.2s;
            background-color: #fff;
        }
        .status-select:focus { border-color: var(--primary-orange); outline: none; box-shadow: 0 0 0 3px rgba(255,166,0,0.1); }

        /* --- ACTION GRID (Jurnal) --- */
        .action-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 25px;
        }
        .action-btn {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 15px 10px; background: #fff; border: 1px solid #e9ecef;
            border-radius: 10px; cursor: pointer; transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }
        .action-btn:hover {
            background: #fffbf0; border-color: var(--primary-orange);
            transform: translateY(-3px); box-shadow: 0 5px 10px rgba(255,166,0,0.15);
        }
        .action-btn:active { transform: scale(0.98); }
        .action-btn span { font-size: 1.6rem; margin-bottom: 5px; }
        .action-btn p { margin: 0; font-size: 0.85rem; font-weight: 600; color: var(--text-dark); }

        /* --- TIMELINE --- */
        .timeline-container {
            border-left: 2px solid #e9ecef; margin-left: 8px; padding-left: 20px;
            max-height: 250px; overflow-y: auto; padding-right: 5px;
        }
        .timeline-item { position: relative; margin-bottom: 20px; animation: fadeIn 0.3s; }
        .timeline-item::before {
            content: ''; position: absolute; left: -27px; top: 6px;
            width: 12px; height: 12px; background: var(--white);
            border: 3px solid var(--primary-orange); border-radius: 50%;
        }
        .time { font-size: 0.75rem; color: #888; margin-bottom: 3px; font-weight: 600; }
        .activity { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); }
        .note { font-size: 0.9rem; color: #666; margin-top: 2px; line-height: 1.4; }

        /* Toast Notification */
        .toast {
            visibility: hidden; min-width: 280px; background-color: #333; color: #fff;
            text-align: center; border-radius: 8px; padding: 16px; position: fixed;
            z-index: 2000; left: 50%; bottom: 30px; transform: translateX(-50%);
            font-size: 0.95rem; opacity: 0; transition: opacity 0.3s, bottom 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .toast.show { visibility: visible; opacity: 1; bottom: 40px; }

        /* Scrollbar cantik untuk modal */
        .modal-body::-webkit-scrollbar, .timeline-container::-webkit-scrollbar { width: 6px; }
        .modal-body::-webkit-scrollbar-track { background: #f1f1f1; }
        .modal-body::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .modal-body::-webkit-scrollbar-thumb:hover { background: #aaa; }

</style>

<div class="reservasi-content">
    <div class="reservasi-header">
        <h1><?= $data['title'] ?></h1>
        <div class="search-container">
            <span class="search-icon"><i class="fas fa-search"></i></span>
            <input type="text" class="search-input" id="searchInput" placeholder="Cari nama kucing...">
        </div>
    </div>

    <div class="cat-grid-container">
        <div class="cat-grid" id="catContainer">
            <?php if(empty($data['activeCats'])): ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: #fff; border-radius: 12px; border: 1px dashed #ccc;">
                    <span style="font-size: 3rem; display: block; margin-bottom: 10px;">🐱</span>
                    <p style="color: #888; font-size: 1.1rem;">Belum ada kucing yang harus dirawat saat ini.</p>
                </div>
            <?php else: ?>
                <?php foreach ($data['activeCats'] as $cat): 
                    // Logic Warna Badge
                    $statusLC = $cat['status_lifecycle']; 
                    $badgeClass = 'rawat'; 
                    if (stripos($statusLC, 'Menunggu') !== false) $badgeClass = 'tunggu';
                    if (stripos($statusLC, 'Siap') !== false) $badgeClass = 'siap';
                ?>
                    <div class="cat-card" 
                         data-name="<?= strtolower($cat['nama_kucing']) ?>"
                         id="card-<?= $cat['id_booking'] . '-' . $cat['id_kucing'] ?>">
                        
                        <div class="cat-img-wrapper">
                            <?php 
                                $fotoName = !empty($cat['foto_kucing']) ? $cat['foto_kucing'] : 'default.png';
                                $imgUrl = BASEURL . '/images/foto_kucing/' . $fotoName;
                            ?>
                            
                            <img src="<?= $imgUrl ?>" 
                                alt="Foto Kucing" 
                                class="cat-img" 
                                onerror="this.src='https://placehold.co/80?text=No+Img'">
                        </div>
                        
                        <div class="cat-details">
                            <div class="cat-name"><?= htmlspecialchars($cat['nama_kucing']) ?></div>
                            <div class="cat-race"><?= htmlspecialchars($cat['ras']) ?></div>
                            
                            <span class="status-badge <?= $badgeClass ?>" 
                                  id="badge-<?= $cat['id_booking'] . '-' . $cat['id_kucing'] ?>">
                                  <?= $statusLC ?>
                            </span>
                        </div>
                        
                        <button class="btn-manage" onclick='openManageModal(<?= json_encode($cat) ?>)'>
                            Kelola
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal-overlay" id="manageModal">
    <div class="modal-content">
        <div class="modal-header">
            <img id="modalCatImage" src="" alt="Foto" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 15px; border: 2px solid #eee;">
            
            <div class="modal-cat-info">
                <h2 id="modalCatName">Nama Kucing</h2>
                <p id="modalCatRace">Ras Kucing</p>
            </div>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>

        <div class="modal-body">
            
            <div id="customerNoteAlert" style="display: none; background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 25px; font-size: 0.95rem; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                    <span style="font-size: 1.2rem;">📝</span>
                    <strong style="font-weight: 700;">Catatan Pemilik:</strong>
                </div>
                <span id="modalCatNote" style="display: block; padding-left: 32px; font-style: italic; color: #533f03;">-</span>
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

            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            <div class="section-title">Input Aktivitas Harian</div>
            <div class="action-grid">
                <div class="action-btn" onclick="postActivity('Makan', '')">
                    <span>🍽️</span><p>Makan</p>
                </div>
                <div class="action-btn" onclick="postActivity('Main', '')">
                    <span>🧶</span><p>Main</p>
                </div>
                <div class="action-btn" onclick="postActivity('Tidur', '')">
                    <span>😴</span><p>Tidur</p>
                </div>
                <div class="action-btn" onclick="postActivity('Grooming', '')">
                    <span>🛁</span><p>Grooming</p>
                </div>
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
    // --- KONFIGURASI ---
    const BASE_URL = "<?= BASEURL ?>"; 
    
    // --- VARIABEL GLOBAL ---
    let activeBookingId = null;
    let activeCatId = null;
    const modal = document.getElementById('manageModal');
    const toast = document.getElementById('toast');

    // --- 1. BUKA MODAL & LOAD DATA ---
    function openManageModal(catData) {
        activeBookingId = catData.id_booking;
        activeCatId = catData.id_kucing; 

        // --- UPDATE FOTO DI MODAL (BARU) ---
        const modalImg = document.getElementById('modalCatImage');
        // Cek apakah ada nama foto di database
        if (catData.foto_kucing && catData.foto_kucing !== "") {
            // Sesuaikan path ini dengan folder di screenshot kamu
            modalImg.src = `${BASE_URL}/images/foto_kucing/${catData.foto_kucing}`;
        } else {
            // Gambar default jika tidak ada foto
            modalImg.src = 'https://placehold.co/80?text=Cat';
        }
        // Tambahkan handler jika file gambar ternyata korup/hilang fisik filenya
        modalImg.onerror = function() {
            this.src = 'https://placehold.co/80?text=Err';
        };

        // Set Header Teks
        document.getElementById('modalCatName').textContent = catData.nama_kucing;
        document.getElementById('modalCatRace').textContent = catData.ras;
        
        // --- SET CATATAN CUSTOMER ---
        const noteAlert = document.getElementById('customerNoteAlert');
        const noteText = document.getElementById('modalCatNote');

        if (catData.keterangan && catData.keterangan.trim() !== "") {
            noteText.textContent = '"' + catData.keterangan + '"';
            noteAlert.style.display = 'block'; 
        } else {
            noteText.textContent = "";
            noteAlert.style.display = 'none'; 
        }
        
        // Set Dropdown Status
        const statusSelect = document.getElementById('lifecycleStatus');
        statusSelect.value = catData.status_lifecycle; 

        // Fetch Timeline
        fetchLogs(activeBookingId, activeCatId);

        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
        activeBookingId = null;
    }

    // --- 2. AJAX FETCH LOGS (Timeline) ---
    async function fetchLogs(bookingId, catId) {
        const timelineContainer = document.getElementById('timelineList');
        timelineContainer.innerHTML = '<div style="text-align:center; padding:20px; color:#aaa;">⏳ Sedang memuat riwayat...</div>';

        try {
            const response = await fetch(`${BASE_URL}/StatusKucing/get_logs`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_booking: bookingId, id_kucing: catId })
            });

            const result = await response.json();
            
            timelineContainer.innerHTML = ''; 

            if(result.status === 'success' && result.data.length > 0) {
                result.data.forEach(log => {
                    renderLogItem(log.jenis_aktivitas, log.catatan, log.jam_format);
                });
            } else {
                timelineContainer.innerHTML = '<div style="text-align:center; padding:30px; color:#ccc; font-style:italic;">Belum ada aktivitas tercatat hari ini.</div>';
            }

        } catch (error) {
            console.error('Error fetching logs:', error);
            timelineContainer.innerHTML = '<p style="color:red; text-align:center;">Gagal memuat data.</p>';
        }
    }

    // Helper: Render 1 baris timeline
    function renderLogItem(activity, note, timeStr) {
        const timelineContainer = document.getElementById('timelineList');
        const newItem = document.createElement('div');
        
        // Cek jika note itu "undefined", null, atau string kosong
        let displayNote = '';
        if (note && note !== 'undefined' && note !== 'null') {
            displayNote = note;
        }

        newItem.classList.add('timeline-item');
        
        // Kita hanya render div class="note" jika displayNote ada isinya
        let noteHtml = displayNote ? `<div class="note">${displayNote}</div>` : '';

        newItem.innerHTML = `
            <div class="time">${timeStr}</div>
            <div class="activity">${activity}</div>
            ${noteHtml}
        `;
        timelineContainer.appendChild(newItem);
    }

    // --- 3. AJAX POST AKTIVITAS (PERBAIKAN DISINI) ---
    async function postActivity(type, defaultNote = '') { // Tambah default = ''
        if (!activeBookingId) {
            alert("Error: ID Booking hilang. Silakan refresh halaman.");
            return;
        }

        const btn = event.currentTarget;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span style="font-size:1.5rem;">⏳</span><p>...</p>'; 
        btn.style.pointerEvents = 'none'; 

        const now = new Date();
        const timeNow = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        
        try {
            const response = await fetch(`${BASE_URL}/StatusKucing/add_activity`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_booking: activeBookingId,
                    id_kucing: activeCatId,
                    jenis: type,       
                    catatan: defaultNote // Ini sekarang akan mengirim string kosong ''
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error("Server Error: " + errorText);
            }

            const result = await response.json();

            if (result.status === 'success') {
                const emptyMsg = document.getElementById('timelineList').querySelector('div[style*="text-align:center"]');
                if(emptyMsg) emptyMsg.remove();

                const list = document.getElementById('timelineList');
                const newItem = document.createElement('div');
                newItem.classList.add('timeline-item');
                
                // Logika tampilan agar tidak muncul "undefined" saat baru saja ditambah
                let displayNote = (defaultNote && defaultNote !== 'undefined') ? defaultNote : '';
                let noteHtml = displayNote ? `<div class="note">${displayNote}</div>` : '';

                newItem.innerHTML = `
                    <div class="time">${timeNow} - Baru saja</div>
                    <div class="activity">${type}</div>
                    ${noteHtml}
                `;
                list.insertBefore(newItem, list.firstChild);

                showToast(`✅ ${type} berhasil disimpan!`);
            } else {
                throw new Error(result.message); 
            }
        } catch (error) {
            console.error("Detail Error:", error);
            alert("GAGAL MENYIMPAN:\n" + error.message); 
        } finally {
            btn.innerHTML = originalContent;
            btn.style.pointerEvents = 'auto';
        }
    }

    // --- 4. AJAX UPDATE STATUS LIFECYCLE ---
    async function updateLifecycleStatus() {
        const newStatus = document.getElementById('lifecycleStatus').value;

        try {
            const response = await fetch(`${BASE_URL}/StatusKucing/update_lifecycle`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_booking: activeBookingId,
                    id_kucing: activeCatId,
                    status_baru: newStatus
                })
            });

            const result = await response.json();

            if (result.status === 'success') {
                showToast(`Status diubah: ${newStatus}`);
                
                const badgeId = `badge-${activeBookingId}-${activeCatId}`;
                const badgeEl = document.getElementById(badgeId);
                if (badgeEl) {
                    badgeEl.textContent = newStatus;
                    badgeEl.className = 'status-badge'; 
                    if (newStatus.includes('Menunggu')) badgeEl.classList.add('tunggu');
                    else if (newStatus.includes('Siap')) badgeEl.classList.add('siap');
                    else badgeEl.classList.add('rawat');
                }

                if (newStatus === 'Selesai') {
                    setTimeout(() => location.reload(), 1000);
                }

            } else {
                showToast("❌ Gagal update status.");
            }
        } catch (error) {
            showToast("❌ Error koneksi.");
        }
    }

    function showToast(msg) {
        toast.textContent = msg;
        toast.className = "toast show";
        setTimeout(() => toast.className = toast.className.replace("show", ""), 3000);
    }

    window.onclick = function(event) {
        if (event.target == modal) closeModal();
    }

    document.getElementById('searchInput').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('.cat-card').forEach(card => {
            const name = card.getAttribute('data-name');
            card.style.display = name.includes(val) ? 'flex' : 'none';
        });
    });
</script>

</html>
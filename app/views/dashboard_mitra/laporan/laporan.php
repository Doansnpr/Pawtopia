<style>
    :root {
        --primary-orange: #fb8c00;
        --dark-orange: #ef6c00;
        --excel-green: #217346;
        --text-dark: #2d3436;
        --text-muted: #636e72;
    }

    .report-container {
        font-family: 'Segoe UI', Inter, sans-serif;
        color: var(--text-dark);
        background-color: var(--bg-light);
        padding: 20px;
        border-radius: 8px;
    }

    /* Header & Filter */
    .report-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 25px; gap: 20px; }
    .header-text h2 { margin: 0 0 5px 0; font-size: 1.5rem; font-weight: 700; color: var(--text-dark); }
    .header-text p { margin: 0; font-size: 0.9rem; color: var(--text-muted); }
    .header-toolbar { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; }
    .filter-group { display: flex; gap: 10px; background: var(--card-bg); padding: 8px 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); align-items: flex-end; }
    .input-wrapper { display: flex; flex-direction: column; }
    .input-wrapper label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 2px; }
    .input-wrapper input { border: 1px solid #ddd; border-radius: 4px; padding: 6px 10px; font-size: 0.9rem; outline: none; transition: border-color 0.2s; }
    .input-wrapper input:focus { border-color: var(--primary-orange); }

    /* Buttons */
    .btn-filter { background-color: var(--primary-orange); color: white; border: none; width: 40px; height: 38px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 2px 4px rgba(251, 140, 0, 0.3); }
    .btn-filter:hover { background-color: var(--dark-orange); transform: translateY(-2px); }
    .btn-excel { background-color: var(--excel-green); color: white; border: none; width: 40px; height: 38px; border-radius: 6px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 2px 4px rgba(33, 115, 70, 0.3); }
    .btn-excel:hover { background-color: #1a5c38; transform: translateY(-2px); }

    /* KPI Grid */
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .kpi-card { background: var(--card-bg); padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 15px; border: 1px solid #eee; transition: all 0.3s ease; position: relative; overflow: hidden; }
    .kpi-card:hover { transform: translateY(-3px); border-color: #ffe0b2; box-shadow: 0 6px 15px rgba(251, 140, 0, 0.1); }
    .kpi-card:hover::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--primary-orange); }
    .kpi-icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
    
    .bg-blue-light { background: #e3f2fd; color: #1565c0; }
    .bg-green-light { background: #e8f5e9; color: #2e7d32; }
    .bg-orange-light { background: #fff3e0; color: #ef6c00; }
    .bg-red-light { background: #ffebee; color: #c62828; }

    .kpi-content { flex: 1; }
    .kpi-title { font-size: 0.85rem; color: var(--text-muted); font-weight: 600; display: block; margin-bottom: 4px; }
    .kpi-number { font-size: 1.5rem; font-weight: 700; color: var(--text-dark); margin: 0; line-height: 1.2; }
    .trend-badge { font-size: 0.75rem; font-weight: 600; margin-top: 5px; display: inline-block; }
    .text-up { color: #27ae60; }
    .text-down { color: #e74c3c; }
    .progress-track { height: 6px; background: #eee; border-radius: 3px; margin-top: 8px; overflow: hidden; }
    .progress-fill { height: 100%; background: var(--primary-orange); border-radius: 3px; }

    /* Table */
    .table-section { background: var(--card-bg); border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eee; }
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; }
    .table-responsive { width: 100%; overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; min-width: 700px; }
    .custom-table th { text-align: left; padding: 12px 15px; background: #f8f9fa; color: #666; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; border-bottom: 2px solid #eee; }
    .custom-table td { padding: 14px 15px; border-bottom: 1px solid #eee; color: #444; font-size: 0.95rem; vertical-align: middle; }
    .custom-table tr:hover { background-color: #fafafa; }

    .btn-detail { background-color: #e3f2fd; color: #1565c0; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: 0.2s; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; }
    .btn-detail:hover { background-color: #1565c0; color: white; }

    /* --- MODAL STYLES --- */
    .modal-backdrop { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
    .modal-content { background: white; width: 90%; max-width: 500px; padding: 25px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: slideDown 0.3s ease-out; position: relative; }
    @keyframes slideDown { from {transform: translateY(-20px); opacity: 0;} to {transform: translateY(0); opacity: 1;} }
    .modal-close { position: absolute; top: 15px; right: 20px; font-size: 1.5rem; cursor: pointer; color: #aaa; transition: 0.2s; }
    .modal-close:hover { color: #e74c3c; }

    /* Scrollbar Cantik untuk Modal */
    .cat-list-container { max-height: 400px; overflow-y: auto; padding: 10px 5px; }
    .cat-list-container::-webkit-scrollbar { width: 6px; }
    .cat-list-container::-webkit-scrollbar-track { background: #f1f1f1; }
    .cat-list-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

    /* --- KARTU KUCING (CARD STYLE) --- */
    .cat-card {
        background: #fff; border: 1px solid #f0f0f0; border-radius: 12px;
        padding: 15px; margin-bottom: 15px; display: flex; align-items: center;
        gap: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: transform 0.2s, box-shadow 0.2s;
    }
    .cat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.08); border-color: var(--primary-orange); }

    .cat-avatar-large {
        width: 80px; height: 80px; border-radius: 50%; object-fit: cover;
        border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background-color: #eee;
    }

    .cat-details { flex: 1; }
    .cat-name-title { margin: 0 0 8px 0; font-size: 1.1rem; font-weight: 700; color: #2d3436; }

    .cat-badges { display: flex; flex-wrap: wrap; gap: 8px; }
    .badge-info {
        padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 4px;
    }
    
    .badge-ras { background-color: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }
    .badge-sex-male { background-color: #e3f2fd; color: #1976d2; border: 1px solid #bbdefb; }
    .badge-sex-female { background-color: #fce4ec; color: #c2185b; border: 1px solid #f8bbd0; }
    .badge-age { background-color: #f3e5f5; color: #7b1fa2; border: 1px solid #e1bee7; }
</style>

<div class="report-container">
    
    <div class="report-header">
        <div class="header-text">
            <h2>Laporan</h2>
            <p>Ringkasan aktivitas bisnis dan keuangan</p>
        </div>
        <div class="header-toolbar">
            <form method="GET" action="<?= BASEURL ?>/DashboardMitra" class="filter-group">
                <input type="hidden" name="page" value="laporan">
                
                <div class="input-wrapper">
                    <label>Dari Tanggal</label>
                    <input type="date" name="start_date" value="<?= $data['laporan']['start_date'] ?>" required>
                </div>
                
                <div class="input-wrapper">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="end_date" value="<?= $data['laporan']['end_date'] ?>" required>
                </div>

                <button type="submit" class="btn-filter" title="Filter Data">
                    <i class="fas fa-search"></i>
                </button>

                <button type="button" onclick="exportTableToExcel('transaksiTable', 'Laporan_Keuangan_PowTopia')" class="btn-excel" title="Download Excel">
                    <i class="fas fa-file-excel"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon-box bg-blue-light">💰</div>
            <div class="kpi-content">
                <span class="kpi-title">Pendapatan Bersih</span>
                <h3 class="kpi-number">Rp <?= number_format($data['laporan']['financial']['pendapatan'], 0, ',', '.') ?></h3>
                <div class="trend-badge <?= ($data['laporan']['growth'] >= 0) ? 'text-up' : 'text-down' ?>">
                    <?= ($data['laporan']['growth'] >= 0) ? '▲' : '▼' ?> <?= abs($data['laporan']['growth']) ?>% 
                    <span style="font-weight: normal; color: #999;">vs bulan lalu</span>
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon-box bg-green-light">✅</div>
            <div class="kpi-content">
                <span class="kpi-title">Booking Selesai</span>
                <h3 class="kpi-number"><?= $data['laporan']['financial']['booking_selesai'] ?></h3>
                <span style="font-size: 0.8rem; color: #999;">Pesanan berhasil</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon-box bg-orange-light">🏠</div>
            <div class="kpi-content">
                <span class="kpi-title">Tingkat Hunian</span>
                <h3 class="kpi-number"><?= $data['laporan']['occupancy']['rate'] ?>%</h3>
                <div class="progress-track">
                    <div class="progress-fill" style="width: <?= $data['laporan']['occupancy']['rate'] ?>%;"></div>
                </div>
                <span style="font-size: 0.8rem; color: #999;">
                    <?= $data['laporan']['occupancy']['terisi'] ?> dari <?= $data['laporan']['occupancy']['kapasitas'] ?> slot terisi
                </span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon-box bg-red-light">❌</div>
            <div class="kpi-content">
                <span class="kpi-title">Dibatalkan</span>
                <h3 class="kpi-number"><?= $data['laporan']['financial']['booking_batal'] ?></h3>
                <span style="font-size: 0.8rem; color: #999;">Pesanan gagal</span>
            </div>
        </div>
    </div>

    <div class="table-section">
        <div class="section-header">
            <h3 style="margin:0; font-size:1.1rem; color:var(--text-dark);">Riwayat Transaksi</h3>
        </div>

        <div class="table-responsive">
            <table class="custom-table" id="transaksiTable">
                <thead>
                    <tr>
                        <th>ID Booking</th>
                        <th>Durasi Inap</th>
                        <th>Paket</th>
                        <th>Pelanggan</th>
                        <th style="text-align:center;">Jml Kucing</th>
                        <th style="text-align:right;">Total Harga</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['laporan']['history'])): ?>
                        <tr><td colspan="7" style="text-align:center; padding: 30px; color: #999;"><i>Tidak ada data transaksi pada periode ini.</i></td></tr>
                    <?php else: ?>
                        <?php foreach($data['laporan']['history'] as $row): 
                            $start = date('d M', strtotime($row['tgl_mulai']));
                            $end   = date('d M Y', strtotime($row['tgl_selesai']));
                            $durasi = $start . ' - ' . $end;
                        ?>
                            <tr>
                                <td style="font-weight:bold; color:var(--primary-orange);">#<?= $row['id_booking'] ?></td>
                                <td><?= $durasi ?></td>
                                <td>
                                    <span style="background:#f1f2f6; padding:3px 8px; border-radius:4px; font-size:0.85rem;">
                                        <?= htmlspecialchars($row['paket']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td style="text-align:center; font-weight:bold;"><?= $row['jumlah_kucing'] ?></td>
                                <td style="text-align:right; font-weight:bold;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td style="text-align:center;">
                                    <button class="btn-detail" onclick="openDetailModal('<?= $row['id_booking'] ?>')"><i class="fas fa-eye"></i> Detail</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="detailModal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <h3 style="margin-top:0; color:var(--primary-orange); border-bottom:1px solid #eee; padding-bottom:10px;">Detail Kucing</h3>
        <p style="color:#666; font-size:0.9rem;">Daftar kucing dalam booking <span id="modalBookingId" style="font-weight:bold;">#...</span></p>
        <div id="modalContentList" class="cat-list-container">
            <div style="text-align:center; padding:20px;">Memuat data...</div>
        </div>
    </div>
</div>

<script>
    // --- 1. Fungsi Export Excel ---
    function exportTableToExcel(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        filename = filename ? filename + '.xls' : 'excel_data.xls';
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableSelect.outerHTML], { type: dataType });
            navigator.msSaveOrOpenBlob( blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
    }

    // --- 2. LOGIKA MODAL DETAIL (FIXED) ---
    const modal = document.getElementById('detailModal');
    const contentList = document.getElementById('modalContentList');
    const bookingIdTitle = document.getElementById('modalBookingId');

    function openDetailModal(idBooking) {
        // Tampilkan Modal
        modal.style.display = 'flex';
        bookingIdTitle.textContent = '#' + idBooking;
        
        // Animasi Loading
        contentList.innerHTML = `
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:40px; color:#999;">
                <i class="fas fa-circle-notch fa-spin" style="font-size:2rem; margin-bottom:10px; color:var(--primary-orange);"></i>
                <span>Mengambil data kucing...</span>
            </div>`;

        const formData = new FormData();
        formData.append('id_booking', idBooking);

        // Fetch Data dari Controller DashboardMitra
        fetch('<?= BASEURL ?>/DashboardMitra/get_booking_details', {
            method: 'POST', 
            body: formData
        })
        .then(response => {
            if (!response.ok) { throw new Error("HTTP error " + response.status); }
            return response.json();
        })
        .then(result => {
            if(result.status === 'success' && result.data.length > 0) {
                let html = '';
                
                result.data.forEach(cat => {
                    // Cek Foto (Fallback)
                    let imgUrl = cat.foto_kucing ? '<?= BASEURL ?>/images/foto_kucing/' + cat.foto_kucing : 'https://placehold.co/100x100?text=Meow';
                    
                    // Cek Jenis Kelamin (Badge Warna)
                    let sexBadgeClass = (cat.jenis_kelamin === 'Jantan' || cat.jenis_kelamin === 'Male') ? 'badge-sex-male' : 'badge-sex-female';
                    let sexIcon = (cat.jenis_kelamin === 'Jantan' || cat.jenis_kelamin === 'Male') ? '<i class="fas fa-mars"></i>' : '<i class="fas fa-venus"></i>';

                    // Template Kartu HTML
                    html += `
                    <div class="cat-card">
                        <img src="${imgUrl}" class="cat-avatar-large" alt="Foto Kucing" onerror="this.src='https://placehold.co/100?text=No+Img'">
                        
                        <div class="cat-details">
                            <h4 class="cat-name-title">${cat.nama_kucing}</h4>
                            
                            <div class="cat-badges">
                                <span class="badge-info badge-ras">
                                    <i class="fas fa-paw"></i> ${cat.ras}
                                </span>
                                <span class="badge-info ${sexBadgeClass}">
                                    ${sexIcon} ${cat.jenis_kelamin}
                                </span>
                                <span class="badge-info badge-age">
                                    <i class="fas fa-birthday-cake"></i> ${cat.umur} Tahun
                                </span>
                            </div>
                        </div>
                    </div>`;
                });
                
                contentList.innerHTML = html;
            } else {
                // Tampilan Kosong
                contentList.innerHTML = `
                    <div style="text-align:center; padding:30px; color:#aaa;">
                        <i class="fas fa-cat" style="font-size:3rem; margin-bottom:10px; opacity:0.5;"></i>
                        <p>Tidak ada data kucing ditemukan.</p>
                    </div>`;
            }
        })
        .catch(err => {
            console.error("Error Detail:", err);
            contentList.innerHTML = `
                <div style="text-align:center; padding:20px; background:#fff5f5; border-radius:8px; color:#c62828;">
                    <i class="fas fa-exclamation-triangle"></i> Gagal memuat data.<br><small>Cek koneksi atau coba lagi.</small>
                </div>`;
        });
    }

    // Fungsi Tutup Modal
    function closeModal() { modal.style.display = 'none'; }
    window.onclick = function(event) { if (event.target == modal) closeModal(); }
</script>
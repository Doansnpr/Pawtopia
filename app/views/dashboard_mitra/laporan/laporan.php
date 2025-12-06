<style>
    /* --- PAGINATION CSS --- */
    .pagination-container {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 20px; padding-top: 20px; border-top: 1px solid #f0f0f0;
    }
    .pagination-info { color: var(--text-grey); font-size: 0.85rem; }
    .pagination-nav { display: flex; gap: 5px; }

    /* --- CSS BARU UNTUK TOMBOL PDF --- */
    .btn-pdf { 
        background: linear-gradient(135deg, #ff7675, #d63031); /* Gradasi Merah */
        box-shadow: 0 4px 10px rgba(214, 48, 49, 0.3); 
    }
    .btn-pdf:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 8px 20px rgba(214, 48, 49, 0.4); 
    }

    /* --- CSS AGAR HASIL CETAK PDF RAPI (CLEAN) --- */
    @media print {
        /* Sembunyikan Sidebar, Header Atas, Filter, dan Tombol-tombol */
        .sidebar, .navbar, .filter-form, .pagination-container, .no-print {
            display: none !important;
        }
        
        /* Reset padding konten utama agar pas di kertas */
        .laporan-content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        /* Hiasan background dihilangkan agar hemat tinta */
        body { 
            background: white; 
        }
        .kpi-card, .table-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }

        /* Paksa tabel agar tidak terpotong */
        .table-responsive { overflow: visible !important; }
    }

    .page-link {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px;
        background: white; border: 1px solid #dfe6e9;
        color: var(--text-grey); font-weight: 600; text-decoration: none;
        transition: all 0.2s; font-size: 0.9rem;
    }
    .page-link:hover {
        border-color: var(--primary-orange); color: var(--primary-orange);
        transform: translateY(-2px);
    }
    .page-link.active {
        background: var(--primary-orange); color: white; border-color: var(--primary-orange);
        box-shadow: 0 4px 10px rgba(255, 159, 67, 0.3);
    }
    .page-link.disabled {
        background: #f1f2f6; color: #b2bec3; cursor: not-allowed; border-color: #f1f2f6;
        transform: none;
    }
    /* --- GLOBAL THEME VARIABLES --- */
    :root {
        --primary-orange: #FF9F43;
        --primary-orange-dark: #EE801E;
        --primary-orange-light: #FFF2E3;
        --text-dark: #2D3436;
        --text-grey: #636E72;
        --bg-color: #F8F9FD;
        --white: #FFFFFF;
        
        --excel-green: #217346;
        --excel-hover: #1e6b41;

        --shadow-soft: 0 5px 15px rgba(0, 0, 0, 0.05);
        --shadow-hover: 0 8px 25px rgba(255, 159, 67, 0.25);
        
        --trend-up: #00b894;
        --trend-down: #ff7675;
    }

    body { 
        font-family: 'Poppins', sans-serif; 
        background-color: var(--bg-color); 
        margin: 30px; padding: 0; 
        color: var(--text-dark);
    }

    /* --- LAYOUT UTAMA (DIPERBAIKI) --- */
    .laporan-content { 
        /* INI KUNCINYA: Memberi jarak dalam agar konten tidak mepet dinding */
        padding: 30px; 
        max-width: 100%;
    }

    /* --- HEADER SECTION --- */
    .laporan-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 30px; /* Jarak ke bawah lebih lega */
        background: var(--white); 
        padding: 25px 30px;
        border-radius: 20px; 
        box-shadow: var(--shadow-soft);
        
        /* Hapus margin samping yang bikin mepet/overflow */
        width: 100%;
        flex-wrap: wrap; 
        gap: 20px;
    }

    .header-text h2 {
        font-size: 1.6rem; font-weight: 700; margin: 0 0 5px 0;
        display: flex; align-items: center; gap: 10px; color: var(--text-dark);
    }
    .header-text p { margin: 0; font-size: 0.9rem; color: var(--text-grey); }

    /* Filter Toolbar */
    .filter-form { display: flex; align-items: flex-end; gap: 15px; flex-wrap: wrap; }
    
    .input-group { display: flex; flex-direction: column; gap: 8px; }
    .input-group label { font-size: 0.8rem; font-weight: 600; color: var(--text-grey); margin-left: 2px; }
    
    .date-input {
        padding: 10px 15px; border-radius: 12px; border: 2px solid #f1f2f6;
        background-color: #fcfcfc; color: var(--text-dark);
        font-family: 'Poppins', sans-serif; font-size: 0.9rem; outline: none; transition: 0.3s;
    }
    .date-input:focus { border-color: var(--primary-orange); box-shadow: 0 0 0 4px var(--primary-orange-light); }

    /* Buttons */
    .btn-tool {
        width: 45px; height: 45px; border-radius: 12px; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        transition: all 0.3s; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .btn-filter { background: linear-gradient(135deg, #FF9F43, #FF7F50); box-shadow: 0 4px 10px rgba(255, 159, 67, 0.3); }
    .btn-filter:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(255, 159, 67, 0.4); }

    .btn-excel { background: linear-gradient(135deg, #217346, #1e6b41); box-shadow: 0 4px 10px rgba(33, 115, 70, 0.3); }
    .btn-excel:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(33, 115, 70, 0.4); }

    /* --- KPI CARDS (STATS) --- */
    .kpi-grid {
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px; /* Jarak antar kartu lebih besar */
        margin-bottom: 35px;
        width: 100%;
    }

    .kpi-card {
        background: var(--white); border-radius: 20px; padding: 25px;
        box-shadow: var(--shadow-soft); border: 1px solid transparent;
        display: flex; align-items: flex-start; gap: 20px;
        transition: all 0.3s; position: relative; overflow: hidden;
    }
    .kpi-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-hover); border-color: var(--primary-orange-light); }

    .kpi-icon {
        width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; flex-shrink: 0;
    }
    .icon-money { background: #e0f9f4; color: #00b894; }
    .icon-check { background: #e3f2fd; color: #0984e3; }
    .icon-home { background: var(--primary-orange-light); color: var(--primary-orange); }
    .icon-cross { background: #ffecec; color: #ff7675; }

    .kpi-info { flex: 1; }
    .kpi-label { font-size: 0.9rem; color: var(--text-grey); font-weight: 600; display: block; margin-bottom: 5px; }
    .kpi-value { font-size: 1.6rem; font-weight: 700; color: var(--text-dark); line-height: 1.2; margin-bottom: 5px; }
    
    .trend-badge { font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 3px; }
    .trend-up { color: var(--trend-up); }
    .trend-down { color: var(--trend-down); }

    /* Progress Bar for Occupancy */
    .progress-bg { width: 100%; height: 8px; background: #f1f2f6; border-radius: 4px; margin-top: 10px; overflow: hidden; }
    .progress-fill { height: 100%; background: var(--primary-orange); border-radius: 4px; }

    .custom-table tfoot {
        background-color: #fff8e1; /* Warna kuning muda lembut */
        border-top: 2px solid var(--primary-orange);
    }
    .custom-table tfoot td {
        padding: 20px 15px;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1rem;
    }
    .total-label {
        text-align: right;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-grey);
    }
    .total-amount {
        color: var(--primary-orange-dark);
        font-size: 1.2rem; /* Lebih besar biar jelas */
    }
    /* --- TABLE SECTION --- */
    .table-card {
        background: var(--white); 
        border-radius: 20px; 
        padding: 30px; /* Padding dalam tabel diperbesar */
        box-shadow: var(--shadow-soft); 
        width: 100%;
    }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; }
    .table-title { font-size: 1.2rem; font-weight: 700; color: var(--text-dark); margin: 0; }

    .table-responsive { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    
    .custom-table th {
        text-align: left; padding: 18px; background: #f8f9fa; color: var(--text-grey);
        font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 2px solid #f1f2f6;
    }
    .custom-table th:first-child { border-top-left-radius: 12px; }
    .custom-table th:last-child { border-top-right-radius: 12px; }

    .custom-table td { padding: 18px; border-bottom: 1px solid #f1f2f6; font-size: 0.95rem; vertical-align: middle; }
    .custom-table tr:hover td { background-color: #fcfcfc; }
    .custom-table tr:last-child td { border-bottom: none; }

    /* Badges in Table */
    .table-badge { padding: 5px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; background: #f1f2f6; color: var(--text-grey); }
    .price-text { font-weight: 700; color: var(--primary-orange-dark); }
    
    /* Button Detail (Small) */
    .btn-detail-small {
        background: var(--info-bg); color: var(--info-blue); border: none; padding: 8px 14px;
        border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.85rem; transition: 0.2s;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .btn-detail-small:hover { background: #d0ebff; transform: translateY(-2px); }

    /* --- MODAL DETAIL (SAME AS STATUS PAGE) --- */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.6); z-index: 1050; backdrop-filter: blur(5px);
        align-items: center; justify-content: center; animation: fadeIn 0.2s ease-out;
    }
    .modal-content {
        background: #fff; width: 90%; max-width: 550px; border-radius: 20px; overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: slideUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .modal-header {
        padding: 20px 25px; background: linear-gradient(to right, #FFF2E3, #fff);
        border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;
    }
    .modal-header h3 { margin: 0; color: var(--text-dark); font-size: 1.2rem; }
    
    .btn-close-icon {
        background: transparent; border: none; font-size: 1.5rem; color: #b2bec3; cursor: pointer;
        transition: 0.3s; width: 35px; height: 35px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .btn-close-icon:hover { color: #ff7675; background: #fff0f0; transform: rotate(90deg); }

    .modal-body-scroll { padding: 20px; max-height: 60vh; overflow-y: auto; }
    
    /* Scrollbar */
    .modal-body-scroll::-webkit-scrollbar { width: 6px; }
    .modal-body-scroll::-webkit-scrollbar-track { background: transparent; }
    .modal-body-scroll::-webkit-scrollbar-thumb { background: #dfe6e9; border-radius: 10px; }

    /* Cat Card inside Modal */
    .cat-modal-card {
        background: #fff; border: 1px solid #f0f0f0; border-radius: 15px; padding: 15px;
        margin-bottom: 15px; display: flex; align-items: center; gap: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.02); transition: 0.2s;
    }
    .cat-modal-card:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-color: var(--primary-orange-light); }
    
    .cat-avatar { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .cat-info h4 { margin: 0 0 5px 0; font-size: 1rem; color: var(--text-dark); }
    .badge-pill { font-size: 0.75rem; padding: 3px 10px; border-radius: 20px; font-weight: 600; display: inline-block; margin-right: 5px; }
    
    .bg-light-orange { background: #fff3e0; color: #ef6c00; }
    .bg-light-blue { background: #e3f2fd; color: #1976d2; }
    .bg-light-pink { background: #fce4ec; color: #c2185b; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { transform: translateY(30px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }
</style>

<div class="laporan-content">
    
    <div class="laporan-header">
        <div class="header-text">
            <h2><i class="fas fa-chart-line" style="color: var(--primary-orange);"></i> Laporan & Keuangan</h2>
            <p>Ringkasan performa bisnis Anda periode ini.</p>
        </div>
        
        <form method="GET" action="<?= BASEURL ?>/DashboardMitra" class="filter-form">
            <input type="hidden" name="page" value="laporan">
            
            <div class="input-group">
                <label>Dari</label>
                <input type="date" name="start_date" value="<?= $data['laporan']['start_date'] ?>" class="date-input" required>
            </div>
            
            <div class="input-group">
                <label>Sampai</label>
                <input type="date" name="end_date" value="<?= $data['laporan']['end_date'] ?>" class="date-input" required>
            </div>

            <button type="submit" class="btn-tool btn-filter" title="Terapkan Filter">
                <i class="fas fa-search"></i>
            </button>

            <?php 
                $urlExport = BASEURL . '/DashboardMitra?page=laporan&action=excel';
                
                if(!empty($data['laporan']['start_date'])) {
                    $urlExport .= '&start_date=' . $data['laporan']['start_date'] . '&end_date=' . $data['laporan']['end_date'];
                }
            ?>

            <a href="<?= $urlExport ?>" class="btn-tool btn-excel" title="Download Excel Full Data" target="_blank">
                <i class="fas fa-file-excel"></i>
            </a>

            <?php 
                // Buat URL Print
                $urlPrint = BASEURL . '/DashboardMitra?page=laporan&action=print';
                
                // Jika ada filter tanggal, tambahkan ke URL agar hasil print sesuai filter
                if(!empty($data['laporan']['start_date'])) {
                    $urlPrint .= '&start_date=' . $data['laporan']['start_date'] . '&end_date=' . $data['laporan']['end_date'];
                }
            ?>

            <a href="<?= $urlPrint ?>" target="_blank" class="btn-tool btn-pdf" title="Cetak Laporan Resmi">
                <i class="fas fa-print"></i>
            </a>
        </form>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon icon-money"><i class="fas fa-wallet"></i></div>
            <div class="kpi-info">
                <span class="kpi-label">Pendapatan Bersih</span>
                <div class="kpi-value">Rp <?= number_format($data['laporan']['financial']['pendapatan'], 0, ',', '.') ?></div>
                <div class="trend-badge <?= ($data['laporan']['growth'] >= 0) ? 'trend-up' : 'trend-down' ?>">
                    <i class="fas fa-arrow-<?= ($data['laporan']['growth'] >= 0) ? 'up' : 'down' ?>"></i> 
                    <?= abs($data['laporan']['growth']) ?>% 
                    <span style="color:#aaa; font-weight:400; margin-left:3px;">vs bulan lalu</span>
                </div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon icon-check"><i class="fas fa-calendar-check"></i></div>
            <div class="kpi-info">
                <span class="kpi-label">Booking Selesai</span>
                <div class="kpi-value"><?= $data['laporan']['financial']['booking_selesai'] ?></div>
                <span style="font-size: 0.8rem; color: #aaa;">Transaksi berhasil</span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon icon-home"><i class="fas fa-warehouse"></i></div>
            <div class="kpi-info">
                <span class="kpi-label">Tingkat Hunian</span>
                <div class="kpi-value"><?= $data['laporan']['occupancy']['rate'] ?>%</div>
                <div class="progress-bg">
                    <div class="progress-fill" style="width: <?= $data['laporan']['occupancy']['rate'] ?>%;"></div>
                </div>
                <span style="font-size: 0.8rem; color: #aaa; margin-top:5px; display:block;">
                    <?= $data['laporan']['occupancy']['terisi'] ?> / <?= $data['laporan']['occupancy']['kapasitas'] ?> slot terisi
                </span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon icon-cross"><i class="fas fa-ban"></i></div>
            <div class="kpi-info">
                <span class="kpi-label">Dibatalkan</span>
                <div class="kpi-value"><?= $data['laporan']['financial']['booking_batal'] ?></div>
                <span style="font-size: 0.8rem; color: #aaa;">Transaksi gagal</span>
            </div>
        </div>
    </div>

    <div class="table-card">
    <div class="table-header">
        <h3 class="table-title">Riwayat Transaksi</h3>
    </div>

    <div class="table-responsive">
        <table class="custom-table" id="transaksiTable">
            <thead>
                <tr>
                    <th>ID Booking</th>
                    <th>Durasi</th>
                    <th>Paket</th>
                    <th>Pelanggan</th>
                    <th class="text-center">Kucing</th>
                    <th class="text-right">Biaya</th> <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['laporan']['history'])): ?>
                    <tr><td colspan="7" style="text-align:center; padding: 40px; color: #999;"><i>Tidak ada data transaksi pada periode ini.</i></td></tr>
                <?php else: ?>
                    <?php foreach($data['laporan']['history'] as $row): 
                        $start = date('d M', strtotime($row['tgl_mulai']));
                        $end   = date('d M Y', strtotime($row['tgl_selesai']));
                    ?>
                        <tr>
                            <td style="font-weight:700; color:var(--primary-orange);">#<?= $row['id_booking'] ?></td>
                            <td><?= $start ?> - <?= $end ?></td>
                            <td><span class="table-badge"><?= htmlspecialchars($row['paket']) ?></span></td>
                            <td style="font-weight:600;"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td style="text-align:center; font-weight:bold;"><?= $row['jumlah_kucing'] ?></td>
                            <td class="price-text" style="text-align:right;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td style="text-align:center;">
                                <button class="btn-detail-small" onclick="openDetailModal('<?= $row['id_booking'] ?>')">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="5" class="total-label">
                        Total Pendapatan (Periode Ini):
                    </td>
                    <td class="text-right total-amount">
                        Rp <?= number_format($data['laporan']['financial']['pendapatan'], 0, ',', '.') ?>
                    </td>
                    <td></td> </tr>
            </tfoot>
            </table>
    </div>

    <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
    <div class="pagination-container">
        <div class="pagination-info">
            Hal <strong><?= $pagination['current_page']; ?></strong> dari <strong><?= $pagination['total_pages']; ?></strong> 
        </div>
        <div class="pagination-nav">
             <?php 
                $queryParams = $_GET; 
                unset($queryParams['page_no']); 
                $queryString = http_build_query($queryParams);
            ?>
            <?php if ($pagination['current_page'] > 1): ?>
                <a href="?<?= $queryString ?>&page_no=<?= $pagination['current_page'] - 1 ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
            <?php else: ?>
                <span class="page-link disabled"><i class="fas fa-chevron-left"></i></span>
            <?php endif; ?>

            <?php 
            $startPage = max(1, $pagination['current_page'] - 2);
            $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
            for ($i = $startPage; $i <= $endPage; $i++): 
            ?>
                <a href="?<?= $queryString ?>&page_no=<?= $i ?>" class="page-link <?= ($i == $pagination['current_page']) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                <a href="?<?= $queryString ?>&page_no=<?= $pagination['current_page'] + 1 ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
            <?php else: ?>
                <span class="page-link disabled"><i class="fas fa-chevron-right"></i></span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
</div>

<div class="modal-overlay" id="detailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Detail Booking <span id="modalBookingId" style="color:var(--primary-orange); font-weight:700;">#...</span></h3>
            <button class="btn-close-icon" onclick="closeModal()">&times;</button>
        </div>
        <div id="modalContentList" class="modal-body-scroll">
            <div style="text-align:center; padding:20px;">Memuat data...</div>
        </div>
    </div>
</div>

<script>
    // --- 2. LOGIKA MODAL DETAIL ---
    const modal = document.getElementById('detailModal');
    const contentList = document.getElementById('modalContentList');
    const bookingIdTitle = document.getElementById('modalBookingId');

    function openDetailModal(idBooking) {
        modal.style.display = 'flex';
        bookingIdTitle.textContent = '#' + idBooking;
        
        contentList.innerHTML = `
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:40px; color:#999;">
                <i class="fas fa-spinner fa-spin" style="font-size:2rem; margin-bottom:10px; color:var(--primary-orange);"></i>
                <span>Mengambil data kucing...</span>
            </div>`;

        const formData = new FormData();
        formData.append('id_booking', idBooking);

        fetch('<?= BASEURL ?>/DashboardMitra/get_booking_details', {
            method: 'POST', body: formData
        })
        .then(res => res.json())
        .then(result => {
            if(result.status === 'success' && result.data.length > 0) {
                let html = '';
                result.data.forEach(cat => {
                    let imgUrl = cat.foto_kucing ? '<?= BASEURL ?>/images/foto_kucing/' + cat.foto_kucing : 'https://placehold.co/100x100?text=Meow';
                    let sexBadge = (cat.jenis_kelamin == 'Jantan' || cat.jenis_kelamin == 'Male') ? 'bg-light-blue' : 'bg-light-pink';
                    let sexIcon = (cat.jenis_kelamin == 'Jantan' || cat.jenis_kelamin == 'Male') ? 'mars' : 'venus';

                    html += `
                    <div class="cat-modal-card">
                        <img src="${imgUrl}" class="cat-avatar" onerror="this.src='https://placehold.co/100?text=Err'">
                        <div class="cat-info">
                            <h4>${cat.nama_kucing}</h4>
                            <div style="margin-top:5px;">
                                <span class="badge-pill bg-light-orange"><i class="fas fa-paw"></i> ${cat.ras}</span>
                                <span class="badge-pill ${sexBadge}"><i class="fas fa-${sexIcon}"></i> ${cat.jenis_kelamin}</span>
                                <span class="badge-pill" style="background:#f3e5f5; color:#7b1fa2;"><i class="fas fa-birthday-cake"></i> ${cat.umur}</span>
                            </div>
                        </div>
                    </div>`;
                });
                contentList.innerHTML = html;
            } else {
                contentList.innerHTML = '<div style="text-align:center; padding:20px; color:#aaa;">Tidak ada data kucing.</div>';
            }
        })
        .catch(err => {
            contentList.innerHTML = '<div style="text-align:center; color:red;">Gagal memuat data.</div>';
        });
    }

    function closeModal() { modal.style.display = 'none'; }
    window.onclick = function(e) { if (e.target == modal) closeModal(); }
</script>
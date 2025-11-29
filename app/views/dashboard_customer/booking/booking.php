<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Booking - PawTopia</title>
   <style>
    :root {
        --primary: #FFA500; /* Orange PawTopia */
        --secondary: #FFD700; /* Gold */
        --accent: #FF6347; /* Coral Red */
        --light: #F8F9FA;
        --dark: #212529;
        --success: #28a745;
        --info: #17a2b8;
        --warning: #ffc107;
        --danger: #dc3545;
        --gray: #6c757d;
        --border: #dee2e6;
        --shadow: 0 6px 20px rgba(0,0,0,0.1);
        --bg-light: #f0f8ff;
        --bg-card: #ffffff;
        --text-primary: #212529;
        --text-secondary: #6c757d;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: var(--bg-light);
        color: var(--text-primary);
        line-height: 1.6;
        padding: 20px;
        min-height: 100vh;
    }

    .container {
        max-width: 100%;
        width: 100%;
        margin: 0 auto;
        padding: 20px;
        padding-top: 40px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid var(--primary);
        background: white;
        border-radius: 12px;
        padding: 20px 30px;
        box-shadow: var(--shadow);
    }

    .header h1 {
        color: var(--primary);
        font-size: 2.2rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 15px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    .header h1::after {
        content: "🐾";
        font-size: 1.4em;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 8px rgba(255, 165, 0, 0.2);
    }

    .btn-primary:hover {
        background-color: #e69500;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(255, 165, 0, 0.3);
    }

    .btn-danger {
        background-color: var(--accent);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(255, 99, 71, 0.2);
    }

    .btn-danger:hover {
        background-color: #e74c3c;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(255, 99, 71, 0.3);
    }

    .booking-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--bg-card);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow);
        margin-top: 30px;
        border: 1px solid var(--border);
    }

    .booking-table th,
    .booking-table td {
        padding: 20px 25px;
        text-align: left;
        border-bottom: 1px solid var(--border);
        font-size: 1.05rem;
    }

    .booking-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: var(--primary);
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .booking-table tr:last-child td {
        border-bottom: none;
    }

    .booking-table tr:hover {
        background-color: #fdfdfd;
        transform: translateX(2px);
        transition: transform 0.2s ease;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.95rem;
        font-weight: bold;
        display: inline-block;
        text-transform: capitalize;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .status-menunggu {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-dikonfirmasi {
        background-color: #d4edda;
        color: #155724;
    }

    .status-menunggu-dp {
        background-color: #cce5ff;
        color: #004085;
    }

    .status-dp-terkonfirmasi {
        background-color: #d4edda;
        color: #155724;
    }

    .status-dp-ditolak {
        background-color: #f8d7da;
        color: #721c24;
    }

    .no-data {
        text-align: center;
        padding: 60px 40px;
        font-size: 1.3rem;
        color: var(--text-secondary);
        background: var(--bg-card);
        border-radius: 15px;
        box-shadow: var(--shadow);
        margin-top: 30px;
        border: 2px dashed var(--border);
    }

    .no-data p {
        margin-bottom: 25px;
        font-size: 1.1rem;
        color: var(--text-primary);
    }

    .no-data .emoji {
        font-size: 2rem;
        margin-right: 10px;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease-in-out;
        padding: 20px;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: var(--bg-card);
        padding: 40px;
        border-radius: 20px;
        width: 95%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        animation: slideUp 0.4s ease-out;
        border: 1px solid var(--border);
    }

    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 3px solid var(--primary);
    }

    .modal-header h2 {
        color: var(--primary);
        font-size: 1.8rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-header h2::before {
        content: "📝";
        font-size: 1.2em;
    }

    .close {
        color: var(--gray);
        font-size: 32px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s;
        background: #f8f9fa;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close:hover {
        color: var(--danger);
        background: #f8d7da;
    }

    .form-group {
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: var(--text-primary);
        font-size: 1.05rem;
    }

    input, select, textarea {
        width: 100%;
        padding: 14px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 1.05rem;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.2);
        background: white;
    }

    .cat-section {
        background: #fafafa;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        position: relative;
        border-left: 6px solid var(--primary);
        transition: transform 0.2s ease;
    }

    .cat-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .cat-section h3 {
        color: var(--primary);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.3rem;
        font-weight: bold;
    }

    .cat-section h3::before {
        content: "🐱";
        font-size: 1.4em;
    }

    .remove-cat {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--danger);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s, transform 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .remove-cat:hover {
        background: #c0392b;
        transform: scale(1.1);
    }

    .add-cat-btn {
        background-color: var(--secondary);
        color: var(--dark);
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        cursor: pointer;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 25px;
        box-shadow: 0 4px 8px rgba(255, 215, 0, 0.2);
    }

    .add-cat-btn:hover {
        background-color: #ffd700;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255, 215, 0, 0.3);
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 35px;
        gap: 15px;
    }

    .btn-submit {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 16px 30px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        flex: 1;
        box-shadow: 0 4px 8px rgba(255, 165, 0, 0.2);
    }

    .btn-submit:hover {
        background-color: #e69500;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(255, 165, 0, 0.3);
    }

    .btn-cancel {
        background-color: var(--gray);
        color: white;
        border: none;
        padding: 16px 30px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        flex: 1;
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.2);
    }

    .btn-cancel:hover {
        background-color: #555;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(108, 117, 125, 0.3);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .container {
            padding: 15px;
        }

        .header {
            padding: 15px 20px;
        }

        .header h1 {
            font-size: 1.8rem;
        }

        .modal-content {
            padding: 30px;
            width: 95%;
            max-width: 600px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit, .btn-cancel {
            margin: 10px 0;
        }

        .booking-table th,
        .booking-table td {
            padding: 15px 20px;
            font-size: 0.95rem;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        .header {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }

        .header h1 {
            font-size: 1.6rem;
            text-align: center;
        }

        .modal-content {
            padding: 25px;
            width: 95%;
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input, select, textarea {
            padding: 12px;
            font-size: 1rem;
        }

        .cat-section {
            padding: 20px;
        }

        .cat-section h3 {
            font-size: 1.2rem;
        }

        .btn-primary {
            padding: 12px 20px;
            font-size: 1rem;
        }

        .no-data {
            padding: 40px 20px;
            font-size: 1.1rem;
        }
    }

    @media (max-width: 480px) {
        .header h1 {
            font-size: 1.4rem;
        }

        .modal-content {
            padding: 20px;
            max-width: 400px;
        }

        .form-actions {
            flex-direction: column;
            gap: 10px;
        }

        .btn-submit, .btn-cancel {
            font-size: 1rem;
            padding: 12px 20px;
        }

        .booking-table th,
        .booking-table td {
            padding: 12px 15px;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
    }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Daftar Booking Kamu</h1>
        <button id="addBookingBtn" class="btn-primary">+ Tambah Booking Baru</button>
    </div>

    <!-- Table will be dynamically loaded here -->
    <div id="bookingTableContainer">
        <!-- Loading state or no data message will appear here -->
        <?php if (empty($bookings)): ?>
    <div class="no-data">
        <p>🎉 Belum ada booking yang kamu buat.</p>
        <p>Klik tombol "Tambah Booking Baru" di atas untuk mulai menitipkan kucing kesayanganmu!</p>
        <button id="addBookingBtnNoData" class="btn-primary">Mulai Booking Sekarang</button>
    </div>
    <?php else: ?>
        <table class="booking-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tempat Penitipan</th>
                    <th>Tanggal</th>
                    <th>Kucing (Jml)</th>
                    <th>Paket</th>
                    <th>Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $index => $b): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($b['tempat_penitipan'] ?? '-') ?></td>
                    <td><?= date('d M Y', strtotime($b['check_in'])) ?> – <?= date('d M Y', strtotime($b['check_out'])) ?></td>
                    <td><?= htmlspecialchars($b['nama_kucing_list']) ?> (<?= $b['cats'] ?>)</td>
                    <td><?= htmlspecialchars($b['paket'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($b['total_harga_formatted']) ?></td>
                    <td><span class="status-badge status-<?= strtolower(str_replace(' ', '_', $b['status'])) ?>"><?= $b['status'] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    </div>
</div>

<!-- Modal for Adding Booking -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Buat Booking Baru 🐾</h2>
            <span class="close">&times;</span>
        </div>
        <form id="bookingForm">
            <div class="form-group">
                <label for="mitra">Pilih Penitipan (Mitra)</label>
                <select id="mitra" required>
                    <option value="">-- Pilih Mitra --</option>
                    <!-- Mitra akan diisi secara dinamis -->
                </select>
            </div>

            <div class="form-group">
                <label for="tgl_mulai">Tanggal Mulai Penitipan</label>
                <input type="date" id="tgl_mulai" required>
            </div>

            <div class="form-group">
                <label for="tgl_selesai">Tanggal Selesai Penitipan</label>
                <input type="date" id="tgl_selesai" required>
            </div>

            <div class="form-group">
                <label for="paket">Pilih Paket</label>
                <select id="paket" required>
                    <option value="">-- Pilih Paket --</option>
                    <!-- Paket akan diisi secara dinamis -->
                </select>
            </div>

            <div class="form-group">
                <label for="total_harga">Total Harga (Estimasi)</label>
                <input type="text" id="total_harga" readonly placeholder="Harga akan dihitung otomatis">
            </div>

            <div class="form-group">
                <label for="cat-container">Kucing yang Akan Dititipkan</label>
                <div id="cat-container">
                    <!-- Cat forms will be added here dynamically -->
                    <div class="cat-section" data-cat-id="1">
                        <h3>Kucing 1</h3>
                        <button type="button" class="remove-cat">×</button>
                        <div class="form-group">
                            <label for="nama_kucing_1">Nama Kucing</label>
                            <input type="text" id="nama_kucing_1" name="kucing[1][nama]" required placeholder="Contoh: Mochi">
                        </div>
                        <div class="form-group">
                            <label for="ras_1">Ras</label>
                            <input type="text" id="ras_1" name="kucing[1][ras]" required placeholder="Contoh: Persian">
                        </div>
                        <div class="form-group">
                            <label for="umur_1">Umur (Tahun)</label>
                            <input type="number" id="umur_1" name="kucing[1][umur]" min="0" max="20" required placeholder="Contoh: 3">
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin_1">Jenis Kelamin</label>
                            <select id="jenis_kelamin_1" name="kucing[1][jenis_kelamin]" required>
                                <option value="">-- Pilih --</option>
                                <option value="Jantan">Jantan</option>
                                <option value="Betina">Betina</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keterangan_1">Keterangan Tambahan</label>
                            <textarea id="keterangan_1" name="kucing[1][keterangan]" rows="3" placeholder="Contoh: Suka main bola, makanan favorit royal canin"></textarea>
                        </div>
                    </div>
                </div>

                <button type="button" id="addCatBtn" class="add-cat-btn">+ Tambah Kucing Lainnya</button>
            </div>

            <div class="form-actions">
                <button type="button" id="cancelBtn" class="btn-cancel">Batal</button>
                <button type="submit" class="btn-submit">Simpan Booking</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Get DOM Elements
    const addBookingBtn = document.getElementById('addBookingBtn');
    const addBookingBtnNoData = document.getElementById('addBookingBtnNoData');
    const bookingModal = document.getElementById('bookingModal');
    const closeModal = document.querySelector('.close');
    const cancelBtn = document.getElementById('cancelBtn');
    const addCatBtn = document.getElementById('addCatBtn');
    const catContainer = document.getElementById('cat-container');
    const bookingForm = document.getElementById('bookingForm');

    let catCount = 1;

    // Open Modal
    function openModal() {
        bookingModal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    // Close Modal
    function closeModalFunc() {
        bookingModal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Enable scrolling
        resetForm();
    }

    // Add Event Listeners
    addBookingBtn.addEventListener('click', openModal);
    addBookingBtnNoData.addEventListener('click', openModal);
    closeModal.addEventListener('click', closeModalFunc);
    cancelBtn.addEventListener('click', closeModalFunc);

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === bookingModal) {
            closeModalFunc();
        }
    });

    // Ambil daftar mitra saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        loadMitras();
    });

    function loadMitras() {
        fetch(BASE_URL + '/DashboardCustomer/getMitras')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const mitraSelect = document.getElementById('mitra');
                    mitraSelect.innerHTML = '<option value="">-- Pilih Mitra --</option>';
                    data.mitras.forEach(mitra => {
                        const option = document.createElement('option');
                        option.value = mitra.id_mitra;
                        option.textContent = mitra.nama_petshop;
                        mitraSelect.appendChild(option);
                    });
                } else {
                    alert('Gagal memuat daftar mitra.');
                }
            })
            .catch(error => {
                console.error('Error loading mitras:', error);
                alert('Terjadi kesalahan saat memuat mitra.');
            });
    }

    // Load paket ketika mitra dipilih
    document.getElementById('mitra').addEventListener('change', function() {
        const id_mitra = this.value;
        if (!id_mitra) {
            document.getElementById('paket').innerHTML = '<option value="">-- Pilih Paket --</option>';
            document.getElementById('total_harga').value = '';
            return;
        }

        fetch(BASE_URL + '/DashboardCustomer/getPackages', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_mitra: id_mitra })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const paketSelect = document.getElementById('paket');
                paketSelect.innerHTML = '<option value="">-- Pilih Paket --</option>';
                data.packages.forEach(paket => {
                    const option = document.createElement('option');
                    option.value = paket.id_paket;
                    option.textContent = `${paket.nama_paket} - Rp ${parseInt(paket.harga).toLocaleString('id-ID')}`;
                    option.dataset.harga = paket.harga; // Simpan harga di dataset
                    paketSelect.appendChild(option);
                });
            } else {
                alert('Gagal memuat paket untuk mitra ini.');
            }
        })
        .catch(error => {
            console.error('Error loading packages:', error);
            alert('Terjadi kesalahan saat memuat paket.');
        });
    });

    // Ambil harga paket saat paket dipilih
    document.getElementById('paket').addEventListener('change', function() {
        const id_paket = this.value;
        if (!id_paket) {
            document.getElementById('total_harga').value = '';
            return;
        }

        // Gunakan harga dari dataset jika tersedia (lebih cepat)
        const selectedOption = this.options[this.selectedIndex];
        let harga = selectedOption.dataset.harga || 0;

        // Jika tidak ada di dataset, ambil via AJAX
        if (!harga) {
            fetch(BASE_URL + '/DashboardCustomer/getPackagePrice', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_paket: id_paket })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    harga = data.harga;
                    calculatePrice(); // Hitung ulang total harga
                }
            })
            .catch(error => {
                console.error('Error fetching package price:', error);
            });
        } else {
            calculatePrice(); // Hitung ulang total harga
        }
    });

    // Add Cat Function
    addCatBtn.addEventListener('click', () => {
        catCount++;
        const newCatSection = document.createElement('div');
        newCatSection.className = 'cat-section';
        newCatSection.setAttribute('data-cat-id', catCount);

        newCatSection.innerHTML = `
            <h3>Kucing ${catCount}</h3>
            <button type="button" class="remove-cat">×</button>
            <div class="form-group">
                <label for="nama_kucing_${catCount}">Nama Kucing</label>
                <input type="text" id="nama_kucing_${catCount}" name="kucing[${catCount}][nama]" required placeholder="Contoh: Mochi">
            </div>
            <div class="form-group">
                <label for="ras_${catCount}">Ras</label>
                <input type="text" id="ras_${catCount}" name="kucing[${catCount}][ras]" required placeholder="Contoh: Persian">
            </div>
            <div class="form-group">
                <label for="umur_${catCount}">Umur (Tahun)</label>
                <input type="number" id="umur_${catCount}" name="kucing[${catCount}][umur]" min="0" max="20" required placeholder="Contoh: 3">
            </div>
            <div class="form-group">
                <label for="jenis_kelamin_${catCount}">Jenis Kelamin</label>
                <select id="jenis_kelamin_${catCount}" name="kucing[${catCount}][jenis_kelamin]" required>
                    <option value="">-- Pilih --</option>
                    <option value="Jantan">Jantan</option>
                    <option value="Betina">Betina</option>
                </select>
            </div>
            <div class="form-group">
                <label for="keterangan_${catCount}">Keterangan Tambahan</label>
                <textarea id="keterangan_${catCount}" name="kucing[${catCount}][keterangan]" rows="3" placeholder="Contoh: Suka main bola, makanan favorit royal canin"></textarea>
            </div>
        `;

        catContainer.appendChild(newCatSection);

        // Add event listener to remove button
        newCatSection.querySelector('.remove-cat').addEventListener('click', () => {
            if (catContainer.children.length > 1) {
                newCatSection.remove();
                updateCatNumbers();
            } else {
                alert("Minimal harus ada 1 kucing!");
            }
        });
    });

    // Update cat numbers after removal
    function updateCatNumbers() {
        const catSections = catContainer.querySelectorAll('.cat-section');
        catSections.forEach((section, index) => {
            const currentId = parseInt(section.getAttribute('data-cat-id'));
            section.setAttribute('data-cat-id', index + 1);
            section.querySelector('h3').textContent = `Kucing ${index + 1}`;
            section.querySelector(`input[name="kucing[${currentId}][nama]"]`).name = `kucing[${index + 1}][nama]`;
            section.querySelector(`input[id="nama_kucing_${currentId}"]`).id = `nama_kucing_${index + 1}`;
            section.querySelector(`input[name="kucing[${currentId}][ras]"]`).name = `kucing[${index + 1}][ras]`;
            section.querySelector(`input[id="ras_${currentId}"]`).id = `ras_${index + 1}`;
            section.querySelector(`input[name="kucing[${currentId}][umur]"]`).name = `kucing[${index + 1}][umur]`;
            section.querySelector(`input[id="umur_${currentId}"]`).id = `umur_${index + 1}`;
            section.querySelector(`select[name="kucing[${currentId}][jenis_kelamin]"]`).name = `kucing[${index + 1}][jenis_kelamin]`;
            section.querySelector(`select[id="jenis_kelamin_${currentId}"]`).id = `jenis_kelamin_${index + 1}`;
            section.querySelector(`textarea[name="kucing[${currentId}][keterangan]"]`).name = `kucing[${index + 1}][keterangan]`;
            section.querySelector(`textarea[id="keterangan_${currentId}"]`).id = `keterangan_${index + 1}`;
        });
        catCount = catSections.length;
    }

    // Calculate total price based on dates and package
    document.getElementById('tgl_mulai').addEventListener('change', calculatePrice);
    document.getElementById('tgl_selesai').addEventListener('change', calculatePrice);
    document.getElementById('paket').addEventListener('change', calculatePrice);

    function calculatePrice() {
        const startDate = new Date(document.getElementById('tgl_mulai').value);
        const endDate = new Date(document.getElementById('tgl_selesai').value);
        const paketSelect = document.getElementById('paket');
        const selectedOption = paketSelect.options[paketSelect.selectedIndex];

        if (!startDate || !endDate || !selectedOption || !selectedOption.value) {
            document.getElementById('total_harga').value = '';
            return;
        }

        // Hitung selisih hari
        const timeDiff = endDate.getTime() - startDate.getTime();
        const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

        if (dayDiff <= 0) {
            document.getElementById('total_harga').value = 'Tanggal tidak valid';
            return;
        }

        // Ambil harga dari dataset atau value
        let hargaPerHari = parseFloat(selectedOption.dataset.harga) || 0;

        if (hargaPerHari === 0) {
            // Jika belum ada di dataset, coba ambil via AJAX (opsional)
            // Untuk demo, kita asumsikan harga sudah ada di dataset
            document.getElementById('total_harga').value = 'Harga tidak ditemukan';
            return;
            }

            const totalPrice = dayDiff * hargaPerHari;
            document.getElementById('total_harga').value = `Rp ${totalPrice.toLocaleString('id-ID')}`;
        }

    // Reset form
    function resetForm() {
        bookingForm.reset();
        document.getElementById('total_harga').value = '';
        // Keep at least one cat section
        while (catContainer.children.length > 1) {
            catContainer.removeChild(catContainer.lastChild);
        }
        catCount = 1;
        // Reset first cat section
        const firstCat = catContainer.querySelector('.cat-section');
        firstCat.querySelector('input[name="kucing[1][nama]"]').value = '';
        firstCat.querySelector('input[name="kucing[1][ras]"]').value = '';
        firstCat.querySelector('input[name="kucing[1][umur]"]').value = '';
        firstCat.querySelector('select[name="kucing[1][jenis_kelamin]"]').value = '';
        firstCat.querySelector('textarea[name="kucing[1][keterangan]"]').value = '';
    }

  // Handle form submission with AJAX
    bookingForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Validate form
        const formData = new FormData(bookingForm);
        const mitra = formData.get('mitra');
        const tgl_mulai = formData.get('tgl_mulai');
        const tgl_selesai = formData.get('tgl_selesai');
        const paket = formData.get('paket');
        const total_harga = formData.get('total_harga');

        if (!mitra || !tgl_mulai || !tgl_selesai || !paket || !total_harga) {
            alert('Semua field wajib diisi!');
            return;
        }

        // Collect cat data
        const cats = [];
        for (let i = 1; i <= catCount; i++) {
            const nama = formData.get(`kucing[${i}][nama]`);
            const ras = formData.get(`kucing[${i}][ras]`);
            const umur = formData.get(`kucing[${i}][umur]`);
            const jenis_kelamin = formData.get(`kucing[${i}][jenis_kelamin]`);
            const keterangan = formData.get(`kucing[${i}][keterangan]`);

            if (!nama || !ras || !umur || !jenis_kelamin) {
                alert(`Data kucing ${i} belum lengkap!`);
                return;
            }

            cats.push({
                nama,
                ras,
                umur,
                jenis_kelamin,
                keterangan
            });
        }

        // Prepare data for AJAX
        const data = {
            mitra,
            tgl_mulai,
            tgl_selesai,
            paket,
            total_harga,
            kucing: cats
        };

        // Send to server
        fetch(BASE_URL + '/DashboardCustomer/saveBooking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                closeModalFunc();
                // Refresh the page or update the booking list dynamically
                location.reload(); // Simple refresh for now
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        });
    });

</script>

</body>
</html>
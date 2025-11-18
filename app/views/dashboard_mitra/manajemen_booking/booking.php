<?php
$reservations = $reservations ?? [];
$statusCounts = $statusCounts ?? [
    'Menunggu Konfirmasi' => 0,
    'Menunggu DP' => 0,
    'Verifikasi DP' => 0,
    'Aktif' => 0,
    'Selesai' => 0,
    'Dibatalkan' => 0
];
?>
<style>
    .reservasi-content {
        padding-bottom: 10px;
    }

    .reservasi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 30px 30px 0 30px;
    }

    .reservasi-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .tab-container {
        display: flex;
        gap: 10px;
        border-bottom: 2px solid var(--border-color);
        padding: 0 30px;
    }

    .tab-item {
        padding: 10px 15px;
        cursor: pointer;
        font-weight: 500;
        color: var(--text-gray);
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        margin-bottom: -2px;
    }

    .tab-item.active {
        color: var(--primary-blue);
        border-bottom-color: var(--primary-blue);
    }

    .data-card {
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin: 20px 30px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .data-table th,
    .data-table td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.95rem;
    }

    .data-table th {
        color: var(--text-dark);
        font-weight: 600;
        background-color: var(--light-bg);
    }

    .action-links {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .action-links a {
        text-decoration: none;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 5px;
        transition: all 0.2s ease-in-out;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        text-align: center;
        display: inline-block;
        min-width: 80px;
        color: #fff;
        background-color: #6c757d;
    }

    .action-links a:hover {
        filter: brightness(90%);
    }

    .action-links a[href*="terima_booking"] {
        background-color: #28a745;
    }

    .action-links a[href*="terima_booking"]:hover {
        background-color: #218838;
    }

    .action-links a[href*="tolak_Boking"] {
        background-color: #dc3545;
    }

    .action-links a[href*="tolak_Boking"]:hover {
        background-color: #c82333;
    }

    .action-links a[href*="check_dp"] {
        background-color: var(--primary-blue, #007bff);
    }

    .action-links a[href*="check_dp"]:hover {
        background-color: #0056b3;
    }

    .action-links a[href*="detail_booking"] {
        background-color: #6c757d;
    }

    .action-links a[href*="detail_booking"]:hover {
        background-color: #5a6268;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-block;
        font-size: 0.85rem;
        background-color: #f3f3f3;
        color: #444;
    }

    .status-menunggu-konfirmasi,
    .status-menunggu-dp {
        background-color: #faf4d3ff;
        color: #ffc400ff;
    }

    .status-verifikasi-dp {
        background-color: #ebf2f3ff;
        color: #2666c5ff;
    }

    .status-aktif {
        background-color: #e2ffe9ff;
        color: #00cf30ff;
    }

    .status-selesai {
        background-color: #d3d3d3ff;
        color: #47494bff;
    }

    .status-dibatalkan {
        background-color: #ffe2e5ff;
        color: #cf0217ff;
    }

    /* === CSS UNTUK MODAL TAMBAH BOOKING === */
    .btn-primary {
        background-color: var(--primary-blue, #007bff);
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.95rem;
        transition: background-color 0.2s;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 0.85rem;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        display: none;
        overflow-y: auto;
        padding: 30px 0;
    }

    .modal-content {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        width: 90%;
        max-width: 600px;
        margin: auto;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 15px 25px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        color: var(--text-dark);
    }

    .modal-close {
        border: none;
        background: transparent;
        font-size: 1.5rem;
        font-weight: 700;
        color: #888;
        cursor: pointer;
    }

    .modal-body {
        padding: 25px;
        flex-grow: 1;
    }

    .modal-step {
        display: none;
    }

    .modal-step.active {
        display: block;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        font-size: 0.95rem;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        box-sizing: border-box;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .modal-footer {
        padding: 15px 25px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        background-color: var(--light-bg, #f9f9f9);
    }

    .cat-form-instance {
        border: 1px dashed var(--border-color);
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
        background-color: #fdfdfd;
    }

    .cat-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .cat-form-header h5 {
        margin: 0;
        font-size: 1.1rem;
    }

    #cat-forms-container {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }
</style>

<div class="reservasi-content">
    <div class="reservasi-header">
        <h1><?= htmlspecialchars($title ?? 'Manajemen Booking'); ?></h1>
        <button id="btnTambahOffline" class="btn-primary">+ Tambah Booking Offline</button>
    </div>

    <div class="tab-container">
        <div class="tab-item active" data-status="Semua">Semua (<?= array_sum($statusCounts); ?>)</div>
        <div class="tab-item" data-status="Menunggu Konfirmasi">Menunggu Konfirmasi (<?= $statusCounts['Menunggu Konfirmasi'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Menunggu DP">Menunggu DP (<?= $statusCounts['Menunggu DP'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Verifikasi DP">Verifikasi DP (<?= $statusCounts['Verifikasi DP'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Aktif">Aktif (<?= $statusCounts['Aktif'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Selesai">Selesai (<?= $statusCounts['Selesai'] ?? 0; ?>)</div>
        <div class="tab-item" data-status="Dibatalkan">Dibatalkan (<?= $statusCounts['Dibatalkan'] ?? 0; ?>)</div>
    </div>

    <div class="data-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Jumlah Kucing</th>
                    <th>Paket</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="reservasi-body">
                <?php
                if (!empty($reservations)):
                    foreach ($reservations as $res):
                ?>
                        <tr data-status="<?= htmlspecialchars($res['status'] ?? ''); ?>" data-id="<?= htmlspecialchars($res['id_booking'] ?? ''); ?>">
                            <td><?= htmlspecialchars($res['nama_lengkap'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['tgl_booking'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['tgl_mulai'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['tgl_selesai'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['jumlah_kucing'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['paket'] ?? ''); ?></td>
                            <td><?= htmlspecialchars($res['total_harga'] ?? ''); ?></td>
                            <td>
                                <?php
                                $statusText = htmlspecialchars($res['status'] ?? '');
                                $statusClass = strtolower(str_replace(' ', '-', $statusText));
                                ?>
                                <span class="status-badge status-<?= $statusClass; ?>">
                                    <?= $statusText; ?>
                                </span>
                            </td>
                            <td class="action-links">
                                <?php
                                $id = htmlspecialchars($res['id_booking'] ?? '');
                                $status = htmlspecialchars($res['status'] ?? '');

                                if (!empty($id)):
                                    if ($status === 'Menunggu Konfirmasi'):
                                ?>
                                        <a href="<?= BASEURL; ?>/BookingMitra/terima_booking/<?= $id; ?>">Terima</a>
                                        <a href="<?= BASEURL; ?>/BookingMitra/tolak_Boking/<?= $id; ?>">Tolak</a>
                                    <?php
                                    elseif ($status === 'Verifikasi DP'):
                                    ?>
                                        <a href="<?= BASEURL; ?>/BookingMitra/check_dp/<?= $id; ?>">Check DP</a>
                                    <?php
                                    else:
                                    ?>
                                        <a href="<?= BASEURL; ?>/BookingMitra/detail_booking/<?= $id; ?>">Detail</a>
                                <?php
                                    endif;
                                endif;
                                ?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Tidak ada data reservasi.</td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
    </div>

</div>
<div id="offlineBookingModal" class="modal-backdrop">
    <div class="modal-content">
        <form id="formOfflineBooking" action="<?= BASEURL; ?>/BookingMitra/tambahOffline" method="POST">

            <div id="modalStep1" class="modal-step active">
                <div class="modal-header">
                    <h3>Tambah Booking Offline (1/2)</h3>
                    <button type="button" class="modal-close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <legend style="font-size: 1.1rem; font-weight: 600; margin-bottom: 10px;">Data Pelanggan</legend>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap Pelanggan</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No. Telepon (Opsional)</label>
                            <input type="tel" name="no_telp" id="no_telp">
                        </div>
                    </fieldset>
                    <hr style="margin: 20px 0;">
                    <fieldset>
                        <legend style="font-size: 1.1rem; font-weight: 600; margin-bottom: 10px;">Data Booking</legend>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="tgl_mulai">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" id="tgl_mulai" required>
                            </div>
                            <div class="form-group">
                                <label for="tgl_selesai">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" id="tgl_selesai" required>
                            </div>
                        </div>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="paket">Paket</label>
                                <select name="paket" id="paket" required>
                                    <option value="Paket A">Paket A</option>
                                    <option value="Paket B">Paket B</option>
                                    <option value="Paket C">Paket C</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="total_harga">Total Harga</label>
                                <input type="number" name="total_harga" id="total_harga" required>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="btnGoToStep2" class="btn-primary">Lanjut ke Data Kucing &rarr;</button>
                </div>
            </div>

            <div id="modalStep2" class="modal-step">
                <div class="modal-header">
                    <h3>Data Kucing (2/2)</h3>
                    <button type="button" class="modal-close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="cat-forms-container">
                    </div>
                    <button type="button" id="btnAddCat" class="btn-primary" style="width: 100%; margin-top: 15px;">+ Tambah Kucing Lagi</button>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnGoToStep1" class="btn-secondary">&larr; Kembali</button>
                    <button type="submit" class="btn-primary">Simpan Booking</button>
                </div>
            </div>

        </form>
    </div>
</div>

<template id="cat-form-template">
    <div class="cat-form-instance">
        <div class="cat-form-header">
            <h5>Data Kucing</h5>
            <button type="button" class="btn-danger btn-sm btnRemoveCat">Hapus</button>
        </div>
        <div class="form-group">
            <label>Nama Kucing</label>
            <input type="text" name="kucing[INDEX][nama_kucing]" required>
        </div>
        <div class="form-grid-2">
            <div class="form-group">
                <label>Ras</label>
                <input type="text" name="kucing[INDEX][ras]">
            </div>
            <div class="form-group">
                <label>Umur (Tahun)</label>
                <input type="number" name="kucing[INDEX][umur]">
            </div>
        </div>
        <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="kucing[INDEX][jenis_kelamin]" required>
                <option value="Jantan">Jantan</option>
                <option value="Betina">Betina</option>
            </select>
        </div>
        <div class="form-group">
            <label>Keterangan (Ciri-ciri/Kondisi)</label>
            <textarea name="kucing[INDEX][keterangan]" rows="2"></textarea>
        </div>
    </div>
</template>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- SCRIPT TAB FILTER ---
        const tabs = document.querySelectorAll('.tab-item');
        const tableBody = document.getElementById('reservasi-body');
        const rows = tableBody ? tableBody.querySelectorAll('tr') : [];

        function filterReservations(status) {
            let hasVisibleRow = false; // Flag untuk cek apakah ada baris yang tampil

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const isNoDataRow = row.querySelector('td[colspan="9"]');

                if (isNoDataRow) {
                    // Sembunyikan baris "Tidak ada data" untuk sementara
                    row.style.display = 'none';
                    return;
                }

                if (status === 'Semua' || rowStatus === status) {
                    row.style.display = ''; // Tampilkan baris
                    hasVisibleRow = true;
                } else {
                    row.style.display = 'none'; // Sembunyikan baris
                }
            });

            // Tampilkan baris "Tidak ada data" jika tidak ada baris lain yang terlihat
            const noDataRow = tableBody.querySelector('td[colspan="9"]');
            if (noDataRow && !hasVisibleRow) {
                noDataRow.parentElement.style.display = ''; // Tampilkan <tr> induknya
            }
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const status = this.getAttribute('data-status');
                filterReservations(status);
            });
        });

        // Klik tab "Semua" saat awal load
        const allTab = document.querySelector('.tab-item[data-status="Semua"]');
        if (allTab) {
            allTab.click();
        }

        // === JAVASCRIPT BARU UNTUK MODAL ===
        const modal = document.getElementById('offlineBookingModal');
        const btnOpenModal = document.getElementById('btnTambahOffline');
        const btnsCloseModal = document.querySelectorAll('[data-dismiss="modal"]');

        const step1 = document.getElementById('modalStep1');
        const step2 = document.getElementById('modalStep2');
        const btnGoToStep2 = document.getElementById('btnGoToStep2');
        const btnGoToStep1 = document.getElementById('btnGoToStep1');

        const btnAddCat = document.getElementById('btnAddCat');
        const catFormsContainer = document.getElementById('cat-forms-container');
        const catFormTemplate = document.getElementById('cat-form-template');
        let catFormIndex = 0;

        function showModal() {
            if (!modal) return;
            modal.style.display = 'block';
            modal.scrollTop = 0; // Reset scroll
            goToStep(1); // Selalu mulai dari step 1

            // Reset form saat dibuka
            const form = document.getElementById('formOfflineBooking');
            if (form) form.reset();

            if (catFormsContainer) catFormsContainer.innerHTML = ''; // Kosongkan form kucing
            catFormIndex = 0;
            addNewCatForm(); // Tambah satu form kucing pertama
        }

        function closeModal() {
            if (!modal) return;
            modal.style.display = 'none';
        }

        function goToStep(stepNumber) {
            if (step1) step1.classList.toggle('active', stepNumber === 1);
            if (step2) step2.classList.toggle('active', stepNumber === 2);
        }

        function addNewCatForm() {
            if (!catFormTemplate || !catFormsContainer) return;

            // Ambil konten dari template
            const templateContent = catFormTemplate.content.cloneNode(true);
            const newForm = templateContent.querySelector('.cat-form-instance');

            // Ganti 'INDEX' di semua input name
            newForm.innerHTML = newForm.innerHTML.replace(/\[INDEX\]/g, `[${catFormIndex}]`);

            // Tambahkan event listener untuk tombol hapus
            newForm.querySelector('.btnRemoveCat').addEventListener('click', function() {
                // Jangan hapus jika ini adalah form terakhir
                if (catFormsContainer.querySelectorAll('.cat-form-instance').length > 1) {
                    this.closest('.cat-form-instance').remove();
                } else {
                    alert('Minimal harus ada 1 data kucing.');
                }
            });

            catFormsContainer.appendChild(newForm);
            catFormIndex++; // Naikkan index untuk form berikutnya
        }

        // Event Listeners
        if (btnOpenModal) btnOpenModal.addEventListener('click', showModal);

        btnsCloseModal.forEach(btn => btn.addEventListener('click', closeModal));

        if (modal) modal.addEventListener('click', function(e) {
            if (e.target === modal) { // Klik di backdrop
                closeModal();
            }
        });

        if (btnGoToStep2) btnGoToStep2.addEventListener('click', () => goToStep(2));
        if (btnGoToStep1) btnGoToStep1.addEventListener('click', () => goToStep(1));
        if (btnAddCat) btnAddCat.addEventListener('click', addNewCatForm);

        // Validasi form sebelum submit
        const form = document.getElementById('formOfflineBooking');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (catFormsContainer && catFormsContainer.querySelectorAll('.cat-form-instance').length === 0) {
                    alert('Harap tambahkan minimal 1 data kucing.');
                    e.preventDefault(); // Hentikan submit
                    goToStep(2); // Pindah ke step 2
                }
            });
        }

    });
</script>
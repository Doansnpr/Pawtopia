<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="input-group shadow-sm" style="max-width: 400px;">
        <span class="input-group-text bg-white border-end-0 ps-3"><i class="fas fa-search text-muted"></i></span>
        <input type="text" id="searchMitra" class="form-control border-start-0 py-2" placeholder="Cari petshop...">
    </div>
    <a href="<?= BASEURL; ?>/DashboardAdmin?page=manajemen_mitra" class="btn btn-light shadow-sm text-secondary">
        <i class="fas fa-sync-alt"></i> Refresh
    </a>
</div>

<div class="bg-white rounded-4 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="py-3 ps-4 rounded-start">No</th>
                    <th class="py-3">Nama Petshop</th>
                    <th class="py-3">Alamat</th>
                    <th class="py-3">No HP</th>
                    <th class="py-3 text-center">Status</th>
                    <th class="py-3 text-center rounded-end">Aksi / Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Mengambil data mitra dari Controller DashboardAdmin (Pastikan di DashboardAdmin case 'manajemen_mitra' sudah load Model Mitra)
                // Jika belum ada di DashboardAdmin, Anda perlu menambahkannya:
                // $mitraModel = new MitraModel($this->db);
                // $data['mitra'] = $mitraModel->getAllMitra();
                
                $mitraModel = new MitraModel($this->db); // Init Model manual jika controller Dashboard belum handle
                $list_mitra = $mitraModel->getAllMitra();

                $no = 1;
                if (!empty($list_mitra)) :
                    foreach ($list_mitra as $row) : 
                ?>
                <tr>
                    <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                    <td class="fw-bold text-dark">
                        <?= htmlspecialchars($row['nama_petshop']); ?>
                        <div class="small text-muted fw-normal">ID: <?= $row['id_mitra']; ?></div>
                    </td>
                    <td class="small text-secondary" style="max-width: 200px;"><?= htmlspecialchars($row['alamat']); ?></td>
                    <td class="small text-secondary"><?= htmlspecialchars($row['no_hp']); ?></td>
                    
                    <td class="text-center">
                        <?php 
                        $status = $row['status'];
                        $badgeClass = match($status) {
                            'Menunggu Verifikasi' => 'bg-warning text-dark',
                            'Menunggu Pembayaran' => 'bg-info text-white',
                            'Pembayaran Diproses' => 'bg-primary',
                            'Terverifikasi' => 'bg-success',
                            'Ditolak', 'Pembayaran Ditolak' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $badgeClass; ?> rounded-pill px-3 py-2 fw-normal">
                            <?= $status; ?>
                        </span>
                    </td>

                    <td class="text-center">
                        
                        <?php if ($status == 'Menunggu Verifikasi'): ?>
                            <form action="<?= BASEURL; ?>/ManajemenMitra/verifikasi" method="POST" class="d-inline">
                                <input type="hidden" name="id_mitra" value="<?= $row['id_mitra']; ?>">
                                <button type="submit" name="aksi" value="terima" class="btn btn-sm btn-success rounded-pill px-3 mb-1" onclick="return confirm('Terima pendaftaran ini? Status akan menjadi Menunggu Pembayaran.')">
                                    <i class="fas fa-check me-1"></i> Terima
                                </button>
                                <button type="submit" name="aksi" value="tolak" class="btn btn-sm btn-outline-danger rounded-pill px-3 mb-1" onclick="return confirm('Tolak pendaftaran ini?')">
                                    <i class="fas fa-times me-1"></i> Tolak
                                </button>
                            </form>

                        <?php elseif ($status == 'Menunggu Pembayaran'): ?>
                            <button class="btn btn-sm btn-light border text-info shadow-sm rounded-pill px-3" disabled>
                                <i class="fas fa-info-circle me-1"></i> Detail
                            </button>

                        <?php elseif ($status == 'Pembayaran Diproses'): ?>
                            <button type="button" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 btn-cek-bayar"
                                    data-bs-toggle="modal" data-bs-target="#modalCekBayar"
                                    data-id="<?= $row['id_mitra']; ?>"
                                    data-nama="<?= $row['nama_petshop']; ?>"
                                    data-bukti="<?= !empty($row['bukti_pembayaran']) ? BASEURL . '/uploads/bukti/' . $row['bukti_pembayaran'] : ''; ?>">
                                <i class="fas fa-search-dollar me-1"></i> Cek Pembayaran
                            </button>

                        <?php else: ?>
                            <span class="text-muted small"><i class="fas fa-check-double"></i> Selesai</span>
                        <?php endif; ?>

                    </td>
                </tr>
                <?php endforeach; else : ?>
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">Belum ada data mitra.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalCekBayar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: #fd7e14;">
                <h5 class="modal-title fw-bold">Verifikasi Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <h5 id="modalNamaMitra" class="fw-bold text-dark mb-3"></h5>
                
                <div class="p-3 bg-light rounded border mb-3">
                    <p class="text-muted small mb-2">Bukti Pembayaran:</p>
                    <img id="modalBuktiImg" src="" alt="Bukti Transfer" class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: contain;">
                    <p id="noBuktiText" class="text-danger mt-3 d-none">Belum ada bukti yang diupload.</p>
                </div>
                
                <p class="small text-muted">Pastikan bukti transfer valid dan jumlah sesuai.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <form action="<?= BASEURL; ?>/ManajemenMitra/validasiPembayaran" method="POST" class="d-flex gap-2">
                    <input type="hidden" name="id_mitra" id="modalIdMitra">
                    
                    <button type="submit" name="aksi" value="tolak" class="btn btn-outline-danger px-4 rounded-pill" onclick="return confirm('Yakin bukti tidak valid?')">
                        <i class="fas fa-times me-2"></i> Tolak
                    </button>
                    
                    <button type="submit" name="aksi" value="terima" class="btn btn-success px-4 rounded-pill" onclick="return confirm('Konfirmasi pembayaran valid? Mitra akan aktif.')">
                        <i class="fas fa-check me-2"></i> Terima Valid
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search Script
    document.getElementById('searchMitra').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    // Modal Cek Pembayaran Script
    const btnCekBayar = document.querySelectorAll('.btn-cek-bayar');
    btnCekBayar.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const bukti = this.getAttribute('data-bukti');

            document.getElementById('modalIdMitra').value = id;
            document.getElementById('modalNamaMitra').innerText = nama;
            
            const imgEl = document.getElementById('modalBuktiImg');
            const noBuktiEl = document.getElementById('noBuktiText');

            if (bukti) {
                imgEl.src = bukti;
                imgEl.classList.remove('d-none');
                noBuktiEl.classList.add('d-none');
            } else {
                imgEl.classList.add('d-none');
                noBuktiEl.classList.remove('d-none');
            }
        });
    });
});
</script>
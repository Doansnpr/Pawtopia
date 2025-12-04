<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="input-group shadow-sm" style="max-width: 400px;">
        <span class="input-group-text bg-white border-end-0 ps-3"><i class="fas fa-search text-muted"></i></span>
        <input type="text" id="searchMitra" class="form-control border-start-0 py-2" placeholder="Cari nama petshop...">
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
                    <th class="py-3 text-center rounded-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Inisialisasi Model & Ambil Data
                $mitraModel = new MitraModel($this->db);
                $list_mitra = $mitraModel->getAllMitra();
                $no = 1;

                if (!empty($list_mitra)) :
                    foreach ($list_mitra as $row) : 
                        $status = $row['status'];
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
                        $badgeClass = match($status) {
                            'Menunggu Verifikasi' => 'bg-warning text-dark',
                            'Menunggu Pembayaran' => 'bg-info text-white',
                            'Pembayaran Diproses' => 'bg-primary',
                            'Terverifikasi' => 'bg-success',
                            'Ditolak', 'Pembayaran Ditolak' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $badgeClass; ?> rounded-pill px-3 py-2 fw-normal"><?= $status; ?></span>
                    </td>

                    <td class="text-center">
                        
                        <?php if ($status == 'Menunggu Verifikasi'): ?>
                            <form action="<?= BASEURL; ?>/ManajemenMitra/verifikasi" method="POST" class="d-inline">
                                <input type="hidden" name="id_mitra" value="<?= $row['id_mitra']; ?>">
                                <button type="submit" name="aksi" value="terima" class="btn btn-sm btn-success rounded-pill px-3 mb-1" onclick="return confirm('Terima pendaftaran ini?')"><i class="fas fa-check"></i></button>
                                <button type="submit" name="aksi" value="tolak" class="btn btn-sm btn-outline-danger rounded-pill px-3 mb-1" onclick="return confirm('Tolak?')"><i class="fas fa-times"></i></button>
                            </form>

                        <?php elseif ($status == 'Menunggu Pembayaran'): ?>
                            <button type="button" class="btn btn-sm text-white shadow-sm rounded-pill px-3 btn-detail"
                                    style="background-color: #fd7e14; border: none;"
                                    /* --- DATA YANG DIKIRIM KE MODAL --- */
                                    data-nama="<?= htmlspecialchars($row['nama_petshop']); ?>"
                                    data-alamat="<?= htmlspecialchars($row['alamat']); ?>"
                                    data-hp="<?= htmlspecialchars($row['no_hp']); ?>"
                                    data-deskripsi="<?= htmlspecialchars($row['deskripsi'] ?? '-'); ?>"
                                    data-kapasitas="<?= htmlspecialchars($row['kapasitas'] ?? 0); ?>"
                                    /* data-foto SUDAH DIHAPUS */
                                    data-ktp="<?= !empty($row['foto_ktp']) ? BASEURL . '/public/uploads/ktp/' . $row['foto_ktp'] : ''; ?>">
                                <i class="fas fa-info-circle me-1"></i> Detail
                            </button>

                        <?php elseif ($status == 'Pembayaran Diproses'): ?>
                            <button type="button" class="btn btn-sm text-white shadow-sm rounded-pill px-3 btn-cek-bayar"
                                    style="background-color: #fd7e14; border: none;"
                                    data-id="<?= $row['id_mitra']; ?>"
                                    data-nama="<?= htmlspecialchars($row['nama_petshop']); ?>"
                                    data-bukti="<?= !empty($row['bukti_pembayaran']) ? BASEURL . '/public/uploads/bukti/' . $row['bukti_pembayaran'] : ''; ?>">
                                <i class="fas fa-search-dollar me-1"></i> Cek Bayar
                            </button>

                        <?php elseif ($status == 'Terverifikasi'): ?>
                            <button type="button" class="btn btn-sm text-white shadow-sm rounded-pill px-3 btn-detail"
                                    style="background-color: #fd7e14; border: none;"
                                    /* --- DATA YANG DIKIRIM KE MODAL --- */
                                    data-nama="<?= htmlspecialchars($row['nama_petshop']); ?>"
                                    data-alamat="<?= htmlspecialchars($row['alamat']); ?>"
                                    data-hp="<?= htmlspecialchars($row['no_hp']); ?>"
                                    data-deskripsi="<?= htmlspecialchars($row['deskripsi'] ?? '-'); ?>"
                                    data-kapasitas="<?= htmlspecialchars($row['kapasitas'] ?? 0); ?>"
                                    /* data-foto SUDAH DIHAPUS */
                                    data-ktp="<?= !empty($row['foto_ktp']) ? BASEURL . '/public/uploads/ktp/' . $row['foto_ktp'] : ''; ?>">
                                <i class="fas fa-info-circle me-1"></i> Detail
                            </button>

                        <?php else: ?>
                            <span class="text-muted small">Selesai</span>
                        <?php endif; ?>

                    </td>
                </tr>
                <?php endforeach; else : ?>
                <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada data mitra.</td></tr>
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
                    <img id="modalBuktiImg" src="" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                    <p id="noBuktiText" class="text-danger mt-3 d-none">Belum ada bukti.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <form action="<?= BASEURL; ?>/ManajemenMitra/validasiPembayaran" method="POST" class="d-flex gap-2">
                    <input type="hidden" name="id_mitra" id="modalIdMitra">
                    <button type="submit" name="aksi" value="tolak" class="btn btn-outline-danger px-4 rounded-pill">Tolak</button>
                    <button type="submit" name="aksi" value="terima" class="btn btn-success px-4 rounded-pill">Terima Valid</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailMitra" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: #fd7e14;">
                <h5 class="modal-title fw-bold"><i class="fas fa-store me-2"></i> Detail Mitra</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-4 text-center border-end">
                        <div class="mt-3"></div> <h4 id="detailNama" class="fw-bold mb-2 text-dark"></h4>
                        <span class="badge bg-success rounded-pill mb-4 px-3 py-2">Terverifikasi</span>
                        
                        <div class="text-start p-3 bg-light rounded border mt-2">
                            <p class="small fw-bold mb-2 text-secondary"><i class="fas fa-id-card me-1"></i> Foto KTP Pemilik:</p>
                            <img id="detailKtp" src="" class="img-fluid rounded border shadow-sm" style="max-height: 120px; width: 100%; object-fit: cover; cursor: pointer;" onclick="window.open(this.src)">
                            <p id="noKtpText" class="text-danger small d-none mb-0">Tidak ada KTP</p>
                            <small class="text-muted d-block mt-2 text-center" style="font-size: 11px;">*Klik gambar untuk zoom</small>
                        </div>
                    </div>

                    <div class="col-md-8 ps-md-4">
                        <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3">Informasi Umum</h6>
                        
                        <div class="row mb-2">
                            <div class="col-4 text-muted small">Alamat</div>
                            <div class="col-8 fw-bold text-dark small" id="detailAlamat"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-muted small">No HP / WA</div>
                            <div class="col-8 fw-bold text-dark small" id="detailHp"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 text-muted small">Kapasitas</div>
                            <div class="col-8 fw-bold text-dark small"><span id="detailKapasitas"></span> Slot</div>
                        </div>

                        <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3 mt-4">Deskripsi Petshop</h6>
                        <div class="bg-light p-3 rounded border" style="min-height: 80px;">
                            <p id="detailDeskripsi" class="text-muted small mb-0"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Search Logic
    const searchInput = document.getElementById('searchMitra');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
            });
        });
    }

    // 2. Logic Tombol Detail (TANPA FOTO PROFIL)
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            // Ambil data
            const nama = this.getAttribute('data-nama');
            const alamat = this.getAttribute('data-alamat');
            const hp = this.getAttribute('data-hp');
            const deskripsi = this.getAttribute('data-deskripsi');
            const kapasitas = this.getAttribute('data-kapasitas');
            const ktp = this.getAttribute('data-ktp');

            // Isi Data ke Modal
            document.getElementById('detailNama').innerText = nama;
            document.getElementById('detailAlamat').innerText = alamat;
            document.getElementById('detailHp').innerText = hp;
            document.getElementById('detailDeskripsi').innerText = deskripsi;
            document.getElementById('detailKapasitas').innerText = kapasitas;
            
            // Atur Foto KTP
            const ktpEl = document.getElementById('detailKtp');
            const noKtpEl = document.getElementById('noKtpText');

            if(ktp && ktp.trim() !== '') {
                ktpEl.src = ktp;
                ktpEl.style.display = 'block';
                noKtpEl.classList.add('d-none');
            } else {
                ktpEl.style.display = 'none';
                noKtpEl.classList.remove('d-none');
            }

            // Buka Modal Manual
            var modalDetail = new bootstrap.Modal(document.getElementById('modalDetailMitra'));
            modalDetail.show();
        });
    });

    // 3. Logic Tombol Cek Bayar
    document.querySelectorAll('.btn-cek-bayar').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('modalIdMitra').value = this.getAttribute('data-id');
            document.getElementById('modalNamaMitra').innerText = this.getAttribute('data-nama');
            
            const bukti = this.getAttribute('data-bukti');
            const imgEl = document.getElementById('modalBuktiImg');
            const noBuktiEl = document.getElementById('noBuktiText');

            if (bukti && bukti.trim() !== '') {
                imgEl.src = bukti;
                imgEl.classList.remove('d-none');
                noBuktiEl.classList.add('d-none');
            } else {
                imgEl.classList.add('d-none');
                noBuktiEl.classList.remove('d-none');
            }

            var modalBayar = new bootstrap.Modal(document.getElementById('modalCekBayar'));
            modalBayar.show();
        });
    });

});
</script>
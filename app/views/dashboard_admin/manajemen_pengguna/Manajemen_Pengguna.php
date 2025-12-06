<div class="bg-white rounded-4 shadow-sm p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        
        <div class="input-group shadow-sm" style="max-width: 400px; width: 100%;">
            <span class="input-group-text bg-white border-end-0 ps-3">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" id="searchInput" class="form-control border-start-0 py-2" placeholder="Cari nama atau email...">
        </div>
        
    </div>
</div>

<div class="bg-white rounded-4 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="py-3 ps-4 rounded-start" style="width: 5%;">No</th>
                    <th class="py-3" style="width: 25%;">Nama Lengkap</th>
                    <th class="py-3" style="width: 15%;">Role</th>
                    <th class="py-3" style="width: 25%;">Email</th>
                    <th class="py-3" style="width: 15%;">No HP</th>
                    <th class="py-3 text-center rounded-end" style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if (!empty($data['users'])) :
                    foreach ($data['users'] as $user) : 
                ?>
                <tr>
                    <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                    <td class="fw-bold text-dark"><?= htmlspecialchars($user['nama_lengkap']); ?></td>
                    <td>
                        <?php 
                            // Menggunakan warna badge yang lebih soft/modern
                            $roleClass = match($user['role']) {
                                'admin' => 'bg-danger-subtle text-danger border border-danger', // Merah soft
                                'mitra' => 'bg-warning-subtle text-warning-emphasis border border-warning', // Kuning soft
                                default => 'bg-info-subtle text-info-emphasis border border-info' // Biru soft
                            };
                        ?>
                        <span class="badge <?= $roleClass; ?> rounded-pill px-3 py-2 fw-normal">
                            <?= ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td class="text-secondary"><?= htmlspecialchars($user['email']); ?></td>
                    <td class="text-secondary"><?= htmlspecialchars($user['no_hp']); ?></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-light border text-warning shadow-sm btn-edit" 
                                    data-bs-toggle="modal" data-bs-target="#modalEdit"
                                    data-id="<?= $user['id_users']; ?>"
                                    data-nama="<?= $user['nama_lengkap']; ?>"
                                    data-email="<?= $user['email']; ?>"
                                    data-hp="<?= $user['no_hp']; ?>"
                                    data-role="<?= $user['role']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="<?= BASEURL; ?>/ManajemenPengguna/hapus/<?= $user['id_users']; ?>" 
                               class="btn btn-sm btn-light border text-danger shadow-sm" 
                               onclick="return confirm('Hapus pengguna ini?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else : ?>
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="py-4">
                            <i class="fas fa-inbox fa-3x mb-3 text-light"></i><br>
                            <span class="fw-bold">Belum ada data pengguna.</span>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">Edit Pengguna</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASEURL; ?>/ManajemenPengguna/ubah" method="POST">
                <div class="modal-body bg-white">
                    <input type="hidden" name="id_users" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">No HP</label>
                            <input type="text" name="no_hp" id="edit_hp" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Role</label>
                            <select name="role" id="edit_role" class="form-select">
                                <option value="user">Customer</option>
                                <option value="mitra">Mitra</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="***">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script Modal Edit
    document.addEventListener('DOMContentLoaded', function () {
        var editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('edit_id').value = this.getAttribute('data-id');
                document.getElementById('edit_nama').value = this.getAttribute('data-nama');
                document.getElementById('edit_email').value = this.getAttribute('data-email');
                document.getElementById('edit_hp').value = this.getAttribute('data-hp');
                document.getElementById('edit_role').value = this.getAttribute('data-role');
            });
        });

        // Search Script
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let val = this.value.toLowerCase();
            let rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
            });
        });
    });
</script>
<?php 
// --- Data Kucing (Tidak Berubah) ---
$activeCats = [
    [
        'id_booking' => 101,
        'nama_kucing' => 'Mochi',
        'ras_kucing' => 'Persia',
        'status_kucing' => 'Dalam Perawatan', // Status saat ini
        'foto_kucing' => 'mochi.jpg' // Placeholder nama file
    ],
    [
        'id_booking' => 102,
        'nama_kucing' => 'Luna',
        'ras_kucing' => 'Maine Coon',
        'status_kucing' => 'Sedang Dimandikan',
        'foto_kucing' => 'luna.jpg' 
    ],
    [
        'id_booking' => 103,
        'nama_kucing' => 'Oreo',
        'ras_kucing' => 'British Shorthair',
        'status_kucing' => 'Siap Dijemput',
        'foto_kucing' => 'oreo.jpg' 
    ],
    [
        'id_booking' => 104,
        'nama_kucing' => 'Kitty',
        'ras_kucing' => 'Anggora',
        'status_kucing' => 'Dalam Perawatan', 
        'foto_kucing' => 'kitty.jpg' 
    ],
    [
        'id_booking' => 105,
        'nama_kucing' => 'Tiger',
        'ras_kucing' => 'Bengali',
        'status_kucing' => 'Sedang Dimandikan', 
        'foto_kucing' => 'tiger.jpg' 
    ],
];

// --- Status yang Diizinkan (Urutan ini penting untuk kolom Kanban) ---
$allowedStatuses = [
    'Sedang Menginap',
    'Layanan Grooming',
    'Perhatian Khusus',
    'Siap Dijemput',
    'Selesai' // Opsi untuk menandai penitipan telah selesai
];

// Menghitung status (tetap berguna untuk informasi umum)
$catStatusCounts = array_count_values(array_column($activeCats, 'status_kucing'));
$catStatusCounts['Semua'] = count($activeCats);

$title = 'Manajemen Status Kucing';
?>

<style>
/* --- Variabel CSS (Jika Ada) --- */
:root {
    --text-dark: #343a40;
    --text-gray: #6c757d;
    --border-color: #dee2e6;
    --light-bg: #f8f9fa;
    --bg-light-yellow: #fff3cd; /* Warna default untuk gambar kucing */
}

.reservasi-content {
    padding: 20px 30px 30px; 
}
.reservasi-header {
    margin-bottom: 20px; /* Sedikit jarak dengan search/kanban */
}
.reservasi-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-dark);
}

/* Bagian Search Box (tetap ada) */
.search-container {
    padding: 0;
    margin-bottom: 20px; /* Sesuaikan jarak */
}
.search-input {
    width: 100%;
    padding: 12px 15px;
    padding-left: 40px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    background-color: var(--light-bg);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}
.search-box-wrapper {
    position: relative;
}
.search-box-wrapper::before {
    content: "🔍";
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: var(--text-gray);
}

/* --- KANBAN BOARD STYLES --- */
.kanban-board {
    display: flex;
    gap: 20px; /* Jarak antar kolom */
    overflow-x: auto; /* Agar bisa digulir jika kolom banyak */
    padding-bottom: 10px; /* Ruang untuk scrollbar */
}

.kanban-column {
    flex: 0 0 300px; /* Lebar kolom tetap, tidak fleksibel membesar */
    background-color: var(--light-bg);
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    min-height: 200px; /* Agar kolom terlihat */
    display: flex;
    flex-direction: column;
}

.kanban-column-header {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.column-count {
    font-size: 0.9rem;
    color: var(--text-gray);
    background-color: #e9ecef;
    padding: 3px 8px;
    border-radius: 5px;
}

.kanban-column-body {
    flex-grow: 1; /* Agar body memenuhi sisa tinggi kolom */
    display: flex;
    flex-direction: column;
    gap: 15px; /* Jarak antar kartu dalam kolom */
    min-height: 100px; /* Penting untuk drag/drop ke kolom kosong */
}

.cat-item-card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
    cursor: grab; /* Menandakan bisa di-drag */
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid transparent; /* Untuk indikasi drag */
}

.cat-item-card:hover {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
}

/* Style saat kartu sedang di-drag */
.cat-item-card.dragging {
    opacity: 0.5;
    transform: rotate(2deg);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

/* Style untuk indikator drop zone (di antara kartu atau di kolom kosong) */
.kanban-column-body.drag-over {
    background-color: rgba(0, 123, 255, 0.1); /* Biru muda */
    border: 1px dashed var(--primary-blue);
}

/* Info Kucing dalam Kartu */
.cat-info {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.cat-status {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-gray);
    margin-bottom: 2px;
}
.cat-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-dark);
}
.cat-race {
    font-size: 0.85rem;
    color: var(--text-gray);
}

/* Gambar Kucing dalam Kartu */
.cat-image-wrapper {
    width: 60px; /* Ukuran lebih kecil untuk kartu */
    height: 60px;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
    background-color: var(--bg-light-yellow);
    flex-shrink: 0; /* Agar gambar tidak mengecil saat info panjang */
}
.cat-image-wrapper img {
    width: 100%; 
    height: 100%;
    object-fit: cover;
    position: absolute; /* Tetap relatif terhadap wrapper */
    top: 0;
    left: 0;
}
/* Menyesuaikan gambar untuk status Siap Dijemput */
/* Ini akan diatur ulang oleh JS saat kartu dipindahkan */
.cat-item-card[data-status*="Siap Dijemput"] .cat-image-wrapper {
    background-color: #d8c29d; 
}

/* Jika tidak ada kucing */
.no-cats-message {
    color: var(--text-gray);
    text-align: center;
    padding: 20px;
    font-style: italic;
    background-color: #f0f0f0;
    border-radius: 8px;
    margin-top: 10px;
}

</style>

<div class="reservasi-content"> 
    <div class="reservasi-header">
        <h1><?= $title; ?></h1>  
    </div>

    <div class="search-container">
        <div class="search-box-wrapper">
            <input type="text" class="search-input" id="cari-kucing" placeholder="Cari kucing berdasarkan nama atau ras...">
        </div>
    </div>
    
    <div class="kanban-board" id="kanban-board">
        <?php foreach ($allowedStatuses as $status): 
            $columnId = strtolower(str_replace(' ', '-', $status)); // ID kolom dari status
        ?>
            <div class="kanban-column" data-status="<?= htmlspecialchars($status); ?>" id="column-<?= $columnId; ?>">
                <div class="kanban-column-header">
                    <span><?= htmlspecialchars($status); ?></span>
                    <span class="column-count" id="count-<?= $columnId; ?>">
                        <?= $catStatusCounts[$status] ?? 0; ?>
                    </span>
                </div>
                <div class="kanban-column-body">
                    <?php 
                    $hasCatsInColumn = false;
                    foreach ($activeCats as $cat): 
                        if ($cat['status_kucing'] === $status):
                            $hasCatsInColumn = true;
                    ?>
                        <div class="cat-item-card" 
                             draggable="true" 
                             data-status="<?= htmlspecialchars($cat['status_kucing']); ?>" 
                             data-id="<?= htmlspecialchars($cat['id_booking']); ?>"
                             data-name="<?= htmlspecialchars($cat['nama_kucing']); ?>"
                             data-race="<?= htmlspecialchars($cat['ras_kucing']); ?>">
                            <div class="cat-image-wrapper">
                                <img src="/assets/img/kucing/<?= htmlspecialchars($cat['foto_kucing'] ?? 'default.jpg'); ?>" alt="Foto <?= htmlspecialchars($cat['nama_kucing']); ?>">
                            </div>
                            <div class="cat-info">
                                <div class="cat-name"><?= htmlspecialchars($cat['nama_kucing']); ?></div>
                                <div class="cat-race">Ras: <?= htmlspecialchars($cat['ras_kucing']); ?></div>
                                </div>
                        </div>
                    <?php 
                        endif;
                    endforeach; 

                    if (!$hasCatsInColumn):
                    ?>
                        <div class="no-cats-message">Tidak ada kucing di status ini.</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columns = document.querySelectorAll('.kanban-column');
        const searchInput = document.getElementById('cari-kucing');
        let draggedItem = null; // Menyimpan kartu yang sedang di-drag

        // --- Fungsi untuk memperbarui jumlah kucing di setiap kolom ---
        function updateColumnCounts() {
            columns.forEach(column => {
                const columnId = column.id;
                const countElement = document.getElementById(`count-${columnId}`);
                const visibleCats = column.querySelectorAll('.cat-item-card[style*="display: flex"], .cat-item-card:not([style*="display: none"])').length;
                countElement.textContent = visibleCats;
                
                const body = column.querySelector('.kanban-column-body');
                const noCatsMessage = column.querySelector('.no-cats-message');
                if (visibleCats === 0) {
                    if (!noCatsMessage) {
                        const msg = document.createElement('div');
                        msg.classList.add('no-cats-message');
                        msg.textContent = 'Tidak ada kucing di status ini.';
                        body.appendChild(msg);
                    } else {
                        noCatsMessage.style.display = 'block';
                    }
                } else {
                    if (noCatsMessage) {
                        noCatsMessage.style.display = 'none';
                    }
                }
            });
        }

        // --- Fungsi Filter Pencarian ---
        function filterCatsBySearch(searchText) {
            const query = searchText.toLowerCase();

            columns.forEach(column => {
                const columnCats = column.querySelectorAll('.cat-item-card');
                columnCats.forEach(item => {
                    const catName = item.getAttribute('data-name').toLowerCase();
                    const catRace = item.getAttribute('data-race').toLowerCase();
                    
                    if (catName.includes(query) || catRace.includes(query)) {
                        item.style.display = 'flex'; // Tampilkan kartu
                    } else {
                        item.style.display = 'none'; // Sembunyikan kartu
                    }
                });
            });
            updateColumnCounts(); // Perbarui jumlah setelah filter
        }

        // --- Event Listeners untuk Drag & Drop ---
        columns.forEach(column => {
            // Drag Start
            column.addEventListener('dragstart', (e) => {
                if (e.target.classList.contains('cat-item-card')) {
                    draggedItem = e.target;
                    setTimeout(() => {
                        e.target.classList.add('dragging'); // Tambah kelas dragging setelah jeda
                    }, 0);
                    e.dataTransfer.setData('text/plain', e.target.dataset.id); // Simpan ID kucing
                    e.dataTransfer.effectAllowed = 'move';
                }
            });

            // Drag End (saat dilepas)
            column.addEventListener('dragend', () => {
                if (draggedItem) {
                    draggedItem.classList.remove('dragging');
                    draggedItem = null;
                }
                updateColumnCounts(); // Perbarui jumlah setelah drag
            });

            // Drag Over (saat item di atas kolom atau area drop)
            column.addEventListener('dragover', (e) => {
                e.preventDefault(); // Mencegah default untuk mengizinkan drop
                const columnBody = column.querySelector('.kanban-column-body');
                
                // Mendapatkan elemen terdekat di mana kartu bisa diletakkan
                const afterElement = getDragAfterElement(columnBody, e.clientY);
                
                if (afterElement == null) {
                    columnBody.appendChild(draggedItem);
                } else {
                    columnBody.insertBefore(draggedItem, afterElement);
                }

                // Tambahkan indikator visual untuk drop zone
                columnBody.classList.add('drag-over');
            });

            // Drag Leave
            column.addEventListener('dragleave', (e) => {
                const columnBody = column.querySelector('.kanban-column-body');
                columnBody.classList.remove('drag-over');
            });

            // Drop
            column.addEventListener('drop', (e) => {
                e.preventDefault();
                const columnBody = column.querySelector('.kanban-column-body');
                columnBody.classList.remove('drag-over'); // Hapus indikator visual

                if (draggedItem) {
                    const newStatus = column.getAttribute('data-status');
                    const catId = draggedItem.getAttribute('data-id');

                    // 1. Perbarui data-status pada elemen kartu yang di-drag
                    draggedItem.setAttribute('data-status', newStatus);
                    
                    // 2. Kirim update ke server (menggunakan AJAX)
                    // Anda perlu membuat fungsi `sendUpdateToServer` ini
                    sendUpdateToServer(catId, newStatus); 

                    console.log(`Kucing ID: ${catId} dipindahkan ke status: ${newStatus}`);
                }
            });
        });

        // Fungsi pembantu untuk menentukan posisi drop (di antara kartu)
        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.cat-item-card:not(.dragging)')];

            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: -Infinity }).element;
        }

        // --- Fungsi untuk Mengirim Update Status ke Server ---
        // Anda harus mengimplementasikan bagian ini menggunakan AJAX (Fetch API atau jQuery.ajax)
        // Fungsi ini akan dipanggil saat kartu berhasil di-drop ke kolom baru.
        function sendUpdateToServer(catId, newStatus) {
            // Contoh menggunakan Fetch API
            fetch('<?= BASEURL; ?>/DashboardMitra/updateCatStatus', { // Ganti dengan URL endpoint Anda
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // 'X-Requested-With': 'XMLHttpRequest' // Jika server Anda memerlukan header ini
                },
                body: JSON.stringify({
                    id_booking: catId,
                    status_kucing: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Status kucing berhasil diperbarui di database.');
                    // Anda bisa menambahkan notifikasi sukses di sini
                } else {
                    console.error('Gagal memperbarui status:', data.message);
                    // Tambahkan notifikasi error jika gagal
                    // Atau kembalikan kartu ke posisi semula
                }
            })
            .catch(error => {
                console.error('Terjadi kesalahan saat mengirim permintaan:', error);
                // Tambahkan notifikasi error jaringan
                // Atau kembalikan kartu ke posisi semula
            });
        }

        // --- Event Listener untuk Pencarian ---
        searchInput.addEventListener('keyup', function() {
            filterCatsBySearch(this.value);
        });

        // Panggil update counts saat halaman dimuat
        updateColumnCounts();
    });
</script>
<div class="ulasan-container">
  <!-- BUTTON TAMBAH -->
  <button id="openFormBtn" class="btn-add">+ Tambah Ulasan</button>

  <!-- HISTORY ULASAN -->
  <div class="history-wrapper">
    <?php if (!empty($data['ulasan'])): ?>
      <?php foreach ($data['ulasan'] as $u): ?>
        <div class="ulasan-card history">
          <h3>Ulasan Kamu</h3>
          <div class="stars-history">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <span class="<?= ($i <= $u['rating']) ? 'active' : ''; ?>">★</span>
            <?php endfor; ?>
          </div>
          <p class="history-text"><?= htmlspecialchars($u['komentar']); ?></p>

          <div class="button-group">
            <button class="btn-ulasan btnEdit"
              data-id="<?= $u['id_ulasan']; ?>"
              data-rating="<?= $u['rating']; ?>"
              data-komentar="<?= htmlspecialchars($u['komentar']); ?>">
              Perbarui Ulasan
            </button>

            <form method="POST" action="<?= BASEURL; ?>/DashboardCustomer/ulasan">
              <input type="hidden" name="mode" value="hapus">
              <input type="hidden" name="id_ulasan" value="<?= $u['id_ulasan']; ?>">
              <button type="submit" class="btn-ulasan btn-delete" onclick="return confirm('Yakin ingin menghapus ulasan ini?');">
                Hapus
              </button>
            </form>
          </div>

        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- POPUP FORM -->
  <div id="popupUlasan" class="popup">
    <div class="popup-content">
      <span id="closePopup" class="close-btn">&times;</span>

      <div class="ulasan-card form-card">
        <div class="ulasan-header">
          <h2>Bagikan Pengalaman Anda</h2>
          <p class="subtext">Pendapat Anda membantu kami menjadi lebih baik</p>
        </div>

        <form method="POST" action="<?= BASEURL; ?>/DashboardCustomer/ulasan" id="formUlasan">
          <input type="hidden" name="mode" id="modeInput" value="baru">
          <input type="hidden" name="id_ulasan" id="idUlasanInput" value="">

          <div class="rating-stars" id="ratingStars">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <span data-value="<?= $i; ?>">★</span>
            <?php endfor; ?>
          </div>

          <input type="hidden" name="rating" id="ratingInput" value="0">
          <textarea name="komentar" id="komentarInput" placeholder="Tulis komentar/masukan Anda di sini..."></textarea>

          <button type="submit" class="btn-ulasan" id="btnSubmit">Kirim Ulasan</button>
        </form>

        <!-- Flash Message -->
        <?php if(!empty($data['flash'])): ?>
          <p style="color: <?= $data['flash']['tipe'] === 'sukses' ? 'green' : 'red'; ?>; margin-top:10px;">
            <?= htmlspecialchars($data['flash']['pesan']); ?>
          </p>
        <?php endif; ?>

      </div>
    </div>
  </div>

</div>

<style>
body {
  background: linear-gradient(180deg, #fff8e1, #fff3cd);
  font-family: 'Poppins', sans-serif;
}

.ulasan-container {
  padding: 40px;
  text-align: center;
}

.btn-add {
  background: #ffa726;
  border: none;
  color: white;
  padding: 12px 20px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  margin-bottom: 20px;
}

.popup {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.8);
  justify-content: center;
  align-items: center;
  animation: fadeIn .3s ease;
  z-index: 9999;
}

.popup-content {
  background: white;
  padding: 25px;
  border-radius: 25px;
  width: 90%;
  max-width: 430px;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
  animation: scaleUp .3s ease;
  z-index: 10000;
}

.close-btn {
  position: absolute;
  top: 12px;
  right: 15px;
  cursor: pointer;
  font-size: 26px;
  font-weight: bold;
  color: #ff5252;
  z-index: 10001;
}

.ulasan-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 6px 20px rgba(255, 183, 77, 0.3);
  padding: 40px;
  text-align: center;
}

.rating-stars {
  display: flex;
  justify-content: center;
  gap: 8px;
  font-size: 35px;
  color: #ddd;
  margin-bottom: 15px;
  cursor: pointer;
}
.rating-stars span { transition: .2s ease; }
.rating-stars span:hover { transform: scale(1.2); }
.rating-stars span.active { color: #ffc107; }

textarea {
  width: 100%;
  min-height: 100px;
  border: 2px solid #ffe082;
  border-radius: 12px;
  padding: 10px;
  resize: none;
  font-size: 14px;
  outline: none;
  margin-bottom: 20px;
}

textarea:focus {
  border-color: #ffc107;
  box-shadow: 0 0 6px rgba(255,193,7,0.4);
}

.btn-ulasan {
  background: #ffc107;
  border: none;
  color: white;
  font-weight: 600;
  padding: 12px 30px;
  border-radius: 10px;
  cursor: pointer;
  transition: .3s;
}

.btn-ulasan:hover {
  background: #ffb300;
  transform: translateY(-2px);
}

.button-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  align-items: stretch;
}

.button-group button {
  width: 100%;
}

.btn-delete {
  background: #f11313ff;
}

.btn-delete:hover {
  background: #d10000;
}

/* HISTORY GRID */
.history-wrapper {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 25px;
  justify-items: center;
  position: relative;
  z-index: 1;
}

.ulasan-card.history {
  width: 260px;
  background: #fff;
  border-radius: 18px;
  padding: 20px;
  text-align: center;
  border: 3px solid #ffa726;
  box-shadow: 
    0 4px 12px rgba(255, 167, 38, 0.3),
    0 0 30px rgba(255, 167, 38, 0.2),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  transition: .3s ease;
  position: relative;
  overflow: hidden;
}

.ulasan-card.history::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, #ffa726, #ff9800, #fb8c00);
  box-shadow: 0 0 10px rgba(255, 167, 38, 0.6);
}

.ulasan-card.history::after {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(
    45deg,
    transparent 30%,
    rgba(255, 167, 38, 0.1) 50%,
    transparent 70%
  );
  transform: rotate(45deg);
  animation: shimmer 3s infinite;
}

.ulasan-card.history:hover {
  transform: translateY(-8px);
  box-shadow: 
    0 8px 25px rgba(255, 167, 38, 0.5),
    0 0 40px rgba(255, 167, 38, 0.4),
    0 0 60px rgba(255, 152, 0, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.9);
  border-color: #ff9800;
}

.ulasan-card.history h3 {
  color: #ff9800;
  font-size: 18px;
  margin-bottom: 12px;
  font-weight: 600;
  position: relative;
  z-index: 2;
}

.stars-history {
  display: flex;
  justify-content: center;
  gap: 5px;
  font-size: 24px;
  margin: 12px 0;
  position: relative;
  z-index: 2;
}

.stars-history span {
  color: #e0e0e0;
  transition: .2s;
}

.stars-history span.active {
  color: #ffa726;
  text-shadow: 0 2px 4px rgba(255, 167, 38, 0.3);
}

.history-text {
  color: #666;
  font-size: 14px;
  line-height: 1.6;
  margin: 15px 0;
  min-height: 60px;
  position: relative;
  z-index: 2;
}

@media(max-width:850px){
  .history-wrapper { grid-template-columns: repeat(2,1fr); }
}
@media(max-width:600px){
  .history-wrapper { grid-template-columns: 1fr; }
}

@keyframes fadeIn { from {opacity:0;} to {opacity:1;} }
@keyframes scaleUp { from {transform:scale(.8);} to {transform:scale(1);} }
@keyframes shimmer {
  0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
  100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
}
</style>

<script>
// =========================
// VARIABEL UTAMA
// =========================
const stars = document.querySelectorAll('#ratingStars span');
const ratingInput = document.getElementById('ratingInput');
const idField = document.getElementById('idUlasanInput');
const popup = document.getElementById('popupUlasan');
const punyaBookingSelesai = <?= $data['punyaBookingSelesai'] ? 'true' : 'false'; ?>;

// =========================
// RATING STARS
// =========================
stars.forEach((star, index) => {
    star.addEventListener('click', () => {
        ratingInput.value = index + 1;
        stars.forEach((s, i) => s.classList.toggle('active', i <= index));
    });
});

// =========================
// BUKA POPUP ULASAN BARU
// =========================
document.getElementById('openFormBtn').onclick = () => {
    if (!punyaBookingSelesai) {
        alert("Kamu hanya bisa memberikan ulasan setelah penitipan selesai.");
        return;
    }
    
    // Sembunyikan history wrapper
    document.querySelector('.history-wrapper').style.display = 'none';
    
    popup.style.display = 'flex';
    document.getElementById('formUlasan').reset();
    idField.value = "";
    stars.forEach(s => s.classList.remove('active'));
    ratingInput.value = 0;
    document.getElementById('modeInput').value = "baru";
    document.getElementById('btnSubmit').innerText = "Kirim Ulasan";
};

// =========================
// TUTUP POPUP
// =========================
document.getElementById('closePopup').onclick = () => {
    popup.style.display = 'none';
    // Tampilkan kembali history wrapper
    document.querySelector('.history-wrapper').style.display = 'grid';
};

// Tutup popup jika klik di luar popup-content
popup.onclick = (e) => {
    if (e.target === popup) {
        popup.style.display = 'none';
        // Tampilkan kembali history wrapper
        document.querySelector('.history-wrapper').style.display = 'grid';
    }
};

// =========================
// EDIT ULASAN
// =========================
document.querySelectorAll('.btnEdit').forEach(btn => {
    btn.onclick = (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        // Sembunyikan history wrapper
        document.querySelector('.history-wrapper').style.display = 'none';
        
        // Tampilkan popup
        popup.style.display = 'flex';

        idField.value = btn.dataset.id;
        document.getElementById('komentarInput').value = btn.dataset.komentar;

        stars.forEach(s => s.classList.remove('active'));
        const r = parseInt(btn.dataset.rating);
        ratingInput.value = r;
        stars.forEach((s, i) => s.classList.toggle('active', i < r));

        document.getElementById('modeInput').value = 'perbarui';
        document.getElementById('btnSubmit').innerText = 'Perbarui Ulasan';
    };
});
</script>
<div class="ulasan-container">

  <!-- BUTTON TAMBAH -->
  <button id="openFormBtn" class="btn-add">+ Tambah Ulasan</button>

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
      </div>
    </div>
  </div>

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
          <button class="btn-ulasan btnEdit"
            data-id="<?= $u['id_ulasan']; ?>"
            data-rating="<?= $u['rating']; ?>"
            data-komentar="<?= htmlspecialchars($u['komentar']); ?>">
            Perbarui Ulasan
          </button>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
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
  background: rgba(0,0,0,0.6);
  justify-content: center;
  align-items: center;
  animation: fadeIn .3s ease;
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
}

.close-btn {
  position: absolute;
  top: 12px;
  right: 15px;
  cursor: pointer;
  font-size: 26px;
  font-weight: bold;
  color: #ff5252;
  z-index: 10;
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

/* HISTORY GRID */
.history-wrapper {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 25px;
  justify-items: center;
}

.ulasan-card.history {
  width: 260px;
  background: #fff;
  border-radius: 18px;
  padding: 18px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(255, 183, 77, 0.25);
  transition: .25s ease;
}

.ulasan-card.history:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 16px rgba(255, 183, 77, 0.35);
}

.stars-history span.active {
  color: #ffc107;
}

@media(max-width:850px){
  .history-wrapper { grid-template-columns: repeat(2,1fr); }
}
@media(max-width:600px){
  .history-wrapper { grid-template-columns: 1fr; }
}

@keyframes fadeIn { from {opacity:0;} to {opacity:1;} }
@keyframes scaleUp { from {transform:scale(.8);} to {transform:scale(1);} }
</style>

<script>
// ⭐ Rating
const stars = document.querySelectorAll('#ratingStars span');
const ratingInput = document.getElementById('ratingInput');
const idField = document.getElementById('idUlasanInput');

stars.forEach((star, index) => {
  star.addEventListener('click', () => {
    ratingInput.value = index + 1;
    stars.forEach((s, i) => s.classList.toggle('active', i <= index));
  });
});

const popup = document.getElementById('popupUlasan');

document.getElementById('openFormBtn').onclick = () => {
  popup.style.display = 'flex';
  document.getElementById('formUlasan').reset();
  idField.value = "";
  stars.forEach(s => s.classList.remove('active'));
  document.getElementById('modeInput').value = "baru";
  document.getElementById('btnSubmit').innerText = "Kirim Ulasan";
};

document.getElementById('closePopup').onclick = () => popup.style.display = 'none';

// Edit
document.querySelectorAll('.btnEdit').forEach(btn => {
  btn.onclick = () => {
    popup.style.display = 'flex';

    idField.value = btn.dataset.id;
    document.getElementById('komentarInput').value = btn.dataset.komentar;

    // --- FIX YANG PALING PENTING ---
    stars.forEach(s => s.classList.remove('active'));

    const r = parseInt(btn.dataset.rating);
    ratingInput.value = r;
    stars.forEach((s, i) => s.classList.toggle('active', i < r));

    document.getElementById('modeInput').value = 'perbarui';
    document.getElementById('btnSubmit').innerText = 'Perbarui Ulasan';
  };
});
</script>

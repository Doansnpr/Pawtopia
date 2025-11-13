<div class="ulasan-container">
  <!-- 📝 FORM ULASAN -->
  <div class="ulasan-card form-card">
    <div class="ulasan-header">
      <img src="<?= BASEURL; ?>/images/cat-feedback.png" alt="logo" class="cat-icon">
      <h2>Bagikan Pengalaman Anda</h2>
      <p class="subtext">Pendapat Anda membantu kami menjadi lebih baik 💛</p>
    </div>

    <!-- ✅ Flash message -->
    <?php if (!empty($data['flash'])): ?>
      <div class="flash-msg"><?= $data['flash']['pesan']; ?></div>
    <?php endif; ?>

    <!-- ⭐ FORM -->
    <form method="POST" action="<?= BASEURL; ?>/DashboardCustomer/ulasan" id="formUlasan">
      <input type="hidden" name="mode" id="modeInput" value="baru">

      <div class="rating-stars" id="ratingStars">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <span data-value="<?= $i; ?>">★</span>
        <?php endfor; ?>
      </div>

      <input type="hidden" name="rating" id="ratingInput" value="0">

      <p class="question">Seberapa puas Anda dengan layanan kami?</p>
      <textarea name="komentar" id="komentarInput" placeholder="Tulis komentar/masukan Anda di sini..."></textarea>

      <button type="submit" class="btn-ulasan" id="btnSubmit">Kirim Ulasan</button>
    </form>
  </div>

  <!-- 💬 HISTORY ULASAN -->
  <?php if (!empty($data['ulasan'])): ?>
  <div class="ulasan-card history" id="boxUlasan">
    <h3>Ulasan Kamu</h3>
    <div class="stars-history">
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <span class="<?= ($i <= $data['ulasan']['rating']) ? 'active' : ''; ?>">★</span>
      <?php endfor; ?>
    </div>
    <p class="history-text">"<?= htmlspecialchars($data['ulasan']['komentar']); ?>"</p>

    <div class="history-btn">
      <button class="btn-ulasan" id="btnEdit">Perbarui Ulasan</button>
    </div>
  </div>
  <?php endif; ?>
</div>

<style>
body {
  background: linear-gradient(180deg, #fff8e1, #fff3cd);
  font-family: 'Poppins', sans-serif;
}

/* === CONTAINER FLEX SEJAJAR === */
.ulasan-container {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  gap: 40px;
  flex-wrap: wrap;
  padding: 50px 20px;
}

/* === CARD UMUM === */
.ulasan-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 6px 20px rgba(255, 183, 77, 0.3);
  padding: 40px;
  width: 420px;
  text-align: center;
  animation: fadeIn 0.6s ease;
}

/* === HEADER FORM === */
.cat-icon {
  width: 70px;
  margin-bottom: 10px;
}

.ulasan-header h2 {
  color: #e0a500;
  font-weight: 700;
  margin-bottom: 5px;
}

.subtext {
  font-size: 14px;
  color: #8b6f37;
  margin-bottom: 20px;
}

/* === FLASH MESSAGE === */
.flash-msg {
  background: #fff3cd;
  color: #856404;
  border: 1px solid #ffeeba;
  padding: 10px;
  margin-bottom: 20px;
  border-radius: 10px;
  animation: fadeIn 0.5s ease;
}

/* === RATING === */
.rating-stars {
  display: flex;
  justify-content: center;
  gap: 8px;
  font-size: 35px;
  color: #ddd;
  margin-bottom: 15px;
  cursor: pointer;
}

.rating-stars span {
  transition: transform 0.2s ease, color 0.2s ease;
}

.rating-stars span:hover {
  transform: scale(1.2);
}

.rating-stars span.active {
  color: #ffc107;
}

/* === TEXTAREA & BUTTON === */
textarea {
  width: 100%;
  min-height: 100px;
  border: 2px solid #ffe082;
  border-radius: 12px;
  padding: 10px;
  resize: none;
  font-size: 14px;
  margin-bottom: 20px;
  outline: none;
}

textarea:focus {
  border-color: #ffc107;
  box-shadow: 0 0 6px rgba(255, 193, 7, 0.5);
}

.btn-ulasan {
  background: #ffc107;
  border: none;
  color: white;
  font-weight: 600;
  padding: 12px 30px;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-ulasan:hover {
  background: #ffb300;
  transform: translateY(-2px);
}

/* === HISTORY === */
.history {
  border-left: 6px solid #ffc107;
  text-align: center;
  animation: fadeInRight 0.7s ease;
}

.history h3 {
  color: #e0a500;
  margin-bottom: 15px;
}

.stars-history {
  display: flex;
  justify-content: center;
  gap: 6px;
  font-size: 25px;
  margin-bottom: 10px;
}

.stars-history span {
  color: #ddd;
}

.stars-history span.active {
  color: #ffc107;
}

.history-text {
  font-style: italic;
  color: #555;
  margin-bottom: 10px;
}

.history-btn {
  margin-top: 15px;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInRight {
  from { opacity: 0; transform: translateX(40px); }
  to { opacity: 1; transform: translateX(0); }
}

/* === RESPONSIVE === */
@media (max-width: 900px) {
  .ulasan-container {
    flex-direction: column;
    align-items: center;
  }
  .history {
    margin-top: 20px;
  }
}
</style>

<script>
const stars = document.querySelectorAll('#ratingStars span');
const ratingInput = document.getElementById('ratingInput');
const modeInput = document.getElementById('modeInput');
const btnSubmit = document.getElementById('btnSubmit');

let selectedRating = 0;

// ⭐ Interaksi bintang
stars.forEach((star, index) => {
  star.addEventListener('mouseover', () => {
    stars.forEach((s, i) => s.classList.toggle('hovered', i <= index));
  });
  star.addEventListener('mouseout', () => {
    stars.forEach(s => s.classList.remove('hovered'));
  });
  star.addEventListener('click', () => {
    selectedRating = index + 1;
    ratingInput.value = selectedRating;
    stars.forEach((s, i) => s.classList.toggle('active', i < selectedRating));
  });
});

// ✏️ Tombol edit isi ulang form
const btnEdit = document.getElementById('btnEdit');
if (btnEdit) {
  btnEdit.addEventListener('click', () => {
    const oldRating = <?= $data['ulasan']['rating'] ?? 0; ?>;
    const oldKomentar = <?= json_encode($data['ulasan']['komentar'] ?? ''); ?>;
    ratingInput.value = oldRating;
    document.getElementById('komentarInput').value = oldKomentar;
    modeInput.value = 'perbarui';
    btnSubmit.textContent = 'Perbarui Ulasan';
    stars.forEach((s, i) => s.classList.toggle('active', i < oldRating));
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}
</script>

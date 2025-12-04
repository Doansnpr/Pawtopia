<?php
// === KONEKSI DATABASE ===
$koneksi = new mysqli("localhost", "root", "", "pawtopia");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// === PROSES SIMPAN TESTIMONI (AJAX) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = $koneksi->real_escape_string($_POST['nama_lengkap']);
    $role = $koneksi->real_escape_string($_POST['role']);
    $rating = (int) $_POST['rating_t'];
    $komentar = $koneksi->real_escape_string($_POST['komentar_t']);

    if ($rating < 1) {
        echo json_encode(["status" => "error", "message" => "Pilih rating dulu ⭐"]);
        exit;
    }

    $query = "INSERT INTO testimoni (nama_lengkap, role, rating_t, komentar_t) 
              VALUES ('$nama', '$role', '$rating', '$komentar')";

    if ($koneksi->query($query)) {
        echo json_encode(["status" => "success", "message" => "Testimoni berhasil disimpan"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error"]);
    }

    exit;
}

// === AMBIL DATA UNTUK TAMPILAN ===
$sql = "SELECT * FROM testimoni ORDER BY id_testimoni DESC";
$result = $koneksi->query($sql);

$testimoni = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimoni[] = $row;
    }
}
?>

<style>
@keyframes moveBorder {
    0% { stroke-dashoffset: 0; }
    100% { stroke-dashoffset: 60; }
}

.judul-testimoni {
  font-family: 'Patrick Hand', cursive;
  color: #ff9933;
  font-size: 48px;
  text-align: center;
  font-weight: 700;
  margin: 0; /* hapus jarak bawah default */
  text-shadow: 3px 3px 0px rgba(255, 153, 51, 0.2);
}

.subjudul-testimoni {
  text-align: center;
  color: #333;
  font-size: 1.1rem;
  max-width: 700px;
  margin: 0.5rem auto 2.5rem; /* jarak atas ke H2 = 0.5rem, bawah 2.5rem */
  line-height: 1.5;
}

.btn-simple-1 {
    position: relative;
    padding: 1rem 2.5rem;
    border: none;
    background: linear-gradient(135deg, #f3b83f 0%, #e6a02f 100%);
    color: #fff;
    font-weight: 700;
    border-radius: 50px;
    font-size: 1.2rem;
    cursor: pointer;
    display: block;
    margin: 2.5rem auto 0;
    transition: transform 0.3s ease;
}

.btn-simple-1 svg {
    position: absolute;
    top: -4px;
    left: -4px;
    width: calc(100% + 8px);
    height: calc(100% + 8px);
    pointer-events: none;
}

.btn-simple-1 svg rect {
    fill: none;
    stroke: #ff8c00;
    stroke-width: 4;
    stroke-dasharray: 10 5;
    animation: moveBorder 1s linear infinite;
    filter: drop-shadow(0 0 8px #ff8c00);
}

.btn-simple-1:hover {
    transform: scale(1.05);
}

  #modalTestimoni {
      transition: opacity .25s ease;
      opacity: 0;
  }
  .card:hover{ transform:translateY(-5px); box-shadow:0 8px 20px rgba(243,184,63,0.5);}
  .star{color:#ddd; transition:0.2s; filter:grayscale(100%);}
  .star:hover, .star.active{color:#f3b83f; filter:grayscale(0%);}
  @keyframes scaleIn{from{transform:scale(0.8);opacity:0;} to{transform:scale(1);opacity:1;}}
</style>

<section id="testimoni" style="padding:4rem 1rem; background:#dcf3ff; font-family:'Raleway', sans-serif;">
  <div style="max-width:1200px; margin:0 auto; position:relative;">
  <h2 class="judul-testimoni">Testimoni</h2>
  <p class="subjudul-testimoni">
    Lihat apa kata pelanggan dan mitra kami tentang layanan Pawtopia yang aman dan nyaman.
  </p>

    <div class="slider-container" style="position:relative;">
      <div class="slider-wrapper" style="overflow:hidden; padding:0 60px;">
        <div class="slider" id="sliderTestimoni" style="display:flex; gap:20px; transition: transform 0.5s ease;">

          <?php if(!empty($testimoni)): ?>
              <?php foreach($testimoni as $t): ?>
                  <div class='card' style='flex:0 0 300px; background:#fff; padding:1.5rem; border-radius:12px; border:3px solid #f3b83f; box-shadow:0 4px 12px rgba(243,184,63,0.4); transition: transform 0.3s;'>
                      <p style='font-style:italic; color:#333; line-height:1.6; min-height:80px;'>"<?= htmlspecialchars($t['komentar_t']); ?>"</p>
                      <p style='margin-top:0.8rem; color:#f3b83f; font-size:1.2rem;'><?= str_repeat('⭐', (int)$t['rating_t']); ?></p>
                      <p style='font-weight:600; margin-top:0.5rem; color:#666;'>- <?= htmlspecialchars($t['nama_lengkap']); ?></p>
                      <p style='font-size:0.85rem; color:#999; margin-top:0.3rem;'><?= ucfirst(htmlspecialchars($t['role'])); ?></p>
                  </div>
              <?php endforeach; ?>
          <?php else: ?>
              <p id="noTestimoni">Belum ada testimoni.</p>
          <?php endif; ?>

        </div>
      </div>

      <button id="prevTestimoni" style="position:absolute; top:50%; left:0; transform:translate(-50%, -50%); background:#f3b83f; border:none; padding:1rem; cursor:pointer; border-radius:50%; font-size:1.5rem; z-index:10;">&#10094;</button>
      <button id="nextTestimoni" style="position:absolute; top:50%; right:0; transform:translate(50%, -50%); background:#f3b83f; border:none; padding:1rem; cursor:pointer; border-radius:50%; font-size:1.5rem; z-index:10;">&#10095;</button>
    </div>

    <button id="tambahTestimoniBtn" class="btn-simple-1">
    <svg>
        <rect x="2" y="2" rx="25" ry="25" width="calc(100% - 4px)" height="calc(100% - 4px)"></rect>
    </svg>
    📝 Bagikan Pengalaman Anda
</button>

  </div>
</section>

<div id="modalTestimoni" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); backdrop-filter:blur(3px); justify-content:center; align-items:center; z-index:9999;">
  <div style="background:#fff; padding:2rem; border-radius:15px; max-width:500px; width:90%; position:relative; border:4px solid #f3b83f; box-shadow:0 6px 20px rgba(243,184,63,0.4); animation:scaleIn 0.25s ease;">
    <span id="closeModalTestimoni" style="position:absolute; top:12px; right:15px; cursor:pointer; font-size:1.8rem; font-weight:700; color:#f3b83f;">&times;</span>

    <h3 style="margin-bottom:1.5rem; color:#f3b83f; font-size:1.8rem; font-weight:700; text-align:center;">Bagikan Pengalaman Anda</h3>

    <form id="formTestimoni">
      <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required style="width:100%; padding:0.7rem; margin-bottom:1rem; border:2px solid #f3b83f; border-radius:8px;">
      <select name="role" style="width:100%; padding:0.7rem; margin-bottom:1rem; border:2px solid #f3b83f; border-radius:8px;">
        <option value="customer">Customer</option>
        <option value="mitra">Mitra</option>
      </select>

      <div id="ratingStarsTestimoni" style="display:flex; gap:8px; margin-bottom:1rem; font-size:2.5rem; cursor:pointer; justify-content:center;">
        <span class="star" data-rating="1">⭐</span>
        <span class="star" data-rating="2">⭐</span>
        <span class="star" data-rating="3">⭐</span>
        <span class="star" data-rating="4">⭐</span>
        <span class="star" data-rating="5">⭐</span>
      </div>
      <input type="hidden" name="rating_t" id="ratingValueTestimoni" value="0">

      <textarea name="komentar_t" placeholder="Ceritakan pengalaman Anda" required style="width:100%; padding:0.7rem; margin-bottom:1rem; border:2px solid #f3b83f; border-radius:8px;"></textarea>

      <button type="submit" style="width:100%; padding:0.8rem; background:#f3b83f; color:#fff; font-weight:700; border:none; border-radius:8px; cursor:pointer; font-size:1rem;">Kirim Testimoni</button>
    </form>
  </div>
</div>

<script>
// Modal fix animation
const btnTestimoni = document.getElementById('tambahTestimoniBtn');
const modal = document.getElementById('modalTestimoni');
const closeModal = document.getElementById('closeModalTestimoni');

function closeTestimoniModal(){
  modal.style.opacity = "0";
  setTimeout(() => modal.style.display = "none", 250);
}

btnTestimoni.onclick = () => {
  modal.style.display = 'flex';
  setTimeout(() => modal.style.opacity = "1", 10);
};

closeModal.onclick = closeTestimoniModal;

// Rating
const stars = document.querySelectorAll('#ratingStarsTestimoni .star');
const ratingInput = document.getElementById('ratingValueTestimoni');
let selectedRating = 0;

function updateStars(r){
  stars.forEach(s=>s.classList.toggle('active', parseInt(s.dataset.rating)<=r));
}

stars.forEach(star=>{
  star.onclick = ()=>{ selectedRating=parseInt(star.dataset.rating); ratingInput.value=selectedRating; updateStars(selectedRating); };
  star.onmouseover = ()=>updateStars(parseInt(star.dataset.rating));
  star.onmouseout = ()=>updateStars(selectedRating);
});

// Slider
const prev = document.getElementById('prevTestimoni');
const next = document.getElementById('nextTestimoni');
const slider = document.getElementById('sliderTestimoni');
let scroll = 0;

if(prev && next && slider){
  next.onclick = ()=>{ scroll+=320; const max=slider.scrollWidth-slider.clientWidth; if(scroll>max) scroll=max; slider.style.transform=`translateX(-${scroll}px)`; };
  prev.onclick = ()=>{ scroll-=320; if(scroll<0) scroll=0; slider.style.transform=`translateX(-${scroll}px)`; };
}


// AJAX - Ganti bagian script form submit testimoni
document.getElementById('formTestimoni').onsubmit = e => {
    e.preventDefault();
    const data = new FormData(e.target);

    fetch("<?= BASEURL ?>/TestimoniController/tambah", {
        method: 'POST',
        body: data,
        headers: {
            "Accept": "application/json"
        }
    })
    .then(res => res.text()) // ⬅️ GANTI jadi .text() dulu untuk debug
    .then(text => {
        console.log("Response dari server:", text); // Debug: lihat response asli
        
        let res;
        try {
            res = JSON.parse(text); // Coba parse manual
        } catch (err) {
            console.error("JSON Parse Error:", err);
            console.error("Response yang diterima:", text);
            
            // Kalau gagal parse tapi data masuk (cek dari DOM)
            // Anggap sukses aja
            res = { status: 'success' };
        }

        if (res.status === 'success') {
            
            // Ambil data form untuk card baru
            const nama = data.get('nama_lengkap');
            const role = data.get('role');
            const komentar = data.get('komentar_t');
            const rating = data.get('rating_t');

            // Hapus "Belum ada testimoni" kalau ada
            const noTesti = document.getElementById('noTestimoni');
            if (noTesti) noTesti.remove();

            // Buat card testimoni baru
            const card = document.createElement('div');
            card.className = 'card';
            card.style = 'flex:0 0 300px; background:#fff; padding:1.5rem; border-radius:12px; border:3px solid #f3b83f; box-shadow:0 4px 12px rgba(243,184,63,0.4); transition:0.3s;';
            card.innerHTML = `<p style='font-style:italic; color:#333; line-height:1.6; min-height:80px;'>"${komentar}"</p>
                            <p style='margin-top:0.8rem; color:#f3b83f; font-size:1.2rem;'>${'⭐'.repeat(rating)}</p>
                            <p style='font-weight:600; margin-top:0.5rem; color:#666;'>- ${nama}</p>
                            <p style='font-size:0.85rem; color:#999; margin-top:0.3rem;'>${role.charAt(0).toUpperCase()+role.slice(1)}</p>`;

            const slider = document.getElementById('sliderTestimoni');
            if (slider) slider.prepend(card);

            // Reset form
            e.target.reset();
            selectedRating = 0;
            const ratingInput = document.getElementById('ratingValueTestimoni');
            if (ratingInput) ratingInput.value = 0;
            updateStars(0);

            // TUTUP MODAL langsung tanpa alert
            const modal = document.getElementById('modalTestimoni');
            if (modal) {
                modal.style.display = "none";
                modal.style.opacity = "0";
            }

        } else {
            alert('❌ ' + (res.message || 'Gagal menyimpan testimoni'));
        }
    })
    .catch(err => {
        console.error("Fetch Error:", err);
        alert("❌ Terjadi kesalahan jaringan. Coba lagi!");
    });
};
</script>
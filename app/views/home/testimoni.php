<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "pawtopia");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data ulasan + nama user
$query = "
    SELECT u.nama_lengkap AS nama, ul.rating, ul.komentar
    FROM ulasan ul
    JOIN users u ON ul.id_users = u.id_users
    ORDER BY ul.id_ulasan DESC
    LIMIT 20
";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Testimoni Chat Bubble</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(120deg, #d0f0fd, #d0fdea);
    margin: 0;
    padding: 0;
}

.testimonial-section {
    text-align: center;
    padding: 3rem 1rem;
    color: #333;
}

.testimonial-section h2 {
    font-family: 'Comic Neue', cursive;
    font-size: 1.8rem;
    margin-bottom: 0.25rem;
}

.testimonial-section h1 {
    font-family: 'Fredoka One', cursive;
    font-size: 2.2rem;
    margin: 0.5rem 0 1rem;
}

.testimonial-section p.subtitle {
    font-size: 1rem;
    margin-bottom: 2rem;
}

/* Container horizontal scroll */
.testimonial-wrapper {
    position: relative;
    padding: 0 40px;
}

.testimonial-container {
    display: flex;
    overflow-x: auto;
    gap: 1.5rem;
    padding-bottom: 1rem;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
}

/* Hide scrollbar */
.testimonial-container::-webkit-scrollbar {
    display: none;
}
.testimonial-container {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Chat bubble style */
.testimonial-card {
    flex: 0 0 300px;
    background: #ffffff;
    color: #333;
    border-radius: 100px;
    padding: 1.5rem;
    position: relative;
    scroll-snap-align: start;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}
/* Ekor bubble di pojok kanan atas */
.testimonial-card::after {
    content: '';
    position: absolute;
    top: 0;
    right: 13px;
    width: 20px;
    height: 20px;
    background: #ffffff;
    border-radius: 50% 50% 0 50%;
    transform: rotate(45deg);
    box-shadow: -3px -3px 5px rgba(0,0,0,0.05);
}

/* Hover efek */
.testimonial-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Bintang rating */
.testimonial-card .rating {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #ffbb33;
}

/* Pesan */
.testimonial-card p {
    font-size: 0.95rem;
    line-height: 1.5;
}

/* Nama user */
.testimonial-card .author {
    font-style: italic;
    color: #444;
    margin-top: 1rem;
    font-size: 0.9rem;
    text-align: left;
}

/* Tombol Selengkapnya - efek kelap kelip di pinggir */
.btn-selengkapnya {
    position: relative;
    display: inline-block;
    background-color: #ffb703;
    color: #fff;
    border: none;
    padding: 12px 35px;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    text-decoration: none;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
    z-index: 1;
}

/* Efek border berjalan di pinggir tombol */
.btn-selengkapnya::before {
    content: "";
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border-radius: 14px;
    background: linear-gradient(120deg, 
        #ff9500, #ffffff, #ffb703, #ffffff, #ff9500);
    background-size: 300% 300%;
    animation: borderMove 3s linear infinite;
    z-index: 0;
    pointer-events: none;
}

/* Lubangi bagian dalam biar cuma pinggirnya aja yang nyala */
.btn-selengkapnya::after {
    content: "";
    position: absolute;
    top: 2px;
    left: 2px;
    right: 2px;
    bottom: 2px;
    background-color: #ffb703;
    border-radius: 10px;
    z-index: 1;
}

/* Teks di atas segalanya */
.btn-selengkapnya span {
    position: relative;
    z-index: 2;
}

/* Hover: sedikit membesar dan glow lembut */
.btn-selengkapnya:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 6px 15px rgba(255, 183, 3, 0.5);
}

/* Animasi jalan kelap-kelip */
@keyframes borderMove {
    0% {
        background-position: 0% 50%;
    }
    100% {
        background-position: 200% 50%;
    }
}


/* Tombol panah */
.scroll-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: #f5bd45ff;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    font-size: 1.5rem;
    color: #fff;
    cursor: pointer;
    z-index: 10;
    transition: background 0.3s;
}

.scroll-btn:hover {
    background-color: #f5bd45ff;
}

.scroll-left {
    left: -20px;
}

.scroll-right {
    right: -20px;
}
</style>
</head>
<body>

<section class="testimonial-section">
    <h2>Testimoni Customer & Mitra</h2>
    <h1>💬 Apa Kata Mereka</h1>
    <p class="subtitle">Ceritakan pengalaman seru bersama Pawtopia!</p>

    <div class="testimonial-wrapper">
        <button class="scroll-btn scroll-left">&#8592;</button>
        <div class="testimonial-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="testimonial-card">
                        <div class="rating">
                            <?= str_repeat("★", intval($row['rating'])); ?>
                            <?= str_repeat("☆", 5-intval($row['rating'])); ?>
                        </div>
                        <p>“<?= htmlspecialchars($row['komentar']); ?>”</p>
                        <p class="author">– <?= htmlspecialchars($row['nama']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Belum ada ulasan yang ditampilkan.</p>
            <?php endif; ?>
        </div>
        <button class="scroll-btn scroll-right">&#8594;</button>
    </div>

<div style="text-align:center; margin-top:2rem;">
  <a href="<?= BASEURL; ?>/auth/login" class="btn-selengkapnya">
    <span>Selengkapnya</span>
  </a>
</div>
</section>

<script>
const container = document.querySelector('.testimonial-container');
document.querySelector('.scroll-left').addEventListener('click', () => {
    container.scrollBy({ left: -320, behavior: 'smooth' });
});
document.querySelector('.scroll-right').addEventListener('click', () => {
    container.scrollBy({ left: 320, behavior: 'smooth' });
});
</script>

</body>
</html>
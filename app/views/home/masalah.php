<style>
    /* Container utama Masalah */
    .masalah-container {
        position: relative;
        width: 100%;
        background-color: #dcf3ffff;
        padding: 100px 20px 80px 20px; /* Jarak atas-bawah yang lega */
        text-align: center;
        overflow: hidden; /* Mencegah elemen bocor keluar */
    }

    .masalah-container h2 {
        font-family: 'Patrick Hand', cursive;
        color: #ff6b6b;
        font-size: 42px;
        margin-bottom: 20px;
    }

    .masalah-container p.sub-text {
        font-family: 'Comic Neue', cursive;
        font-size: 18px;
        color: #888;
        max-width: 700px;
        margin: 0 auto 60px auto;
        line-height: 1.6;
    }

    /* Grid System Flexbox */
    .masalah-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
    }

    /* Card Styling */
    .masalah-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        width: 280px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-bottom: 6px solid #ddd;
        transition: transform 0.3s ease;
    }

    .masalah-card:hover {
        transform: translateY(-10px);
    }

    .masalah-icon {
        font-size: 45px;
        margin-bottom: 15px;
        display: block;
    }

    .masalah-card h3 {
        font-family: 'Patrick Hand', cursive;
        font-size: 24px;
        color: #444;
        margin-bottom: 10px;
    }

    .masalah-card p {
        font-size: 15px;
        color: #666;
        line-height: 1.5;
    }

    /* Warna Warni Border Bawah */
    .border-red { border-bottom-color: #ff7675; }
    .border-blue { border-bottom-color: #74b9ff; }
    .border-yellow { border-bottom-color: #ffeaa7; }
    .border-purple { border-bottom-color: #a29bfe; }
</style>

<div class="masalah-container">
    <h2>Bingung Cari Tempat Penitipan? 😿</h2>
    <p class="sub-text">
        Ninggalin anabul itu emang bikin cemas. Banyak banget masalah yang sering bikin kita ragu buat nitipin kucing kesayangan ke orang lain.
    </p>

    <div class="masalah-grid">
        <div class="masalah-card border-red">
            <span class="masalah-icon">💸</span>
            <h3>Harga Gak Jelas</h3>
            <p>Suka kaget liat tagihan akhir karena banyak biaya tambahan yang gak dibilang di awal.</p>
        </div>

        <div class="masalah-card border-blue">
            <span class="masalah-icon">🏚️</span>
            <h3>Fasilitas Buruk</h3>
            <p>Khawatir kandang sempit, kotor, atau sirkulasi udara jelek yang bikin kucing stress.</p>
        </div>

        <div class="masalah-card border-yellow">
            <span class="masalah-icon">🤷‍♂️</span>
            <h3>Info Minim</h3>
            <p>Susah cari review jujur atau foto lokasi yang real, jadi ragu ini tempat aman atau enggak.</p>
        </div>

        <div class="masalah-card border-purple">
            <span class="masalah-icon">📍</span>
            <h3>Lokasi Jauh</h3>
            <p>Tempat yang bagus seringkali jauh banget dari rumah, bikin repot pas mau antar-jemput.</p>
        </div>
    </div>
</div>
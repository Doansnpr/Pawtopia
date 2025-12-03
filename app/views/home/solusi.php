<style>
    /* Container Solusi */
    .solusi-wrapper {
        width: 100%;
        background-color: #f0faff; /* Biru muda segar */
        position: relative;
    }

    /* Wave di dalam flow normal (GAK NABRAK LAGI) */
    .wave-separator {
        width: 100%;
        line-height: 0; /* Menghilangkan gap putih kecil */
        background-color: #dcf3ffff;
    }
    
    .wave-separator svg {
        display: block;
        width: 100%;
        height: auto;
        fill: #f0faff; /* Warna sama dengan bg solusi */
    }

    .solusi-content {
        padding: 50px 20px 100px 20px;
        text-align: center;
    }

    .solusi-content h2 {
        font-family: 'Patrick Hand', cursive;
        color: #0984e3;
        font-size: 42px;
        margin-bottom: 20px;
    }

    .solusi-content p.sub-solusi {
        font-family: 'Comic Neue', cursive;
        font-size: 18px;
        color: #636e72;
        max-width: 700px;
        margin: 0 auto 60px auto;
    }

    /* Grid 2x2 yang Pasti */
    .solusi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* Tetap 2 kolom */
        gap: 30px;
        max-width: 900px;
        margin: 0 auto;
    }

    /* Responsif HP (1 kolom) */
    @media (max-width: 768px) {
        .solusi-grid {
            grid-template-columns: 1fr;
        }
    }

    .solusi-item {
        background: white;
        border-radius: 20px;
        padding: 30px;
        text-align: left;
        display: flex;
        flex-direction: column;
        border: 2px solid transparent;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: 0.3s;
    }

    .solusi-item:hover {
        border-color: #ff9933;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }

    /* Warna Icon Background */
    .bg-g { background: #e2f9e1; color: #2ecc71; }
    .bg-b { background: #e3f2fd; color: #3498db; }
    .bg-o { background: #fff3e0; color: #ff9f43; }
    .bg-p { background: #f3e5f5; color: #9b59b6; }

    .solusi-item h3 {
        font-family: 'Patrick Hand', cursive;
        font-size: 22px;
        color: #333;
        margin-bottom: 10px;
    }

    .solusi-item p {
        font-size: 15px;
        color: #666;
        line-height: 1.5;
    }
</style>

<div class="solusi-wrapper">
    <div class="wave-separator">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <div class="solusi-content">
        <h2>Tenang, Pawtopia Punya Solusinya! 😻</h2>
        <p class="sub-solusi">
            Gak perlu bingung lagi. Kami bantu kamu temukan penitipan terbaik dengan fitur yang bikin hati tenang dan anabul senang.
        </p>

        <div class="solusi-grid">
            <div class="solusi-item">
                <div class="icon-circle bg-g">✨</div>
                <h3>Booking Instan</h3>
                <p>Cari petshop, cek ketersediaan kamar, dan booking langsung dari HP semudah pesan ojek online!</p>
            </div>
            <div class="solusi-item">
                <div class="icon-circle bg-b">📸</div>
                <h3>Laporan Harian</h3>
                <p>Pantau kondisi anabul lewat fitur update foto & video harian dari mitra. Kangen jadi terobati.</p>
            </div>
            <div class="solusi-item">
                <div class="icon-circle bg-o">🛡️</div>
                <h3>Pembayaran Aman</h3>
                <p>Uangmu aman di sistem kami dan baru diteruskan ke mitra setelah layanan selesai.</p>
            </div>
            <div class="solusi-item">
                <div class="icon-circle bg-p">📝</div>
                <h3>Profil Kesehatan</h3>
                <p>Data vaksin dan riwayat medis tersimpan rapi, memudahkan mitra merawat anabulmu.</p>
            </div>
        </div>
    </div>
</div>
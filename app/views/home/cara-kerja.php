<section class="carakerja-section" id="carakerja">
    <style>
        .carakerja-section {
            background-color: #f0faff;
            padding: 0;
            color: #333;
            text-align: center;
            position: relative;
        }

        /* Wave Atas - CEMBUNG KE ATAS */
        .wave-separator-top-carakerja {
            width: 100%;
            line-height: 0;
            background-color: #dcf3ffff; /* Background dari section sebelumnya (solusi) */
            height: 80px;
            overflow: hidden;
        }

        .wave-separator-top-carakerja svg {
            display: block;
            width: 100%;
            height: 80px;
            fill: #f0faff; /* Warna section cara kerja */
        }

        /* Content dengan padding */
        .carakerja-content {
            padding: 60px 20px;
        }

        .judul-carakerja {
            font-family: 'Patrick Hand', cursive;
            color: #ff9933;
            font-size: 48px;
            text-align: center;
            font-weight: 700;
            margin: 0 0 20px 0;
            text-shadow: 3px 3px 0px rgba(255, 153, 51, 0.2);
        }

        .deskripsi-carakerja {
            margin-bottom: 50px;
            color: #7a4b00;
            max-width: 700px;
            line-height: 1.6;
            display: inline-block;
            font-size: 17px;
        }

        .carakerja-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap; 
            max-width: 1000px;
            margin: 0 auto;
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); 
            padding: 50px 40px;
        }

        .carakerja-logo {
            width: 100%;
            max-width: 400px;
            height: auto;
            object-fit: contain;
        }

        .langkah-grid {
            padding: 0;
            text-align: left;
            width: 100%;
            max-width: 450px;
            flex-shrink: 0;
        }

        .langkah-card {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .langkah-nomor {
            background: #ff9933;
            color: white;
            border-radius: 50%;
            font-size: 1.2em;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 35px;
            height: 35px;
            margin-right: 15px;
            font-family: 'Patrick Hand', cursive;
        }

        .langkah-isi {
            color: #4b4b4b;
            font-family: 'Patrick Hand', cursive;
            font-size: 18px;
            margin: 0;
            line-height: 1.3;
        }
        
        .carakerja-button-container {
            margin-top: 30px;
            width: 100%;
        }

        .carakerja-button {
            background: #fca120;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            display: inline-block;
            text-decoration: none;
        }

        .carakerja-button:hover {
            background: #e18e0a;
        }

        @media (max-width: 768px) {
            .wave-separator-top-carakerja {
                height: 50px;
            }

            .wave-separator-top-carakerja svg {
                height: 50px;
            }

            .carakerja-content {
                padding: 40px 20px;
            }

            .judul-carakerja {
                font-size: 36px;
            }

            .carakerja-wrapper {
                flex-direction: column;
                gap: 30px;
                padding: 40px 20px;
            }

            .carakerja-logo {
                max-width: 300px;
            }
        }
    </style>

    <!-- Wave Atas - CEMBUNG KE ATAS (SEPERTI BUKIT) -->
    <div class="wave-separator-top-carakerja">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill-opacity="1" d="M0,100L48,85.3C96,71,192,43,288,37.3C384,32,480,48,576,58.7C672,69,768,75,864,69.3C960,64,1056,48,1152,42.7C1248,37,1344,43,1392,45.3L1440,48L1440,100L1392,100C1344,100,1248,100,1152,100C1056,100,960,100,864,100C768,100,672,100,576,100C480,100,384,100,288,100C192,100,96,100,48,100L0,100Z"></path>
        </svg>
    </div>

    <!-- Content Cara Kerja -->
    <div class="carakerja-content">
        <div class="container">
            <h2 class="judul-carakerja">Cara Kerja Pawtopia</h2>
            <p class="deskripsi-carakerja">
                Ikuti langkah-langkah mudah di bawah ini untuk mulai menggunakan layanan Pawtopia, baik sebagai Customer, Mitra, atau Admin.
            </p>
            
            <div class="carakerja-wrapper">
                <img src="<?= BASEURL; ?>/images/logoCaraKerja.png" alt="Logo Pawtopia" class="carakerja-logo">

                <div class="langkah-grid">
                    <div class="langkah-card">
                        <span class="langkah-nomor">1</span>
                        <p class="langkah-isi">Login di aplikasi Pawtopia.</p>
                    </div>
                    <div class="langkah-card">
                        <span class="langkah-nomor">2</span>
                        <p class="langkah-isi">Pilih peranmu → Customer / Mitra / Admin.</p>
                    </div>
                    <div class="langkah-card">
                        <span class="langkah-nomor">3</span>
                        <p class="langkah-isi">Customer: pilih hotel kucing & lakukan booking.</p>
                    </div>
                    <div class="langkah-card">
                        <span class="langkah-nomor">4</span>
                        <p class="langkah-isi">Mitra: kelola profil & konfirmasi penitipan.</p>
                    </div>
                    <div class="langkah-card">
                        <span class="langkah-nomor">5</span>
                        <p class="langkah-isi">Admin: pantau transaksi dan data sistem.</p>
                    </div>
                    
                    <div class="carakerja-button-container" style="text-align: right; margin-top: 40px;">
                        <a href="<?= BASEURL; ?>/auth/login" class="carakerja-button">Coba Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
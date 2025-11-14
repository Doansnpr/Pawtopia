<section class="carakerja-section" id="carakerja">
    <style>
        .carakerja-section {
            background-color: #eef6fa;
            padding: 60px 20px;
            color: #333;
            text-align: center;
        }

        .judul-carakerja {
            color: #ff9933;
            font-size: 26px;
            margin-bottom: 15px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
            font-family: 'Comic Neue', cursive;
        }

        .deskripsi-carakerja {
            margin-bottom: 50px;
            color: #7a4b00;
            max-width: 700px;
            line-height: 1.6;
            display: inline-block;
            font-size: 17px;
        }

        /* ✅ PERUBAHAN UTAMA: Wrapper menjadi KOTAK TUNGGAL */
        .carakerja-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px; /* Jarak antara Logo dan Langkah */
            flex-wrap: wrap; 
            max-width: 1000px;
            margin: 0 auto;
            
            /* Gaya Kotak Tunggal */
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); 
            padding: 50px 40px; /* Padding di sekitar Logo & Langkah */
        }

        /* ❌ Hapus Gaya Kotak dari Logo */
        .carakerja-logo {
            width: 100%;
            max-width: 400px; /* Sedikit dikecilkan agar seimbang */
            height: auto;
            object-fit: contain;
            /* Hapus background, shadow, border-radius, padding */
        }

        /* ❌ Hapus Gaya Kotak dari Kotak Langkah */
        .langkah-grid {
            /* Hapus background, shadow, border-radius */
            padding: 0; /* Hapus padding agar tidak tumpang tindih dengan padding wrapper */
            text-align: left;
            width: 100%;
            max-width: 450px; /* Batasi lebar langkah */
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
        
        /* ⚠️ PENYESUAIAN POSISI TOMBOL */
        .carakerja-button-container {
            margin-top: 30px; /* Jarak lebih dekat ke kotak besar */
            /* Pindahkan tombol di bawah kotak langkah agar sejajar dengan sisi kanan langkah */
            /* Kita perlu membungkus wrapper dan button container agar bisa diatur di desktop */
            width: 100%; /* Agar bisa rata tengah dengan section */
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
</section>
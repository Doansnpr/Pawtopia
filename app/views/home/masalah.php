<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawtopia - Solusi Penitipan Kucing Terpercaya</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Comic Sans MS', 'Chalkboard SE', 'Comic Neue', cursive, sans-serif;
            overflow-x: hidden;
            background: #dcf3ff;
            min-height: 100vh;
        }

        .section {
            min-height: 100vh;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        html {
            scroll-behavior: smooth;
        }

        .navbar {
            display: none;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 42px;
            font-weight: bold;
            color: #333;
        }

        .logo-paw {
            font-size: 48px;
        }

        .nav-menu {
            display: flex;
            gap: 50px;
            align-items: center;
        }

        .nav-link {
            color: #666;
            text-decoration: none;
            font-size: 20px;
            font-weight: 500;
            transition: color 0.3s ease;
            cursor: pointer;
        }

        .nav-link:hover {
            color: #FF9966;
        }

        .nav-link.active {
            color: #FF9966;
        }

        .hero {
            text-align: center;
            padding: 100px 40px;
            background: #dcf3ff;
        }

        .hero-title {
            font-size: 64px;
            color: #FF9966;
            margin-bottom: 40px;
            font-weight: bold;
            line-height: 1.3;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .cat-emoji {
            font-size: 70px;
            display: inline-block;
            animation: wiggle 1.5s ease-in-out infinite;
        }

        @keyframes wiggle {
            0%, 100% { transform: rotate(-5deg); }
            50% { transform: rotate(5deg); }
        }

        .hero-subtitle {
            font-size: 24px;
            color: #555;
            max-width: 950px;
            margin: 0 auto;
            line-height: 1.8;
            font-weight: 500;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 40px;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 45px 35px;
            border-radius: 30px;
            border: 4px solid;
            transition: all 0.3s ease;
            min-height: 300px;
            position: relative;
        }

        .feature-card.green {
            border-color: #90C47C;
            background: linear-gradient(to bottom, #ffffff 0%, #f0f8e8 100%);
        }

        .feature-card.blue {
            border-color: #7CB9E8;
            background: linear-gradient(to bottom, #ffffff 0%, #e3f2fd 100%);
        }

        .feature-card.pink {
            border-color: #FFB6C1;
            background: linear-gradient(to bottom, #ffffff 0%, #ffe8f0 100%);
        }

        .feature-card.purple {
            border-color: #B39DDB;
            background: linear-gradient(to bottom, #ffffff 0%, #f3e8ff 100%);
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-title {
            font-size: 26px;
            font-weight: bold;
            color: #FF9966;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .feature-text {
            font-size: 20px;
            color: #666;
            line-height: 1.7;
        }



        .cta-section {
            display: none;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 25px;
                padding: 25px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
                gap: 25px;
            }

            .hero-title {
                font-size: 42px;
            }

            .hero-subtitle {
                font-size: 20px;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Page 1: Masalah -->
    <div class="page active" id="page-masalah">
        <nav class="navbar">
            <div class="logo">
                <span class="logo-paw">🐾</span>
                <span>PAWTOPIA</span>
            </div>
            <div class="nav-menu">
                <a class="nav-link" onclick="showPage('masalah')">Beranda</a>
                <a class="nav-link" href="#">Fasilitas</a>
                <a class="nav-link" href="#">Layanan</a>
                <a class="nav-link" href="#">Cara Kerja</a>
                <a class="nav-link" href="#">Testimoni</a>
            </div>
        </nav>

        <div class="hero">
            <h1 class="hero-title">
                Bingung Cari Tempat Penitipan Kucing?
                <span class="cat-emoji"></span>
            </h1>
            <p class="hero-subtitle">
                Nyari tempat penitipan kucing itu kadang bikin pusing. Harga beda-beda, fasilitas nggak jelas, review susah dicari, dan kadang tempatnya jauh dari rumah. Jadinya bingung mau nitip di mana yang bener-bener aman dan nyaman.
            </p>
        </div>

        <div class="container">
            <div class="features">
                <div class="feature-card green">
                    <div class="feature-title">Harga Tidak Transparan</div>
                    <div class="feature-text">Setiap tempat punya harga berbeda tanpa info jelas soal fasilitas yang didapat.</div>
                </div>

                <div class="feature-card blue">
                    <div class="feature-title">Fasilitas Tidak Jelas</div>
                    <div class="feature-text">Sulit tau seperti apa kondisi dan fasilitas sebelum datang langsung ke lokasi.</div>
                </div>

                <div class="feature-card pink">
                    <div class="feature-title">Review Sulit Dicari</div>
                    <div class="feature-text">Ulasan dari pengguna tidak terkumpul dengan jelas, sehingga butuh waktu lebih lama untuk menemukan informasi yang dibutuhkan..</div>
                </div>

                <div class="feature-card purple">
                    <div class="feature-title">Lokasi Jauh</div>
                    <div class="feature-text">Tempat yang bagus dan terpercaya sering kali letaknya jauh dari rumah.</div>
                </div>
            </div>

            <div class="cta-section">
                <button class="cta-button" onclick="showPage('solusi')">Lihat Solusinya →</button>
            </div>
        </div>
    </div>

    <!-- Page 2: Solusi -->
    <div class="page" id="page-solusi">
        <nav class="navbar">
            <div class="logo">
                <span class="logo-paw">🐾</span>
                <span>PAWTOPIA</span>
            </div>
            <div class="nav-menu">
                <a class="nav-link" onclick="showPage('masalah')">Beranda</a>
                <a class="nav-link" href="#">Fasilitas</a>
                <a class="nav-link" href="#">Layanan</a>
                <a class="nav-link" href="#">Cara Kerja</a>
                <a class="nav-link" href="#">Testimoni</a>
            </div>
        </nav>

        <div class="hero">
            <h1 class="hero-title">
                Kenapa Memilih Pawtopia
                <span class="cat-emoji"></span>
            </h1>
            <p class="hero-subtitle">
                Tenang, Pawtopia ada buat bantu kamu. Di sini kamu bisa lihat pilihan petshop, cek fasilitas, baca review, sampai booking tempat penitipan langsung dari satu aplikasi. Cepat, gampang, dan anti ribet. Nitip kucing jadi lebih tenang, kamu juga jadi lebih santai.
            </p>
        </div>

        <div class="container">
            <div class="features">
                <div class="feature-card green">
                    <div class="feature-title">Booking Mudah & Cepat</div>
                    <div class="feature-text">Cari dan pesan hotel kucing sesuai fasilitas dan harga.</div>
                </div>

                <div class="feature-card blue">
                    <div class="feature-title">Laporan Harian Lengkap</div>
                    <div class="feature-text">Update perkembangan kucing dengan foto/video dari mitra.</div>
                </div>

                <div class="feature-card pink">
                    <div class="feature-title">Pembayaran Fleksibel & Aman</div>
                    <div class="feature-text">Metode pembayaran online atau bayar di tempat.</div>
                </div>

                <div class="feature-card purple">
                    <div class="feature-title">Kelola Praktis</div>
                    <div class="feature-text">Mitra dapat mengatur profil, kapasitas, tarif, dan memantau laporan keuangan.</div>
                </div>
            </div>

            <div class="cta-section">
                <button class="cta-button" onclick="alert('Terima kasih! Fitur booking akan segera hadir 🚀')">Mulai Sekarang →</button>
            </div>
        </div>
    </div>

    <script>
        // Smooth scroll behavior is handled by CSS
        // Active nav link changes on scroll
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('.section');
            const navLinks = document.querySelectorAll('.nav-link');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Raleway', Arial, sans-serif;
            min-height: 100vh;
            background: #dcf3ffff;
        }

        /* Wave Footer */
        .wave-footer {
            position: relative;
            background: #49b0ac;
            color: #fff;
            padding: 5rem 2rem 2rem;
            margin-top: 5rem;
        }

        /* Wave SVG */
        .wave-container {
            position: absolute;
            top: -195px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .wave-container svg {
            position: relative;
            display: block;
            width: 100%;
            height: 196px;
        }

        /* Footer Content */
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2rem;
            align-items: center;
            position: relative;
            z-index: 3;
        }

        /* Kontak Kami - Kiri */
        .contact-left {
            text-align: left;
        }

        .contact-left h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .contact-left p {
            margin: 0.5rem 0;
            font-size: 0.95rem;
        }

        .contact-left a {
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
        }

        .contact-left a:hover {
            color: #ff9933;
        }

        /* Logo - Tengah */
        .logo-center {
            text-align: center;
        }
.logo-center img {
            height: 125px;
            cursor: pointer;
            animation: floatBounce 3s ease-in-out infinite;
        }

        .logo-center img:hover {
            animation: wiggle 0.5s ease-in-out infinite;
        }

        /* Animasi float dan bounce */
        @keyframes floatBounce {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            25% {
                transform: translateY(-10px) rotate(2deg);
            }
            50% {
                transform: translateY(0px) rotate(0deg);
            }
            75% {
                transform: translateY(-5px) rotate(-2deg);
            }
        }

        /* Animasi wiggle saat hover */
        @keyframes wiggle {
            0%, 100% {
                transform: rotate(0deg) scale(1);
            }
            25% {
                transform: rotate(-10deg) scale(1.1);
            }
            50% {
                transform: rotate(10deg) scale(1.15);
            }
            75% {
                transform: rotate(-5deg) scale(1.1);
            }
        }
        
        /* Hubungi Kami - Kanan */
        .social-right {
            text-align: right;
        }

        .social-right h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .social-links {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
        }

        .social-links a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .social-links a:hover {
            color: #ff9933;
            transform: scale(1.05);
        }

        .social-links i {
            font-size: 1.1rem;
        }

        /* Copyright */
        .footer-bottom {
            text-align: center;
            margin-top: 3rem;
            padding-top: 1.5rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 2.5rem;
            }

            .contact-left,
            .social-right {
                text-align: center;
            }

            .social-links {
                align-items: center;
            }

            .wave-container {
                top: -80px;
            }

            .wave-container svg {
                height: 100px;
            }

            .wave-footer {
                padding: 4rem 1.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Footer with Wave -->
    <footer class="wave-footer">
        <!-- Wave SVG -->
        <div class="wave-container">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,60 C200,100 400,20 600,60 C800,100 1000,20 1200,60 L1200,120 L0,120 Z" 
                      fill="#49b0ac"></path>
            </svg>
        </div>

        <div class="footer-content">
            <!-- Kontak Kami - Kiri -->
            <div class="contact-left">
                <h3>Kontak Kami</h3>
                <p>📞 <a href="tel:+6281234567890">+62 812 3456 7890</a></p>
                <p>✉ <a href="mailto:info@pawtopia.com">pawtopia457@gmail.com</a></p>
            </div>

            <!-- Logo - Tengah -->
            <div class="logo-center">
                <a href="index.html">
        <img src="<?= BASEURL; ?>/images/logo.png" alt="">
                </a>
            </div>

            <!-- Hubungi Kami - Kanan -->
            <div class="social-right">
                <h3>Ikuti Kami</h3>
                <div class="social-links">
                    <a href="https://www.instagram.com/username" target="_blank">
                        <i class="fa-brands fa-instagram"></i> Instagram
                    </a>
                    <a href="https://www.facebook.com/username" target="_blank">
                        <i class="fa-brands fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://www.tiktok.com/@username" target="_blank">
                        <i class="fa-brands fa-tiktok"></i> TikTok
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            &copy; 2025 Pawtopia. Semua hak cipta dilindungi.
        </div>
    </footer>

</body>
</html>
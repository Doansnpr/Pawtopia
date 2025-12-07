<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Login | PawTopia</title>
  <style>
    /* --- CSS GLOBAL --- */
    * {
      box-sizing: border-box; /* Agar padding tidak merusak lebar elemen */
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #d4f2ff;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh; /* Pakai min-height supaya bisa discroll di HP */
      padding: 20px; /* Jarak aman di layar kecil */
    }

    .login-container {
      display: flex;
      background-color: #bce9ff;
      border-radius: 20px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      padding: 40px;
      width: 100%; 
      max-width: 900px; /* Batas maksimal lebar di desktop */
      justify-content: space-between;
      align-items: center;
      gap: 30px;
      transition: all 0.3s ease;
    }

    /* --- SISI KIRI (LOGO) --- */
    .logo-side {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 45%; /* Di desktop ambil 45% */
    }

    .logo-side img {
      width: 100%;
      max-width: 300px; /* Logo tidak terlalu besar */
      height: auto;
      object-fit: contain;
    }

    /* --- SISI KANAN (FORM) --- */
    .form-side {
      background: white;
      border-radius: 15px;
      width: 55%; /* Di desktop ambil 55% */
      padding: 35px 30px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: all 0.3s ease;
    }

    h2 {
      font-family: "Comic Sans MS", cursive;
      color: #d68c00;
      margin-top: 0;
      margin-bottom: 20px;
      text-align: center;
      font-size: 1.8rem;
    }

    form {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .input-wrapper {
      position: relative;
      width: 100%;
      margin-bottom: 15px;
    }

    input {
      width: 100%;
      padding: 12px 45px 12px 15px;
      border: 2px solid orange;
      border-radius: 15px;
      outline: none;
      font-size: 16px;
      background-color: white;
    }

    .toggle-password {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      font-size: 18px;
      color: #d68c00;
      padding: 0;
    }

    button[type="submit"] {
      width: 100%;
      background: orange;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 15px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
      transition: 0.3s;
    }

    button[type="submit"]:hover {
      background: #d97706;
    }

    .create-account, .forgot-password {
      display: block;
      margin-top: 15px;
      text-align: center;
      color: orange;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
      font-size: 0.95rem;
    }

    .create-account:hover, .forgot-password:hover {
      color: #d97706;
      text-decoration: underline;
    }

    .back-btn {
      display: inline-block;
      margin-bottom: 15px;
      color: orange;
      text-decoration: none;
      font-size: 16px;
      align-self: flex-start;
      font-weight: bold;
    }

    /* --- RESPONSIVE MEDIA QUERIES (INI KUNCINYA) --- */
    
    /* Tablet & Laptop Kecil (Max 900px) */
    @media (max-width: 900px) {
      .login-container {
        padding: 30px;
        gap: 20px;
      }
      .logo-side img {
        max-width: 220px;
      }
    }

    /* Mobile (Max 768px) - Berubah jadi susun ke bawah */
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column; /* Susun vertikal */
        padding: 30px 20px;
        width: 100%;
      }

      .logo-side {
        width: 100%;
        margin-bottom: 20px;
      }

      .logo-side img {
        max-width: 180px; /* Perkecil logo di HP */
      }

      .form-side {
        width: 100%; /* Form memenuhi lebar container */
        padding: 25px 20px;
      }

      h2 {
        font-size: 1.5rem; /* Sesuaikan ukuran font */
      }
    }

    /* HP Kecil (Max 480px) */
    @media (max-width: 480px) {
      body {
        padding: 10px;
      }
      
      .login-container {
        padding: 20px 15px;
        border-radius: 15px;
      }

      .logo-side img {
        max-width: 150px;
      }

      input {
        font-size: 14px;
        padding: 10px;
      }
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="login-container">
    <div class="logo-side">
      <img src="<?= BASEURL; ?>/images/logo.png" alt="Logo">
    </div>

    <div class="form-side" id="loginForm">
      <h2>Login to Continue</h2>
      <form method="POST" action="<?= BASEURL; ?>/auth/login">
        <div class="input-wrapper">
          <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-wrapper">
          <input id="login-password" type="password" name="password" placeholder="Password" required>
          <button type="button" class="toggle-password" data-target="login-password">👁️‍🗨️</button>
        </div>

        <button type="submit">LOGIN</button>
      </form>

      <a href="#" class="forgot-password" id="forgotLink">Forgot Password?</a>
      <a href="<?= BASEURL; ?>/auth/register" class="create-account">Create Account</a>
    </div>

    <div class="form-side" id="forgotForm" style="display:none;">
      <a href="#" class="back-btn" id="backToLogin">← Back</a>
      <h2>Change Password</h2>
      <form method="POST" action="<?= BASEURL; ?>/auth/forgotPassword">
        <div class="input-wrapper">
          <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="input-wrapper">
          <input id="new-password" type="password" name="new_password" placeholder="New password" required>
          <button type="button" class="toggle-password" data-target="new-password">👁️‍🗨️</button>
        </div>

        <button type="submit">UPDATE PASSWORD</button>
      </form>
    </div>
  </div>

  <script>
    // 👁️ Toggle show/hide password
    document.querySelectorAll('.toggle-password').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        input.type = (input.type === 'password') ? 'text' : 'password';
      });
    });

    // 🔹 Ganti tampilan login <-> forgot password
    document.getElementById('forgotLink').addEventListener('click', e => {
      e.preventDefault();
      document.getElementById('loginForm').style.display = 'none';
      document.getElementById('forgotForm').style.display = 'flex';
    });

    document.getElementById('backToLogin').addEventListener('click', e => {
      e.preventDefault();
      document.getElementById('forgotForm').style.display = 'none';
      document.getElementById('loginForm').style.display = 'flex';
    });

    // 🔹 SweetAlert Flash Message
    <?php if (isset($_SESSION['flash'])) : ?>
      Swal.fire({
          icon: '<?php echo $_SESSION['flash']['tipe']; ?>',
          title: '<?php echo $_SESSION['flash']['pesan']; ?>',
          text: '<?php echo $_SESSION['flash']['aksi']; ?>',
          confirmButtonColor: '#f39c12'
      });
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
  </script>
</body>
</html>
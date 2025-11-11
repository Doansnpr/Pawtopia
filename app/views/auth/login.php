<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | PawTopia</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #d4f2ff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-container {
      display: flex;
      background-color: #bce9ff;
      border-radius: 20px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      padding: 40px;
      width: 800px;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
    }

    .logo-side {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 35%;
    }

    .logo-side img {
      width: 250px;
      max-width: 100%;
      height: auto;
    }

    .form-side {
      background: white;
      border-radius: 15px;
      width: 50%;
      padding: 30px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: all 0.3s ease;
    }

    h2 {
      font-family: "Comic Sans MS", cursive;
      color: #d68c00;
      margin-bottom: 20px;
      text-align: center;
    }

    form {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .input-wrapper {
      position: relative;
      width: 90%;
    }

    input {
      width: 100%;
      padding: 10px 45px 10px 15px;
      margin: 8px 0;
      border: 2px solid orange;
      border-radius: 15px;
      outline: none;
      box-sizing: border-box;
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
      padding: 2px;
    }

    button[type="submit"] {
      width: 90%;
      background: orange;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 15px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 15px;
      transition: 0.3s;
    }

    button[type="submit"]:hover {
      background: #d97706;
    }

    .create-account, .forgot-password {
      display: block;
      margin-top: 10px;
      text-align: center;
      color: orange;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    .create-account:hover, .forgot-password:hover {
      color: #d97706;
      text-decoration: underline;
    }

    .back-btn {
      display: inline-block;
      margin-bottom: 10px;
      color: orange;
      text-decoration: none;
      font-size: 16px;
      align-self: flex-start;
    }

    @media (max-width: 900px) {
      .login-container { width: 90%; padding: 30px; }
      .logo-side img { width: 200px; }
    }

    @media (max-width: 700px) {
      .login-container { flex-direction: column; text-align: center; }
      .logo-side { width: 100%; margin-bottom: 20px; }
      .form-side { width: 100%; }
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="login-container">
    <div class="logo-side">
      <img src="<?= BASEURL; ?>/images/logo.png" alt="Logo">
    </div>

    <!-- 🔹 LOGIN FORM -->
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

    <!-- 🔹 FORGOT PASSWORD FORM -->
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

  <!-- ✅ SCRIPT DIBENERIN -->
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

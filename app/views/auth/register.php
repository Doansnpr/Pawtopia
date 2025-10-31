
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Account | PawTopia</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@700&family=Poppins&display=swap');
    body {
      margin: 0; padding: 0; font-family: 'Poppins', sans-serif;
      background: url('<?= BASEURL ?>/images/logo.png') no-repeat center center fixed;
      background-size: 850px auto; background-color: #d4f2ff;
      display: flex; align-items: center; justify-content: center;
      height: 100vh;
    }
    .register-container {
      background: rgba(255,255,255,0.85);
      border-radius: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      padding: 40px; width: 400px; text-align: center;
    }
    h2 { font-family: 'Comic Neue', cursive; color: #d68c00; font-size: 26px; margin-bottom: 25px; }
    .input-wrapper { position: relative; margin-bottom: 15px; width: 100%; }
    input, select {
      width: 100%; padding: 10px 50px 10px 15px;
      border: 2px solid orange; border-radius: 15px; outline: none;
      font-size: 15px; box-sizing: border-box; background-color: white;
    }
    select {
      appearance: none;
      background-image: url("data:image/svg+xml;utf8,<svg fill='orange' height='18' viewBox='0 0 24 24' width='18' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
      background-repeat: no-repeat; background-position: right 18px center;
      background-size: 18px; cursor: pointer;
    }
    select:hover { border-color: #ffb300; box-shadow: 0 0 5px rgba(255,179,0,0.3); }
    .toggle-password {
      position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer; font-size: 18px;
      color: #d68c00; padding: 2px;
    }
    .toggle-password:focus { outline: 2px solid rgba(214,140,0,0.3); border-radius: 5px; }
    button[type="submit"] {
      background: orange; color: white; border: none;
      border-radius: 20px; padding: 12px; width: 100%;
      font-weight: bold; cursor: pointer; font-size: 15px;
      margin-top: 10px; transition: 0.2s;
    }
    button[type="submit"]:hover { background: #e69500; }
    .login-link {
      display: block; margin-top: 15px;
      color: orange; text-decoration: underline; font-weight: 500; font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="register-container">
    <h2>Create Account</h2>
    <form method="POST" action="<?= BASEURL; ?>/auth/register">
      <div class="input-wrapper">
        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
      </div>
      <div class="input-wrapper">
        <input type="text" name="no_hp" placeholder="Nomor Telepon" required>
      </div>
      <div class="input-wrapper">
        <input type="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-wrapper">
        <input id="reg-password" type="password" name="password" placeholder="Password" required>
        <button type="button" class="toggle-password" data-target="reg-password">👁️‍🗨️</button>
      </div>
      <div class="input-wrapper">
        <select name="role" required>
          <option value="">-- Create as --</option>
          <option value="customer">Customer</option>
          <option value="mitra">Mitra</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button type="submit">CREATE</button>
    </form>
    <a href="<?= BASEURL; ?>/index.php?auth=login" class="login-link">Sudah punya akun?</a>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  </div>
  <script>
    document.querySelectorAll('.toggle-password').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = document.getElementById(btn.getAttribute('data-target'));
        if (input.type === 'password') input.type = 'text';
        else input.type = 'password';
      });
    });
  </script>
</body>
</html>

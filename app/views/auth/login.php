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
    }
    .logo-side { display: flex; align-items: center; justify-content: center; width: 35%; height: 70%; }
    .logo-side img { width: 250px; max-width: 100%; height: auto; }
    .form-side {
      background: white;
      border-radius: 15px;
      width: 50%;
      padding: 30px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    h2 { font-family: "Comic Sans MS", cursive; color: #d68c00; margin-bottom: 20px; text-align: center; }
    form { width: 100%; display: flex; flex-direction: column; align-items: center; }
    .input-wrapper { position: relative; width: 90%; }
    input {
      width: 100%; padding: 10px 42px 10px 10px; margin: 8px 0;
      border: 2px solid orange; border-radius: 15px; outline: none;
      box-sizing: border-box; font-size: 16px;
    }
    .toggle-password {
      position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer;
      font-size: 18px; line-height: 1; padding: 4px; color: #d68c00;
    }
    .toggle-password:focus { outline: 2px solid rgba(214,140,0,0.3); border-radius: 6px; }
    .form-bottom { width: 90%; display: flex; justify-content: flex-end; margin-top: 5px; }
    .form-bottom a { text-decoration: underline; color: orange; font-size: 13px; }
    button[type="submit"] {
      width: 90%; background: orange; color: white; padding: 10px;
      border: none; border-radius: 15px; font-weight: bold; cursor: pointer; margin-top: 15px;
    }
    .create-account { text-align: center; margin-top: 15px; color: orange; text-decoration: underline; display: block; font-weight: 500; }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="logo-side">
      <img src="<?= BASEURL; ?>/images/logo.png" alt="Logo" >
    </div>
    <div class="form-side">
      <h2>Login to Continue</h2>
      <form method="POST" action="<?= BASEURL; ?>/auth/login">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">LOGIN</button>
      </form>
      <a href="<?= BASEURL; ?>/auth/register">Create Account</a>
      <?php if(isset($error)) echo "<p style='color:red;'>".htmlspecialchars($error)."</p>"; ?>
    </div>
  </div>
</body>
<script>
  document.querySelectorAll('.toggle-password').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var targetId = btn.getAttribute('data-target');
      var input = document.getElementById(targetId);
      if (!input) return;
      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '👁';
      } else {
        input.type = 'password';
        btn.textContent = '👁';
      }
    });
  });
</script>
</html>

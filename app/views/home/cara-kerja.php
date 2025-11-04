<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cara Kerja Pawtopia</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #eef6fa; margin: 0; padding: 0; }
        .container { display: flex; align-items: center; justify-content: center; gap: 50px; padding: 50px; }
        .text { max-width: 400px; }
        h2 { font-family: 'Comic Neue', cursive; color: #333; }
        ul { list-style: none; padding: 0; }
        li { margin: 10px 0; font-size: 16px; color: #7a4b00; }
        li span { background: #fca120; color: #fff; border-radius: 50%; padding: 5px 10px; margin-right: 8px; }
        button { background: #fca120; border: none; padding: 10px 20px; border-radius: 6px; color: #fff; font-weight: bold; cursor: pointer; }
        button:hover { background: #e18e0a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="assets/img/pawtopia-logo.png" alt="Pawtopia Logo" width="200">
        </div>
        <div class="text">
            <h2>Cara Kerja Pawtopia</h2>
            <ul>
                <?php foreach ($steps as $step): ?>
                    <li><span><?= $step['nomor'] ?></span><?= htmlspecialchars($step['isi']) ?></li>
                <?php endforeach; ?>
            </ul>
            <button>Coba Sekarang</button>
        </div>
    </div>
</body>
</html>

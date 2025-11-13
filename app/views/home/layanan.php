<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Layanan Pawtopia</title>
  <link rel="stylesheet" href="/pawtopia/public/css/style.css">
</head>
<body>
  <section class="penitipan">
    <h2>Pilihan Penitipan</h2>
    <p class="sub">Sesuaikan layanan dengan kebutuhan anda</p>

    <div class="cards">
      <?php
        $layanan = [
          [
            "judul" => "Full Day Care",
            "deskripsi" => "Cocok buat kamu yang kerja seharian tapi ingin si meong tetap aktif & dijaga.",
            "kelas" => "full"
          ],
          [
            "judul" => "Half Day Care",
            "deskripsi" => "Cocok untuk keperluan singkat atau saat kamu hanya keluar beberapa jam.",
            "kelas" => "half"
          ],
          [
            "judul" => "Overnight Stay",
            "deskripsi" => "Cocok buat kamu yang harus pergi luar kota atau menginap di luar rumah.",
            "kelas" => "overnight"
          ]
        ];

        foreach ($layanan as $item) {
          echo "
          <div class='card {$item['kelas']}'>
            <h3>{$item['judul']}</h3>
            <p>{$item['deskripsi']}</p>
          </div>";
        }
      ?>
    </div>
         <!-- ===== BIAYA PENITIPAN – 100% MIRIP FIGMA (HORIZONTAL + DOTTED) ===== -->
  <section class="biaya-figma">
    <div class="container">
      <h2>
        <img src="/pawtopia/public/img/cats-left.png" alt="" class="cat-left">
        Biaya Penitipan
        <img src="/pawtopia/public/img/cats-right.png" alt="" class="cat-right">
      </h2>

      <div class="cards">
        <?php
          $layanan = [
            [
              "judul" => "Full Day Care",
              "harga" => "25 K",
              "durasi" => "08.00 - 17.00",
              "keterangan" => "Penitipan seharian penuh. Termasuk makan, air minum, dan waktu bermain di area play zone.",
              "warna" => "#FF80AB"
            ],
            [
              "judul" => "Half Day Care",
              "harga" => "15 K",
              "durasi" => "08.00 - 12.00<br>13.00 - 17.00",
              "keterangan" => "Penitipan setengah hari. Termasuk makan 1x dan pengawasan staf.",
              "warna" => "#FFB74D"
            ],
            [
              "judul" => "Overnight Stay",
              "harga" => "35 K",
              "durasi" => "Bermalam",
              "keterangan" => "Penitipan menginap. Termasuk makan malam, grooming ringan, dan update foto si meong.",
              "warna" => "#4FC3F7"
            ]
          ];

          foreach ($layanan as $item) {
            echo "
            <div class='card' style='--color: {$item['warna']}'>
              <h3>{$item['judul']}</h3>
              <div class='info'>
                <div class='row'>
                  <span>Harga</span>
                  <strong>{$item['harga']}</strong>
                </div>
                <div class='row'>
                  <span>Durasi</span>
                  <strong>{$item['durasi']}</strong>
                </div>
              </div>
              <div class='desc'>
                <strong>Keterangan</strong><br>
                <p>{$item['keterangan']}</p>
              </div>
            </div>";
          }
        ?>
      </div>

      <!-- Info Box -->
      <div class="info-box">
        <div class="icon">Speaker</div>
        <div class="text">
          <strong>Informasi</strong><br>
          Hai, cat lover!<br>
          Untuk mengamankan tempat penitipan kucingmu, cukup bayar <strong>DP 40%</strong> dulu, ya <span class="heart">Heart</span>
        </div>
      </div>
    </div>
  </section>
</body>
</html>

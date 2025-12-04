<?php
// Load mail helper
require_once dirname(dirname(dirname(__DIR__))) . '/config/mail.php';

// Load Database class
require_once dirname(dirname(__DIR__)) . '/core/Database.php';

// Proses update status jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_mitra']) && isset($_POST['status'])) {
    $id_mitra = $_POST['id_mitra'];
    $status = $_POST['status'];
    
    try {
        // Gunakan Database class
        $database = new Database();
        $conn = $database->getConnection();
        
        // Update status di database
        $stmt = $conn->prepare("UPDATE mitra SET status = ? WHERE id_mitra = ?");
        $stmt->bind_param("ss", $status, $id_mitra);
        $stmt->execute();
        $stmt->close();
        
        // Ambil data mitra untuk email (JOIN dengan users untuk dapat email)
        $stmt = $conn->prepare("
            SELECT m.*, u.email, u.nama_lengkap 
            FROM mitra m 
            INNER JOIN users u ON m.id_users = u.id_users 
            WHERE m.id_mitra = ?
        ");
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();
        $mitra = $result->fetch_assoc();
        $stmt->close();
        
        // Debug: cek apakah data mitra ditemukan
        if (!$mitra) {
            throw new Exception("Data mitra dengan ID {$id_mitra} tidak ditemukan");
        }
        
        // Kirim email hanya untuk status tertentu
        if (in_array($status, ['Pembayaran Ditolak', 'Terverifikasi', 'Ditolak Verifikasi'])) {
            // Validasi field yang diperlukan
            if (!isset($mitra['email']) || !isset($mitra['nama_petshop'])) {
                throw new Exception("Data email atau nama petshop tidak lengkap");
            }
            
            $to = $mitra['email'];
            $namaPetshop = $mitra['nama_petshop'];
            
            // Tentukan subject dan body berdasarkan status
            if ($status === 'Pembayaran Ditolak') {
                $subject = 'Pembayaran Registrasi Ditolak - ' . $namaPetshop;
                $htmlBody = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                        .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Pembayaran Ditolak</h1>
                        </div>
                        <div class='content'>
                            <h2>Halo, {$namaPetshop}</h2>
                            <p>Dengan menyesal kami informasikan bahwa <strong>pembayaran Anda ditolak</strong>.</p>
                            <p>Alasan penolakan:</p>
                            <ul>
                                <li>Bukti pembayaran tidak jelas atau tidak sesuai</li>
                                <li>Nominal pembayaran tidak sesuai</li>
                                <li>Atau alasan teknis lainnya</li>
                            </ul>
                            <p>Silakan lakukan pembayaran ulang dengan memastikan bukti transfer jelas dan nominal sesuai.</p>
                        </div>
                        <div class='footer'>
                            <p>&copy; 2024 Pawtopia Pet Shop Management System</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
            } 
            elseif ($status === 'Terverifikasi') {
                $subject = 'Selamat! Akun Anda Telah Terverifikasi - ' . $namaPetshop;
                $htmlBody = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                        .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; }
                        .success-badge { background: #28a745; color: white; padding: 10px 20px; border-radius: 20px; display: inline-block; margin: 20px 0; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>🎉 Selamat!</h1>
                        </div>
                        <div class='content'>
                            <h2>Halo, {$namaPetshop}</h2>
                            <div class='success-badge'>✓ AKUN TERVERIFIKASI</div>
                            <p>Akun Anda telah <strong>berhasil diverifikasi</strong>!</p>
                            <p>Sekarang Anda dapat:</p>
                            <ul>
                                <li>Login ke dashboard mitra</li>
                                <li>Mengelola layanan pet shop Anda</li>
                                <li>Menerima pesanan dari pelanggan</li>
                                <li>Mengakses semua fitur mitra</li>
                            </ul>
                            <p>Terima kasih telah bergabung sebagai mitra kami!</p>
                        </div>
                        <div class='footer'>
                            <p>&copy; 2024 Pawtopia Pet Shop Management System</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
            } 
            elseif ($status === 'Ditolak Verifikasi') {
                $subject = 'Pemberitahuan Verifikasi Akun - ' . $namaPetshop;
                $htmlBody = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: #ffc107; color: #333; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                        .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Pemberitahuan Verifikasi</h1>
                        </div>
                        <div class='content'>
                            <h2>Halo, {$namaPetshop}</h2>
                            <p>Dengan menyesal kami informasikan bahwa <strong>pengajuan verifikasi Anda ditolak</strong>.</p>
                            <p>Alasan penolakan:</p>
                            <ul>
                                <li>Data atau dokumen tidak lengkap</li>
                                <li>Informasi tidak sesuai persyaratan</li>
                                <li>Atau alasan lain yang tidak memenuhi kriteria</li>
                            </ul>
                            <p>Anda dapat melakukan pendaftaran ulang dengan memastikan semua data terisi dengan benar dan lengkap.</p>
                        </div>
                        <div class='footer'>
                            <p>&copy; 2024 Pawtopia Pet Shop Management System</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
            }
            
            // Kirim email
            try {
                sendEmailNotif($to, $subject, $htmlBody);
                error_log("Email berhasil dikirim ke: " . $to);
            } catch (Exception $emailError) {
                error_log("Gagal kirim email: " . $emailError->getMessage());
                throw new Exception("Gagal mengirim email: " . $emailError->getMessage());
            }
            
            echo "<script>alert('Status berhasil diupdate dan email terkirim!'); window.location.href='';</script>";
        } else {
            echo "<script>alert('Status berhasil diupdate!'); window.location.href='';</script>";
        }
        
    } catch (Exception $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='';</script>";
    }
    exit;
}
?>

<h2>Test Panel Status Mitra</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nama Petshop</th>
        <th>Email</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($data['mitra'] as $m): ?>
    <tr>
        <td><?= $m['id_mitra'] ?></td>
        <td><?= $m['nama_petshop'] ?></td>
        <td><?= $m['email'] ?></td>
        <td><?= $m['status'] ?></td>
        <td>
            <form method="post">
                <input type="hidden" name="id_mitra" value="<?= $m['id_mitra'] ?>">

                <select name="status">
                    <option value="Pembayaran Diproses" <?= $m['status'] == 'Pembayaran Diproses' ? 'selected' : '' ?>>Pembayaran Diproses</option>
                    <option value="Pembayaran Ditolak" <?= $m['status'] == 'Pembayaran Ditolak' ? 'selected' : '' ?>>Pembayaran Ditolak</option>
                    <option value="Terverifikasi" <?= $m['status'] == 'Terverifikasi' ? 'selected' : '' ?>>Terverifikasi</option>
                    <option value="Ditolak Verifikasi" <?= $m['status'] == 'Ditolak Verifikasi' ? 'selected' : '' ?>>Ditolak Verifikasi</option>
                </select>

                <button type="submit">Update & Kirim Email</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
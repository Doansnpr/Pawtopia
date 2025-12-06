<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/AuthModel.php'; 
require_once __DIR__ . '/../../config/mail.php'; 

class ManajemenMitra extends Controller
{
    protected $db;
    protected $authModel; 

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();
        $this->authModel = new AuthModel($this->db);
    }

    // ====================================================================
    // FUNGSI 1: VERIFIKASI DATA (Tombol Centang/Silang di Tabel Admin)
    // ====================================================================
    public function verifikasi()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_mitra = $_POST['id_mitra'];
            $aksi = $_POST['aksi']; // 'terima' atau 'tolak'

            // 1. Ambil Data Mitra (Email & Nama)
            $mitra = $this->authModel->getMitraById($id_mitra);
            
            if (!$mitra) {
                $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'Data tidak ditemukan', 'tipe' => 'error'];
                header('Location: ' . BASEURL . '/DashboardAdmin?page=manajemen_mitra');
                exit;
            }

            $email = $mitra['email'];
            $nama  = $mitra['nama_petshop'];

            // --- SKENARIO A: TERIMA DATA (CENTANG) ---
            // Ubah status jadi 'Menunggu Pembayaran' agar user dapat Pop-up Upload
            if ($aksi === 'terima') {
                $this->authModel->updateMitraStatus($id_mitra, 'Menunggu Pembayaran');

                // Kirim Email: Instruksi Bayar
                $subject = "Verifikasi Data Berhasil - Langkah Selanjutnya";
                $body    = "
                    <h3>Halo $nama,</h3>
                    <p>Selamat! Data pendaftaran petshop Anda telah <b>disetujui</b> oleh Admin.</p>
                    <p>Langkah selanjutnya: Silakan login ke akun Anda dan upload bukti pembayaran pendaftaran sebesar <b>Rp 50.000</b>.</p>
                    <br>
                    <a href='".BASEURL."/auth/login' style='background:#28a745; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Login Disini</a>
                ";
                
                sendEmailNotif($email, $subject, $body);

                $_SESSION['flash'] = ['pesan' => 'Data Diterima', 'aksi' => 'Email tagihan telah dikirim ke mitra.', 'tipe' => 'success'];
            } 
            
            // --- SKENARIO B: TOLAK DATA (SILANG) ---
            // Kirim Email Ditolak lalu HAPUS DATA
            else {
                // 1. Kirim Email Penolakan Dulu
                $subject = "Pendaftaran Ditolak - Pawtopia";
                $body    = "
                    <h3>Halo $nama,</h3>
                    <p>Mohon maaf, pengajuan kemitraan Anda <b>DITOLAK</b>.</p>
                    <p>Alasan: Data yang Anda lampirkan tidak valid atau tidak memenuhi syarat kami.</p>
                    <p>Data akun Anda telah dihapus dari sistem. Silakan melakukan registrasi ulang dengan data yang benar.</p>
                ";
                sendEmailNotif($email, $subject, $body);

                // 2. Hapus Data dari Database (Mitra & User)
                
                // [FIX UTAMA DISINI] 
                // Hapus data di tabel 'mitra_paket' DULU (Anak) agar tidak error Foreign Key
                $stmtPaket = $this->db->prepare("DELETE FROM mitra_paket WHERE id_mitra = ?");
                $stmtPaket->bind_param("s", $id_mitra);
                $stmtPaket->execute();
                $stmtPaket->close();

                // Hapus tabel mitra (Induk)
                $stmt = $this->db->prepare("DELETE FROM mitra WHERE id_mitra = ?");
                $stmt->bind_param("s", $id_mitra);
                $stmt->execute();
                $stmt->close();

                // Hapus tabel users
                if(isset($mitra['id_users'])) {
                    $stmt2 = $this->db->prepare("DELETE FROM users WHERE id_users = ?");
                    $stmt2->bind_param("s", $mitra['id_users']);
                    $stmt2->execute();
                    $stmt2->close();
                }

                $_SESSION['flash'] = ['pesan' => 'Ditolak & Dihapus', 'aksi' => 'Notifikasi dikirim dan data dihapus.', 'tipe' => 'warning'];
            }
            
            header('Location: ' . BASEURL . '/DashboardAdmin?page=manajemen_mitra');
            exit;
        }
    }

    // ====================================================================
    // FUNGSI 2: VALIDASI PEMBAYARAN (Tombol di Modal Cek Bayar)
    // ====================================================================
    public function validasiPembayaran()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_mitra = $_POST['id_mitra'];
            $aksi = $_POST['aksi']; // 'terima' atau 'tolak'

            $mitra = $this->authModel->getMitraById($id_mitra);
            $email = $mitra['email'];
            $nama  = $mitra['nama_petshop'];

            // --- SKENARIO C: PEMBAYARAN VALID (TERIMA) ---
            // Ubah status jadi 'Terverifikasi' -> User bisa masuk Dashboard
            if ($aksi === 'terima') {
                $this->authModel->updateMitraStatus($id_mitra, 'Terverifikasi');

                // Kirim Email Selamat
                $subject = "Selamat! Akun Mitra Pawtopia Aktif";
                $body    = "
                    <h2 style='color:green;'>Selamat Bergabung!</h2>
                    <p>Halo <b>$nama</b>,</p>
                    <p>Pembayaran Anda telah diterima dan valid. Akun Anda sekarang statusnya <b>TERVERIFIKASI</b>.</p>
                    <p>Anda sudah dapat login dan mulai mengelola layanan Anda.</p>
                    <br>
                    <a href='".BASEURL."/auth/login'>Login Dashboard</a>
                ";
                sendEmailNotif($email, $subject, $body);

                $_SESSION['flash'] = ['pesan' => 'Verifikasi Sukses', 'aksi' => 'Mitra resmi bergabung.', 'tipe' => 'success'];
            } 
            
            // --- SKENARIO D: PEMBAYARAN TIDAK VALID (TOLAK) ---
            // Ubah status BALIK ke 'Menunggu Pembayaran' -> User kena Pop-up Upload lagi
            else {
                // Update status & Kosongkan bukti bayar
                $stmt = $this->db->prepare("UPDATE mitra SET status = 'Menunggu Pembayaran', bukti_pembayaran = NULL WHERE id_mitra = ?");
                $stmt->bind_param("s", $id_mitra);
                $stmt->execute();
                $stmt->close();

                // Kirim Email Upload Ulang
                $subject = "Bukti Pembayaran Ditolak";
                $body    = "
                    <h3 style='color:red;'>Pembayaran Ditolak</h3>
                    <p>Halo $nama,</p>
                    <p>Bukti pembayaran yang Anda kirimkan <b>tidak jelas / nominal salah / tidak valid</b>.</p>
                    <p>Status akun Anda dikembalikan ke <b>Menunggu Pembayaran</b>.</p>
                    <p>Silakan login kembali dan <b>upload ulang</b> bukti transfer yang benar.</p>
                ";
                sendEmailNotif($email, $subject, $body);

                $_SESSION['flash'] = ['pesan' => 'Pembayaran Ditolak', 'aksi' => 'Mitra diminta upload ulang.', 'tipe' => 'warning'];
            }

            header('Location: ' . BASEURL . '/DashboardAdmin?page=manajemen_mitra');
            exit;
        }
    }
}
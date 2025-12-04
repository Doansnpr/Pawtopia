<?php
class Auth extends Controller {
    private $authModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        require_once __DIR__ . '/../models/AuthModel.php';
        $db = new Database();
        $this->authModel = new AuthModel($db->getConnection());
    }

    public function index() {
        $this->login();
    }

    // LOGIN
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = $this->authModel->loginUser($email, $password);
            
            if ($user) {

                // ================================================
                // ⬇️ TAMBAHAN: BLOK CEK STATUS MITRA (disesuaikan)
                // ================================================
                if (strtolower($user['role']) === 'mitra') {

                    // ambil status mitra berdasarkan id_users
                    $mitra = $this->authModel->getMitraStatus($user['id_users']);

                    if ($mitra && isset($mitra['status'])) {
                        $status = strtolower(trim($mitra['status']));

                        // Kalau masih menunggu verifikasi admin -> TOLAK login
                        if ($status === 'menunggu verifikasi' || $status === 'menunggu_verifikasi' || $status === 'menunggu-verifikasi') {
                            $_SESSION['flash'] = [
                                'pesan' => 'Login Gagal!',
                                'aksi'  => 'Akun Anda masih menunggu verifikasi oleh admin. Mohon tunggu info melalui email.',
                                'tipe'  => 'warning'
                            ];
                            header('Location: ' . BASEURL . '/auth/login');
                            exit;
                        }

                        // Jika menunggu pembayaran -> boleh login, beri notifikasi agar bayar
                        if ($status === 'menunggu pembayaran' || $status === 'menunggu_pembayaran' || $status === 'menunggu-pembayaran') {
                            
                            $_SESSION['user'] = $user;
                            
                            // KITA UBAH ISINYA AGAR DITANGKAP JS SEBAGAI TRIGGER UPLOAD
                            $_SESSION['flash'] = [
                                'pesan' => 'Verifikasi Diperlukan',
                                'aksi'  => 'force_upload', // <--- PENTING: Ini kode rahasia buat memanggil Pop-up Upload
                                'tipe'  => 'warning'
                            ];
                            header('Location: ' . BASEURL . '/DashboardMitra');
                            exit;
                        }
                        // Jika pembayaran diproses -> boleh login, beri tahu sedang diproses
                        if ($status === 'pembayaran diproses' || $status === 'pembayaran_diproses' || $status === 'pembayaran-diproses') {
                            $_SESSION['user'] = $user;
                            $_SESSION['flash'] = [
                                'pesan' => 'Pembayaran Sedang Diproses',
                                'aksi'  => 'Pembayaran Anda sedang diverifikasi oleh admin. Mohon tunggu email konfirmasi.',
                                'tipe'  => 'info'
                            ];
                            header('Location: ' . BASEURL . '/DashboardMitra');
                            exit;
                        }

                        // Jika terverifikasi -> login normal (lanjut ke bagian asli)
                        if ($status === 'terverifikasi' || $status === 'verified') {
                            // biarkan lanjut ke bagian asli
                        }
                    }
                }
                // ================================================
                // ⬆️ END TAMBAHAN
                // ================================================

                // === BAGIAN ASLI PUNYAMU — TIDAK AKU UBAH SAMA SEKALI ===
                $_SESSION['user'] = $user;
                $_SESSION['flash'] = [
                    'pesan' => 'Login Berhasil!',
                    'aksi'  => 'Selamat datang, ' . htmlspecialchars($user['nama_lengkap']),
                    'tipe'  => 'success'
                ];

                $role = strtolower($user['role']);
                switch ($role) {
                    case 'admin':
                        header('Location: ' . BASEURL . '/DashboardAdmin');
                        break;
                    case 'mitra':
                        header('Location: ' . BASEURL . '/DashboardMitra');
                        break;
                    default:
                        header('Location: ' . BASEURL . '/DashboardCustomer');
                        break;
                }
                exit;
            }

            // LOGIN GAGAL — PUNYA KAMU, TIDAK DIUBAH
            $_SESSION['flash'] = [
                'pesan' => 'Login Gagal!',
                'aksi'  => 'Email atau password salah.',
                'tipe'  => 'error'
            ];
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $this->view('auth/login');
    }

    // REGISTER (MODIFIED: DOUBLE SAVE LOGIC)
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $nama = trim($_POST['nama_lengkap'] ?? '');
            $nohp = trim($_POST['no_hp'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = ucfirst(trim($_POST['role'] ?? 'Customer'));

            // Validasi Mitra
            if (strtolower($role) === 'mitra') {
                if (empty($_POST['agree_fee'])) {
                    $_SESSION['flash'] = [
                        'pesan' => 'Harus Setuju Biaya Pendaftaran',
                        'aksi'  => 'Centang persetujuan pembayaran 50.000.',
                        'tipe'  => 'warning'
                    ];
                    header('Location: ' . BASEURL . '/auth/register');
                    exit;
                }
            }

            // --- HELPER UPLOAD DOUBLE SAVE (UPLOADS & IMAGES) ---
            $uploadFile = function($inputName, $subfolder = 'mitra') {
                if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) return '';
                
                $file = $_FILES[$inputName];
                $root = $_SERVER['DOCUMENT_ROOT'] . '/pawtopia'; // Sesuaikan folder project

                // Folder 1: public/uploads/mitra/
                $dir_uploads = $root . '/public/uploads/' . trim($subfolder, '/') . '/';
                
                // Folder 2: public/images/mitra/
                $dir_images = $root . '/public/images/' . trim($subfolder, '/') . '/';

                // Buat folder jika belum ada
                if (!is_dir($dir_uploads)) mkdir($dir_uploads, 0777, true);
                if (!is_dir($dir_images)) mkdir($dir_images, 0777, true);

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','gif','webp'];
                if (!in_array($ext, $allowed)) return '';

                $filename = $inputName . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                
                $target_uploads = $dir_uploads . $filename;
                $target_images  = $dir_images . $filename;

                // 1. Upload ke folder utama (uploads)
                if (move_uploaded_file($file['tmp_name'], $target_uploads)) {
                    // 2. Copy ke folder kedua (images) agar sinkron
                    copy($target_uploads, $target_images);
                    return $filename; 
                }
                return '';
            };

            $mitraData = [];

            if (strtolower($role) === 'mitra') {
                // Upload File dengan Helper Baru
                $foto_filename = $uploadFile('foto_petshop', 'mitra');
                $ktp_filename  = $uploadFile('foto_ktp', 'mitra'); 

                $mitraData = [
                    'nama_petshop' => trim($_POST['nama_petshop'] ?? ''),
                    'alamat'       => trim($_POST['alamat_petshop'] ?? ''),
                    'no_hp_petshop'=> trim($_POST['no_hp_petshop'] ?? ''),
                    'deskripsi'    => trim($_POST['deskripsi'] ?? ''),
                    'kapasitas'    => (int)($_POST['kapasitas'] ?? 0),
                    'lokasi_lat'   => $_POST['lokasi_lat'] ?? '',
                    'lokasi_lng'   => $_POST['lokasi_lng'] ?? '',
                    'foto_profil'  => $foto_filename,
                    'foto_ktp'     => $ktp_filename, 
                    'data_paket'   => [] 
                ];

                // Ambil paket dinamis
                foreach ($_POST as $k => $v) {
                    if (preg_match('/^harga_paket(\d+)$/', $k, $m)) {
                        $idx = $m[1];
                        $nama_key = 'nama_paket' . $idx;
                        $harga = (int)$v;
                        $nama_paket_item = trim($_POST[$nama_key] ?? '');
                        
                        if ($harga > 0 && $nama_paket_item !== '') {
                            $mitraData['data_paket'][] = [
                                'nama'  => $nama_paket_item,
                                'harga' => $harga
                            ];
                        }
                    }
                }
            }

            // Eksekusi Model
            $success = $this->authModel->registerUser($nama, $nohp, $email, $password, $role, $mitraData);

            if ($success) {
                $_SESSION['flash'] = [
                    'pesan' => 'Akun Berhasil Dibuat!',
                    'aksi'  => 'Silakan login untuk melanjutkan.',
                    'tipe'  => 'success'
                ];
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            } else {
                $_SESSION['flash'] = [
                    'pesan' => 'Gagal Mendaftar!',
                    'aksi'  => 'Email mungkin sudah digunakan.',
                    'tipe'  => 'error'
                ];
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }
        }

        $this->view('auth/register');
    }

    // FORGOT PASSWORD
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $new_password = trim($_POST['new_password'] ?? '');

            if (empty($email) || empty($new_password)) {
                $_SESSION['flash'] = [
                    'pesan' => 'Data Tidak Lengkap!',
                    'aksi'  => 'Isi email dan password baru.',
                    'tipe'  => 'warning'
                ];
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }

            $updated = $this->authModel->updatePasswordByEmail($email, $new_password);
            $_SESSION['flash'] = $updated ? [
                'pesan' => 'Password Berhasil Diubah!',
                'aksi'  => 'Silakan login kembali.',
                'tipe'  => 'success'
            ] : [
                'pesan' => 'Gagal Mengubah Password!',
                'aksi'  => 'Email tidak ditemukan.',
                'tipe'  => 'error'
            ];
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $this->view('auth/login');
    }

    //percobaan
    public function sendStatusEmail($emailTujuan, $namaPetshop, $statusBaru){
        require_once __DIR__ . '/../../config/mail.php';

        $subject = "Status Akun Mitra Anda: " . ucfirst($statusBaru);

        $htmlBody = "
            <h2>Halo $namaPetshop,</h2>
            <p>Status akun mitra Anda telah diperbarui menjadi:</p>
            <h3><b>$statusBaru</b></h3>
            <p>Terima kasih telah menggunakan Pawtopia.</p>
            <br>
            <small>Email otomatis — jangan balas pesan ini.</small>
        ";

        // Gunakan Gmail API
        try {
            sendEmailNotif($emailTujuan, $subject, $htmlBody, null);
            return true;
        } catch (Exception $e) {
            error_log("Email gagal: " . $e->getMessage());
            return false;
        }
    }


    public function logout1() {
        // 1. Hapus semua session
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();

        // 2. REDIRECT KE HALAMAN LOGIN (Ini yang bikin halaman tidak putih/rusak)

    }

    public function logout() {
        $_SESSION = [];
        session_unset();
        
        session_destroy();

        session_start();
        
        $_SESSION['flash'] = [
            'pesan' => 'Berhasil Keluar',
            'aksi'  => 'Anda telah logout dari sistem.',
            'tipe'  => 'success'
        ];

        // 4. Redirect kembali ke Halaman Login
        header('Location: ' . BASEURL . 'auth/login');
        exit;
    }
}
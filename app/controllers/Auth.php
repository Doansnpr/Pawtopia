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

    // REGISTER
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Variabel nama lengkap user
            $nama = trim($_POST['nama_lengkap'] ?? '');
            
            $nohp = trim($_POST['no_hp'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = ucfirst(trim($_POST['role'] ?? 'Customer'));

            // Validasi Mitra: Cek persetujuan fee
            if (strtolower($role) === 'mitra') {
                if (empty($_POST['agree_fee'])) {
                    $_SESSION['flash'] = [
                        'pesan' => 'Harus Setuju Biaya Pendaftaran',
                        'aksi'  => 'Centang persetujuan pembayaran 50.000 untuk mendaftar sebagai Mitra.',
                        'tipe'  => 'warning'
                    ];
                    header('Location: ' . BASEURL . '/auth/register');
                    exit;
                }
            }

            // Helper upload (FIXED PATH: ke public/images)
            $uploadFile = function($inputName, $subfolder = 'mitra') {
                if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) return '';
                $file = $_FILES[$inputName];

                // UBAH PATH DISINI: Mengarah ke public/images/
                $upload_dir = realpath(__DIR__ . '/../../public/images/' ) ?: (__DIR__ . '/../../public/images/');
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                
                // Subfolder (misal: public/images/mitra/)
                $upload_dir = rtrim($upload_dir, '/') . '/' . trim($subfolder, '/') . '/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','gif','webp'];
                if (!in_array($ext, $allowed)) return '';

                $filename = $inputName . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $target = $upload_dir . $filename;

                if (@move_uploaded_file($file['tmp_name'], $target)) {
                    return $filename; // Mengembalikan nama file saja
                }
                return '';
            };

            $mitraData = [];

            if (strtolower($role) === 'mitra') {
                // Upload File (Folder tujuan: mitra)
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
                    'foto_ktp'     => $ktp_filename, // Masukkan ke array agar dikirim ke Model
                    'data_paket'   => [] 
                ];

                // Ambil paket dinamis
                foreach ($_POST as $k => $v) {
                    if (preg_match('/^harga_paket(\d+)$/', $k, $m)) {
                        $idx = $m[1];
                        $nama_key = 'nama_paket' . $idx;
                        $harga = (int)$v;
                        
                        // Pakai variabel baru agar tidak menimpa $nama user
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
                    'aksi'  => 'Email mungkin sudah digunakan atau terjadi kesalahan sistem.',
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
                'aksi'  => 'Silakan login kembali dengan password baru.',
                'tipe'  => 'success'
            ] : [
                'pesan' => 'Gagal Mengubah Password!',
                'aksi'  => 'Email tidak ditemukan di sistem.',
                'tipe'  => 'error'
            ];
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $this->view('auth/login');
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
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }
}
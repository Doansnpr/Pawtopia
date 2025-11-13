<?php
class Auth extends Controller {
    private $authModel;

    public function __construct() {
        // pastikan AuthModel dimuat
        if (!class_exists('AuthModel')) {
            require_once __DIR__ . '/../models/AuthModel.php';
        }

        $db = new Database(); 
        $this->authModel = new AuthModel($db->getConnection()); 
    }

    public function index() {
        $this->login(); 
    }

    // ✅ LOGIN
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            
            $user = $this->authModel->loginUser($email, $password); 

            if ($user) {
                // simpan user ke session
                $_SESSION['user'] = $user; 
                
                $_SESSION['flash'] = [
                    'pesan' => 'Login Berhasil!',
                    'aksi'  => 'Selamat datang, ' . $user['nama_lengkap'] . '!',
                    'tipe'  => 'success'
                ];

                // 🔸 Langsung ke halaman utama (index di folder home)
                header('Location: ' . BASEURL . '/home/index');
                exit;
            } else {
                $_SESSION['flash'] = [
                    'pesan' => 'Login Gagal!',
                    'aksi'  => 'Email atau password salah.',
                    'tipe'  => 'error'
                ];

                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }
        } else {
            // hanya tampilkan halaman login
            $this->view('auth/login');
            exit;
        }
    }

    // ✅ REGISTER
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama_lengkap']);
            $nohp = trim($_POST['no_hp']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']); 
            $role = ucfirst(trim($_POST['role']));

            $success = $this->authModel->registerUser($nama, $nohp, $email, $password, $role);

            if ($success) {
                $_SESSION['flash'] = [
                    'pesan' => 'Akun Berhasil Dibuat!',
                    'aksi'  => 'Silakan login untuk melanjutkan.',
                    'tipe'  => 'success'
                ];

                // setelah register langsung ke login
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            } else {
                $_SESSION['flash'] = [
                    'pesan' => 'Gagal Mendaftar!',
                    'aksi'  => 'Email sudah digunakan atau data tidak valid.',
                    'tipe'  => 'error'
                ];

                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }
        } else {
            // tampilkan form register
            $this->view('auth/register');
            exit;
        }
    }

    // ✅ FORGOT PASSWORD
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $new_password = trim($_POST['new_password']);

            if (empty($email) || empty($new_password)) {
                $_SESSION['flash'] = [
                    'pesan' => 'Data Tidak Lengkap!',
                    'aksi'  => 'Isi email dan password baru.',
                    'tipe'  => 'warning'
                ];
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }

            // update password via model
            $updated = $this->authModel->updatePasswordByEmail($email, $new_password);

            if ($updated) {
                $_SESSION['flash'] = [
                    'pesan' => 'Password Berhasil Diubah!',
                    'aksi'  => 'Silakan login kembali dengan password baru.',
                    'tipe'  => 'success'
                ];
            } else {
                $_SESSION['flash'] = [
                    'pesan' => 'Gagal Mengubah Password!',
                    'aksi'  => 'Email tidak ditemukan di sistem.',
                    'tipe'  => 'error'
                ];
            }

            // 🔸 Setelah ubah password, langsung kembali ke halaman login
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        } else {
            // kalau akses langsung tanpa POST, tampilkan halaman login saja
            $this->view('auth/login');
            exit;
        }
    }
}

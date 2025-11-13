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
            $nama = trim($_POST['nama_lengkap'] ?? '');
            $nohp = trim($_POST['no_hp'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = ucfirst(trim($_POST['role'] ?? 'Customer'));

            $mitraData = [];
            if (strtolower($role) === 'mitra') {
                $mitraData = [
                    'nama_petshop'   => $_POST['nama_petshop'] ?? '',
                    'alamat_petshop' => $_POST['alamat_petshop'] ?? '',
                    'no_hp_petshop'  => $_POST['no_hp_petshop'] ?? '',
                    'deskripsi'      => $_POST['deskripsi'] ?? '',
                    'kapasitas'      => $_POST['kapasitas'] ?? 0,
                    'harga_paket1'   => $_POST['harga_paket1'] ?? 0,
                    'harga_paket2'   => $_POST['harga_paket2'] ?? 0,
                    'harga_paket3'   => $_POST['harga_paket3'] ?? 0,
                    'lokasi_lat'     => $_POST['lokasi_lat'] ?? '',
                    'lokasi_lng'     => $_POST['lokasi_lng'] ?? '',
                    'foto_petshop'   => $_FILES['foto_petshop'] ?? null
                ];
            }

            $success = $this->authModel->registerUser($nama, $nohp, $email, $password, $role, $mitraData);

            $_SESSION['flash'] = $success ? [
                'pesan' => 'Akun Berhasil Dibuat!',
                'aksi'  => 'Silakan login untuk melanjutkan.',
                'tipe'  => 'success'
            ] : [
                'pesan' => 'Gagal Mendaftar!',
                'aksi'  => 'Email sudah digunakan atau data tidak valid.',
                'tipe'  => 'error'
            ];

            header('Location: ' . BASEURL . '/auth/' . ($success ? 'login' : 'register'));
            exit;
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
}

<?php
class Auth extends Controller {
    private $authModel;

    public function __construct() {
    require_once __DIR__ . '/../models/AuthModel.php';

    $db = new Database();
    $this->authModel = new AuthModel($db->getConnection());
}


    public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->authModel->loginUser($email, $password);

        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            echo "<script>alert('Login berhasil!'); window.location='".BASEURL."/home';</script>";
        } else {
            $error = "Email atau password salah!";
            $this->view('auth/login', ['error' => $error]);
        }
    } else {
        $this->view('auth/login');
    }
}


    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama_lengkap'];
            $nohp = $_POST['no_hp'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = ucfirst($_POST['role']);

            $success = $this->authModel->registerUser($nama, $nohp, $email, $password, $role);

            if ($success) {
                echo "<script>alert('Akun berhasil dibuat! Silakan login.'); window.location='".BASEURL."/auth/login';</script>";
            } else {
                $error = "Gagal membuat akun! Pastikan data valid.";
                $this->view('auth/register', ['error' => $error]);
            }
        } else {
            $this->view('auth/register');
        }
    }
}

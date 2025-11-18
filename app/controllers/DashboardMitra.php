<?php 

require_once '../app/core/Database.php';
require_once '../app/models/ProfilMitra.php';

class DashboardMitra extends Controller
{
    protected $db;
    protected $ProfilMitra;

    public function __construct()
    {
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();

        // FIX: inisialisasi model yang benar
        $this->ProfilMitra = new ProfilMitra($this->db);
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/auth/login");
            exit;
        }

        $current_page = $_GET['page'] ?? 'dashboard';

        $data = [
            'title'   => 'Dashboard Mitra',
            'content' => 'dashboard_mitra/dashboard_content'
        ];

        // 🔵 PAGE RESERVASI
        if ($current_page === 'reservasi') {

            require_once '../app/models/BookingModel.php';
            $bookingModel = new BookingModel($this->db);

            $data['reservations']   = $bookingModel->getAllBookings();
            $data['statusCounts']   = $bookingModel->getStatusCounts();
            $data['content']        = 'dashboard_mitra/manajemen_booking/booking';
        }

        // 🔵 PAGE PROFIL
        else if ($current_page === 'profil') {

            $user_id = $_SESSION['user']['id_users'];

            // FIX: pakai model yang benar
            $mitra_data = $this->ProfilMitra->getMitraByUserId($user_id);

            if (!$mitra_data) {
                die("Data mitra tidak ditemukan untuk user ini. Silakan hubungi admin.");
            }

            $data['mitra']   = $mitra_data;
            $data['content'] = 'dashboard_mitra/profile/profile';
        }

        $this->view('layouts/dashboard_layout', $data);
    }

    // 🔵 UPDATE PROFIL
    public function updateProfile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // hanya izinkan POST
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: " . BASEURL . "/dashboardMitra?page=profil");
            exit;
        }

        // FIX: Ambil data mitra berdasarkan session user
        $user_id = $_SESSION['user']['id_users'];
        $mitra_data = $this->ProfilMitra->getMitraByUserId($user_id);

        if (!$mitra_data) {
            die("Data mitra tidak ditemukan!");
        }

        $id_mitra = $mitra_data['id_mitra'];

        // Kumpulkan data form
        $data = [
            "nama_petshop"  => $_POST['nama_petshop']  ?? '',
            "alamat"        => $_POST['alamat']        ?? '',
            "no_hp"         => $_POST['no_hp']         ?? '',
            "deskripsi"     => $_POST['deskripsi']     ?? '',
            "kapasitas"     => (int)($_POST['kapasitas']     ?? 0),
            "harga_paket1"  => (int)($_POST['harga_paket1']  ?? 0),
            "harga_paket2"  => (int)($_POST['harga_paket2']  ?? 0),
            "harga_paket3"  => (int)($_POST['harga_paket3']  ?? 0),
            "lokasi_lat"    => !empty($_POST['latitude'])  ? (float)$_POST['latitude']  : null,
            "lokasi_lng"    => !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null,
            "foto_profil"   => $mitra_data['foto_profil'] ?? ''
        ];

        // 🔵 UPLOAD FOTO BARU
        if (!empty($_FILES['foto_petshop']['name']) && $_FILES['foto_petshop']['error'] == 0) {

            $fileTmp  = $_FILES['foto_petshop']['tmp_name'];
            $fileName = time() . "_" . basename($_FILES['foto_petshop']['name']);

            $uploadDir = "public/uploads/mitra/";
            $destPath  = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {

                if (move_uploaded_file($fileTmp, $destPath)) {

                    // hapus foto lama jika ada
                    if (!empty($mitra_data['foto_profil']) &&
                        file_exists($uploadDir . $mitra_data['foto_profil'])) 
                    {
                        unlink($uploadDir . $mitra_data['foto_profil']);
                    }

                    $data["foto_profil"] = $fileName;
                }
            }
        }

        // 🔵 UPDATE DATABASE
        $result = $this->ProfilMitra->updateMitra($id_mitra, $data);

        if ($result) {
            $_SESSION['success'] = "Profil berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui profil!";
        }

        // 🔥 PENTING: redirect balik ke halaman profil
        header("Location: " . BASEURL . "/dashboardMitra?page=profil");
        exit;
    }
}

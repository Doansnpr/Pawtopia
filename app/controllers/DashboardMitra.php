
<?php
require_once '../app/core/Database.php'; 
require_once '../app/models/BookingModel.php'; 
require_once '../app/models/StatusModel.php'; 
require_once '../app/models/ProfilMitra.php';

class DashboardMitra extends Controller
{
    protected $db;
    protected $ProfilMitra;

    public function __construct()
    {
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();
        $this->ProfilMitra = new ProfilMitra($this->db);
    }

    public function index(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/auth/login");
            exit;
        }

        $id_user = null;
        if (is_array($_SESSION['user'])) {
            $id_user = $_SESSION['user']['id_users'] ?? $_SESSION['user']['id'];
        } else {
            $id_user = $_SESSION['user'];
        }

        $mitra_data = $this->ProfilMitra->getMitraByUserId($id_user);
        
        if (!$mitra_data) {
            header("Location: " . BASEURL . "/home"); 
            exit;
        }

        $id_mitra = $mitra_data['id_mitra'];
        $_SESSION['id_mitra'] = $id_mitra; 

        $current_page = $_GET['page'] ?? 'dashboard';
        
        $data = [
            'mitra_info' => $mitra_data, 
            'content'    => 'dashboard_mitra/dashboard_content' 
        ];

        if ($current_page === 'reservasi') {

            require_once '../app/models/BookingModel.php';
            $bookingModel = new BookingModel($this->db);

            $paket_mitra = $bookingModel->getPackagesByMitra($id_mitra);

            $data['reservations'] = $bookingModel->getAllBookings($id_mitra);
            $data['statusCounts'] = $bookingModel->getStatusCounts($id_mitra);
            $data['paket_mitra']  = $paket_mitra; 
            
            $data['title']   = 'Manajemen Reservasi';
            $data['content'] = 'dashboard_mitra/manajemen_booking/booking';

        } 
        else if ($current_page === 'status') { 

            require_once '../app/models/StatusKucingModel.php'; 
            $statusModel = new StatusKucingModel($this->db);   
            
            $activeCats = $statusModel->getActiveCatsByMitra($id_mitra);

            $data['activeCats'] = $activeCats;
            $data['title']      = 'Manajemen Status Kucing';
            $data['content']    = 'dashboard_mitra/manajemen_status_penitipan/status'; 

        } 
        else if ($current_page === 'profil') {
            
            $data['mitra']   = $mitra_data; 
            $data['title']   = 'Profil Saya';
            $data['content'] = 'dashboard_mitra/profile/profile';
        }

        $this->view('layouts/dashboard_layout', $data);
    }

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

<?php
// Load file core database dan model
require_once '../app/core/Database.php'; 
require_once '../app/models/DashboardModel.php'; 
require_once '../app/models/BookingCustModel.php'; 
require_once '../app/models/StatusModel.php'; 
require_once '../app/models/CariModel.php';
require_once '../app/models/ProfilCustomer.php';
require_once '../app/controllers/Prof_Customer.php';

class DashboardCustomer extends Controller {

    private $db;
    private $dashModel;
    private $bookingModel;
    private $statusModel;
    private $cariModel;

    public function __construct() {
        // 1. Ambil koneksi dari file Database.php kamu
        $db_class = new Database();
        $this->db = $db_class->getConnection(); 

        // 2. Load semua model dengan koneksi tersebut
        $this->dashModel    = new DashboardModel($this->db);
        $this->bookingModel = new BookingCustModel($this->db);
        $this->statusModel  = new StatusModel($this->db);
        $this->cariModel    = new CariModel($this->db);
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;
        if (!$id_user) { header('Location: ' . BASEURL . '/auth/login'); exit; }

        $tahun = $_GET['tahun'] ?? date("Y");
        
        // Pakai $this->dashModel
        $data = [
            'title'           => 'Dashboard',
            'content'         => 'dashboard_customer/index',
            'nama_pengguna'   => $this->dashModel->getNamaUser($id_user),
            'kucing_list'     => $this->dashModel->getAllKucingUser($id_user),
            'mitra_list'      => $this->dashModel->getAllMitra(),
            'bookings'        => $this->dashModel->getActiveBookings($id_user),
            'jumlah_kucing'   => $this->dashModel->getCountActiveKucing($id_user),
            'rating_mitra'    => $this->dashModel->getRataRatingMitra(),
            'chart_data'      => $this->dashModel->getDataChartBooking($id_user, $tahun),
            'tahun_pilih'     => $tahun,
            'bulan_nama'      => ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]
        ];


        $this->view('layouts/dashboard_layoutCus', $data);
    }

    public function profil() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user_id = $_SESSION['user']['id_users'] ?? null;
        if (!$user_id) { header('Location: ' . BASEURL . '/auth/login'); exit; }

        $profilController = new Prof_Customer($this->db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mode = $_POST['mode'] ?? '';
            if ($mode === 'update_password') {
                $profilController->updatePassword($user_id, $_POST);
            } else {
                $profilController->updateData($user_id, $_POST);
            }
            exit;
        }

        $dataProfil = $profilController->tampilkanProfil($user_id);
        $data = [
            'title'   => 'Profil Customer',
            'content' => 'dashboard_customer/profile/profile',
            'profil'  => $dataProfil['profil'],
            'riwayat' => $dataProfil['riwayat'],
            'flash'   => $dataProfil['flash']
        ];
        $this->view('layouts/dashboard_layoutCus', $data);
    }

    public function Booking() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;
        
        $data = [
            'title'    => 'Booking Layanan',
            'content'  => 'dashboard_customer/booking/booking',
            'mitras'   => $this->bookingModel->getAllMitra(),
            'cats'     => $this->bookingModel->getMyCats($id_user),
            'bookings' => $this->bookingModel->getMyBookings($id_user)
        ];
        $this->view('layouts/dashboard_layoutCus', $data);
    }

    public function Penitipan() {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

        // Pakai $this->cariModel yang sudah diload di __construct
        $hotArrivals = $this->cariModel->getHotArrivals();
        $mitraList   = $this->cariModel->getRandomMitra($keyword);

        $data = [
            'title'       => 'Cari Penitipan',
            'content'     => 'dashboard_customer/pilih_penitipan/penitipan',
            'hotArrivals' => $hotArrivals, 
            'mitraList'   => $mitraList,   
            'keyword'     => $keyword      
        ];
        $this->view('layouts/dashboard_layoutCus', $data);
    }

    public function ulasan() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;
        if (!$id_user) { header('Location: ' . BASEURL . '/auth/login'); exit; }

        $id_user_safe = $this->db->real_escape_string($id_user);

        // ... (Kode POST ulasan tetap sama, gunakan $this->db->query) ...
        // Agar tidak kepanjangan saya skip bagian POST, tapi biarkan logika aslimu ada di sini

        $bookingSiapUlas = $this->dashModel->getBookingSiapUlas($id_user);
        $result = $this->db->query("SELECT * FROM ulasan WHERE id_users = '$id_user_safe' ORDER BY tgl_ulasan DESC");
        $ulasan_data = ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
        
        $ulasan_with_balasan = [];
        foreach ($ulasan_data as $u) {
            $id_ulasan = $u['id_ulasan'];
            $resBalasan = $this->db->query("SELECT balasan FROM balasan_ulasan WHERE id_ulasan = '$id_ulasan' LIMIT 1");
            $u['balasan_mitra'] = ($resBalasan && $resBalasan->num_rows > 0) ? $resBalasan->fetch_assoc()['balasan'] : null;
            $ulasan_with_balasan[] = $u;
        }

        $data = [
            'title' => 'Beri Ulasan',
            'content' => 'dashboard_customer/ulasan',
            'id_user' => $id_user,
            'ulasan' => $ulasan_with_balasan,
            'booking_siap_ulas' => $bookingSiapUlas,
            'punyaBookingSelesai' => !empty($bookingSiapUlas),
            'flash' => $_SESSION['flash'] ?? null
        ];
        unset($_SESSION['flash']);
        $this->view('layouts/dashboard_layoutCus', $data);
    }

    public function status_penitipan($id_booking = null) {
        // 1. Cek Session & Auth
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;
        
        if (!$id_user) { 
            header('Location: ' . BASEURL . '/auth/login'); 
            exit; 
        }

        // 2. Ambil SEMUA Booking Aktif User (Untuk Sidebar/Navigasi)
        // Ingat: Model 'getAllActiveBookings' mengembalikan array banyak baris
        $allActiveBookings = $this->statusModel->getAllActiveBookings($id_user);

        // 3. Cek apakah ada booking aktif sama sekali?
        if (empty($allActiveBookings)) {
            $data = [
                'title' => 'Status Penitipan',
                'content' => 'dashboard_customer/status_penitipan/status',
                'sidebar_bookings' => [], // List kosong
                'detail_booking'   => [], // Detail kosong
                'logs_by_cat'      => [],
                'pesan_kosong'     => 'Anda tidak memiliki penitipan aktif saat ini.'
            ];
            $this->view('layouts/dashboard_layoutCus', $data);
            return;
        }

        // 4. Tentukan ID Booking mana yang mau ditampilkan
        // Jika user tidak memilih ID (null), atau ID yang dipilih tidak valid,
        // maka default pilih booking yang paling baru (index 0)
        if ($id_booking == null) {
            $id_booking = $allActiveBookings[0]['id_booking'];
        }

        // 5. Ambil DETAIL Kucing & Log untuk ID yang dipilih
        $detailBooking = $this->statusModel->getDetailBooking($id_booking, $id_user);
        $activityLogs  = $this->statusModel->getActivityLogs($id_booking);
        
        // 6. Proses Logika Foto (Looping pada $detailBooking)
        $pathFolderKucing = '/pawtopia/public/images/kucing/';
        $pathDefault = '/pawtopia/public/images/default-cat.jpg';
        
        foreach ($detailBooking as $key => $cat) {
            $f = $cat['foto_kucing'];
            $detailBooking[$key]['foto_kucing_url'] = (!empty($f) && $f !== 'default.jpg') ? $pathFolderKucing . $f : $pathDefault;
        }

        // 7. Grouping Log berdasarkan ID Kucing
        $logsByCat = [];
        foreach ($activityLogs as $log) {
            $logsByCat[$log['id_kucing'] ?? 'unknown'][] = $log;
        }

        // 8. Kirim Data ke View
        $data = [
            'title' => 'Status Penitipan',
            'content' => 'dashboard_customer/status_penitipan/status',
            
            // Data Penting:
            'sidebar_bookings' => $allActiveBookings, // Untuk list di samping/atas (Pilih Booking)
            'active_id'        => $id_booking,        // Untuk menandai mana yang sedang aktif
            'detail_booking'   => $detailBooking,     // Data kucing-kucing di booking tersebut
            'logs_by_cat'      => $logsByCat,         // Log aktivitas
            
            'pesan_kosong'     => null
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    // --- JSON DETAIL (SUDAH PAKAI MODEL DARI __CONSTRUCT) ---
    public function getMitraDetailJson($id_mitra) {
        // Bersihkan output
        if (ob_get_length()) ob_clean();
        
        // Gunakan model yang sudah diload di __construct ($this->cariModel)
        $data = $this->cariModel->getMitraDetailById($id_mitra);
        
        header('Content-Type: application/json');
        
        if (!$data) {
            echo json_encode(['error' => 'Data tidak ditemukan']);
        } else {
            echo json_encode($data);
        }
        exit;
    }
}
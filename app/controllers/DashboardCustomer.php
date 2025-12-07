<?php
// Load semua Model yang dibutuhkan di awal
require_once '../app/core/Database.php'; 
require_once '../app/models/DashboardModel.php'; 
require_once '../app/models/BookingCustModel.php'; 
require_once '../app/models/StatusModel.php'; 
require_once '../app/models/CariModel.php';
require_once '../app/models/ProfilCustomer.php';
require_once '../app/controllers/Prof_Customer.php';

class DashboardCustomer extends Controller {

    // Properti untuk menyimpan instance Model & Database
    private $db;
    private $bookingModel;
    private $dashModel;
    private $statusModel;
    private $cariModel;

    public function __construct() {
        // 1. Inisialisasi Koneksi Database (MVC)
        $db_class = new Database();
        $this->db = $db_class->getConnection(); 

        // 2. Inisialisasi Semua Model (Pass koneksi $this->db ke Model)
        $this->bookingModel = new BookingCustModel($this->db);
        $this->dashModel    = new DashboardModel($this->db); 
        $this->statusModel  = new StatusModel($this->db);
        $this->cariModel    = new CariModel($this->db);
    }

    // --- HALAMAN UTAMA DASHBOARD ---
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $id_user = $_SESSION['user']['id_users'] ?? null;
        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $tahun = $_GET['tahun'] ?? date("Y");
        
        // Panggil method dari Model yang sudah di-init di __construct
        $nama_pengguna  = $this->dashModel->getNamaUser($id_user);
        $kucingList     = $this->dashModel->getAllKucingUser($id_user);
        $mitraList      = $this->dashModel->getAllMitra();
        $chartData      = $this->dashModel->getDataChartBooking($id_user, $tahun);
        $activeBookings = $this->dashModel->getActiveBookings($id_user);
        $jumlahKucing   = $this->dashModel->getCountActiveKucing($id_user);
        $ratingMitra    = $this->dashModel->getRataRatingMitra();

        $data = [
            'title'           => 'Dashboard',
            'content'         => 'dashboard_customer/index',
            'nama_pengguna'   => $nama_pengguna,
            'kucing_list'     => $kucingList,
            'mitra_list'      => $mitraList,
            'bookings'        => $activeBookings,
            'jumlah_kucing'   => $jumlahKucing,
            'rating_mitra'    => $ratingMitra,
            'chart_data'      => $chartData,
            'tahun_pilih'     => $tahun,
            'bulan_nama'      => ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    // --- HALAMAN PROFIL ---
    public function profil() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user_id = $_SESSION['user']['id_users'] ?? null;
        
        if (!$user_id) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        // Panggil Controller Profil (Pass koneksi DB yang sudah ada)
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

    // --- HALAMAN BOOKING ---
    public function Booking() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/auth/login");
            exit;
        }

        $id_user = $_SESSION['user']['id_users'] ?? $_SESSION['user']['id'];

        // Gunakan $this->bookingModel yang sudah ready
        $data_mitra   = $this->bookingModel->getAllMitra();
        $data_kucing  = $this->bookingModel->getMyCats($id_user);
        $data_booking = $this->bookingModel->getMyBookings($id_user);

        $data = [
            'title'    => 'Booking Layanan',
            'mitras'   => $data_mitra,
            'cats'     => $data_kucing,
            'bookings' => $data_booking,
            'content'  => 'dashboard_customer/booking/booking'
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    // --- HALAMAN PENITIPAN (CARI) ---
    public function Penitipan() {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

        // Gunakan $this->cariModel yang sudah ready
        $hotArrivals = $this->cariModel->getHotArrivals();
        
        // Perbaikan: gunakan getAllMitra() karena getRandomMitra() mungkin tidak ada/salah nama
        $mitraList   = $this->cariModel->getAllMitra($keyword); 

        $data = [
            'title'       => 'Cari Penitipan',
            'content'     => 'dashboard_customer/pilih_penitipan/penitipan',
            'hotArrivals' => $hotArrivals, 
            'mitraList'   => $mitraList,   
            'keyword'     => $keyword      
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    // --- HALAMAN ULASAN ---
    public function ulasan() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;
        
        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        // Gunakan $this->db yang sudah terkoneksi
        $id_user_safe = $this->db->real_escape_string($id_user);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mode = $_POST['mode'] ?? 'baru';
            $id_ulasan_post = $this->db->real_escape_string($_POST['id_ulasan'] ?? '');

            if ($mode === 'hapus' && $id_ulasan_post !== '') {
                $this->db->query("DELETE FROM ulasan WHERE id_ulasan = '$id_ulasan_post' AND id_users = '$id_user_safe'");
                $this->db->query("DELETE FROM balasan_ulasan WHERE id_ulasan = '$id_ulasan_post'");
                
                $_SESSION['flash'] = [
                    'pesan' => ($this->db->error) ? 'Error Hapus: '.$this->db->error : 'Ulasan berhasil dihapus.',
                    'tipe' => ($this->db->error) ? 'error' : 'sukses'
                ];
            } elseif ($mode === 'perbarui' && $id_ulasan_post !== '') {
                $rating = (int)($_POST['rating'] ?? 0);
                $komentar = trim($this->db->real_escape_string($_POST['komentar'] ?? ''));
                
                $this->db->query("UPDATE ulasan SET rating = '$rating', komentar = '$komentar', tgl_ulasan = NOW() WHERE id_ulasan = '$id_ulasan_post' AND id_users = '$id_user_safe'");
                
                $_SESSION['flash'] = [
                    'pesan' => ($this->db->error) ? 'Error Update: '.$this->db->error : 'Ulasan berhasil diperbarui.',
                    'tipe' => ($this->db->error) ? 'error' : 'sukses'
                ];
            } elseif ($mode === 'baru') {
                $rating = (int)($_POST['rating'] ?? 0);
                $komentar = trim($this->db->real_escape_string($_POST['komentar'] ?? ''));

                // Logika insert ulasan baru
                $resBooking = $this->db->query("SELECT b.id_booking FROM booking b JOIN booking_lifecycle bl ON b.id_booking = bl.id_booking LEFT JOIN ulasan u ON b.id_booking = u.id_booking WHERE b.id_users = '$id_user_safe' AND b.status = 'selesai' AND bl.status = 'Selesai' AND u.id_booking IS NULL ORDER BY b.tgl_booking DESC LIMIT 1");
                $id_booking_post = ($resBooking && $resBooking->num_rows > 0) ? $resBooking->fetch_assoc()['id_booking'] : null;

                if (!$id_booking_post) {
                    $_SESSION['flash'] = ['pesan' => 'Error: Tidak ada booking selesai untuk diulas.', 'tipe' => 'error'];
                } else {
                    $resMax = $this->db->query("SELECT MAX(id_ulasan) AS last_id FROM ulasan");
                    $lastId = ($resMax && $resMax->num_rows > 0) ? $resMax->fetch_assoc()['last_id'] : null;
                    $num = ($lastId) ? (int)substr($lastId, 6) + 1 : 1;
                    $newId = "Ulasan" . str_pad($num, 3, "0", STR_PAD_LEFT);

                    $this->db->query("INSERT INTO ulasan (id_ulasan, id_users, id_booking, rating, komentar, tgl_ulasan) VALUES ('$newId', '$id_user_safe', '$id_booking_post', '$rating', '$komentar', NOW())");

                    $_SESSION['flash'] = [
                        'pesan' => ($this->db->error) ? 'Error: '.$this->db->error : 'Ulasan berhasil dikirim!',
                        'tipe' => ($this->db->error) ? 'error' : 'sukses'
                    ];
                }
            }
            header('Location: ' . BASEURL . '/DashboardCustomer/ulasan');
            exit;
        }

        // Ambil Data View Ulasan (Gunakan $this->dashModel)
        $bookingSiapUlas = $this->dashModel->getBookingSiapUlas($id_user); 
        
        $result = $this->db->query("SELECT * FROM ulasan WHERE id_users = '$id_user_safe' ORDER BY tgl_ulasan DESC");
        $ulasan_data = ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
        
        $ulasan_with_balasan = [];
        foreach ($ulasan_data as $u) {
            $id_ulasan_safe = $this->db->real_escape_string($u['id_ulasan']);
            $resBalasan = $this->db->query("SELECT balasan, tgl_balasan FROM balasan_ulasan WHERE id_ulasan = '$id_ulasan_safe' LIMIT 1");
            
            if ($resBalasan && $resBalasan->num_rows > 0) {
                $balasan = $resBalasan->fetch_assoc();
                $u['balasan_mitra'] = $balasan['balasan'];
                $u['tgl_balasan_mitra'] = $balasan['tgl_balasan']; 
            } else {
                $u['balasan_mitra'] = null;
                $u['tgl_balasan_mitra'] = null;
            }
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

    // --- HALAMAN STATUS PENITIPAN ---
    public function status_penitipan($id_booking = null) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;
        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        // Gunakan $this->statusModel yang sudah ready (Jangan di-new lagi)
        if ($id_booking == null) {
            $lastBooking = $this->statusModel->getLatestActiveBooking($id_user);
            if ($lastBooking) {
                $id_booking = $lastBooking['id_booking'];
            } else {
                $data = [
                    'title' => 'Status Penitipan',
                    'content' => 'dashboard_customer/status_penitipan/status',
                    'booking' => null,
                    'log_activity' => [],
                    'pesan_kosong' => 'Anda tidak memiliki penitipan aktif saat ini.'
                ];
                $this->view('layouts/dashboard_layoutCus', $data);
                return;
            }
        }

        $bookingDetail = $this->statusModel->getDetailBooking($id_booking, $id_user);
        
        if (!$bookingDetail) {
            header('Location: ' . BASEURL . '/DashboardCustomer');
            exit;
        }

        // Siapkan URL Foto
        $baseUrlClean = rtrim(BASEURL, '/'); 
        $fotoName = $bookingDetail['foto_kucing'];
        $bookingDetail['foto_kucing_url'] = (!empty($fotoName)) 
            ? $baseUrlClean . '/public/images/kucing/' . $fotoName 
            : $baseUrlClean . '/public/images/default-cat.jpg';

        $activityLogs = $this->statusModel->getActivityLogs($id_booking);

        foreach ($activityLogs as $key => $log) {
            $activityLogs[$key]['url_foto_fixed'] = (!empty($log['url_foto']))
                ? $baseUrlClean . '/public/images/logs/' . $log['url_foto']
                : null;
        }

        $data = [
            'title' => 'Status Penitipan',
            'content' => 'dashboard_customer/status_penitipan/status', 
            'booking' => $bookingDetail,
            'log_activity' => $activityLogs
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }
}
?>
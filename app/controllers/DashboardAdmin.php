<?php
require_once '../app/core/Database.php';
require_once '../app/models/PenggunaModel.php';
require_once '../app/models/MitraModel.php';
require_once '../app/models/LaporanAdminModel.php';
class DashboardAdmin extends Controller
{
    protected $db;
    protected $userModel;
    protected $laporanModel;

    public function __construct()
    {
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();
        $this->userModel = new PenggunaModel($this->db);
        $this->laporanModel = new LaporanAdminModel($this->db);
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/auth/login");
            exit;
        }

        $id_user = is_array($_SESSION['user']) ? ($_SESSION['user']['id_users'] ?? $_SESSION['user']['id']) : $_SESSION['user'];
        $admin_data = $this->userModel->getUserById($id_user);

        if (!$admin_data) {
            session_destroy();
            header("Location: " . BASEURL . "/auth/login");
            exit;
        }

        $page = $_GET['page'] ?? 'dashboard';

        $data = [
            'admin_info' => $admin_data,
            'title'      => 'Dashboard Admin',
            'current_page' => $page,
            // Statistik Dummy agar tidak error saat view dashboard load
            'stats' => [
                'total_mitra' => 2,
                'total_transaksi' => 15,
                'total_user' => 45
            ]
        ];

        switch ($page) {
            case 'manajemen_pengguna':
                $data['users'] = $this->userModel->getAllUsers();
                $data['title'] = 'Manajemen Pengguna';
                // SESUAIKAN DENGAN SCREENSHOT STRUKTUR FOLDER ANDA
                // Folder: manajemen_pengguna, File: Manajemen_Pengguna.php
                $data['content_view'] = 'dashboard_admin/manajemen_pengguna/Manajemen_Pengguna'; 
                break;

            case 'manajemen_mitra':
                $data['title'] = 'Manajemen Mitra';
                $data['content_view'] = 'dashboard_admin/manajemen_mitra/manajemen_mitra';
                break;

            case 'manajemen_transaksi':
                $data['title'] = 'Monitoring Transaksi';
                $data['content_view'] = 'dashboard_admin/manajemen_transaksi/index';
                break;

            case 'laporan':
                // --- LOGIKA CETAK SEMUA DATA ---
                if (isset($_GET['print_all'])) {
                    $data['title'] = 'Cetak Laporan Lengkap';
                    
                    // Ambil statistik
                    $data['stats'] = $this->laporanModel->getStatistikKeuangan();
                    
                    // Ambil SEMUA riwayat (Kita kasih limit sangat besar agar terambil semua, misal 10.000 data)
                    // Offset 0 berarti mulai dari awal
                    $data['riwayat'] = $this->laporanModel->getAllRiwayatBooking(10000, 0); 
                    
                    // Load View KHUSUS CETAK (Tanpa Sidebar/Header Dashboard)
                    // Kita akan buat file baru namanya 'cetak_laporan.php'
                    require_once '../app/views/dashboard_admin/laporan/cetak_laporan.php';
                    exit; // Stop script agar layout dashboard utama tidak ikut termuat
                }

                // --- LOGIKA HALAMAN BIASA (DENGAN PAGINATION) ---
                $data['title'] = 'Laporan Pendapatan & Booking';
                
                $limit = 4; 
                $page_no = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
                if ($page_no < 1) $page_no = 1;
                $offset = ($page_no - 1) * $limit;

                $total_data = $this->laporanModel->countAllRiwayat();
                $total_pages = ceil($total_data / $limit);
                
                $data['stats'] = $this->laporanModel->getStatistikKeuangan();
                $data['riwayat'] = $this->laporanModel->getAllRiwayatBooking($limit, $offset);
                
                $data['pagination'] = [
                    'current_page' => $page_no,
                    'total_pages' => $total_pages,
                    'has_next' => $page_no < $total_pages,
                    'has_prev' => $page_no > 1
                ];
                
                $data['content_view'] = 'dashboard_admin/laporan/laporan';
                break;

            default: // dashboard
                $data['title'] = 'Dashboard Overview';
                // View ini null karena konten dashboard sudah ada di index.php
                $data['content_view'] = null; 
                break;
        }

        $this->view('dashboard_admin/index', $data);
    }

    public function ubah()
    {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // $_POST sekarang akan berisi 'status' dari form select di modal
        if ($this->userModel->updateUser($_POST)) {
            // Set Flash Message Sukses
            header('Location: ' . BASEURL . '/dashboard_admin?page=manajemen_pengguna');
        } else {
            // Set Flash Message Gagal
            }
        }
    }

}
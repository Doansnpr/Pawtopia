<?php
require_once '../app/core/Database.php';
require_once '../app/models/PenggunaModel.php';
require_once '../app/models/MitraModel.php';
class DashboardAdmin extends Controller
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();
        $this->userModel = new PenggunaModel($this->db);
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
                $data['title'] = 'Laporan Sistem';
                $data['content_view'] = 'dashboard_admin/laporan/index';
                break;

            default: // dashboard
                $data['title'] = 'Dashboard Overview';
                // View ini null karena konten dashboard sudah ada di index.php
                $data['content_view'] = null; 
                break;
        }

        $this->view('dashboard_admin/index', $data);
    }
}
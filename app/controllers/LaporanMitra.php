<?php
require_once '../app/core/Database.php';
require_once '../app/models/BookingModel.php';
require_once '../app/models/LaporanMitraModel.php';


class LaporanMitra extends Controller {
    protected $db;

    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mitra') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $id_mitra = $_SESSION['id_mitra'];
        
        require_once '../app/models/LaporanMitraModel.php';
        $laporanModel = new LaporanMitraModel($this->db);

        // Filter Tanggal
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate   = $_GET['end_date'] ?? date('Y-m-d');

        // Ambil Data
        $financialStats = $laporanModel->getFinancialStats($id_mitra, $startDate, $endDate);
        $occupancyStats = $laporanModel->getOccupancyStats($id_mitra);
        $transactions   = $laporanModel->getTransactionHistory($id_mitra, $startDate, $endDate);

        // Hitung Growth
        $lastMonthRev = $laporanModel->getPreviousMonthRevenue($id_mitra);
        $currentRev   = $financialStats['pendapatan'];
        $growth = 0;
        
        if ($lastMonthRev > 0) {
            $growth = (($currentRev - $lastMonthRev) / $lastMonthRev) * 100;
        } else if ($currentRev > 0) {
            $growth = 100;
        }

        $data['laporan'] = [
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'financial'  => $financialStats,
            'occupancy'  => $occupancyStats,
            'history'    => $transactions,
            'growth'     => round($growth, 1)
        ];

        $data['title'] = 'Laporan Bisnis';
        
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('dashboard_mitra/laporan/laporan', $data); 
        $this->view('templates/footer');
    }

    // Ajax Detail
    public function get_booking_details() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_booking = $_POST['id_booking'] ?? null;
            
            if ($id_booking) {
                require_once '../app/models/LaporanMitraModel.php';
                $laporanModel = new LaporanMitraModel($this->db);
                
                $details = $laporanModel->getBookingDetails($id_booking);
                
                echo json_encode([
                    'status' => 'success',
                    'data' => $details
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID Booking tidak ditemukan']);
            }
        }
    }
}
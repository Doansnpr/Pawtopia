<?php
require_once '../app/core/Database.php';
require_once '../app/models/BookingModel.php';
require_once '../app/models/LaporanMitraModel.php';


class LaporanMitra extends Controller {
    protected $db;

    public function index() {
        var_dump($_GET); 
        die();
        // 1. Cek Login
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mitra') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $id_mitra = $_SESSION['id_mitra'];
        
        require_once '../app/models/LaporanMitraModel.php';
        $laporanModel = new LaporanMitraModel($this->db);

        // 2. AMBIL DATA DARI GET (Gunakan !empty agar lebih aman daripada isset)
        // Jika user belum pilih, variabel ini akan bernilai string kosong ''
        $startDate = !empty($_GET['start_date']) ? $_GET['start_date'] : '';
        $endDate   = !empty($_GET['end_date'])   ? $_GET['end_date']   : '';

        // 3. Default Values (Data Kosong)
        $financialStats = ['pendapatan' => 0, 'booking_selesai' => 0, 'booking_batal' => 0];
        $occupancyStats = ['rate' => 0, 'terisi' => 0, 'kapasitas' => 0];
        $transactions   = [];
        $growth         = 0;
        
        // Penanda Logic: Filter aktif HANYA JIKA kedua tanggal terisi
        $hasFilter = ($startDate != '' && $endDate != '');

        // 4. LOGIKA UTAMA: Hanya query ke database jika ada filter
        if ($hasFilter) {
            $financialStats = $laporanModel->getFinancialStats($id_mitra, $startDate, $endDate);
            $occupancyStats = $laporanModel->getOccupancyStats($id_mitra);
            $transactions   = $laporanModel->getTransactionHistory($id_mitra, $startDate, $endDate);

            // Hitung Growth hanya jika ada data
            $lastMonthRev = $laporanModel->getPreviousMonthRevenue($id_mitra);
            $currentRev   = $financialStats['pendapatan'];
            
            if ($lastMonthRev > 0) {
                $growth = (($currentRev - $lastMonthRev) / $lastMonthRev) * 100;
            } else if ($currentRev > 0) {
                $growth = 100;
            }
        }

        // 5. Kirim ke View
        $data['laporan'] = [
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'financial'  => $financialStats,
            'occupancy'  => $occupancyStats,
            'history'    => $transactions,
            'growth'     => round($growth, 1),
            'has_filter' => $hasFilter // Variable kunci untuk View
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
<?php
require_once '../app/core/Database.php'; 
require_once '../app/models/StatusKucingModel.php'; 
require_once '../app/models/ProfilMitra.php'; 

class StatusKucing extends Controller { 
    
    protected $db; 
    protected $statusModel; 
    protected $profilMitra;
    
    public function __construct() {
        $db_instance = new Database(); 
        $this->db = $db_instance->getConnection(); 

        $this->statusModel = new StatusKucingModel($this->db);
        $this->profilMitra = new ProfilMitra($this->db);
    }
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header("Location: " . BASEURL . "/auth/login"); exit; }

        $id_user = is_array($_SESSION['user']) ? ($_SESSION['user']['id_users'] ?? $_SESSION['user']['id']) : $_SESSION['user'];
        $mitra_data = $this->profilMitra->getMitraByUserId($id_user);
        if (!$mitra_data) { header("Location: " . BASEURL); exit; }

        $id_mitra = $mitra_data['id_mitra'];
        
        $flatCats = $this->statusModel->getActiveCatsByMitra($id_mitra);

        $groupedBookings = [];
        
        foreach ($flatCats as $cat) {
            $bookingId = $cat['id_booking'];
            
            if (!isset($groupedBookings[$bookingId])) {
                $groupedBookings[$bookingId] = [
                    'id_booking' => $bookingId,
                    'nama_pemilik' => $cat['nama_pemilik'] ?? 'Customer',
                    'tgl_checkin' => $cat['tanggal_checkin'] ?? '-',
                    'cats' => []
                ];
            }
            $groupedBookings[$bookingId]['cats'][] = $cat;
        }

        $data = [
            'groupedBookings' => $groupedBookings,
            'mitra_info' => $mitra_data,
            'title'      => 'Manajemen Tamu Bulu'
        ];
        
        $this->view('dashboard_mitra/status_kucing/index', $data, 'dashboard_layout'); 
    }

    public function get_logs() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $id_booking = $input['id_booking'] ?? null;
            $id_kucing  = $input['id_kucing'] ?? null;

            if($id_booking && $id_kucing) {
                $logs = $this->statusModel->getLogsByCat($id_booking, $id_kucing);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'data' => $logs]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID Missing']);
            }
            exit;
        }
    }

    // --- PERBAIKAN UTAMA DI SINI ---
    public function add_activity() {
        // Hapus error_reporting(0) saat development, aktifkan hanya di production
        // error_reporting(0); 
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $input = json_decode(file_get_contents('php://input'), true);

                $catIds = $input['id_kucing'] ?? null; 
                $bookingId = $input['id_booking'] ?? null;
                $jenis = $input['jenis'] ?? 'Info';
                $catatan = $input['catatan'] ?? '';

                if (empty($catIds) || empty($bookingId)) {
                    throw new Exception("Data tidak lengkap (ID Booking/Kucing kosong).");
                }

                // Normalisasi ke Array (untuk support Single & Bulk)
                if (!is_array($catIds)) {
                    $catIds = [$catIds];
                }

                $successCount = 0;
                
                foreach ($catIds as $id_kucing) {
                    $dataLog = [
                        'id_booking'      => $bookingId,
                        'id_kucing'       => $id_kucing,
                        'jenis_aktivitas' => $jenis,
                        'catatan'         => $catatan
                    ];
                    
                    // Karena Model sekarang return TRUE/FALSE, logika ini aman:
                    if ($this->statusModel->addLog($dataLog)) {
                        $successCount++;
                    }
                }

                if ($successCount > 0) {
                    echo json_encode(['status' => 'success', 'message' => "$successCount aktivitas tersimpan"]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => "Gagal menyimpan ke database"]);
                }

            } catch (Exception $e) {
                http_response_code(500); // Kirim kode error server
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
            exit;
        }
    }

    public function update_lifecycle() {
        header('Content-Type: application/json'); // Pastikan header JSON
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);

            $id_booking = $input['id_booking'];
            $id_kucing = $input['id_kucing'];
            $status_baru = $input['status_baru'];

            if ($this->statusModel->updateLifecycleStatus($id_booking, $id_kucing, $status_baru)) {
                echo json_encode(['status' => 'success', 'message' => 'Status update']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed update']);
            }
            exit;
        }
    }
}
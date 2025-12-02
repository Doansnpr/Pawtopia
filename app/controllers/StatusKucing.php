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

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/auth/login");
            exit;
        }

        // Logic user/mitra (disederhanakan sesuai kode Anda)
        $id_user = is_array($_SESSION['user']) ? ($_SESSION['user']['id_users'] ?? $_SESSION['user']['id']) : $_SESSION['user'];
        $mitra_data = $this->profilMitra->getMitraByUserId($id_user);

        if (!$mitra_data) { header("Location: " . BASEURL); exit; }

        $id_mitra = $mitra_data['id_mitra'];
        $_SESSION['id_mitra'] = $id_mitra;

        // Ambil Data Kucing dari Model Baru
        $activeCats = $this->statusModel->getActiveCatsByMitra($id_mitra);

        $data = [
            'activeCats' => $activeCats,
            'mitra_info' => $mitra_data,
            'title'      => 'Status & Aktivitas Kucing'
        ];
        
        $this->view('dashboard_mitra/status_kucing/index', $data, 'dashboard_layout'); 
    }

    public function get_logs() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ambil input JSON
            $input = json_decode(file_get_contents('php://input'), true);
            
            $id_booking = $input['id_booking'] ?? $_POST['id_booking'];
            $id_kucing  = $input['id_kucing'] ?? $_POST['id_kucing'];

            $logs = $this->statusModel->getLogsByCat($id_booking, $id_kucing);
            
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'data' => $logs]);
            exit;
        }
    }

    public function add_activity() {
        // [PENTING] Matikan error HTML agar tidak merusak JSON
        error_reporting(0);
        ini_set('display_errors', 0);
        
        // Beritahu browser bahwa responnya PASTI JSON
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Ambil data JSON
                $rawInput = file_get_contents('php://input');
                $input = json_decode($rawInput, true);

                // 1. Cek JSON Valid atau Tidak
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("JSON Invalid. Data tidak terbaca.");
                }

                // 2. Cek Data Kosong
                if (empty($input['id_booking']) || empty($input['id_kucing'])) {
                    throw new Exception("ID Booking atau ID Kucing hilang.");
                }

                // 3. Mapping Data (Perhatikan nama key-nya)
                // Pastikan 'jenis' dari JS masuk ke 'jenis_aktivitas' di DB
                $data = [
                    'id_booking'      => $input['id_booking'],
                    'id_kucing'       => $input['id_kucing'],
                    'jenis_aktivitas' => isset($input['jenis']) ? $input['jenis'] : 'Info',
                ];

                // 4. Panggil Model
                $result = $this->statusModel->addLog($data);

                if ($result === true) {
                    echo json_encode(['status' => 'success', 'message' => 'Berhasil disimpan']);
                } else {
                    // Jika gagal, $result berisi pesan error string dari Model
                    throw new Exception($result);
                }

            } catch (Exception $e) {
                // Tangkap error apapun dan kirim sebagai JSON
                echo json_encode([
                    'status' => 'error', 
                    'message' => $e->getMessage()
                ]);
            }
            exit;
        }
    }

    public function update_lifecycle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);

            $id_booking = $input['id_booking'];
            $id_kucing = $input['id_kucing'];
            $status_baru = $input['status_baru'];

            if ($this->statusModel->updateLifecycleStatus($id_booking, $id_kucing, $status_baru)) {
                echo json_encode(['status' => 'success', 'message' => 'Status utama diperbarui']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal update status']);
            }
            exit;
        }
    }
}
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

        // Inisialisasi Model yang Benar
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
        $_SESSION['id_mitra'] = $id_mitra; // Pastikan session id_mitra terset
        
        $flatCats = $this->statusModel->getActiveCatsByMitra($id_mitra);

        $groupedBookings = [];
        
        foreach ($flatCats as $cat) {
            $bookingId = $cat['id_booking'];
            
            if (!isset($groupedBookings[$bookingId])) {
                $groupedBookings[$bookingId] = [
                    'id_booking' => $bookingId,
                    'nama_pemilik' => $cat['nama_pemilik'] ?? 'Customer',
                    'tgl_checkin' => $cat['tgl_mulai'] ?? '-', // Sesuaikan nama kolom di model
                    'tgl_mulai' => $cat['tgl_mulai'],     // PENTING UNTUK HITUNG HARGA
                    'tgl_selesai' => $cat['tgl_selesai'], // PENTING UNTUK HITUNG HARGA
                    'total_harga' => $cat['total_harga'], // PENTING UNTUK HITUNG HARGA
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
        
        $this->view('dashboard_mitra/manajemen_status_penitipan/status', $data, 'dashboard_layout'); 
    }

    public function get_logs() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $id_booking = $input['id_booking'] ?? null;
            $id_kucing  = $input['id_kucing'] ?? null;

            if($id_booking && $id_kucing) {
                $logs = $this->statusModel->getLogsByCat($id_booking, $id_kucing);
                echo json_encode(['status' => 'success', 'data' => $logs]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID Missing']);
            }
            exit;
        }
    }

    public function add_activity() {
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
                http_response_code(500); 
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
            exit;
        }
    }

    public function update_lifecycle() {
        header('Content-Type: application/json'); 
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

    // --- FUNGSI CHECKOUT UTAMA (Dipanggil via AJAX Fetch) ---
    public function proses_checkout($id_booking) {
        // Karena dipanggil via AJAX, kita harus return JSON, bukan redirect header location
        // Kecuali jika Anda memanggilnya via link biasa <a>
        
        // Cek tipe request (AJAX atau bukan) untuk menentukan respons
        $isAjax = isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_mitra'])) {
            if ($isAjax) {
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
                exit;
            } else {
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }
        }

        $id_mitra = $_SESSION['id_mitra'];
        $denda = isset($_GET['denda']) ? (int)$_GET['denda'] : 0;
        
        // Kita tidak butuh total_baru dari klien karena Model akan menghitungnya (total_lama + denda)
        // Tapi biarkan parameter ini ada untuk kompatibilitas jika nanti butuh
        $total_baru = 0; 

        // Panggil Model StatusKucingModel (Pastikan method finalizeBooking sudah dipindah ke sini!)
        $berhasil = $this->statusModel->finalizeBooking($id_booking, $id_mitra, $total_baru, $denda);

        if ($isAjax) {
            header('Content-Type: application/json');
            if ($berhasil) {
                echo json_encode(['status' => 'success', 'message' => 'Booking Selesai & Denda Tersimpan']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal update database']);
            }
            exit;
        } else {
            // Fallback jika dipanggil via URL browser biasa
            if ($berhasil) {
                $msg = 'Booking Selesai!';
                if($denda > 0) {
                    $msg .= ' (Terdapat denda keterlambatan Rp ' . number_format($denda,0,',','.') . ')';
                }
                $_SESSION['flash'] = ['pesan' => 'Berhasil!', 'aksi' => $msg, 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal!', 'aksi' => 'Terjadi kesalahan saat update data.', 'tipe' => 'error'];
            }
            header('Location: ' . BASEURL . '/DashboardMitra?page=reservasi');
            exit;
        }
    }
}
<?php
require_once '../app/core/Database.php'; 
require_once '../app/models/BookingModel.php'; 
require_once '../app/models/ProfilMitra.php'; 

class BookingMitra extends Controller { 
    
    protected $db; 
    protected $bookingModel; 
    protected $profilMitra;
    
    public function __construct() {
        $db_instance = new Database(); 
        $this->db = $db_instance->getConnection(); 

        $this->bookingModel = new BookingModel($this->db);
        $this->profilMitra  = new ProfilMitra($this->db);
    }
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/auth/login");
            exit;
        }

        $id_user = is_array($_SESSION['user']) ? ($_SESSION['user']['id_users'] ?? $_SESSION['user']['id']) : $_SESSION['user'];
        $mitra_data = $this->profilMitra->getMitraByUserId($id_user);
        
        $reservations = [];
        $statusCounts = [];
        $paket_mitra = [];

        $searchKeyword = $_GET['search'] ?? null;
        $filterPayment = $_GET['status_bayar'] ?? null; // Menangkap filter 'belum_bayar'

        if ($mitra_data) {
            $id_mitra = $mitra_data['id_mitra'];
            $_SESSION['id_mitra'] = $id_mitra;

            // Model sudah diperbaiki sebelumnya untuk menangani logika "Belum Bayar"
            $reservations = $this->bookingModel->getAllBookings($id_mitra, $searchKeyword, $filterPayment); 
            $statusCounts = $this->bookingModel->getStatusCounts($id_mitra);
            $paket_mitra  = $this->bookingModel->getPackagesByMitra($id_mitra);
        } else {
            $statusCounts = $this->bookingModel->getStatusCounts('0');
        }

        $data = [
            'reservations' => $reservations,
            'statusCounts' => $statusCounts,
            'paket_mitra'  => $paket_mitra, 
            'mitra_info'   => $mitra_data,
            'search_val'   => $searchKeyword ?? '',
            'filter_val'   => $filterPayment ?? ''
        ];
        
        $this->view('dashboard_mitra/manajemen_booking/booking', $data, 'dashboard_layout'); 
    }

    public function tambahOffline() {
        if (session_status() === PHP_SESSION_NONE) session_start(); 
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['id_mitra'])) {
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }

            // Validasi Input
            $catsData = isset($_POST['kucing']) ? array_values($_POST['kucing']) : [];
            if (empty($catsData)) {
                $_SESSION['flash'] = ['pesan' => 'Data Kurang!', 'aksi' => 'Minimal 1 data kucing.', 'tipe' => 'error'];
                header('Location: ' . BASEURL . '/DashboardMitra?page=reservasi');
                exit;
            }

            $userData = [
                'nama_lengkap' => $_POST['nama_lengkap'],
                'no_telp'      => $_POST['no_telp'] ?? '',
                'role'         => 'Customer'
            ];

            $cleanHarga = preg_replace('/[^0-9]/', '', $_POST['total_harga']);

            $bookingData = [
                'tgl_mulai'     => $_POST['tgl_mulai'],
                'tgl_selesai'   => $_POST['tgl_selesai'],
                'paket'         => $_POST['paket'],
                'total_harga'   => (int) $cleanHarga,
                'id_mitra'      => $_SESSION['id_mitra'], 
                'jumlah_kucing' => count($catsData),
                'status'        => 'Aktif', 
                'tgl_booking'   => date('Y-m-d H:i:s')
            ];

            // createOfflineBooking di Model otomatis set pembayaran jadi 'Lunas'
            $sukses = $this->bookingModel->createOfflineBooking($userData, $catsData, $bookingData); 

            if ($sukses) {
                $_SESSION['flash'] = ['pesan' => 'Berhasil!', 'aksi' => 'Booking Offline Tersimpan (Lunas).', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal!', 'aksi' => 'Terjadi kesalahan sistem.', 'tipe' => 'error'];
            }
            header('Location: ' . BASEURL . '/DashboardMitra?page=reservasi');
            exit;
        }
    }

    public function lunas_booking($id_booking) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_mitra'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $berhasil = $this->bookingModel->createPelunasan($id_booking);

        if ($berhasil) {
            $_SESSION['flash'] = ['pesan' => 'Lunas!', 'aksi' => 'Pembayaran berhasil diverifikasi.', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'Gagal!', 'aksi' => 'Error saat update database.', 'tipe' => 'error'];
        }

        header('Location: ' . BASEURL . '/DashboardMitra?page=reservasi');
        exit;
    }

    public function getDetailJson($id_booking) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_mitra'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        $data = $this->bookingModel->getBookingDetail($id_booking);

        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function terima_booking($id_booking) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_mitra'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $id_mitra = $_SESSION['id_mitra'];
        $berhasil = $this->bookingModel->updateStatusBooking($id_booking, 'Menunggu DP', $id_mitra);

        if ($berhasil) {
            $_SESSION['flash'] = [
                'pesan' => 'Booking Diterima!',
                'aksi'  => 'Status berubah menjadi Menunggu DP.',
                'tipe'  => 'success'
            ];
        } else {
            $_SESSION['flash'] = [
                'pesan' => 'Gagal Menerima!',
                'aksi'  => 'Data tidak ditemukan atau error sistem.',
                'tipe'  => 'error'
            ];
        }

        header('Location: ' . BASEURL . '/DashboardMitra?page=reservasi');
        exit;
    }

    public function tolak_booking($id_booking){
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_mitra'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $id_mitra = $_SESSION['id_mitra'];
        $berhasil = $this->bookingModel->updateStatusBooking($id_booking, 'Dibatalkan', $id_mitra);

        if ($berhasil) {
            $_SESSION['flash'] = [
                'pesan' => 'Booking Ditolak!',
                'aksi'  => 'Pesanan telah dibatalkan.',
                'tipe'  => 'success' 
            ];
        } else {
            $_SESSION['flash'] = [
                'pesan' => 'Gagal Menolak!',
                'aksi'  => 'Data tidak ditemukan atau error sistem.',
                'tipe'  => 'error'
            ];
        }

        header('Location: ' . BASEURL . '/DashboardMitra?page=reservasi');
        exit;
    }

    // --- BAGIAN INI YANG DIUBAH PENTING ---
    public function getDpJson($id_booking) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_mitra'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        // PERBAIKAN QUERY: 
        // Mengambil foto dari tabel 'pembayaran' (p.foto_dp), BUKAN 'booking'
        // Karena struktur database sudah kita ubah agar pembayaran terpisah
        
        $query = "SELECT b.id_booking, b.total_harga, u.nama_lengkap, p.bukti_transfer 
                  FROM booking b 
                  JOIN users u ON b.id_users = u.id_users 
                  LEFT JOIN pembayaran p ON b.id_booking = p.id_booking
                  WHERE b.id_booking = ?
                  ORDER BY p.tgl_bayar DESC LIMIT 1"; 
        
        $stmt = $this->db->prepare($query); 
        $stmt->bind_param("s", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($result) {
            // Ambil foto dari kolom foto_dp (dari tabel pembayaran)
            $fotoName = isset($result['bukti_transfer']) ? trim($result['bukti_transfer']) : null;
            $fotoUrl = null;

            if (!empty($fotoName)) {
                $baseUrlClean = rtrim(BASEURL, '/'); 
                $fotoUrl = $baseUrlClean . '/images/bukti_pembayaran/' . $fotoName;
            }

            ob_clean(); 

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success', 
                'data' => [
                    'id_booking' => $result['id_booking'],
                    'nama' => $result['nama_lengkap'],
                    'total' => (int) $result['total_harga'],
                    'foto_url' => $fotoUrl
                ]
            ]);
            exit; 
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            exit; 
        }
    }

    public function verifikasi_dp($id_booking, $action){
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_mitra']) || empty($_SESSION['id_mitra'])) {
            $_SESSION['flash'] = [
                'pesan' => 'Sesi Habis!',
                'aksi'  => 'Silakan login ulang kembali.',
                'tipe'  => 'error'
            ];
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $id_mitra = $_SESSION['id_mitra'];

        $status_baru = '';
        $pesan_flash = [];

        if ($action === 'terima') {
            $status_baru = 'Aktif'; 
            $pesan_flash = [
                'pesan' => 'Verifikasi DP Berhasil!',
                'aksi'  => 'Booking ID #' . $id_booking . ' kini Menunggu Pelunasan.',
                'tipe'  => 'success'
            ];
        } elseif ($action === 'tolak') {
            $status_baru = 'DP Ditolak'; // Ubah status ke DP Ditolak (lebih spesifik dari Dibatalkan)
            $pesan_flash = [
                'pesan' => 'DP Ditolak!',
                'aksi'  => 'Customer perlu upload ulang bukti.',
                'tipe'  => 'warning'
            ];
        } else {
            $_SESSION['flash'] = [
                'pesan' => 'Aksi Tidak Valid!',
                'aksi'  => 'Parameter URL salah.',
                'tipe'  => 'warning'
            ];
            header('Location: ' . BASEURL . '/DashboardMitra?page=reservasi');
            exit;
        }

        $berhasil = $this->bookingModel->updateStatusBooking($id_booking, $status_baru, $id_mitra);

        if ($berhasil) {
            $_SESSION['flash'] = $pesan_flash;
        } else {
            $_SESSION['flash'] = [
                'pesan' => 'Gagal Menyimpan!',
                'aksi'  => 'Terjadi kesalahan saat update database.',
                'tipe'  => 'error'
            ];
        }

        header('Location: ' . BASEURL . '/DashboardMitra?page=reservasi');
        exit;
    }
}
<?php
require_once '../app/core/Database.php';
require_once '../app/models/BookingCustModel.php';

class BookingCustomer extends Controller {

    protected $db;
    protected $bookingModel;

    public function __construct() {
        // Inisialisasi koneksi database
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();

        // Inisialisasi model
        $this->bookingModel = new BookingModel($this->db);
    }

    // ...
/**
 * Halaman Booking (daftar & tambah booking)
 */
public function Booking() { // lowercase sesuai konvensi routing
    if (session_status() === PHP_SESSION_NONE) session_start();
    $id_user = $_SESSION['user']['id_users'] ?? null;

    if (!$id_user) {
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }

    $bookings = $this->bookingModel->getBookingsByUser($id_user);
    $totalSpending = $this->bookingModel->getTotalSpending($id_user);
    $totalBookings = $this->bookingModel->getTotalBookings($id_user);
    
    // ⬇⬇ PENTING: Ambil daftar Mitra Aktif ⬇⬇
    $mitras = $this->bookingModel->getActiveMitras(); 

    $data = [
        'title' => 'Booking',
        'content' => 'dashboard_customer/booking/booking',
        'bookings' => $bookings,
        'totalSpending' => 'Rp ' . number_format($totalSpending, 0, ',', '.'),
        'totalBookings' => $totalBookings,
        'id_user' => $id_user,
        'mitras' => $mitras // ⬅ Kirim data mitra ke View
    ];

    $this->view('layouts/dashboard_layoutCus', $data);
}

// Hapus fungsi getActiveMitras() yang lama di Controller, karena sudah pindah ke Model!
// ...

    // === API Methods (AJAX) ===


   public function saveBooking() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $id_user = $_SESSION['user']['id_users'] ?? null;

    if (!$id_user) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
        exit;
    }

    header('Content-Type: application/json');

    // ✅ Ambil input JSON dengan aman
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON format.']);
        exit;
    }

    $id_mitra = trim($input['mitra'] ?? '');
    $tgl_mulai = trim($input['tgl_mulai'] ?? '');
    $tgl_selesai = trim($input['tgl_selesai'] ?? '');
    $id_paket = trim($input['paket'] ?? '');
    $total_harga_sent = isset($input['total_harga']) ? (float) filter_var($input['total_harga'], FILTER_SANITIZE_NUMBER_FLOAT) : 0;

    // ✅ Validasi wajib
    if (!$id_mitra || !$tgl_mulai || !$tgl_selesai || !$id_paket) {
        echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi.']);
        exit;
    }

    // ✅ Validasi tanggal
    $start = strtotime($tgl_mulai);
    $end = strtotime($tgl_selesai);
    if ($end < $start) {
        echo json_encode(['success' => false, 'message' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.']);
        exit;
    }

    // ✅ Validasi: apakah paket benar-benar milik mitra ini?
    $validPackages = $this->bookingModel->getPackagesByMitra($id_mitra);
    $validPaketIds = array_column($validPackages, 'id_paket');
    if (!in_array($id_paket, $validPaketIds)) {
        echo json_encode(['success' => false, 'message' => 'Paket tidak valid untuk mitra yang dipilih.']);
        exit;
    }

    // ✅ Ambil data paket dari database (antisipasi manipulasi harga)
    $paketData = $this->bookingModel->getPackageById($id_paket);
    if (!$paketData) {
        echo json_encode(['success' => false, 'message' => 'Paket tidak ditemukan.']);
        exit;
    }

    $nama_paket = $paketData['nama_paket'];
    $harga_paket = (float)$paketData['harga'];
    $days = max(1, ceil(($end - $start) / (60 * 60 * 24)));
    $total_harga = $days * $harga_paket; // ✅ Pakai harga dari DB, bukan dari frontend

    // ✅ Ambil data kucing
    $cats = [];
    $kucing_input = $input['kucing'] ?? [];
    if (is_array($kucing_input)) {
        foreach ($kucing_input as $i => $catData) {
            $nama = trim($catData['nama'] ?? '');
            $ras = trim($catData['ras'] ?? '');
            $umur = isset($catData['umur']) ? (int)$catData['umur'] : null;
            $jk = trim($catData['jenis_kelamin'] ?? '');

            if ($nama === '' || $ras === '' || $umur === null || $jk === '') {
                echo json_encode([
                    'success' => false,
                    'message' => "Data kucing ke-" . ($i+1) . " tidak lengkap."
                ]);
                exit;
            }

            $cats[] = [
                'nama' => $nama,
                'ras' => $ras,
                'umur' => $umur,
                'jenis_kelamin' => $jk,
                'keterangan' => trim($catData['keterangan'] ?? '')
            ];
        }
    }

    if (empty($cats)) {
        echo json_encode(['success' => false, 'message' => 'Harap tambahkan setidaknya satu kucing.']);
        exit;
    }

    // ✅ Simpan booking
    $result = $this->bookingModel->createBooking($id_user, $id_mitra, $tgl_mulai, $tgl_selesai, $id_paket, $total_harga, $cats);

    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Booking berhasil dibuat! Silakan tunggu konfirmasi dari mitra.',
            'id_booking' => $result['id_booking'],
            'total_harga' => 'Rp ' . number_format($total_harga, 0, ',', '.')
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ Gagal membuat booking: ' . ($result['error'] ?? 'Terjadi kesalahan sistem.')
        ]);
    }
}

    public function getActiveMitras() {
    $sql = "SELECT id_mitra, nama_petshop 
            FROM mitra 
            WHERE status = 'active' OR status = 1";

    $result = $this->db->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

    public function getPackages() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;

        if (!$id_user) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Silakan login.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'POST only.']);
            exit;
        }

        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id_mitra = $input['id_mitra'] ?? '';

        if (!$id_mitra) {
            echo json_encode(['success' => false, 'message' => 'ID Mitra tidak valid.']);
            exit;
        }

        $packages = $this->bookingModel->getPackagesByMitra($id_mitra);
        echo json_encode(['success' => true, 'packages' => $packages]);
    }

    public function getPackagePrice() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;

        if (!$id_user) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Silakan login.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'POST only.']);
            exit;
        }

        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id_paket = $input['id_paket'] ?? '';

        if (!$id_paket) {
            echo json_encode(['success' => false, 'message' => 'ID Paket tidak valid.']);
            exit;
        }

        $harga = $this->bookingModel->getPriceByPackageId($id_paket);
        echo json_encode(['success' => true, 'harga' => (float)$harga]);
    }
}
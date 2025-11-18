<?php
require_once '../app/core/Database.php'; 
require_once '../app/models/BookingModel.php'; 

class BookingMitra extends Controller { 
    
    protected $db; 
    protected $bookingModel; 
    protected $userModel; // Tambahkan ini
    
    public function __construct() {
        
        $db_instance = new Database(); 
        $this->db = $db_instance->getConnection(); 

        $this->bookingModel = new BookingModel($this->db);
    }
    
    public function index() {
        $reservations = $this->bookingModel->getAllBookings();
        $statusCounts = $this->bookingModel->getStatusCounts();

        $data = [
            'reservations' => $reservations,
            'statusCounts' => $statusCounts,
        ];
        
        $this->view('dashboard_mitra/manajemen_booking/booking', $data, 'dashboard_mitra_layout'); 
    }

    
    /**
     * METHOD BARU UNTUK MENANGANI MODAL
     */
    public function tambahOffline() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Data Pelanggan (untuk tabel users)
            $userData = [
                'nama_lengkap' => $_POST['nama_lengkap'] ?? 'Pelanggan Offline',
                'no_telp' => $_POST['no_telp'] ?? null,
                'email' => 'offline_' . time() . '@system.local', // Email unik dummy
                'password' => password_hash('offline123', PASSWORD_DEFAULT), // Password dummy
                'role' => 'Pelanggan' // Sesuaikan dengan role Anda
            ];

            // 2. Data Kucing (array)
            $catsData = $_POST['kucing'] ?? [];

            // 3. Data Booking (untuk tabel booking)
            $bookingData = [
                'tgl_mulai' => $_POST['tgl_mulai'],
                'tgl_selesai' => $_POST['tgl_selesai'],
                'paket' => $_POST['paket'],
                'total_harga' => $_POST['total_harga'],
                'id_mitra' => $_SESSION['mitra_id'] ?? null, // Ambil id_mitra dari SESI
                'jumlah_kucing' => count($catsData),
                'status' => 'Menunggu Konfirmasi', // Status awal
                'tgl_booking' => date('Y-m-d H:i:s')
            ];

            // Cek data penting
            if (empty($catsData) || empty($bookingData['tgl_mulai']) || empty($bookingData['id_mitra'])) {
                // Flasher::setFlash('Gagal', 'Data tidak lengkap atau ID Mitra tidak ditemukan di Sesi.', 'danger');
                header('Location: ' . BASEURL . '/BookingMitra');
                exit;
            }

            $result = $this->bookingModel->createOfflineBooking($userData, $catsData, $bookingData);

            if ($result) {
                // Flasher::setFlash('Berhasil', 'Booking offline berhasil ditambahkan.', 'success');
            } else {
                // Flasher::setFlash('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'danger');
            }

            header('Location: ' . BASEURL . '/BookingMitra');
            exit;

        } else {
            header('Location: ' . BASEURL . '/BookingMitra');
            exit;
        }
    }
}
<?php

class DashboardCustomer extends Controller {

    private $db_host = "localhost";
    private $db_user = "root";
    private $db_pass = "";
    private $db_name = "pawtopia";

    private function getKoneksi() {
        $koneksi = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);
        return $koneksi;
    }

    // --- HALAMAN UTAMA DASHBOARD ---
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;

        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $koneksi = $this->getKoneksi();
        $id_user_safe = $koneksi->real_escape_string($id_user);

        // Ambil nama user
        $query_user = $koneksi->query("SELECT nama_lengkap FROM users WHERE id_users = '$id_user_safe'");
        $user = ($query_user && $query_user->num_rows > 0) ? $query_user->fetch_assoc() : null;

        // QUERY BOOKING TERAKHIR (DIPERBAIKI)
        // Sekarang ambil status dari booking_lifecycle (bl.status)
        $query_booking = $koneksi->query("
            SELECT b.id_booking, 
                   m.nama_petshop AS tempat_penitipan, 
                   b.tgl_booking AS tanggal_penitipan, 
                   COALESCE(bl.status, b.status) AS status, -- Ambil status lifecycle dulu
                   db.id_kucing
            FROM booking b
            JOIN mitra m ON b.id_mitra = m.id_mitra
            LEFT JOIN detail_booking db ON b.id_booking = db.id_booking
            LEFT JOIN booking_lifecycle bl ON b.id_booking = bl.id_booking AND db.id_kucing = bl.id_kucing
            WHERE b.id_users = '$id_user_safe'
              AND TRIM(LOWER(b.status)) NOT IN ('selesai', 'dibatalkan')
            ORDER BY b.tgl_booking DESC
            LIMIT 1
        ");
        $booking = ($query_booking && $query_booking->num_rows > 0) ? $query_booking->fetch_assoc() : null;
        
        // Ambil nama kucing
        $nama_kucing = '-';
        if ($booking && isset($booking['id_kucing'])) {
            $id_kucing = $booking['id_kucing'];
            $q_kucing = $koneksi->query("SELECT nama_kucing FROM kucing WHERE id_kucing = '$id_kucing'");
            if($q_kucing && $q_kucing->num_rows > 0) {
                $nama_kucing = $q_kucing->fetch_assoc()['nama_kucing'];
            }
        }

        // Statistik Pengeluaran & Jumlah Kucing (Tetap sama)
        $query_pengeluaran = $koneksi->query("SELECT SUM(total_harga) AS total_pengeluaran FROM booking WHERE id_users = '$id_user_safe'");
        $pengeluaran = ($query_pengeluaran) ? $query_pengeluaran->fetch_assoc() : null;
        $total_pengeluaran = $pengeluaran['total_pengeluaran'] ?? 0;

        $query_jumlah_kucing = $koneksi->query("
            SELECT COUNT(*) AS jumlah FROM booking 
            WHERE id_users = '$id_user_safe' 
            AND TRIM(LOWER(status)) NOT IN ('selesai', 'dibatalkan')
        ");
        $jumlah_kucing = ($query_jumlah_kucing) ? $query_jumlah_kucing->fetch_assoc()['jumlah'] : 0;

        $data = [
            'title' => 'Dashboard',
            'content' => 'dashboard_customer/index',
            'nama_pengguna' => $user['nama_lengkap'] ?? 'Pengguna',
            'nama_kucing' => $nama_kucing,
            'tgl_booking' => $booking['tanggal_penitipan'] ?? '-',
            'status' => $booking['status'] ?? '-', // Ini sekarang status dari lifecycle
            'pengeluaran' => 'Rp ' . number_format($total_pengeluaran, 0, ',', '.'),
            'id_user' => $id_user,
            'jumlah_kucing' => (int)$jumlah_kucing
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    // --- HALAMAN ULASAN (TIDAK PERLU DIUBAH, SUDAH OKE) ---
    public function ulasan() {
       // ... (Biarkan kode ulasan anda seperti sebelumnya) ...
       // Saya potong biar tidak kepanjangan, karena tidak ada perubahan logika status disini
       if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;
        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $koneksi = $this->getKoneksi();
        $id_user_safe = $koneksi->real_escape_string($id_user);

        // Ambil Ulasan
        $result = $koneksi->query("SELECT * FROM ulasan WHERE id_users = '$id_user_safe' ORDER BY id_ulasan DESC");
        $ulasan = ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

        // Handle POST Form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = (int)($_POST['rating'] ?? 0);
            $komentar = trim($koneksi->real_escape_string($_POST['komentar'] ?? ''));
            $mode = $_POST['mode'] ?? 'baru';
            $id_ulasan_post = $koneksi->real_escape_string($_POST['id_ulasan'] ?? '');

            // Ambil id_booking terakhir untuk relasi
            $resBooking = $koneksi->query("SELECT id_booking FROM booking WHERE id_users = '$id_user_safe' ORDER BY id_booking DESC LIMIT 1");
            $booking = ($resBooking && $resBooking->num_rows > 0) ? $resBooking->fetch_assoc() : null;
            $id_booking_val = $booking ? "'".$booking['id_booking']."'" : "NULL";

            if ($mode === 'perbarui' && $id_ulasan_post !== '') {
                $koneksi->query("UPDATE ulasan SET rating = '$rating', komentar = '$komentar' WHERE id_ulasan = '$id_ulasan_post'");
                $_SESSION['flash'] = ['pesan' => ($koneksi->error) ? 'Error: '.$koneksi->error : 'Ulasan berhasil diperbarui'];
            } else {
                // Generate ID Manual
                $resMax = $koneksi->query("SELECT MAX(id_ulasan) AS last_id FROM ulasan");
                $lastId = ($resMax && $resMax->num_rows > 0) ? $resMax->fetch_assoc()['last_id'] : null;
                $num = ($lastId) ? (int)substr($lastId, 6) + 1 : 1;
                $newId = "Ulasan" . str_pad($num, 3, "0", STR_PAD_LEFT);

                $koneksi->query("INSERT INTO ulasan (id_ulasan, id_users, id_booking, rating, komentar, tgl_ulasan) 
                                 VALUES ('$newId', '$id_user_safe', $id_booking_val, '$rating', '$komentar', NOW())");
                $_SESSION['flash'] = ['pesan' => ($koneksi->error) ? 'Error: '.$koneksi->error : 'Ulasan berhasil dikirim!'];
            }
            header('Location: ' . BASEURL . '/DashboardCustomer/ulasan');
            exit;
        }

        $data = [
            'title' => 'Beri Ulasan',
            'content' => 'dashboard_customer/ulasan',
            'id_user' => $id_user,
            'ulasan' => $ulasan,
            'flash' => $_SESSION['flash'] ?? null
        ];
        unset($_SESSION['flash']);
        $this->view('layouts/dashboard_layoutCus', $data);
    }

    // --- FITUR STATUS PENITIPAN ---
    // --- FITUR STATUS PENITIPAN ---
    public function status_penitipan($id_booking = null) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;
        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $model = $this->model('StatusModel');

        // ... (Logika cek ID Booking tetap sama) ...
        if ($id_booking == null) {
            $lastBooking = $model->getLatestActiveBooking($id_user);
            if ($lastBooking) {
                $id_booking = $lastBooking['id_booking'];
            } else {
                // ... (Handling kosong tetap sama) ...
                $data = [
                    'title' => 'Status Penitipan',
                    'content' => 'dashboard_customer/status_penitipan/status',
                    'booking' => null,
                    'log_activity' => [],
                    'pesan_kosong' => 'Anda tidak memiliki penitipan aktif saat ini.'
                ];
                $this->view('layouts/dashboard_layoutCus', $data);
                return;
            }
        }

        $bookingDetail = $model->getDetailBooking($id_booking, $id_user);
        
        if (!$bookingDetail) {
            header('Location: ' . BASEURL . '/DashboardCustomer');
            exit;
        }

        // 1. SIAPKAN FOTO PROFIL KUCING (UTAMA)
        $baseUrlClean = rtrim(BASEURL, '/'); 
        $fotoName = $bookingDetail['foto_kucing'];
        if (!empty($fotoName)) {
            $bookingDetail['foto_kucing_url'] = $baseUrlClean . '/public/images/kucing/' . $fotoName;
        } else {
            $bookingDetail['foto_kucing_url'] = $baseUrlClean . '/public/images/default-cat.jpg';
        }

        // 2. AMBIL LOG AKTIVITAS
        $activityLogs = $model->getActivityLogs($id_booking);

        // 3. [PERBAIKAN UTAMA] SIAPKAN URL FOTO UNTUK SETIAP LOG AKTIVITAS
        // Kita loop array-nya untuk menambah 'url_foto_fixed' agar View tinggal pakai
        foreach ($activityLogs as $key => $log) {
            if (!empty($log['url_foto'])) {
                // Asumsi foto aktivitas disimpan di folder: public/images/logs/
                $activityLogs[$key]['url_foto_fixed'] = $baseUrlClean . '/public/images/logs/' . $log['url_foto'];
            } else {
                $activityLogs[$key]['url_foto_fixed'] = null;
            }
        }

        $data = [
            'title' => 'Status Penitipan',
            'content' => 'dashboard_customer/status_penitipan/status', 
            'booking' => $bookingDetail,
            'log_activity' => $activityLogs // Data log yang sudah ada URL fotonya
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }
}
?>
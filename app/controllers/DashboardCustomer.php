<?php

require_once __DIR__ . '/../models/DashboardModel.php';

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

        // 1. Panggil Model
        // Pastikan Anda sudah meload model ini (require atau lewat method model(
        $dashModel = new DashboardModel();

        // 2. Siapkan Variabel Filter
        $tahun = $_GET['tahun'] ?? date("Y");
        
        // 3. Ambil Data dari Model
        $nama_pengguna = $dashModel->getNamaUser($id_user);
        $kucingList    = $dashModel->getAllKucingUser($id_user);
        $mitraList     = $dashModel->getAllMitra();
        $chartData     = $dashModel->getDataChartBooking($id_user, $tahun);
        $activeBookings= $dashModel->getActiveBookings($id_user);
        $jumlahKucing  = $dashModel->getCountActiveKucing($id_user);
        $ratingMitra   = $dashModel->getRataRatingMitra();

        // 4. Bungkus data untuk dikirim ke View
        $data = [
            'title'           => 'Dashboard',
            'content'         => 'dashboard_customer/index', // View file
            'nama_pengguna'   => $nama_pengguna,
            'kucing_list'     => $kucingList,    // Array [id => nama]
            'mitra_list'      => $mitraList,     // Array [id => nama]
            'bookings'        => $activeBookings, // Array data tabel
            'jumlah_kucing'   => $jumlahKucing,
            'rating_mitra'    => $ratingMitra,   // Array [id_mitra => rating]
            'chart_data'      => $chartData,     // Array [0..11] booking count
            'tahun_pilih'     => $tahun,
            'bulan_nama'      => ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    // --- HALAMAN ULASAN (TIDAK PERLU DIUBAH, SUDAH OKE) ---
    public function ulasan() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;
        
        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $koneksi = $this->getKoneksi();
        $id_user_safe = $koneksi->real_escape_string($id_user);
        $dashModel = new DashboardModel();

        // --- Handle POST Form ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mode = $_POST['mode'] ?? 'baru';
            $id_ulasan_post = $koneksi->real_escape_string($_POST['id_ulasan'] ?? '');

            if ($mode === 'hapus' && $id_ulasan_post !== '') {
                $koneksi->query("DELETE FROM ulasan WHERE id_ulasan = '$id_ulasan_post' AND id_users = '$id_user_safe'");
                $_SESSION['flash'] = [
                    'pesan' => ($koneksi->error) ? 'Error Hapus: '.$koneksi->error : 'Ulasan berhasil dihapus.',
                    'tipe' => ($koneksi->error) ? 'error' : 'sukses'
                ];
            } elseif ($mode === 'perbarui' && $id_ulasan_post !== '') {
                $rating = (int)($_POST['rating'] ?? 0);
                $komentar = trim($koneksi->real_escape_string($_POST['komentar'] ?? ''));
                $koneksi->query("
                    UPDATE ulasan 
                    SET rating = '$rating', komentar = '$komentar', tgl_ulasan = NOW() 
                    WHERE id_ulasan = '$id_ulasan_post' AND id_users = '$id_user_safe'
                ");
                $_SESSION['flash'] = [
                    'pesan' => ($koneksi->error) ? 'Error Update: '.$koneksi->error : 'Ulasan berhasil diperbarui.',
                    'tipe' => ($koneksi->error) ? 'error' : 'sukses'
                ];
            } elseif ($mode === 'baru') {
                $rating = (int)($_POST['rating'] ?? 0);
                $komentar = trim($koneksi->real_escape_string($_POST['komentar'] ?? ''));

                // Ambil booking terakhir yang status selesai dan belum diulas
                $resBooking = $koneksi->query("
                    SELECT b.id_booking 
                    FROM booking b 
                    LEFT JOIN ulasan u ON b.id_booking = u.id_booking
                    WHERE b.id_users = '$id_user_safe' AND b.status = 'selesai' AND u.id_booking IS NULL
                    ORDER BY b.tgl_booking DESC
                    LIMIT 1
                ");
                $id_booking_post = ($resBooking && $resBooking->num_rows > 0) ? $resBooking->fetch_assoc()['id_booking'] : null;

                if (!$id_booking_post) {
                    $_SESSION['flash'] = ['pesan' => 'Error: Tidak ada booking selesai untuk diulas.', 'tipe' => 'error'];
                } else {
                    // Generate ID Manual
                    $resMax = $koneksi->query("SELECT MAX(id_ulasan) AS last_id FROM ulasan");
                    $lastId = ($resMax && $resMax->num_rows > 0) ? $resMax->fetch_assoc()['last_id'] : null;
                    $num = ($lastId) ? (int)substr($lastId, 6) + 1 : 1;
                    $newId = "Ulasan" . str_pad($num, 3, "0", STR_PAD_LEFT);

                    $koneksi->query("
                        INSERT INTO ulasan (id_ulasan, id_users, id_booking, rating, komentar, tgl_ulasan) 
                        VALUES ('$newId', '$id_user_safe', '$id_booking_post', '$rating', '$komentar', NOW())
                    ");

                    $_SESSION['flash'] = [
                        'pesan' => ($koneksi->error) ? 'SQL Error INSERT: '.$koneksi->error : 'Ulasan berhasil dikirim!',
                        'tipe' => ($koneksi->error) ? 'error' : 'sukses'
                    ];
                }
            }

            // Redirect supaya flash muncul dan halaman reload
            header('Location: ' . BASEURL . '/DashboardCustomer/ulasan');
            exit;
        }

        // --- Ambil Data untuk View ---
        $bookingSiapUlas = $dashModel->getBookingSiapUlas($id_user);

        $result = $koneksi->query("SELECT * FROM ulasan WHERE id_users = '$id_user_safe' ORDER BY tgl_ulasan DESC");
        $ulasan = ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $data = [
            'title' => 'Beri Ulasan',
            'content' => 'dashboard_customer/ulasan',
            'id_user' => $id_user,
            'ulasan' => $ulasan,
            'booking_siap_ulas' => $bookingSiapUlas,
            'punyaBookingSelesai' => !empty($bookingSiapUlas),
            'flash' => $_SESSION['flash'] ?? null
        ];
        unset($_SESSION['flash']);
        $this->view('layouts/dashboard_layoutCus', $data);
    }

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
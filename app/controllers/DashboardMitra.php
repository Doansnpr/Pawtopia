<?php
require_once '../app/core/Database.php';
require_once '../app/models/BookingModel.php';
require_once '../app/models/StatusModel.php';
require_once '../app/models/ProfilMitra.php';
require_once '../app/models/LaporanMitraModel.php';
require_once '../app/models/UlasanModel.php';

class DashboardMitra extends Controller
{
    protected $db;
    protected $ProfilMitra;

    public function __construct()
    {
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();
        $this->ProfilMitra = new ProfilMitra($this->db);
    }

    public function index(){
        // 1. Cek Session (Sudah Benar)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/auth/login");
            exit;
        }


        // 2. Ambil ID User (Sudah Benar)
        $id_user = null;
        if (is_array($_SESSION['user'])) {
            $id_user = $_SESSION['user']['id_users'] ?? $_SESSION['user']['id'];
        } else {
            $id_user = $_SESSION['user'];
        }

        $mitra_data = $this->ProfilMitra->getMitraByUserId($id_user);

        // 4. Cek apakah Data Mitra Ditemukan
        if (!$mitra_data) {
            // Jika user login tapi belum terdaftar sebagai mitra, lempar ke home/registrasi mitra

            header("Location: " . BASEURL . "/home");
            exit;
        }

        // 5. Simpan ID Mitra ke Session (Penting untuk query laporan nanti)
        $id_mitra = $mitra_data['id_mitra'];
        $_SESSION['id_mitra'] = $id_mitra;

        require_once '../app/models/MitraModel.php';
        $notifModel = new MitraModel($this->db);

        $notifications = $notifModel->getRecentNotifications($id_mitra);
        $notif_count  = $notifModel->countUnreadNotifications($id_mitra);

        // 6. Tentukan Halaman Aktif
        $current_page = $_GET['page'] ?? 'dashboard';

        // 7. SIAPKAN DATA UNTUK VIEW (BAGIAN PENTING)
        $data = [
            'title'         => 'Dashboard Mitra',
            'notifications' => $notifications, // <--- MASUKKAN DATA NOTIFIKASI DI SINI
            'notif_count'  => $notif_count,
            'mitra_profile' => $mitra_data, // Isinya ada nama_petshop, nama_pemilik, foto, dll
            'content'       => 'dashboard_mitra/dashboard_content'
        ];

        if ($current_page === 'reservasi') {
            require_once '../app/models/BookingModel.php';
            $bookingModel = new BookingModel($this->db);

            // 1. Ambil Input
            $searchKeyword = $_GET['search'] ?? '';
            $filterPayment = $_GET['status_bayar'] ?? '';
            $tabStatus     = $_GET['tab_status'] ?? 'Semua'; // <--- TAMBAHAN INI
            
            // --- LOGIKA PAGINATION ---
            $limit = 5; // Jumlah data per halaman
            $page = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $limit;

            // 2. Ambil Data & Hitung Total
            $total_data = $bookingModel->countAllBookings($id_mitra, $searchKeyword, $filterPayment, $tabStatus);
            $total_pages = ceil($total_data / $limit);

            $data['reservations'] = $bookingModel->getAllBookings($id_mitra, $searchKeyword, $filterPayment, $limit, $offset, $tabStatus);
            // 3. Kirim Data Pagination ke View
            $data['active_tab'] = $tabStatus;
            $data['pagination'] = [
                'current_page' => $page,
                'total_pages'  => $total_pages,
                'total_data'   => $total_data
            ];

            // Data pendukung lain
            $paket_mitra = $bookingModel->getPackagesByMitra($id_mitra);
            $data['statusCounts'] = $bookingModel->getStatusCounts($id_mitra);
            $data['paket_mitra']  = $paket_mitra;
            $data['search_val']   = $searchKeyword;
            $data['filter_val']   = $filterPayment;

            $data['title']   = 'Manajemen Booking';
            $data['content'] = 'dashboard_mitra/manajemen_booking/booking';

        
        }   else if ($current_page === 'status') {

            require_once '../app/models/StatusKucingModel.php';
            $statusModel = new StatusKucingModel($this->db);

            $flatCats = $statusModel->getActiveCatsByMitra($id_mitra);

            $groupedBookings = [];

            foreach ($flatCats as $row) {
                $bookingId = $row['id_booking'];

                // Buat wadah booking jika belum ada
                if (!isset($groupedBookings[$bookingId])) {
                    $groupedBookings[$bookingId] = [
                        'id_booking'   => $row['id_booking'],
                        'nama_pemilik' => $row['nama_pemilik'],
                        
                        // --- BAGIAN PENTING YANG WAJIB DITAMBAHKAN ---
                        // Agar fitur hitung denda & harga di View jalan
                        'tgl_mulai'    => $row['tgl_mulai'],
                        'tgl_selesai'  => $row['tgl_selesai'], // <--- JANGAN LUPA INI
                        'total_harga'  => $row['total_harga'], // <--- JANGAN LUPA INI
                        // ---------------------------------------------

                        'cats'         => [] 
                    ];
                }

                // Masukkan data kucing ke dalam booking yang sesuai
                $groupedBookings[$bookingId]['cats'][] = $row;
            }

            // Masukkan ke $data
            $data['groupedBookings'] = $groupedBookings; 
            
            // Config halaman
            $data['title']   = 'Manajemen Status Kucing';
            $data['content'] = 'dashboard_mitra/manajemen_status_penitipan/status'; 
        }
        else if ($current_page === 'laporan') {
            
            // 1. Load Model yang Dibutuhkan
            require_once '../app/models/LaporanMitraModel.php';  
            
            // [PERBAIKAN] Load ProfilMitra, bukan MitraModel
            require_once '../app/models/ProfilMitra.php'; 
            
            $laporanModel = new LaporanMitraModel($this->db);
            $profilModel  = new ProfilMitra($this->db); // [PERBAIKAN] Instansiasi ProfilMitra

            // 2. Ambil & Validasi Filter Tanggal
            $startDate = !empty($_GET['start_date']) ? $_GET['start_date'] : '';
            $endDate   = !empty($_GET['end_date'])   ? $_GET['end_date']   : '';

            // ----------------------------------------------------------------
            // BAGIAN A: LOGIKA EXPORT EXCEL (CSV)
            // ----------------------------------------------------------------
            if (isset($_GET['action']) && $_GET['action'] === 'excel') {
                
                if (ob_get_length()) ob_end_clean();

                $dataExport = $laporanModel->getExportData($id_mitra, $startDate, $endDate);
                $stats      = $laporanModel->getFinancialStats($id_mitra, $startDate, $endDate);
                $grandTotal = $stats['pendapatan'];

                $filename = "Laporan_Transaksi_" . date('Ymd_His') . ".csv";
                
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Pragma: no-cache');
                header('Expires: 0');

                $output = fopen('php://output', 'w');

                fputcsv($output, ['ID Booking', 'Tgl Mulai', 'Tgl Selesai', 'Paket', 'Pelanggan', 'Jml Kucing', 'Total Harga']);

                if (!empty($dataExport)) {
                    foreach ($dataExport as $row) {
                        fputcsv($output, [
                            '#' . $row['id_booking'], 
                            $row['tgl_mulai'],
                            $row['tgl_selesai'],
                            $row['paket'],
                            $row['nama_lengkap'],
                            $row['jumlah_kucing'],
                            $row['total_harga']
                        ]);
                    }
                    fputcsv($output, []);
                    fputcsv($output, ['', '', '', '', '', 'TOTAL PENDAPATAN:', $grandTotal]);
                } else {
                    fputcsv($output, ['Tidak ada data pada periode ini']);
                }

                fclose($output);
                exit; 
            }

            if (isset($_GET['action']) && $_GET['action'] === 'print') {
                
                // 1. [PERBAIKAN] Ambil Profil Mitra Pakai ProfilMitra
                // Pastikan Anda sudah menambahkan fungsi getMitraById di ProfilMitra.php
                $mitraProfile = $profilModel->getMitraById($id_mitra); 

                // 2. Ambil SEMUA Data (Tanpa Limit)
                $allTransactions = $laporanModel->getTransactionHistory($id_mitra, $startDate, $endDate, 10000, 0);
                
                // 3. Ambil Statistik
                $finStats = $laporanModel->getFinancialStats($id_mitra, $startDate, $endDate);
                $occStats = $laporanModel->getOccupancyStats($id_mitra);

                // 4. Siapkan Data View
                $data = [
                    'mitra_profile' => $mitraProfile, // Data ini dipakai untuk Kop Surat
                    'laporan' => [
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                        'financial'  => $finStats,
                        'occupancy'  => $occStats,
                        'history'    => $allTransactions
                    ]
                ];

                // 5. Load View Cetak
                require_once __DIR__ . '/../views/dashboard_mitra/laporan/cetak_laporan_mitra.php';
                exit; 
            }

            // ----------------------------------------------------------------
            // BAGIAN C: TAMPILAN DASHBOARD BIASA (WEB VIEW)
            // ----------------------------------------------------------------
            $limit  = 5; 
            $page   = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $limit;

            $total_data  = $laporanModel->countTransactionHistory($id_mitra, $startDate, $endDate);
            $total_pages = ceil($total_data / $limit);

            $transactions   = $laporanModel->getTransactionHistory($id_mitra, $startDate, $endDate, $limit, $offset);
            $financialStats = $laporanModel->getFinancialStats($id_mitra, $startDate, $endDate);
            $occupancyStats = $laporanModel->getOccupancyStats($id_mitra);

            $lastMonthRev = $laporanModel->getPreviousMonthRevenue($id_mitra);
            $currentRev   = $financialStats['pendapatan'];
            
            $growth = 0;
            if ($lastMonthRev > 0) {
                $growth = (($currentRev - $lastMonthRev) / $lastMonthRev) * 100;
            } else if ($currentRev > 0) {
                $growth = 100;
            }

            $growthClass = 'neutral';
            if ($growth > 0) $growthClass = 'positive';
            if ($growth < 0) $growthClass = 'negative';

            $data['laporan'] = [
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'financial'  => $financialStats,
                'occupancy'  => $occupancyStats,
                'history'    => $transactions,
                'growth'     => round($growth, 1),
                'growth_cls' => $growthClass
            ];

            $data['pagination'] = [
                'current_page' => $page,
                'total_pages'  => $total_pages,
                'total_data'   => $total_data
            ];

            $data['title']   = 'Laporan';
            $data['content'] = 'dashboard_mitra/laporan/laporan';

        }else if ($current_page === 'ulasan') {
            $ulasanModel = new UlasanModel($this->db);

            // Ambil data dari model
            $data['list_ulasan'] = $ulasanModel->getUlasanByMitra($id_mitra);
            $data['statistik']   = $ulasanModel->getAverageRating($id_mitra);
        
            $data['title']   = 'Ulasan Pelanggan';
        
            // Ini yang bikin konten berubah jadi ulasan
            $data['content'] = 'dashboard_mitra/manajemen_ulasan/ulasan';
        }  else if ($current_page === 'profil') {
            $user_id = $_SESSION['user']['id_users'];
            $mitra_data = $this->ProfilMitra->getMitraByUserId($user_id);

            if (!$mitra_data) {
                echo "Data mitra tidak ditemukan."; exit;
            }

            $id_mitra = $mitra_data['id_mitra'];
            $paket_data = $this->ProfilMitra->getPaketByMitra($id_mitra);

            $data['mitra']   = $mitra_data;
            $data['paket']   = $paket_data;
            $data['title']   = 'Profil Saya';
            $data['content'] = 'dashboard_mitra/profile/profile';
        }

        $this->view('layouts/dashboard_layout', $data);
    }


    public function get_booking_details()
    {
        // Cek apakah ada request POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_booking = $_POST['id_booking'] ?? null;

            if ($id_booking) {
                // Panggil Model
                require_once '../app/models/LaporanMitraModel.php';
                $laporanModel = new LaporanMitraModel($this->db);

                // Ambil data
                $details = $laporanModel->getBookingDetails($id_booking);

                // Kirim respon JSON
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'data' => $details
                ]);
                exit; // Penting agar tidak merender HTML lain
            } else {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'ID Booking tidak ditemukan']);
                exit;
            }
        }
    }

     public function updateProfile()  {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: ' . BASEURL . '/DashboardMitra?page=profil');
            exit;
        }

        $user_id = $_SESSION['user']['id_users'];
        $mitra_data = $this->ProfilMitra->getMitraByUserId($user_id);

        if (!$mitra_data) {
            die("Data mitra error!");
        }

        $id_mitra = $mitra_data['id_mitra'];

        // Di dalam updateProfile()
        $uploadDir = __DIR__ . "/../../public/uploads/mitra/";

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                $_SESSION['error'] = "Gagal membuat folder upload.";
                header('Location: ' . BASEURL . '/DashboardMitra?page=profil');
                exit;
            }
        }

        // --- DATA FORM ---
        $lat = !empty($_POST['lokasi_lat']) ? $_POST['lokasi_lat'] : $mitra_data['lokasi_lat'];
        $lng = !empty($_POST['lokasi_lng']) ? $_POST['lokasi_lng'] : $mitra_data['lokasi_lng'];

        $data = [
            "nama_petshop"  => $_POST['nama_petshop'],
            "alamat"        => $_POST['alamat'],
            "no_hp"         => $_POST['no_hp'],
            "deskripsi"     => $_POST['deskripsi'],
            "kapasitas"     => (int)$_POST['kapasitas'],
            "lokasi_lat"    => $lat,
            "lokasi_lng"    => $lng,
            "foto_profil"   => $mitra_data['foto_profil']
        ];

        // --- UPLOAD FOTO ---
        if (!empty($_FILES['foto_petshop']['name']) && $_FILES['foto_petshop']['error'] === 0) {

            $fileTmp  = $_FILES['foto_petshop']['tmp_name'];
            $fileName = "foto_petshop_" . time() . "_" . basename($_FILES['foto_petshop']['name']);

            // PATH FOLDER FIX
            $uploadDir = __DIR__ . "/../../public/uploads/mitra/";

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {

                if (move_uploaded_file($fileTmp, $uploadDir . $fileName)) {

                    // Hapus file lama kalau ada
                    if (!empty($mitra_data['foto_profil'])) {
                        $oldPath = $uploadDir . $mitra_data['foto_profil'];
                        if (file_exists($oldPath)) unlink($oldPath);
                    }

                    $data['foto_profil'] = $fileName;
                }
            }
        }

        // --- UPDATE DATABASE ---
        $updateMitra = $this->ProfilMitra->updateMitra($id_mitra, $data);

        // --- UPDATE PAKET ---
        if ($updateMitra) {
            $this->ProfilMitra->deletePaketByMitra($id_mitra);

            if (!empty($_POST['nama_paket']) && !empty($_POST['harga_paket'])) {
                $nama_pakets = $_POST['nama_paket'];
                $harga_pakets = $_POST['harga_paket'];

                for ($i = 0; $i < count($nama_pakets); $i++) {
                    if (!empty($nama_pakets[$i]) && $harga_pakets[$i] !== '') {
                        $this->ProfilMitra->insertPaket($id_mitra, $nama_pakets[$i], $harga_pakets[$i]);
                    }
                }
            }
            $_SESSION['success'] = "Profil berhasil diperbarui!";
        } 
        else {
            $_SESSION['error'] = "Gagal memperbarui profil!";
        }

        echo '<!DOCTYPE html><html><body>';
        echo '<script>window.location.href = "' . BASEURL . '/DashboardMitra?page=profil";</script>';
        echo '</body></html>';
        exit;
    }

    public function uploadBuktiBayar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Ambil ID Mitra
            $id_mitra = $_SESSION['id_mitra'] ?? null;
            if (!$id_mitra) {
                // Fallback cari ID Mitra kalau session hilang
                $user_id = $_SESSION['user']['id_users'] ?? $_SESSION['user']['id'];
                $mitraData = $this->ProfilMitra->getMitraByUserId($user_id);
                $id_mitra = $mitraData['id_mitra'];
            }

            // Proses Upload
            if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] === UPLOAD_ERR_OK) {
                
                $file = $_FILES['bukti_bayar'];
                $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($ext, $allowed)) {
                    // Folder Tujuan
                    $uploadDir = __DIR__ . '/../../public/images/BuktiBayar/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    // Nama file
                    $filename = 'Bukti_' . $id_mitra . '_' . time() . '.' . $ext;
                    $targetPath = $uploadDir . $filename;

                    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                        
                        // FIX: Menggunakan prepare statement MySQLi yang benar
                        
                        $tgl = date('Y-m-d H:i:s');
                        $status_baru = 'Pembayaran Diproses';
                        
                        $query = "UPDATE mitra SET bukti_pembayaran = ?, tgl_pembayaran = ?, status = ? WHERE id_mitra = ?";
                        
                        $stmt = $this->db->prepare($query);
                        // "ssss" artinya 4 parameter bertipe String
                        $stmt->bind_param("ssss", $filename, $tgl, $status_baru, $id_mitra);
                        $stmt->execute();
                        $stmt->close();

                        // SUKSES -> Trigger Pop-up Terakhir & Logout
                        $_SESSION['flash'] = [
                            'pesan' => 'Bukti Terkirim!',
                            'aksi'  => 'Tunggu email selanjutnya dari pawtopia457@gmail.com apakah akun anda terverifikasi atau ditolak verifikasi',
                            'tipe'  => 'success_logout'
                        ];
                        
                        header('Location: ' . BASEURL . '/DashboardMitra');
                        exit;
                    }
                }
            }

            // JIKA GAGAL
            $_SESSION['flash'] = [
                'pesan' => 'Gagal Upload',
                'aksi'  => 'File error atau format salah.',
                'tipe'  => 'error'
            ];
            header('Location: ' . BASEURL . '/DashboardMitra');
            exit;
        }
    }

    public function balasUlasan(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/DashboardMitra?page=ulasan');
            exit;
        }

        $id_ulasan = $_POST['id_ulasan'] ?? null;
        $balasan   = trim($_POST['balasan'] ?? '');

        if (!$id_ulasan || $balasan === '') {
            $_SESSION['flash'] = [
                'pesan' => 'Balasan tidak boleh kosong.',
                'tipe'  => 'error'
            ];
            header('Location: ' . BASEURL . '/DashboardMitra?page=ulasan');
            exit;
        }

        require_once '../app/models/UlasanModel.php';
        $ulasanModel = new UlasanModel($this->db);

        if ($ulasanModel->simpanBalasan($id_ulasan, $balasan)) {
            $_SESSION['flash'] = [
                'pesan' => 'Balasan berhasil disimpan.',
                'tipe'  => 'success'
            ];
        } else {
            $_SESSION['flash'] = [
                'pesan' => 'Gagal menyimpan balasan.',
                'tipe'  => 'error'
            ];
        }

        header('Location: ' . BASEURL . '/DashboardMitra?page=ulasan');
        exit;
    }

    public function hapusBalasan($id_ulasan){
        if (session_status() === PHP_SESSION_NONE) session_start();

        require_once '../app/models/UlasanModel.php';
        $ulasanModel = new UlasanModel($this->db);

        if ($ulasanModel->hapusBalasan($id_ulasan)) {
            $_SESSION['flash'] = [
                'pesan' => 'Balasan berhasil dihapus.',
                'tipe'  => 'success'
            ];
        } else {
            $_SESSION['flash'] = [
                'pesan' => 'Gagal menghapus balasan.',
                'tipe'  => 'error'
            ];
        }

        header('Location: ' . BASEURL . '/DashboardMitra?page=ulasan');
        exit;
    }

    
}


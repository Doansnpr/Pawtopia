<?php
require_once '../app/core/Database.php';
require_once '../app/models/BookingModel.php';
require_once '../app/models/StatusModel.php';
require_once '../app/models/ProfilMitra.php';
require_once '../app/models/LaporanMitraModel.php';

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

        // Ambil Data Notifikasi
        $data['notifications'] = $notifModel->getRecentNotifications($id_mitra);
        $data['notif_count']   = $notifModel->countUnreadNotifications($id_mitra);

        // 6. Tentukan Halaman Aktif
        $current_page = $_GET['page'] ?? 'dashboard';

        // 7. SIAPKAN DATA UNTUK VIEW (BAGIAN PENTING)
        $data = [
            'title'         => 'Dashboard Mitra',
            
            'mitra_profile' => $mitra_data, // Isinya ada nama_petshop, nama_pemilik, foto, dll
            
            'content'       => 'dashboard_mitra/dashboard_content'
        ];


        if ($current_page === 'reservasi') {
            require_once '../app/models/BookingModel.php';
            $bookingModel = new BookingModel($this->db);

            // 1. Ambil Input
            $searchKeyword = $_GET['search'] ?? '';
            $filterPayment = $_GET['status_bayar'] ?? '';
            
            // --- LOGIKA PAGINATION ---
            $limit = 4; // Jumlah data per halaman
            $page = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $limit;

            // 2. Ambil Data & Hitung Total
            // Panggil fungsi count dulu untuk tahu total halaman
            $total_data = $bookingModel->countAllBookings($id_mitra, $searchKeyword, $filterPayment);
            $total_pages = ceil($total_data / $limit);

            // Panggil fungsi get data dengan limit & offset
            $data['reservations'] = $bookingModel->getAllBookings($id_mitra, $searchKeyword, $filterPayment, $limit, $offset);
            
            // 3. Kirim Data Pagination ke View
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

        
        } else if ($current_page === 'status') {

            require_once '../app/models/StatusKucingModel.php';
            $statusModel = new StatusKucingModel($this->db);

            $activeCats = $statusModel->getActiveCatsByMitra($id_mitra);

            $data['activeCats'] = $activeCats;
            $data['title']      = 'Manajemen Status Kucing';
            $data['content']    = 'dashboard_mitra/manajemen_status_penitipan/status';

        } else if ($current_page === 'laporan') {
            require_once '../app/models/LaporanMitraModel.php';  
            $laporanModel = new LaporanMitraModel($this->db);

            // 1. Ambil Filter Tanggal
            $startDate = !empty($_GET['start_date']) ? $_GET['start_date'] : '';
            $endDate   = !empty($_GET['end_date'])   ? $_GET['end_date']   : '';

            // --- LOGIKA EXPORT KE CSV DENGAN TOTAL PENDAPATAN ---
            if (isset($_GET['action']) && $_GET['action'] === 'excel') {
                
                // 1. Bersihkan Buffer
                if (ob_get_length()) ob_end_clean();

                $start = $_GET['start_date'] ?? date('Y-m-01');
                $end   = $_GET['end_date'] ?? date('Y-m-d');
                
                // Ambil Data Baris (Transaksi)
                $dataExport = $laporanModel->getExportData($id_mitra, $start, $end);

                // 2. [BARU] Ambil Data Total Pendapatan
                // Kita panggil fungsi stats agar angkanya SAMA PERSIS dengan di footer website
                $stats = $laporanModel->getFinancialStats($id_mitra, $start, $end);
                $grandTotal = $stats['pendapatan'];

                // 3. Set Header CSV
                $filename = "Laporan_Transaksi_" . date('Ymd_His') . ".csv";
                
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Pragma: no-cache');
                header('Expires: 0');

                $output = fopen('php://output', 'w');

                // Header Kolom
                fputcsv($output, ['ID Booking', 'Tgl Mulai', 'Tgl Selesai', 'Paket', 'Pelanggan', 'Jml Kucing', 'Total Harga']);

                // Isi Data
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

                    // 4. [BARU] Tulis Baris Total di Paling Bawah
                    fputcsv($output, []); // Kasih 1 baris kosong biar rapi
                    fputcsv($output, [
                        '', '', '', '', '', // Kosongkan 5 kolom pertama
                        'TOTAL PENDAPATAN:', // Label di kolom ke-6
                        $grandTotal          // Nilai Total di kolom ke-7
                    ]);

                } else {
                    fputcsv($output, ['Tidak ada data pada periode ini']);
                }

                fclose($output);
                exit; 
            }

            // 2. Default Values
            $financialStats = ['pendapatan' => 0, 'booking_selesai' => 0, 'booking_batal' => 0];
            $occupancyStats = ['rate' => 0, 'terisi' => 0, 'kapasitas' => 0];
            $transactions   = [];
            $growth         = 0;
            $growthClass    = 'neutral';
            
            // Default Pagination Data
            $total_pages = 0;
            $total_data = 0;
            $page = 1;

            // 3. Cek Filter Aktif
            $hasFilter = ($startDate != '' && $endDate != '');

            // 4. QUERY DATABASE (Jika Filter Aktif)
            if ($hasFilter) {
                // --- LOGIKA PAGINATION ---
                $limit = 5; // Batas data per halaman
                $page = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1;
                if ($page < 1) $page = 1;
                $offset = ($page - 1) * $limit;

                // A. Hitung Total Data dulu
                $total_data = $laporanModel->countTransactionHistory($id_mitra, $startDate, $endDate);
                $total_pages = ceil($total_data / $limit);

                // B. Ambil Data Transaksi (Pakai Limit & Offset)
                $transactions = $laporanModel->getTransactionHistory($id_mitra, $startDate, $endDate, $limit, $offset);

                // C. Data Statistik Lainnya
                $financialStats = $laporanModel->getFinancialStats($id_mitra, $startDate, $endDate);
                $occupancyStats = $laporanModel->getOccupancyStats($id_mitra);

                // D. Hitung Growth
                $lastMonthRev = $laporanModel->getPreviousMonthRevenue($id_mitra);
                $currentRev   = $financialStats['pendapatan'];

                if ($lastMonthRev > 0) {
                    $growth = (($currentRev - $lastMonthRev) / $lastMonthRev) * 100;
                } else if ($currentRev > 0) {
                    $growth = 100; 
                }

                if ($growth > 0) $growthClass = 'positive';
                if ($growth < 0) $growthClass = 'negative';
            }

            // 5. Masukkan ke Array Data
            $data['laporan'] = [
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'financial'  => $financialStats,
                'occupancy'  => $occupancyStats,
                'history'    => $transactions,
                'growth'     => round($growth, 1),
                'growth_cls' => $growthClass,
                'has_filter' => $hasFilter
            ];

            // 6. Kirim Data Pagination ke View
            $data['pagination'] = [
                'current_page' => $page,
                'total_pages'  => $total_pages,
                'total_data'   => $total_data
            ];

            $data['title']   = 'Laporan';
            $data['content'] = 'dashboard_mitra/laporan/laporan';


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
            $uploadDir = __DIR__. "/../../public/uploads/mitra/";

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
                        // Pastikan di database, kolom 'status' sudah support value 'pembayaran_diproses'
                        // Jika kolom status kamu ENUM, tolong ubah jadi VARCHAR atau tambahkan 'pembayaran_diproses' di ENUM-nya.
                        
                        $tgl = date('Y-m-d H:i:s');
                        $status_baru = 'pembayaran_diproses';
                        
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
}

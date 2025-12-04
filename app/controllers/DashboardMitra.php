<?php
require_once '../app/core/Database.php';
require_once '../app/models/BookingModel.php';
require_once '../app/models/StatusModel.php';
require_once '../app/models/ProfilMitra.php';

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASEURL . "/auth/login");
            exit;
        }

        $id_user = is_array($_SESSION['user'])
            ? ($_SESSION['user']['id_users'] ?? $_SESSION['user']['id'])
            : $_SESSION['user'];

        $mitra_data = $this->ProfilMitra->getMitraByUserId($id_user);

        if (!$mitra_data) {
            header("Location: " . BASEURL . "/home");
            exit;
        }

        $id_mitra = $mitra_data['id_mitra'];
        $_SESSION['id_mitra'] = $id_mitra;

        $current_page = $_GET['page'] ?? 'dashboard';

        $data = [
            'mitra_info' => $mitra_data,
            'content'    => 'dashboard_mitra/dashboard_content'
        ];

        if ($current_page === 'reservasi') {

            require_once '../app/models/BookingModel.php';
            $bookingModel = new BookingModel($this->db);

            $paket_mitra = $bookingModel->getPackagesByMitra($id_mitra);

            $data['reservations'] = $bookingModel->getAllBookings($id_mitra);
            $data['statusCounts'] = $bookingModel->getStatusCounts($id_mitra);
            $data['paket_mitra']  = $paket_mitra;

            $data['title']   = 'Manajemen Reservasi';
            $data['content'] = 'dashboard_mitra/manajemen_booking/booking';

        } 
        else if ($current_page === 'status') {

            require_once '../app/models/StatusKucingModel.php';
            $statusModel = new StatusKucingModel($this->db);

            $data['activeCats'] = $statusModel->getActiveCatsByMitra($id_mitra);
            $data['title']      = 'Manajemen Status Kucing';
            $data['content']    = 'dashboard_mitra/manajemen_status_penitipan/status';

        } 
        else if ($current_page === 'profil') {
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

    // ==========================================
    // METHOD UNTUK UPLOAD BUKTI PEMBAYARAN (FIXED)
    // ==========================================
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
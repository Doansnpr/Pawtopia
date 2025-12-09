<?php
require_once '../app/core/Database.php';
require_once '../app/models/BookingCustModel.php';

class BookingCustomer extends Controller {
    
    private $db;
    private $bookingModel;

    public function __construct() {
        $db = new Database();
        $this->db = $db->getConnection();
        $this->bookingModel = new BookingCustModel($this->db);
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Customer') {
            header('Location: ' . BASEURL . '/auth/login'); exit;
        }

        header('Location: ' . BASEURL . '/DashboardCustomer/Booking');
        exit;
    }

    // API JSON (Tidak diubah)
    public function get_paket_mitra() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $packages = $this->bookingModel->getPackagesByMitra($input['id_mitra'] ?? '');
            header('Content-Type: application/json');
            echo json_encode($packages);
            exit;
        }
    }
    

    public function get_detail_booking() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $detail = $this->bookingModel->getBookingById($input['id_booking']);
            header('Content-Type: application/json');
            echo json_encode($detail);
            exit;
        }
    }

    // --- PROSES SIMPAN (DIPERBAIKI) ---
    public function simpan() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mode = $_POST['mode']; 
            
            // 1. SIAPKAN DATA KUCING (TAPI JANGAN INSERT KE DB DULU)
            $catsProcessed = [];
            $rawCats = $_POST['cats'] ?? [];
            
            foreach ($rawCats as $index => $cat) {
                $umur_fix = $cat['umur_angka'] . ' ' . $cat['umur_satuan'];
                
                $nama_foto = null;

                // Proses Upload Foto (Tetap dilakukan sekarang agar file tersimpan)
                if (!empty($_FILES['cats']['name'][$index]['foto'])) {
                    $fileName = $_FILES['cats']['name'][$index]['foto'];
                    $fileTmp  = $_FILES['cats']['tmp_name'][$index]['foto'];
                    $fileErr  = $_FILES['cats']['error'][$index]['foto'];

                    if ($fileErr === 0) {
                        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                        // Tambahkan prefix TEMP agar tahu ini belum diverifikasi
                        $new_name = "TEMP_CAT_" . time() . "" . $index . "" . rand(100,999) . "." . $ext;
                        $target = "../public/images/foto_kucing/" . $new_name;
                        
                        if (!file_exists("../public/images/foto_kucing/")) {
                            mkdir("../public/images/foto_kucing/", 0777, true);
                        }

                        if (move_uploaded_file($fileTmp, $target)) {
                            $nama_foto = $new_name;
                        }
                    }
                } else {
                    // Jika mode edit dan tidak upload foto baru, ambil foto lama (hidden input)
                    $nama_foto = $_POST['existing_foto'][$index] ?? null;
                }

                $catsProcessed[] = [
                    'id_kucing'      => $cat['id_kucing'] ?? null, // Null jika baru
                    'nama'           => $cat['nama'],
                    'ras'            => $cat['ras'],
                    'gender'         => $cat['gender'],
                    'umur'           => $umur_fix,
                    'keterangan'     => $cat['keterangan'],
                    'foto'           => $nama_foto 
                ];
            }

            // --- PERUBAHAN UTAMA DISINI ---
            // Ubah array data kucing menjadi JSON String
            // Ini agar Model bisa menyimpannya ke kolom 'booking_details' atau sejenisnya
            // bukannya langsung insert ke tabel kucing.
            $catsJson = json_encode($catsProcessed);


            // 2. DATA HEADER
            $harga_clean = preg_replace('/[^0-9]/', '', $_POST['total_harga']);
            
            $bookingData = [
                'id_mitra'    => $_POST['id_mitra'] ?? $_POST['id_mitra_edit'], 
                'tgl_mulai'   => $_POST['tgl_mulai'],
                'tgl_selesai' => $_POST['tgl_selesai'],
                'paket'       => $_POST['paket_nama'],
                'total_harga' => (int) $harga_clean,
                // Kirim data JSON kucing lewat bookingData atau argumen terpisah
                // Disini saya asumsikan dikirim via argumen ke-3
            ];

            // 3. EKSEKUSI MODEL
            $berhasil = false;
            $pesan = '';

            if ($mode == 'tambah') {
                // Pass JSON ($catsJson), bukan Array.
                // Model harus diupdate untuk menyimpan string ini ke kolom temporary di tabel booking
                $berhasil = $this->bookingModel->createOnlineBooking($id_user, $bookingData, $catsJson);
                $pesan = $berhasil ? 'Booking Berhasil Dibuat! Menunggu Verifikasi.' : 'Gagal Membuat Booking';
            } else if ($mode == 'edit') {
                $id_booking = $_POST['id_booking'];
                // Saat edit pun, simpan sebagai JSON lagi sampai diverifikasi ulang
                $berhasil = $this->bookingModel->updateBooking($id_booking, $bookingData, $catsJson);
                $pesan = $berhasil ? 'Perubahan Disimpan! Menunggu Verifikasi.' : 'Gagal Update Booking';
            }

            $_SESSION['flash'] = [
                'tipe'  => $berhasil ? 'success' : 'error',
                'pesan' => $pesan,
                'aksi'  => $berhasil ? 'Data tersimpan.' : 'Silakan coba lagi.'
            ];

            header('Location: ' . BASEURL . '/DashboardCustomer/Booking');
            exit;
        }
    }

    // --- PROSES BATALKAN ---
    public function batalkan($id_booking) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($this->bookingModel->cancelBooking($id_booking)) {
            $_SESSION['flash'] = [
                'tipe'  => 'success',
                'pesan' => 'Booking Dibatalkan',
                'aksi'  => 'Status berubah menjadi Dibatalkan.'
            ];
        } else {
            $_SESSION['flash'] = [
                'tipe'  => 'error',
                'pesan' => 'Gagal Membatalkan',
                'aksi'  => 'Terjadi kesalahan sistem.'
            ];
        }

        header('Location: ' . BASEURL . '/DashboardCustomer/Booking');
        exit;
    }

    public function upload_bukti() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_booking = $_POST['id_booking'];
            $berhasil = false;

            $dataPayment = [
                'jenis_pembayaran' => $_POST['jenis_pembayaran'],
                'jumlah_bayar'     => (int) $_POST['nominal_bayar'] 
            ];
            
            if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] == 0) {
                $target_dir = "../public/images/bukti_pembayaran/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                
                $ext = pathinfo($_FILES["bukti_bayar"]["name"], PATHINFO_EXTENSION);
                $filename = "BUKTI_" . time() . "_" . rand(100,999) . "." . $ext;
                
                if (move_uploaded_file($_FILES["bukti_bayar"]["tmp_name"], $target_dir . $filename)) {
                    $berhasil = $this->bookingModel->processPayment($id_booking, $dataPayment, $filename);
                }
            }

            if ($berhasil) {
                $_SESSION['flash'] = [
                    'tipe'  => 'success',
                    'pesan' => 'Pembayaran Terkirim!',
                    'aksi'  => 'Mohon tunggu verifikasi admin.'
                ];
            } else {
                $_SESSION['flash'] = [
                    'tipe'  => 'error',
                    'pesan' => 'Gagal Upload',
                    'aksi'  => 'Silakan coba lagi.'
                ];
            }

            header('Location: ' . BASEURL . '/DashboardCustomer/Booking');
            exit;
        }
    }
}
<?php
require_once '../app/core/Database.php';
require_once '../app/models/MitraModel.php';

class ManajemenMitra extends Controller
{
    protected $db;
    protected $mitraModel;

    public function __construct()
    {
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();
        $this->mitraModel = new MitraModel($this->db);
    }

    // Aksi untuk mengubah status Awal (Verifikasi Pendaftaran)
    public function verifikasi()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_mitra = $_POST['id_mitra'];
            $aksi = $_POST['aksi']; // 'terima' atau 'tolak'

            // Logika Status 1: Menunggu Verifikasi
            if ($aksi === 'terima') {
                $status_baru = 'Menunggu Pembayaran';
                $pesan = 'Mitra diterima, menunggu pembayaran.';
            } else {
                $status_baru = 'Ditolak';
                $pesan = 'Pendaftaran mitra ditolak.';
            }

            if ($this->mitraModel->updateStatus($id_mitra, $status_baru)) {
                $_SESSION['flash'] = ['pesan' => 'Sukses', 'aksi' => $pesan, 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'Terjadi kesalahan sistem', 'tipe' => 'error'];
            }
            
            header('Location: ' . BASEURL . '/DashboardAdmin?page=manajemen_mitra');
            exit;
        }
    }

    // Aksi untuk Validasi Pembayaran (Dari Modal Pop-up)
    public function validasiPembayaran()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_mitra = $_POST['id_mitra'];
            $aksi = $_POST['aksi']; // 'terima' atau 'tolak'

            // Logika Status 2: Pembayaran Diproses
            if ($aksi === 'terima') {
                $status_baru = 'Terverifikasi'; // Mitra Aktif
                $pesan = 'Pembayaran valid. Mitra kini Aktif!';
            } else {
                $status_baru = 'Pembayaran Ditolak'; // Mitra harus upload ulang
                $pesan = 'Pembayaran ditolak. Mitra perlu upload ulang.';
            }

            if ($this->mitraModel->updateStatus($id_mitra, $status_baru)) {
                $_SESSION['flash'] = ['pesan' => 'Sukses', 'aksi' => $pesan, 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'Terjadi kesalahan sistem', 'tipe' => 'error'];
            }

            header('Location: ' . BASEURL . '/DashboardAdmin?page=manajemen_mitra');
            exit;
        }
    }
}
<?php
require_once '../app/core/Database.php';
require_once '../app/models/PenggunaModel.php';

class ManajemenPengguna extends Controller
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();
        $this->userModel = new PenggunaModel($this->db);
    }

    // Hapus function index() di sini karena View sudah dihandle DashboardAdmin



    public function ubah()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->updateUser($_POST)) {
                $_SESSION['flash'] = ['pesan' => 'Berhasil!', 'aksi' => 'Data pengguna diperbarui', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal!', 'aksi' => 'Tidak ada perubahan data', 'tipe' => 'error'];
            }
            header('Location: ' . BASEURL . '/DashboardAdmin?page=manajemen_pengguna');
            exit;
        }
    }

    public function hapus($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if ($this->userModel->hapusUser($id)) {
            $_SESSION['flash'] = ['pesan' => 'Terhapus!', 'aksi' => 'Pengguna telah dihapus', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'Gagal!', 'aksi' => 'Data tidak bisa dihapus', 'tipe' => 'error'];
        }
        header('Location: ' . BASEURL . '/DashboardAdmin?page=manajemen_pengguna');
        exit;
    }
}
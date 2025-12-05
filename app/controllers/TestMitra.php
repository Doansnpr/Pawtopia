<?php
require_once __DIR__ . '/../core/Controller.php'; 
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/AuthModel.php';
require_once __DIR__ . '/Auth.php'; 

class TestMitra extends Controller {

    public function index() {
        $db = new Database();
        $authModel = new AuthModel($db->getConnection());

        // Ambil semua data mitra
        $data['mitra'] = $authModel->getAllMitra(); 
        $this->view('testmitra/index', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_mitra'], $_POST['status'])) {
            
            $id_mitra = $_POST['id_mitra'];
            $status   = $_POST['status'];

            $db = new Database();
            $conn = $db->getConnection(); // Kita butuh koneksi raw untuk delete manual
            $authModel = new AuthModel($conn);
            $authController = new Auth(); // Untuk kirim email

            // 1. Ambil data mitra dulu (untuk email)
            $mitra = $authModel->getMitraById($id_mitra);

            if ($mitra) {
                // 2. KIRIM EMAIL DULU (Sesuai request)
                if (!empty($mitra['email'])) {
                    $authController->sendStatusEmail(
                        $mitra['email'],
                        $mitra['nama_petshop'],
                        $status
                    );
                }

                // 3. LOGIKA HAPUS ATAU UPDATE
                if ($status === 'Ditolak Verifikasi' || $status === 'Pembayaran Ditolak') {
                    // === SKENARIO DITOLAK: HAPUS DATA ===
                    
                    // Hapus dari tabel mitra
                    $stmt1 = $conn->prepare("DELETE FROM mitra WHERE id_mitra = ?");
                    $stmt1->bind_param("s", $id_mitra);
                    $stmt1->execute();
                    
                    // Hapus dari tabel users (Karena akun mitra terkait user)
                    // Asumsi: id_users ada di data $mitra yang kita ambil tadi
                    if(isset($mitra['id_users'])) {
                        $stmt2 = $conn->prepare("DELETE FROM users WHERE id_users = ?");
                        $stmt2->bind_param("s", $mitra['id_users']);
                        $stmt2->execute();
                    }
                    
                    // Notifikasi ke Admin
                    echo "<script>alert('Mitra DITOLAK. Email terkirim & Data BERHASIL DIHAPUS permanen.'); window.location.href='" . BASEURL . "/TestMitra';</script>";
                    exit;

                } else {
                    // === SKENARIO DITERIMA / PROSES: UPDATE STATUS SAJA ===
                    $authModel->updateMitraStatus($id_mitra, $status);
                    
                    echo "<script>alert('Status berhasil diupdate jadi " . $status . "'); window.location.href='" . BASEURL . "/TestMitra';</script>";
                    exit;
                }
            }
        }
        
        // Default redirect jika gagal
        header("Location: " . BASEURL . "/TestMitra");
        exit;
    }
}
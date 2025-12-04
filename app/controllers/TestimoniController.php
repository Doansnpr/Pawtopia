<?php
require_once '../app/core/Database.php';
require_once '../app/models/TestimoniModel.php';

class TestimoniController extends Controller {
    protected $db;
    protected $testimoniModel;

    public function __construct() {
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();
        $this->testimoniModel = new TestimoniModel($this->db);
    }

    public function index() {
        $testimoni = $this->testimoniModel->getAll();

        $data = [
            'testimoni' => $testimoni,
            'title' => 'Testimoni Pelanggan'
        ];

        $this->view('testimoni/index', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama_lengkap'] ?? '';
            $role = $_POST['role'] ?? 'customer';
            $komentar = $_POST['komentar_t'] ?? '';
            $rating = (int)($_POST['rating_t'] ?? 0);

            $data = [
                'nama_lengkap' => $nama,
                'role' => $role,
                'komentar_t' => $komentar,
                'rating_t' => $rating
            ];

            $result = $this->testimoniModel->add($data);

            header('Content-Type: application/json');
            if ($result === true) {
                echo json_encode(['status'=>'success', 'message'=>'Testimoni berhasil ditambahkan']);
            } else {
                echo json_encode(['status'=>'error', 'message'=>$result]);
            }
            exit;
        }
    }
}
?>

<?php
// 1. Panggil file Database dan Model Testimoni agar bisa dipakai di sini
require_once '../app/core/Database.php';
require_once '../app/models/TestimoniModel.php';

class Home extends Controller {
    // Siapkan properti untuk menampung koneksi dan model
    protected $db;
    protected $testimoniModel;

    public function __construct() {
        // 2. Inisialisasi koneksi database dan model saat Controller dimuat
        $db_instance = new Database();
        $this->db = $db_instance->getConnection();
        $this->testimoniModel = new TestimoniModel($this->db);
    }

    public function index() {
        // 3. Ambil data testimoni dari model
        $testimoni = $this->testimoniModel->getAll();

        $data['title'] = 'Halaman Utama Pawtopia';
        $data['testimoni'] = $testimoni;

        // Kirim semua data ke view
        $this->view('home/index', $data);
    }
}
<?php
require_once '../app/core/Database.php';
require_once '../app/models/CariModel.php';

class CariPenitipan extends Controller {

    protected $db;
    protected $cariModel;

    public function __construct() {
        $db_instance = new Database();
        $this->db = $db_instance->getConnection(); // Ini sekarang me-return object MySQLi
        $this->cariModel = new CariModel($this->db);
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        // Ambil Data (MySQLi Version)
        $hotArrivals = $this->cariModel->getHotArrivals();
        $mitraList = $this->cariModel->getRandomMitra($keyword);

        $data = [
            'title'       => 'Cari Penitipan',
            'content'     => 'dashboard_customer/pilih_penitipan/penitipan',
            'hotArrivals' => $hotArrivals, 
            'mitraList'   => $mitraList,   
            'keyword'     => $keyword      
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }
}
?>
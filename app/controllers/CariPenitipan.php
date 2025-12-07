<?php
require_once '../app/core/Controller.php';
require_once '../app/models/CariModel.php';

class CariPenitipan extends Controller
{
    private $cariModel;

   public function __construct()
{
    require_once '../app/core/Database.php';
    $db_instance = new Database();
    $this->db = $db_instance->getConnection();
    $this->cariModel = new CariModel($this->db);
}

    public function index()
    {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

        $data['keyword']     = $keyword;
        $data['mitraList']   = $this->cariModel->getAllMitra($keyword);
        $data['topRated']    = $this->cariModel->getTopRatedMitra();
        $data['hotArrivals'] = $this->cariModel->getHotArrivals();

        $this->view('dashboard_customer/pilih_penitipan/penitipan', $data);
    }

    /* ==========================================================
        ENDPOINT DETAIL (Modal)
       ========================================================== */
    public function getDetailMitra($id_mitra = null)
    {
        header('Content-Type: application/json');

        if (!$id_mitra) {
            echo json_encode([
                'success' => false,
                'message' => 'ID mitra tidak dikirim.'
            ]);
            return;
        }

        $id_mitra = htmlspecialchars(trim($id_mitra));

        $mitra = $this->cariModel->getMitraDetailById($id_mitra);
        if (!$mitra) {
            echo json_encode([
                'success' => false,
                'message' => 'Detail mitra tidak ditemukan.'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $mitra
        ]);
    }
}
}
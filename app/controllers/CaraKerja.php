<?php
require_once __DIR__ . '/../models/CaraKerjaModel.php';

class CaraKerjaController {
    public function index() {
        $model = new CaraKerjaModel();
        $steps = $model->getSteps();

        // kirim data ke view
        require __DIR__ . '/../views/home/cara-kerja.php';
    }
}
?>

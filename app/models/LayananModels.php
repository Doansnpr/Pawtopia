<?php
class LayananModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getPilihanLayanan() {
        return $this->conn->query("SELECT * FROM layanan_pilihan");
    }

    public function getBiayaLayanan() {
        return $this->conn->query("SELECT * FROM layanan_biaya");
    }
}
?>

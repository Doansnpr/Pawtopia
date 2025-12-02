<?php

class MitraModel {
    private $conn;
    private $table = 'mitra';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllMitra() {
        // Mengambil data mitra (bisa di-join dengan users jika butuh nama pemilik asli)
        // Disini saya ambil dari tabel mitra saja sesuai struktur yang Anda kirim
        $query = "SELECT * FROM {$this->table} ORDER BY tgl_daftar DESC";
        $result = $this->conn->query($query);
        
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getMitraById($id_mitra) {
        $query = "SELECT * FROM {$this->table} WHERE id_mitra = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateStatus($id_mitra, $status_baru) {
        $query = "UPDATE {$this->table} SET status = ? WHERE id_mitra = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $status_baru, $id_mitra);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
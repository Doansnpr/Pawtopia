<?php

class UlasanModel {
    private $conn;
    private $table = 'ulasan';
    private $tableBalasan = 'balasan_ulasan';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUlasanByMitra($id_mitra) {
        $query = "SELECT 
                    u.id_ulasan,
                    u.rating, 
                    u.komentar, 
                    u.tgl_ulasan,
                    usr.nama_lengkap,   -- Ini mengambil nama Pengirim Ulasan (Bukan Mitra)
                    -- usr.foto_profil, -- BARIS INI SAYA HAPUS KARENA KOLOMNYA TIDAK ADA DI DB
                    bu.balasan,         -- Ambil isi balasan
                    bu.tgl_balasan      -- Ambil tanggal balasan
                  FROM ulasan u
                  -- Join ke booking hanya untuk filter berdasarkan id_mitra
                  JOIN booking b ON u.id_booking = b.id_booking
                  
                  -- PERBAIKAN NAMA: Join users langsung ke ulasan (u.id_users)
                  -- Ini menjamin nama yang muncul adalah nama Customer (Bintang/Asha)
                  JOIN users usr ON u.id_users = usr.id_users
                  
                  -- PERBAIKAN BALASAN: Ambil data balasan pakai LEFT JOIN
                  LEFT JOIN {$this->tableBalasan} bu ON u.id_ulasan = bu.id_ulasan
                  
                  WHERE b.id_mitra = ?
                  ORDER BY u.tgl_ulasan DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    // --- Bagian bawah ini TIDAK ADA YANG DIUBAH (Sesuai kodemu yang sudah benar) ---
    
    public function getAverageRating($id_mitra) {
        $query = "SELECT AVG(u.rating) as rata_rata, COUNT(u.id_ulasan) as total_ulasan
                  FROM ulasan u
                  JOIN booking b ON u.id_booking = b.id_booking
                  WHERE b.id_mitra = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function simpanBalasan($id_ulasan, $balasan) {
        $tgl = date('Y-m-d H:i:s');

        // 1. Cek apakah sudah ada balasan?
        $cekQuery = "SELECT id_balasan FROM {$this->tableBalasan} WHERE id_ulasan = ?";
        $stmtCek = $this->conn->prepare($cekQuery);
        if (!$stmtCek) die("❌ Error Prepare Cek: " . $this->conn->error);
        
        $stmtCek->bind_param("s", $id_ulasan);
        $stmtCek->execute();
        $resCek = $stmtCek->get_result();

        // 2. Tentukan Query (UPDATE atau INSERT)
        if ($resCek->num_rows > 0) {
            // UPDATE
            $query = "UPDATE {$this->tableBalasan} SET balasan = ?, tgl_balasan = ? WHERE id_ulasan = ?";
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) die("❌ Error Prepare Update: " . $this->conn->error);
            
            $stmt->bind_param("sss", $balasan, $tgl, $id_ulasan);
        } else {
            // INSERT
            $query = "INSERT INTO {$this->tableBalasan} (id_ulasan, balasan, tgl_balasan) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) die("❌ Error Prepare Insert: " . $this->conn->error);
            
            $stmt->bind_param("sss", $id_ulasan, $balasan, $tgl);
        }
        
        // 3. Eksekusi dan Cek Error
        if (!$stmt->execute()) {
             die("❌ Error Execute Simpan: " . $stmt->error);
        }

        return true;
    }
    
    public function hapusBalasan($id_ulasan) {
        $query = "DELETE FROM {$this->tableBalasan} WHERE id_ulasan = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) die("❌ Error Prepare Hapus: " . $this->conn->error);
        
        $stmt->bind_param("s", $id_ulasan);
        return $stmt->execute();
    }
}
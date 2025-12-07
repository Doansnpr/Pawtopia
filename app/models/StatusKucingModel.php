<?php

class StatusKucingModel {
    private $conn;
    private $table_logs = 'activity_log'; 
    private $table_lifecycle = 'booking_lifecycle'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getActiveCatsByMitra($id_mitra) {
        // Query ini menggabungkan tabel booking, users, detail_booking, kucing, dan lifecycle
        // Tujuannya untuk mendapatkan data lengkap kartu dashboard
        $query = "SELECT 
                    b.id_booking, 
                    b.tgl_mulai,
                    b.tgl_selesai,
                    b.id_mitra,
                    u.nama_lengkap AS nama_pemilik, 
                    k.id_kucing, 
                    k.nama_kucing, 
                    k.ras, 
                    k.foto_kucing,
                    k.keterangan, 
                    COALESCE(bl.status, 'Menunggu Kedatangan') as status_lifecycle
                  FROM booking b
                  JOIN users u ON b.id_users = u.id_users  
                  JOIN detail_booking db ON b.id_booking = db.id_booking
                  JOIN kucing k ON db.id_kucing = k.id_kucing
                  LEFT JOIN booking_lifecycle bl ON b.id_booking = bl.id_booking AND k.id_kucing = bl.id_kucing
                  WHERE b.id_mitra = ? 
                  AND (bl.status IS NULL OR bl.status != 'Selesai')
                  ORDER BY b.tgl_mulai ASC, b.id_booking DESC";

        $stmt = $this->conn->prepare($query);

        // Error handling jika query salah ketik
        if (!$stmt) {
            die("SQL ERROR in getActiveCatsByMitra: " . $this->conn->error);
        }

        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();

        $cats = [];
        while ($row = $result->fetch_assoc()) {
            // Fallback jika nama pemilik kosong
            if (empty($row['nama_pemilik'])) { 
                $row['nama_pemilik'] = 'Pelanggan #' . $row['id_booking']; 
            }
            $cats[] = $row;
        }
        return $cats;
    }

    public function getLogsByCat($id_booking, $id_kucing) {
        $query = "SELECT * FROM {$this->table_logs} 
                  WHERE id_booking = ? AND id_kucing = ? 
                  ORDER BY waktu_log DESC"; 
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) return [];

        $stmt->bind_param("ss", $id_booking, $id_kucing);
        
        if (!$stmt->execute()) {
            return [];
        }
        
        $result = $stmt->get_result();
        $logs = [];
        
        while ($row = $result->fetch_assoc()) {
            // Format jam agar siap tampil di JS
            $time = strtotime($row['waktu_log']); 
            $row['jam_format'] = date('H:i', $time); // Contoh: 14:30
            $row['tgl_format'] = date('d M', $time); // Contoh: 06 Des
            $logs[] = $row;
        }
        return $logs;
    }

    public function addLog($data) {
        // HAPUS bagian 'catatan'
        $query = "INSERT INTO {$this->table_logs} 
                (id_booking, id_kucing, jenis_aktivitas) 
                VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) return false; 
        
        // Hapus binding parameter ke-4
        $stmt->bind_param("sss", 
            $data['id_booking'], 
            $data['id_kucing'], 
            $data['jenis_aktivitas']
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateLifecycleStatus($id_booking, $id_kucing, $status_baru) {
        // Cek apakah data status sudah ada sebelumnya
        $cek = "SELECT id_lifecycle FROM {$this->table_lifecycle} WHERE id_booking = ? AND id_kucing = ?";
        $stmtCek = $this->conn->prepare($cek);
        $stmtCek->bind_param("ss", $id_booking, $id_kucing);
        $stmtCek->execute();
        $resCek = $stmtCek->get_result();
        
        if ($resCek->num_rows > 0) {
            // Jika sudah ada -> Lakukan UPDATE
            $query = "UPDATE {$this->table_lifecycle} SET status = ? WHERE id_booking = ? AND id_kucing = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $status_baru, $id_booking, $id_kucing);
        } else {
            // Jika belum ada -> Lakukan INSERT
            $query = "INSERT INTO {$this->table_lifecycle} (id_booking, id_kucing, status) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $id_booking, $id_kucing, $status_baru);
        }
        
        return $stmt->execute();
    }
}
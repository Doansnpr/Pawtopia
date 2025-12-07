<?php

class StatusKucingModel {
    private $conn;
    private $table_logs = 'activity_log'; 
    private $table_lifecycle = 'booking_lifecycle'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getActiveCatsByMitra($id_mitra) {
        // PERBAIKAN QUERY:
        // 1. Join ke Lifecycle diubah agar hanya mengambil ID status TERAKHIR (MAX id_lifecycle)
        // 2. Ditambahkan GROUP BY agar data kucing tidak duplikat
        
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
                  
                  -- JOIN KHUSUS: Hanya ambil data lifecycle TERBARU untuk kucing tersebut
                  LEFT JOIN booking_lifecycle bl ON bl.id_lifecycle = (
                      SELECT MAX(id_lifecycle) 
                      FROM booking_lifecycle 
                      WHERE id_booking = b.id_booking AND id_kucing = k.id_kucing
                  )

                  WHERE b.id_mitra = ? 
                  AND (bl.status IS NULL OR bl.status != 'Selesai')
                  
                  -- OBAT ANTI DUPLIKAT:
                  GROUP BY b.id_booking, k.id_kucing
                  
                  ORDER BY b.tgl_mulai ASC, b.id_booking DESC";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("SQL ERROR in getActiveCatsByMitra: " . $this->conn->error);
        }

        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();

        $cats = [];
        while ($row = $result->fetch_assoc()) {
            if (empty($row['nama_pemilik'])) { 
                $row['nama_pemilik'] = 'Pelanggan #' . $row['id_booking']; 
            }
            $cats[] = $row;
        }
        return $cats;
    }

    // ... (Function getLogsByCat, addLog, updateLifecycleStatus TETAP SAMA, tidak perlu diubah) ...
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
            $time = strtotime($row['waktu_log']); 
            $row['jam_format'] = date('H:i', $time); 
            $row['tgl_format'] = date('d M', $time); 
            $logs[] = $row;
        }
        return $logs;
    }

    public function addLog($data) {
        $query = "INSERT INTO {$this->table_logs} 
                (id_booking, id_kucing, jenis_aktivitas) 
                VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) return false; 
        
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
        $cek = "SELECT id_lifecycle FROM {$this->table_lifecycle} WHERE id_booking = ? AND id_kucing = ?";
        $stmtCek = $this->conn->prepare($cek);
        $stmtCek->bind_param("ss", $id_booking, $id_kucing);
        $stmtCek->execute();
        $resCek = $stmtCek->get_result();
        
        if ($resCek->num_rows > 0) {
            $query = "UPDATE {$this->table_lifecycle} SET status = ? WHERE id_booking = ? AND id_kucing = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $status_baru, $id_booking, $id_kucing);
        } else {
            $query = "INSERT INTO {$this->table_lifecycle} (id_booking, id_kucing, status) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $id_booking, $id_kucing, $status_baru);
        }
        
        return $stmt->execute();
    }
}
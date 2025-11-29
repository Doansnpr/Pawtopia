<?php

class StatusKucingModel {
    private $conn;
    private $table_logs = 'activity_log'; 
    private $table_lifecycle = 'booking_lifecycle'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Mengambil daftar kucing (Tidak ada perubahan, ini sudah benar)
    public function getActiveCatsByMitra($id_mitra) {
        $query = "SELECT 
                    b.id_booking, 
                    b.tgl_mulai,
                    b.tgl_selesai,
                    b.id_mitra,
                    k.id_kucing, 
                    k.nama_kucing, 
                    k.ras, 
                    k.foto_kucing,
                    k.keterangan, -- [TAMBAHAN BARU] Ambil kolom keterangan
                    COALESCE(bl.status, 'Menunggu Kedatangan') as status_lifecycle
                  FROM booking b
                  JOIN detail_booking db ON b.id_booking = db.id_booking
                  JOIN kucing k ON db.id_kucing = k.id_kucing
                  LEFT JOIN booking_lifecycle bl ON b.id_booking = bl.id_booking AND k.id_kucing = bl.id_kucing
                  WHERE b.id_mitra = ? 
                  AND (bl.status IS NULL OR bl.status != 'Selesai')
                  ORDER BY b.tgl_mulai ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();

        $cats = [];
        while ($row = $result->fetch_assoc()) {
            $cats[] = $row;
        }
        return $cats;
    }

    // 2. PERBAIKAN DI SINI (Mengambil riwayat log)
    public function getLogsByCat($id_booking, $id_kucing) {
        // PERBAIKAN: Ganti 'timestamp' menjadi 'waktu_log' sesuai database Anda
        $query = "SELECT * FROM {$this->table_logs} 
                  WHERE id_booking = ? AND id_kucing = ? 
                  ORDER BY waktu_log DESC"; 
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $id_booking, $id_kucing);
        
        if (!$stmt->execute()) {
            return []; // Kembalikan array kosong jika error
        }
        
        $result = $stmt->get_result();
        $logs = [];
        
        while ($row = $result->fetch_assoc()) {
            // PERBAIKAN: Gunakan 'waktu_log' untuk konversi waktu
            $time = strtotime($row['waktu_log']); 
            
            // Format data untuk dikirim ke JS
            $row['jam_format'] = date('H:i', $time);
            $row['tgl_format'] = date('d M', $time); 
            $logs[] = $row;
        }
        return $logs;
    }
    
    public function addLog($data) {
        // Hapus kolom catatan dari Query
        $query = "INSERT INTO activity_log 
                (id_booking, id_kucing, jenis_aktivitas) 
                VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) return "SQL Error: " . $this->conn->error;
        
        // Ubah "ssss" jadi "sss" (karena cuma 3 data)
        // Hapus $data['catatan']
        $stmt->bind_param("sss", 
            $data['id_booking'], 
            $data['id_kucing'], 
            $data['jenis_aktivitas']
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return "SQL Error: " . $stmt->error;
        }
    }
    // 4. Update Status Lifecycle (Tidak ada perubahan)
    public function updateLifecycleStatus($id_booking, $id_kucing, $status_baru) {
        // Cek dulu apakah data sudah ada?
        $cek = "SELECT id_lifecycle FROM {$this->table_lifecycle} WHERE id_booking = ? AND id_kucing = ?";
        $stmtCek = $this->conn->prepare($cek);
        $stmtCek->bind_param("ss", $id_booking, $id_kucing);
        $stmtCek->execute();
        
        if ($stmtCek->get_result()->num_rows > 0) {
            // Update
            $query = "UPDATE {$this->table_lifecycle} SET status = ? WHERE id_booking = ? AND id_kucing = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $status_baru, $id_booking, $id_kucing);
        } else {
            // Insert Baru
            $query = "INSERT INTO {$this->table_lifecycle} (id_booking, id_kucing, status) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $id_booking, $id_kucing, $status_baru);
        }
        
        return $stmt->execute();
    }
}
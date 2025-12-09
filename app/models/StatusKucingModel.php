<?php

class StatusKucingModel {
    private $conn;
    private $table_logs = 'activity_log'; 
    private $table_lifecycle = 'booking_lifecycle'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getActiveCatsByMitra($id_mitra) {
        // QUERY LENGKAP & AMAN
        $query = "SELECT 
                    b.id_booking, 
                    b.tgl_mulai,
                    b.tgl_selesai,
                    b.id_mitra,
                    b.total_harga, 
                    b.status as status_booking, 
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
                
                -- Ambil status lifecycle terakhir
                LEFT JOIN booking_lifecycle bl ON bl.id_lifecycle = (
                    SELECT MAX(id_lifecycle) 
                    FROM booking_lifecycle 
                    WHERE id_booking = b.id_booking AND id_kucing = k.id_kucing
                )

                WHERE b.id_mitra = ? 
                AND b.status = 'Aktif'  -- Hanya tampilkan booking yang Aktif
                AND (bl.status IS NULL OR bl.status != 'Selesai') -- Kucing yg sudah selesai tidak tampil
                
                GROUP BY b.id_booking, k.id_kucing
                ORDER BY b.tgl_mulai ASC, b.id_booking DESC";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            // Debugging: Jika error, pesan ini akan muncul di layar
            die("Error Query Model: " . $this->conn->error);
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

    // --- (Fungsi Log & Update Status Biarkan Saja/Sama seperti sebelumnya) ---
    public function getLogsByCat($id_booking, $id_kucing) {
        $query = "SELECT * FROM {$this->table_logs} WHERE id_booking = ? AND id_kucing = ? ORDER BY waktu_log DESC"; 
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $id_booking, $id_kucing);
        $stmt->execute();
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
        $query = "INSERT INTO {$this->table_logs} (id_booking, id_kucing, jenis_aktivitas) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $data['id_booking'], $data['id_kucing'], $data['jenis_aktivitas']);
        return $stmt->execute();
    }

    public function updateLifecycleStatus($id_booking, $id_kucing, $status_baru) {
        // Cek apakah sudah ada status hari ini/terakhir
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

    public function finalizeBooking($id_booking, $id_mitra, $ignore_total_baru, $denda) {
        $this->conn->begin_transaction();
        try {
            // 1. Ambil Status Lama
            $qryCek = "SELECT status FROM booking WHERE id_booking = ? AND id_mitra = ?";
            $stmtCek = $this->conn->prepare($qryCek);
            $stmtCek->bind_param("ss", $id_booking, $id_mitra);
            $stmtCek->execute();
            $resCek = $stmtCek->get_result()->fetch_assoc();
            $stmtCek->close();

            if (!$resCek) throw new Exception("Data tidak ditemukan");
            $status_lama = $resCek['status'];

            // 2. UPDATE STATUS & DENDA SAJA (Total Harga JANGAN diubah)
            $query = "UPDATE booking SET 
                    status = 'Selesai', 
                    biaya_denda = ?, 
                    tgl_ambil_aktual = NOW() 
                    WHERE id_booking = ? AND id_mitra = ?";
            
            $stmt = $this->conn->prepare($query);
            
            // Parameter: (Denda [int], ID Booking [string], ID Mitra [string])
            $stmt->bind_param("iss", $denda, $id_booking, $id_mitra);
            
            if (!$stmt->execute()) throw new Exception("Gagal update booking");
            $stmt->close();

            // 3. Kembalikan Kapasitas Mitra
            if ($status_lama === 'Aktif') {
                $updateKap = "UPDATE mitra SET kapasitas = kapasitas + 1 WHERE id_mitra = ?";
                $stmtKap = $this->conn->prepare($updateKap);
                $stmtKap->bind_param("s", $id_mitra);
                $stmtKap->execute();
                $stmtKap->close();
            }
            
            // 4. Update Lifecycle Kucing
            $updLife = "UPDATE booking_lifecycle SET status = 'Selesai' WHERE id_booking = ?";
            $stmtLife = $this->conn->prepare($updLife);
            $stmtLife->bind_param("s", $id_booking);
            $stmtLife->execute();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
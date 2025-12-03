<?php

class LaporanMitraModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTransactionHistory($id_mitra, $startDate, $endDate, $limit = 10, $offset = 0) {
        $query = "SELECT 
                    b.id_booking, b.tgl_mulai, b.tgl_selesai,
                    u.nama_lengkap, b.total_harga, b.paket,
                    COUNT(db.id_kucing) as jumlah_kucing
                  FROM booking b
                  LEFT JOIN users u ON b.id_users = u.id_users
                  LEFT JOIN detail_booking db ON b.id_booking = db.id_booking
                  WHERE b.id_mitra = ? 
                  AND DATE(b.tgl_booking) BETWEEN ? AND ?
                  GROUP BY b.id_booking
                  ORDER BY b.tgl_booking DESC
                  LIMIT ? OFFSET ?"; // Tambah LIMIT & OFFSET

        $stmt = $this->conn->prepare($query);
        // Bind param tambah "ii" (integer) di akhir untuk limit & offset
        $stmt->bind_param("sssii", $id_mitra, $startDate, $endDate, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // --- 2. BARU: Hitung Total Data (Untuk Pagination) ---
    public function countTransactionHistory($id_mitra, $startDate, $endDate) {
        // Query sama, tapi SELECT COUNT(*)
        $query = "SELECT COUNT(*) as total FROM (
                    SELECT b.id_booking 
                    FROM booking b
                    WHERE b.id_mitra = ? 
                    AND DATE(b.tgl_booking) BETWEEN ? AND ?
                    GROUP BY b.id_booking
                  ) as subquery";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $id_mitra, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    // 2. Ambil Detail Kucing (Untuk Modal Pop-up)
    public function getBookingDetails($id_booking) {
        $query = "SELECT k.nama_kucing, k.ras, k.foto_kucing, k.umur, k.jenis_kelamin
                  FROM detail_booking db
                  JOIN kucing k ON db.id_kucing = k.id_kucing
                  WHERE db.id_booking = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cats = [];
        while($row = $result->fetch_assoc()){
            $cats[] = $row;
        }
        return $cats;
    }

    // 3. Statistik Keuangan
    // public function getFinancialStats($id_mitra, $startDate, $endDate) {
    //     $queryRev = "SELECT COALESCE(SUM(total_harga), 0) as total_pendapatan,
    //                         COUNT(*) as total_selesai
    //                  FROM booking 
    //                  WHERE id_mitra = ? AND status = 'Selesai' 
    //                  AND DATE(tgl_booking) BETWEEN ? AND ?";
        
    //     $stmt = $this->conn->prepare($queryRev);
    //     $stmt->bind_param("sss", $id_mitra, $startDate, $endDate);
    //     $stmt->execute();
    //     $resRev = $stmt->get_result()->fetch_assoc();

    //     $queryCancel = "SELECT COUNT(*) as total_batal FROM booking 
    //                     WHERE id_mitra = ? AND status = 'Dibatalkan' 
    //                     AND DATE(tgl_booking) BETWEEN ? AND ?";
    //     $stmt2 = $this->conn->prepare($queryCancel);
    //     $stmt2->bind_param("sss", $id_mitra, $startDate, $endDate);
    //     $stmt2->execute();
    //     $resCancel = $stmt2->get_result()->fetch_assoc();

    //     return [
    //         'pendapatan' => $resRev['total_pendapatan'],
    //         'booking_selesai' => $resRev['total_selesai'],
    //         'booking_batal' => $resCancel['total_batal']
    //     ];
    // }

    public function getFinancialStats($id_mitra, $startDate, $endDate) {
        
        $queryRev = "SELECT COALESCE(SUM(total_harga), 0) as total_pendapatan,
                            COUNT(*) as total_selesai
                    FROM booking 
                    WHERE id_mitra = ? 
                    AND status NOT IN ('Dibatalkan', 'Booking Ditolak', 'DP Ditolak') 
                    AND DATE(tgl_booking) BETWEEN ? AND ?";
        
        $stmt = $this->conn->prepare($queryRev);
        $stmt->bind_param("sss", $id_mitra, $startDate, $endDate);
        $stmt->execute();
        $resRev = $stmt->get_result()->fetch_assoc();

        // Query untuk menghitung yang batal (tetap sama)
        $queryCancel = "SELECT COUNT(*) as total_batal FROM booking 
                        WHERE id_mitra = ? AND status IN ('Dibatalkan', 'Booking Ditolak', 'DP Ditolak') 
                        AND DATE(tgl_booking) BETWEEN ? AND ?";
        $stmt2 = $this->conn->prepare($queryCancel);
        $stmt2->bind_param("sss", $id_mitra, $startDate, $endDate);
        $stmt2->execute();
        $resCancel = $stmt2->get_result()->fetch_assoc();

        return [
            'pendapatan' => $resRev['total_pendapatan'],
            'booking_selesai' => $resRev['total_selesai'], // Ini akan menghitung total transaksi aktif juga
            'booking_batal' => $resCancel['total_batal']
        ];
    }

    // 4. Statistik Okupansi
    public function getOccupancyStats($id_mitra) {
        $qCap = "SELECT kapasitas FROM mitra WHERE id_mitra = ?";
        $stmt = $this->conn->prepare($qCap);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $mitra = $stmt->get_result()->fetch_assoc();
        $kapasitas = $mitra['kapasitas'] ?? 0;

        $today = date('Y-m-d');
        $qFill = "SELECT COUNT(db.id_kucing) as terisi
                  FROM detail_booking db
                  JOIN booking b ON db.id_booking = b.id_booking
                  WHERE b.id_mitra = ? 
                  AND b.status NOT IN ('Dibatalkan', 'Selesai', 'Menunggu Pembayaran')
                  AND ? BETWEEN b.tgl_mulai AND b.tgl_selesai";
        
        $stmt2 = $this->conn->prepare($qFill);
        $stmt2->bind_param("ss", $id_mitra, $today);
        $stmt2->execute();
        $filled = $stmt2->get_result()->fetch_assoc()['terisi'] ?? 0;

        return [
            'kapasitas' => $kapasitas,
            'terisi' => $filled,
            'rate' => ($kapasitas > 0) ? round(($filled/$kapasitas)*100) : 0
        ];
    }

    // 5. Pendapatan Bulan Lalu
    public function getPreviousMonthRevenue($id_mitra) {
        $start = date('Y-m-01', strtotime('last month'));
        $end = date('Y-m-t', strtotime('last month'));
        $query = "SELECT COALESCE(SUM(total_harga), 0) as total 
                  FROM booking WHERE id_mitra = ? AND status = 'Selesai' 
                  AND DATE(tgl_booking) BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $id_mitra, $start, $end);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }

    // FUNGSI KHUSUS EXPORT (TANPA LIMIT & OFFSET)
    public function getExportData($id_mitra, $startDate, $endDate) {
        $query = "SELECT 
                    b.id_booking, 
                    b.tgl_mulai,
                    b.tgl_selesai,
                    u.nama_lengkap, 
                    b.total_harga, 
                    b.paket,
                    COUNT(db.id_kucing) as jumlah_kucing
                FROM booking b
                LEFT JOIN users u ON b.id_users = u.id_users
                LEFT JOIN detail_booking db ON b.id_booking = db.id_booking
                WHERE b.id_mitra = ? 
                AND DATE(b.tgl_booking) BETWEEN ? AND ?
                GROUP BY b.id_booking
                ORDER BY b.tgl_booking DESC"; // Tidak ada LIMIT di sini

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $id_mitra, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
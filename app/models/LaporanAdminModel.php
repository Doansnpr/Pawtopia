<?php

class LaporanAdminModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Mengambil Statistik Keuangan (Card Atas)
    public function getStatistikKeuangan() {
        $stats = [
            'gmv_total' => 0,          
            'pendapatan_admin' => 0,   
            'jml_mitra_bayar' => 0,    
            'booking_selesai' => 0
        ];

        // Hitung GMV (Total Uang Customer ke Mitra)
        $queryGMV = "SELECT SUM(total_harga) as total FROM booking WHERE status = 'Selesai'";
        $resGMV = $this->conn->query($queryGMV);
        $stats['gmv_total'] = $resGMV->fetch_assoc()['total'] ?? 0;

        // Hitung Pendapatan Admin (50rb x Mitra Terverifikasi)
        $queryMitra = "SELECT COUNT(*) as total FROM mitra WHERE status = 'Terverifikasi'";
        $resMitra = $this->conn->query($queryMitra);
        $jmlMitra = $resMitra->fetch_assoc()['total'] ?? 0;
        
        $biaya_pendaftaran = 50000; 
        $stats['jml_mitra_bayar'] = $jmlMitra;
        $stats['pendapatan_admin'] = $jmlMitra * $biaya_pendaftaran;

        // Hitung Booking Selesai
        $queryBooking = "SELECT COUNT(*) as total FROM booking WHERE status = 'Selesai'";
        $resBooking = $this->conn->query($queryBooking);
        $stats['booking_selesai'] = $resBooking->fetch_assoc()['total'] ?? 0;

        return $stats;
    }

    // 2. Hitung TOTAL Data Riwayat (PENTING UNTUK PAGINATION)
    // Fungsi ini wajib ada karena dipanggil di Controller
    public function countAllRiwayat() {
        $query = "SELECT COUNT(*) as total FROM booking";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // 3. Ambil Data Riwayat dengan LIMIT & OFFSET
    // Perhatikan parameter ($limit, $offset)
    public function getAllRiwayatBooking($limit = 10, $offset = 0) {
        $query = "SELECT 
                    b.id_booking,
                    b.tgl_booking,
                    b.total_harga,
                    b.status,
                    u.nama_lengkap as nama_customer,
                    m.nama_petshop,
                    b.paket
                  FROM booking b
                  JOIN users u ON b.id_users = u.id_users
                  JOIN mitra m ON b.id_mitra = m.id_mitra
                  ORDER BY b.tgl_booking DESC
                  LIMIT ? OFFSET ?"; // Query SQL Limit Offset
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $limit, $offset); // Binding parameter integer
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
}
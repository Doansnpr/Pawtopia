<?php

class BookingModel {
    private $conn;
    private $table = 'booking'; 
    private $userTable = 'users'; 
    private $catTable = 'kucing'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBookings() {
        $query = "
            SELECT 
                b.id_booking, 
                u.nama_lengkap,
                b.tgl_booking, 
                b.tgl_mulai, 
                b.tgl_selesai, 
                b.jumlah_kucing,
                b.paket,
                b.total_harga,
                b.id_kucing,
                b.status
            FROM 
                {$this->table} b
            JOIN 
                {$this->userTable} u ON b.id_users = u.id_users 
            ORDER BY 
                b.tgl_mulai DESC";

        $result = $this->conn->query($query);
        
        if (!$result) { 
            return [];
        }

        $bookings = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
            $result->free();
        }
        
        return $bookings;
    }
    
    public function getStatusCounts() {
        $query = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $result = $this->conn->query($query);
        
        $counts = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['status']] = $row['count'];
            }
        }
        
        $default_statuses = [
            'Menunggu Konfirmasi' => 0, 
            'Menunggu DP' => 0, 
            'Verifikasi DP' => 0,
            'Aktif' => 0, 
            'Selesai' => 0, 
            'Dibatalkan' => 0 
        ];
        
        return array_merge($default_statuses, $counts);
    }
}
<?php
// File: app/models/BookingModel.php

class BookingModel {
    private $conn;
    private $table = 'booking'; 
    private $userTable = 'users'; 
    private $catTable = 'kucing'; // Asumsi nama tabel kucing

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Mengambil semua data booking, digabungkan dengan nama pelanggan.
     */
    public function getAllBookings() {
        $query = "
            SELECT 
                b.id_booking, 
                u.nama_lengkap AS name, 
                b.tgl_mulai AS check_in, 
                b.tgl_selesai AS check_out, 
                -- Kita ambil id_kucing, nanti dihitung/ditampilkan di View
                b.id_kucing, 
                1 AS cats, -- Placeholder sementara untuk Jumlah Kucing (1 per baris)
                b.status
            FROM 
                {$this->table} b
            JOIN 
                {$this->userTable} u ON b.id_users = u.id_users 
            ORDER BY 
                b.tgl_mulai DESC";

        $result = $this->conn->query($query);
        
        // --- DEBUGGING KRITIS ---
        if (!$result) { 
             // Tampilkan error SQL jika data tidak muncul
             // die("Query GAGAL di BookingModel. Error: " . $this->conn->error . " | Query: " . $query); 
             return [];
        }
        // -------------------------

        $bookings = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
            $result->free();
        }
        
        return $bookings;
    }
    
    // ... (getStatusCounts() tetap sama) ...
    public function getStatusCounts() {
        $query = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $result = $this->conn->query($query);
        
        $counts = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['status']] = $row['count'];
            }
        }
        $default_statuses = ['Menunggu' => 0, 'Terkonfirmasi' => 0, 'Selesai' => 0, 'Dibatalkan' => 0];
        return array_merge($default_statuses, $counts);
    }
}
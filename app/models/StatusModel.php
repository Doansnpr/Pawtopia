<?php
class StatusModel {
    private $conn;
    
    // Definisi Nama Table
    private $tableBooking = 'booking';
    private $tableDetail = 'detail_booking';
    private $tableKucing = 'kucing';
    private $tableMitra = 'mitra';
    private $tableLogs = 'activity_log'; 
    private $tableLifecycle = 'booking_lifecycle'; 

    public function __construct() {
        // KONFIGURASI DATABASE
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "pawtopia";

        $this->conn = new mysqli($host, $username, $password, $database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Ganti nama function agar lebih sesuai (Active Bookings - Jamak)
    public function getAllActiveBookings($id_user) {
        $query = "SELECT id_booking, status, tgl_booking 
                  FROM " . $this->tableBooking . " 
                  WHERE id_users = ? 
                  -- Ambil semua status KECUALI yang sudah beres/batal
                  -- Tambahkan 'selesai' jika ingin menyembunyikan yang sudah rampung
                  AND TRIM(LOWER(status)) NOT IN ('dibatalkan', 'menunggu pembayaran', 'selesai') 
                  ORDER BY tgl_booking DESC"; 
                  // HAPUS 'LIMIT 1' DISINI

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();

        // Ganti fetch_assoc() menjadi fetch_all(MYSQLI_ASSOC)
        // Agar data yang dikembalikan berbentuk Array List (banyak data), bukan cuma 1 baris
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDetailBooking($id_booking, $id_user) {
        // PERBAIKAN: Ditambahkan GROUP BY agar tidak duplikat jika ada banyak history status
        $query = "SELECT 
                    b.id_booking,
                    -- Ambil status terbaru dari lifecycle (subquery) atau status booking
                    COALESCE(
                        (SELECT status FROM " . $this->tableLifecycle . " WHERE id_booking = b.id_booking AND id_kucing = k.id_kucing ORDER BY updated_at DESC LIMIT 1),
                        b.status
                    ) AS status_utama, 
                    b.tgl_booking, 
                    b.tgl_mulai,    
                    b.tgl_selesai,  
                    m.nama_petshop,
                    k.id_kucing,
                    k.nama_kucing,
                    k.ras,
                    k.umur,
                    k.jenis_kelamin,
                    k.keterangan,
                    k.foto_kucing
                  FROM " . $this->tableBooking . " b
                  JOIN " . $this->tableMitra . " m ON b.id_mitra = m.id_mitra
                  JOIN " . $this->tableDetail . " db ON b.id_booking = db.id_booking
                  JOIN " . $this->tableKucing . " k ON db.id_kucing = k.id_kucing
                  WHERE b.id_booking = ? 
                  AND b.id_users = ?
                  GROUP BY k.id_kucing"; // PENTING: Mencegah duplikat data kucing

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $id_booking, $id_user);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getActivityLogs($id_booking) {
        // Pastikan tabel log ada kolom id_kucing supaya bisa dipisah
        $checkTable = $this->conn->query("SHOW TABLES LIKE '" . $this->tableLogs . "'");
        
        if ($checkTable->num_rows == 0) {
            return []; 
        }

        $query = "SELECT * FROM " . $this->tableLogs . " 
                  WHERE id_booking = ? 
                  ORDER BY waktu_log DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
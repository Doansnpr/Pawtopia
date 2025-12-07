<?php
class StatusModel {
    private $conn;
    
    // Definisi Nama Table
    private $tableBooking = 'booking';
    private $tableDetail = 'detail_booking';
    private $tableKucing = 'kucing';
    private $tableMitra = 'mitra';
    private $tableLogs = 'activity_log'; // Sesuaikan nama tabel log anda (activity_log atau activity_logs)
    private $tableLifecycle = 'booking_lifecycle'; // TAMBAHAN: Tabel Lifecycle

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

    public function getLatestActiveBooking($id_user) {
    // KITA PERBAIKI QUERY-NYA:
    // Hapus 'selesai' dari daftar blacklist (NOT IN).
    // Kita hanya tidak mau menampilkan yang 'dibatalkan' atau masih 'menunggu pembayaran'.
    // Biarkan 'selesai' muncul agar Customer bisa melihat tombol "Konfirmasi Selesai".

    $query = "SELECT id_booking, status 
              FROM " . $this->tableBooking . " 
              WHERE id_users = ? 
              AND TRIM(LOWER(status)) NOT IN ('dibatalkan', 'menunggu pembayaran') 
              ORDER BY tgl_booking DESC 
              LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

    /**
     * 2. Ambil Detail Booking Lengkap (DIPERBAIKI)
     * Mengambil status dari booking_lifecycle
     */
    public function getDetailBooking($id_booking, $id_user) {
        // PERUBAHAN DISINI:
        // 1. Join ke booking_lifecycle (bl)
        // 2. Ambil bl.status sebagai status_utama
        // 3. Gunakan COALESCE: Jika di lifecycle belum ada (null), ambil dari booking biasa
        
        $query = "SELECT 
                    b.id_booking,
                    -- Prioritaskan status dari lifecycle. Jika kosong, pakai status administrasi
                    COALESCE(bl.status, b.status) AS status_utama, 
                    b.tgl_booking, 
                    b.tgl_mulai,    
                    b.tgl_selesai,  
                    m.nama_petshop,
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
                  -- JOIN KE LIFECYCLE BERDASARKAN BOOKING DAN KUCING
                  LEFT JOIN " . $this->tableLifecycle . " bl ON b.id_booking = bl.id_booking AND k.id_kucing = bl.id_kucing
                  WHERE b.id_booking = ? 
                  AND b.id_users = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $id_booking, $id_user);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    /**
     * 3. Ambil Log Aktivitas (Timeline)
     */
    public function getActivityLogs($id_booking) {
        // Pastikan nama tabel benar sesuai gambar Anda (activity_log)
        $checkTable = $this->conn->query("SHOW TABLES LIKE '" . $this->tableLogs . "'");
        
        if ($checkTable->num_rows == 0) {
            return []; 
        }

        $query = "SELECT * FROM " . $this->tableLogs . " 
                  WHERE id_booking = ? 
                  ORDER BY waktu_log DESC"; // Sesuai gambar tabel activity_log anda kolomnya 'waktu_log'

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
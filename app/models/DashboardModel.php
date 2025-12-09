<?php

class DashboardModel {
    private $db;

    // --- PERBAIKAN DI SINI ---
    // Terima parameter $db yang dikirim dari Controller
    public function __construct($db) {
        $this->db = $db;
    }
    // -------------------------

    public function getNamaUser($id_user) {
        // Gunakan prepared statement atau escape string biar aman, tapi query biasa jg oke
        $id_user = $this->db->real_escape_string($id_user); 
        $query = $this->db->query("SELECT nama_lengkap FROM users WHERE id_users = '$id_user'");
        
        // Cek jika query berhasil
        if ($query && $query->num_rows > 0) {
            $data = $query->fetch_assoc();
            return $data['nama_lengkap'];
        }
        return 'Pengguna';
    }

    public function getAllKucingUser($id_user) {
        $list = [];
        $id_user = $this->db->real_escape_string($id_user);
        $result = $this->db->query("SELECT id_kucing, nama_kucing FROM kucing WHERE id_users = '$id_user'");
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $list[$row['id_kucing']] = $row['nama_kucing'];
            }
        }
        return $list;
    }

    public function getAllMitra() {
        $list = [];
        $result = $this->db->query("SELECT id_mitra, nama_petshop FROM mitra");
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $list[$row['id_mitra']] = $row['nama_petshop'];
            }
        }
        return $list;
    }

    public function getDataChartBooking($id_user, $tahun) {
        $dataBooking = array_fill(1, 12, 0);
        $id_user = $this->db->real_escape_string($id_user);
        $tahun = $this->db->real_escape_string($tahun);

        $query = $this->db->query("
            SELECT MONTH(tgl_booking) AS bulan, COUNT(*) AS total_booking
            FROM booking
            WHERE id_users = '$id_user'
              AND YEAR(tgl_booking) = '$tahun'
            GROUP BY MONTH(tgl_booking)
        ");

        if ($query) {
            while ($row = $query->fetch_assoc()) {
                $dataBooking[(int)$row['bulan']] = (int)$row['total_booking'];
            }
        }
        
        return array_values($dataBooking);
    }

    public function getActiveBookings($id_user) {
        $bookings = [];
        $id_user = $this->db->real_escape_string($id_user);
        
        $query = $this->db->query("
            SELECT b.id_booking, 
                b.id_mitra, 
                k.nama_kucing, 
                m.nama_petshop AS tempat_penitipan, 
                b.tgl_booking, 
                b.status 
            FROM booking b
            JOIN mitra m ON b.id_mitra = m.id_mitra
            JOIN detail_booking db ON b.id_booking = db.id_booking
            JOIN kucing k ON db.id_kucing = k.id_kucing 
            WHERE b.id_users = '$id_user'
            AND TRIM(LOWER(b.status)) NOT IN ('selesai', 'dibatalkan')
            ORDER BY b.tgl_booking DESC
        ");
        
        if ($query && $query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                $bookings[] = $row;
            }
        }
        return $bookings;
    }

    public function getCountActiveKucing($id_user) {
            $id_user = $this->db->real_escape_string($id_user);
            $query = $this->db->query("
                SELECT COUNT(db.id_kucing) AS jumlah
                FROM detail_booking db
                JOIN booking b ON db.id_booking = b.id_booking
                WHERE b.id_users = '$id_user'
                AND TRIM(LOWER(b.status)) NOT IN ('selesai','dibatalkan')
            ");
            
            if ($query) {
                $row = $query->fetch_assoc();
                return (int)($row['jumlah'] ?? 0);
            }
            return 0;
        }

        public function getBookingSiapUlas($id_user) {
        $list = [];
        $id_user_safe = $this->db->real_escape_string($id_user);
        
        $query = $this->db->query("
            SELECT 
                b.id_booking, 
                b.tgl_booking, 
                m.nama_petshop,
                k.nama_kucing
            FROM booking b
            JOIN mitra m ON b.id_mitra = m.id_mitra
            JOIN detail_booking db ON b.id_booking = db.id_booking
            JOIN kucing k ON db.id_kucing = k.id_kucing
            WHERE b.id_users = '$id_user_safe' 
              AND TRIM(LOWER(b.status)) = 'selesai'
              AND b.id_booking NOT IN (
                  SELECT id_booking FROM ulasan WHERE id_users = '$id_user_safe'
              )
            ORDER BY b.tgl_booking DESC
        ");

        if ($query && $query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }
    
    public function getRataRatingMitra() {
        $query = $this->db->query("
            SELECT m.id_mitra, AVG(u.rating) AS rata_rating
            FROM ulasan u
            JOIN booking b ON u.id_booking = b.id_booking
            JOIN mitra m ON b.id_mitra = m.id_mitra
            GROUP BY m.id_mitra
        ");
        
        $ratings = [];
        if ($query) {
            while ($row = $query->fetch_assoc()) {
                $ratings[$row['id_mitra']] = round($row['rata_rating'], 1);
            }
        }
        return $ratings;
    }
}
?>
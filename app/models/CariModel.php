<?php
class CariModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil Mitra Baru (Slider)
    public function getHotArrivals() {
        // Query menggunakan LEFT JOIN agar mitra tanpa paket tetap muncul
        $sql = "SELECT m.id_mitra, m.nama_petshop, m.foto_profil, m.tgl_daftar,
                (SELECT MIN(p.harga) FROM mitra_paket p WHERE p.id_mitra = m.id_mitra) as harga_mulai
                FROM mitra m
                WHERE m.status = 'Terverifikasi' OR m.status = 'active'
                ORDER BY m.tgl_daftar DESC 
                LIMIT 10";
        
        $result = $this->db->query($sql);
        
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Ambil Data Utama (Acak/Shuffle)
    public function getRandomMitra($keyword = null) {
        $sql = "SELECT m.id_mitra, m.nama_petshop, m.alamat, m.foto_profil,
                
                (SELECT MIN(p.harga) FROM mitra_paket p WHERE p.id_mitra = m.id_mitra) as harga_mulai,

                (SELECT AVG(u.rating) FROM ulasan u 
                 JOIN booking b ON u.id_booking = b.id_booking 
                 WHERE b.id_mitra = m.id_mitra) as rating_rata,

                (SELECT u.komentar FROM ulasan u 
                 JOIN booking b ON u.id_booking = b.id_booking 
                 WHERE b.id_mitra = m.id_mitra 
                 ORDER BY u.tgl_ulasan DESC LIMIT 1) as ulasan_terbaru

                FROM mitra m
                WHERE (m.status = 'Terverifikasi' OR m.status = 'active') ";

        // Jika ada pencarian
        if (!empty($keyword)) {
            // Escape string agar aman di MySQLi
            $safe_key = $this->db->real_escape_string($keyword);
            $sql .= " AND (m.nama_petshop LIKE '%$safe_key%' OR m.alamat LIKE '%$safe_key%')";
        }

        // Shuffle
        $sql .= " ORDER BY RAND() LIMIT 12";

        $result = $this->db->query($sql);
        
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
}
?>
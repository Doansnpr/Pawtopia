<?php
class CariModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil Mitra Baru (Slider)
    public function getHotArrivals() {
        $sql = "SELECT m.id_mitra, m.nama_petshop, m.foto_profil, m.tgl_daftar,
                (SELECT MIN(p.harga) FROM mitra_paket p WHERE p.id_mitra = m.id_mitra) as harga_mulai,
                (SELECT AVG(u.rating) FROM ulasan u 
                 JOIN booking b ON u.id_booking = b.id_booking 
                 WHERE b.id_mitra = m.id_mitra) as rating_rata
                FROM mitra m
                WHERE m.status = 'Terverifikasi' OR m.status = 'active'
                ORDER BY m.tgl_daftar DESC 
                LIMIT 10";
        
        $result = $this->db->query($sql);
        
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Tambahkan flag is_new untuk slider (Contoh: terdaftar 7 hari terakhir)
                $row['is_new'] = (strtotime($row['tgl_daftar']) > strtotime('-7 days'));
                $data[] = $row;
            }
        }
        return $data;
    }

    // Ambil Data Utama (Acak/Shuffle)
    public function getRandomMitra($keyword = null) {
        // ... (Kode ini sudah benar, tidak perlu diubah) ...
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

        if (!empty($keyword)) {
            $safe_key = $this->db->real_escape_string($keyword);
            $sql .= " AND (m.nama_petshop LIKE '%$safe_key%' OR m.alamat LIKE '%$safe_key%')";
        }

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
    
    // Ambil Detail Mitra Berdasarkan ID
    public function getMitraDetailById($id_mitra) {
        $id_mitra = $this->db->real_escape_string($id_mitra);

        // PERBAIKAN: Tambahkan lokasi_lat, lokasi_lng, dan pastikan tanda kurung SQL ditutup
        $sql_mitra = "SELECT m.id_mitra, m.nama_petshop, m.alamat, m.deskripsi, m.foto_profil, m.no_hp, m.lokasi_lat, m.lokasi_lng,
                      (SELECT AVG(u.rating) FROM ulasan u 
                       JOIN booking b ON u.id_booking = b.id_booking 
                       WHERE b.id_mitra = m.id_mitra) as rating_rata
                      FROM mitra m
                      WHERE m.id_mitra = '$id_mitra' AND (m.status = 'Terverifikasi' OR m.status = 'active')"; 
        
        $result_mitra = $this->db->query($sql_mitra);
        $mitra_data = $result_mitra ? $result_mitra->fetch_assoc() : null;

        if (!$mitra_data) {
            return null;
        }

        // Ambil Daftar Paket
        $sql_paket = "SELECT * FROM mitra_paket WHERE id_mitra = '$id_mitra' ORDER BY harga ASC";
        $result_paket = $this->db->query($sql_paket);
        $paket_data = [];
        if ($result_paket && $result_paket->num_rows > 0) {
            while ($row = $result_paket->fetch_assoc()) {
                $paket_data[] = $row;
            }
        }
        $mitra_data['paket'] = $paket_data;

        // Ambil Ulasan Terbaru (maksimal 3) - Sudah benar
        $sql_ulasan = "SELECT u.rating, u.komentar, c.nama_lengkap, 
                        (SELECT b_ulasan.balasan FROM balasan_ulasan b_ulasan WHERE b_ulasan.id_ulasan = u.id_ulasan LIMIT 1) as balasan_mitra
                      FROM ulasan u 
                      JOIN booking b ON u.id_booking = b.id_booking 
                      JOIN users us ON b.id_users = us.id_users 
                      JOIN customer c ON us.id_customer = c.id_customer
                      WHERE b.id_mitra = '$id_mitra' 
                      ORDER BY u.tgl_ulasan DESC LIMIT 3";
        $result_ulasan = $this->db->query($sql_ulasan);
        $ulasan_data = [];
        if ($result_ulasan && $result_ulasan->num_rows > 0) {
            while ($row = $result_ulasan->fetch_assoc()) {
                $ulasan_data[] = $row;
            }
        }
        $mitra_data['ulasan'] = $ulasan_data;

        return $mitra_data;
    }
}
?>
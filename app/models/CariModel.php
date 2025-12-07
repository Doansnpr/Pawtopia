<?php
class CariModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 1. Slider (Pendatang Baru)
    public function getHotArrivals() {
        $sql = "SELECT m.id_mitra, m.nama_petshop, m.alamat, m.foto_profil, m.tgl_daftar, m.status,
                (SELECT MIN(p.harga) FROM mitra_paket p WHERE p.id_mitra = m.id_mitra) as harga_mulai,
                (SELECT AVG(u.rating) FROM ulasan u 
                 JOIN booking b ON u.id_booking = b.id_booking 
                 WHERE b.id_mitra = m.id_mitra) as rating_rata,
                (SELECT COUNT(u.id_ulasan) FROM ulasan u 
                 JOIN booking b ON u.id_booking = b.id_booking 
                 WHERE b.id_mitra = m.id_mitra) as count_ulasan
                FROM mitra m
                -- SAYA HAPUS SYARAT STATUS DISINI BIAR MUNCUL SEMUA DULU
                WHERE 1=1 
                ORDER BY m.tgl_daftar DESC 
                LIMIT 10";
        
        $result = $this->db->query($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $is_recent = (strtotime($row['tgl_daftar']) > strtotime('-30 days'));
                $no_review = ($row['count_ulasan'] == 0);
                $row['is_new'] = ($is_recent && $no_review);
                $data[] = $row;
            }
        }
        return $data;
    }

    // 2. Grid (Jelajahi Mitra) - INI YANG KITA PAKSA MUNCUL SEMUA
    public function getRandomMitra($keyword = null) {
        $sql = "SELECT m.id_mitra, m.nama_petshop, m.alamat, m.foto_profil, m.status,
                
                -- Ambil Harga
                (SELECT MIN(p.harga) FROM mitra_paket p WHERE p.id_mitra = m.id_mitra) as harga_mulai,
                
                -- Ambil Rating (Subquery)
                (SELECT AVG(u.rating) FROM ulasan u 
                 JOIN booking b ON u.id_booking = b.id_booking 
                 WHERE b.id_mitra = m.id_mitra) as rating_rata,

                -- Ambil Komentar Terakhir
                (SELECT u.komentar FROM ulasan u 
                 JOIN booking b ON u.id_booking = b.id_booking 
                 WHERE b.id_mitra = m.id_mitra 
                 ORDER BY u.tgl_ulasan DESC LIMIT 1) as ulasan_terbaru

                FROM mitra m
                WHERE 1=1 "; // Trik agar query selalu benar

        if (!empty($keyword)) {
            $safe_key = $this->db->real_escape_string($keyword);
            $sql .= " AND (m.nama_petshop LIKE '%$safe_key%' OR m.alamat LIKE '%$safe_key%')";
        }

        // --- BAGIAN INI SAYA NON-AKTIFKAN AGAR SEMUA STATUS MUNCUL ---
        // $sql .= " AND (m.status = 'Terverifikasi' OR m.status = 'active') ";
        // -------------------------------------------------------------

        // SAYA HAPUS 'RAND()' DAN GANTI URUT ID BIAR POSISINYA GAK ILANG-ILANG
        $sql .= " ORDER BY m.id_mitra DESC "; 
        
        // LIMIT SAYA NAIKKAN JADI 100 BIAR DATA KE-8, KE-9 DST MUNCUL
        $sql .= " LIMIT 100";

        $result = $this->db->query($sql);
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
    
    // 3. Pop Up Detail
    public function getMitraDetailById($id_mitra) {
        $id_mitra = $this->db->real_escape_string($id_mitra);

        // A. Data Dasar
        $sql_mitra = "SELECT * FROM mitra WHERE id_mitra = '$id_mitra'"; 
        $result_mitra = $this->db->query($sql_mitra);
        
        if (!$result_mitra || $result_mitra->num_rows == 0) return null;
        $mitra_data = $result_mitra->fetch_assoc();

        // Hitung Rating
        $res_rating = $this->db->query("SELECT AVG(rating) as rating_rata FROM ulasan u JOIN booking b ON u.id_booking = b.id_booking WHERE b.id_mitra = '$id_mitra'");
        $mitra_data['rating_rata'] = ($res_rating) ? $res_rating->fetch_assoc()['rating_rata'] : 0;

        // B. Paket
        $mitra_data['paket'] = [];
        $sql_paket = "SELECT * FROM mitra_paket WHERE id_mitra = '$id_mitra' ORDER BY harga ASC";
        $result_paket = $this->db->query($sql_paket);
        if ($result_paket) {
            while ($row = $result_paket->fetch_assoc()) {
                $mitra_data['paket'][] = $row;
            }
        }

        // C. Ulasan (Fix Join Users agar tidak error)
        $mitra_data['ulasan'] = [];
        $sql_ulasan = "SELECT u.rating, u.komentar, u.tgl_ulasan, us.nama_lengkap,
                       (SELECT bu.balasan FROM balasan_ulasan bu WHERE bu.id_ulasan = u.id_ulasan LIMIT 1) as balasan_mitra
                       FROM ulasan u 
                       JOIN booking b ON u.id_booking = b.id_booking 
                       JOIN users us ON b.id_users = us.id_users 
                       WHERE b.id_mitra = '$id_mitra' 
                       ORDER BY u.tgl_ulasan DESC LIMIT 3";

        $result_ulasan = $this->db->query($sql_ulasan);
        if ($result_ulasan) {
            while ($row = $result_ulasan->fetch_assoc()) {
                $mitra_data['ulasan'][] = $row;
            }
        }

        return $mitra_data;
    }
}
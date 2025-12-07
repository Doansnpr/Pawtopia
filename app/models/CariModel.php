<?php

class CariModel {
    private $db; // Asumsikan $this->db adalah instance mysqli atau sejenisnya

    public function __construct($db) {
        $this->db = $db;
    }

    // --- FUNGSI UTAMA UNTUK HALAMAN DEPAN ---

    // Mengambil semua Mitra (untuk tampilan utama)
    public function getAllMitra($keyword = null) {
        $sql = "SELECT m.id_mitra, m.nama_petshop, m.alamat, m.foto_profil,
                (SELECT MIN(p.harga) FROM mitra_paket p WHERE p.id_mitra = m.id_mitra) as harga_mulai,
                (SELECT AVG(u.rating) FROM ulasan u 
                 JOIN booking b ON u.id_booking = b.id_booking 
                 WHERE b.id_mitra = m.id_mitra) as rating_rata
                FROM mitra m
                WHERE (m.status = 'Terverifikasi' OR m.status = 'active')";

        if (!empty($keyword)) {
            $safe_key = $this->db->real_escape_string($keyword);
            $sql .= " AND (m.nama_petshop LIKE '%$safe_key%' OR m.alamat LIKE '%$safe_key%')";
        }

        $sql .= " ORDER BY RAND() LIMIT 20"; // Batasi hasil pencarian

        $result = $this->db->query($sql);
        
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Mengambil Mitra Rekomendasi (Rating Terbaik)
    public function getTopRatedMitra() {
        $sql = "SELECT m.id_mitra, m.nama_petshop, m.foto_profil, m.alamat,
                (SELECT MIN(p.harga) FROM mitra_paket p WHERE p.id_mitra = m.id_mitra) as harga_mulai,
                (SELECT AVG(u.rating) FROM ulasan u 
                 JOIN booking b ON u.id_booking = b.id_booking 
                 WHERE b.id_mitra = m.id_mitra) as rating_rata
                FROM mitra m
                WHERE (m.status = 'Terverifikasi' OR m.status = 'active') 
                  AND m.id_mitra IN (
                      SELECT b.id_mitra FROM booking b
                      JOIN ulasan u ON b.id_booking = u.id_booking
                      GROUP BY b.id_mitra HAVING AVG(u.rating) >= 4
                  )
                ORDER BY rating_rata DESC, m.tgl_daftar DESC 
                LIMIT 5";
        
        $result = $this->db->query($sql);
        
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Mengambil Mitra Baru (Hot Arrivals)
    public function getHotArrivals() {
        $sql = "SELECT m.id_mitra, m.nama_petshop, m.foto_profil, m.tgl_daftar, m.alamat,
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
                $row['is_new'] = (strtotime($row['tgl_daftar']) > strtotime('-7 days'));
                $data[] = $row;
            }
        }
        return $data;
    }

    // --- FUNGSI UNTUK MODAL DETAIL (ENDPOINT AJAX) ---

    // Mengambil Detail Mitra, Paket, dan Ulasan Sekaligus
    public function getMitraDetailById($id_mitra) {
        $id_mitra = $this->db->real_escape_string($id_mitra);

        // 1. Ambil Detail Mitra dan Rata-rata Rating
        $sql_mitra = "SELECT m.id_mitra, m.nama_petshop, m.alamat, m.deskripsi, m.foto_profil, m.no_hp, m.lokasi_lat, m.lokasi_lng,
                      (SELECT AVG(u.rating) FROM ulasan u 
                       JOIN booking b ON u.id_booking = b.id_booking 
                       WHERE b.id_mitra = m.id_mitra) as rating_rata
                      FROM mitra m
                      WHERE m.id_mitra = '$id_mitra' AND (m.status = 'Terverifikasi' OR m.status = 'active')"; 
        
        $result_mitra = $this->db->query($sql_mitra);
        $mitra_data = $result_mitra ? $result_mitra->fetch_assoc() : null;

        if (!$mitra_data) {
            return null; // Mitra tidak ditemukan
        }

        // 2. Ambil Daftar Paket
        $sql_paket = "SELECT id_paket, nama_paket, harga FROM mitra_paket WHERE id_mitra = '$id_mitra' ORDER BY harga ASC";
        $result_paket = $this->db->query($sql_paket);
        $paket_data = [];
        if ($result_paket && $result_paket->num_rows > 0) {
            while ($row = $result_paket->fetch_assoc()) {
                $paket_data[] = $row;
            }
        }
        $mitra_data['paket'] = $paket_data; // Masukkan ke array utama

        // 3. Ambil Ulasan Terbaru (maksimal 3)
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
        $mitra_data['ulasan'] = $ulasan_data; // Masukkan ke array utama

        // Mengembalikan Mitra Detail, Paket, dan Ulasan dalam satu array
        return $mitra_data;
    }
}
?>
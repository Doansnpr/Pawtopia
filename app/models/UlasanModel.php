<?php

class UlasanModel {
    private $conn;
    private $table = 'ulasan';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get reviews specifically for a logged-in partner
    public function getUlasanByMitra($id_mitra) {
        // We join 'ulasan' with 'booking' to filter by id_mitra
        // We join with 'users' to get the customer's name and photo
        $query = "SELECT 
                    u.id_ulasan,
                    u.rating, 
                    u.komentar, 
                    u.tgl_ulasan, 
                    usr.nama_lengkap, 
                    usr.foto_profil
                  FROM ulasan u
                  JOIN booking b ON u.id_booking = b.id_booking
                  JOIN users usr ON b.id_users = usr.id_users
                  WHERE b.id_mitra = ?
                  ORDER BY u.tgl_ulasan DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    // Calculate average rating and total reviews for the dashboard statistics
    public function getAverageRating($id_mitra) {
        $query = "SELECT AVG(u.rating) as rata_rata, COUNT(u.id_ulasan) as total_ulasan
                  FROM ulasan u
                  JOIN booking b ON u.id_booking = b.id_booking
                  WHERE b.id_mitra = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
<?php

class BookingCustModel {
    private $conn;
    private $table = 'booking';

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Ambil Riwayat Booking milik Customer Login
    public function getMyBookings($id_user) {
        // Mengambil data booking + Nama Mitra (Petshop)
        $query = "SELECT b.*, u.nama_lengkap as nama_mitra, u.alamat as alamat_mitra
                  FROM booking b
                  LEFT JOIN users u ON b.id_mitra = u.id_users 
                  WHERE b.id_users = ? 
                  ORDER BY b.tgl_booking DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // 2. Ambil Daftar Mitra (Untuk Dropdown di Form Tambah)
    public function getAllMitra() {
        $query = "SELECT id_users, nama_lengkap, alamat FROM users WHERE role = 'Mitra'";
        $result = $this->conn->query($query);
        $mitras = [];
        while ($row = $result->fetch_assoc()) {
            $mitras[] = $row;
        }
        return $mitras;
    }

    // 3. Ambil Daftar Kucing Milik Customer (Untuk Pilihan Checkbox)
    public function getMyCats($id_user) {
        $query = "SELECT id_kucing, nama_kucing, ras FROM kucing WHERE id_users = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $cats = [];
        while ($row = $result->fetch_assoc()) {
            $cats[] = $row;
        }
        return $cats;
    }

    // 4. Proses Simpan Booking Online
    public function createOnlineBooking($id_user, $data) {
        $this->conn->begin_transaction();

        try {
            // A. Generate ID
            $id_booking = 'BKG-' . time() . rand(100, 999);
            $status_awal = 'Menunggu Konfirmasi'; // Status awal booking online

            // B. Simpan Header Booking
            $queryHeader = "INSERT INTO booking (id_booking, id_users, id_mitra, tgl_booking, tgl_mulai, tgl_selesai, paket, total_harga, status) 
                            VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?)";
            
            $stmtH = $this->conn->prepare($queryHeader);
            $stmtH->bind_param("ssssssis", 
                $id_booking, 
                $id_user, 
                $data['id_mitra'], 
                $data['tgl_mulai'], 
                $data['tgl_selesai'], 
                $data['paket'], 
                $data['total_harga'], 
                $status_awal
            );
            
            if (!$stmtH->execute()) throw new Exception("Gagal Simpan Header");

            // C. Simpan Detail Kucing & Lifecycle
            $stmtDetail = $this->conn->prepare("INSERT INTO detail_booking (id_detail, id_booking, id_kucing) VALUES (?, ?, ?)");
            $stmtLife   = $this->conn->prepare("INSERT INTO booking_lifecycle (id_booking, id_kucing, status) VALUES (?, ?, 'Menunggu Kedatangan')");

            foreach ($data['kucing'] as $index => $id_kucing) {
                // Generate ID Detail (DBK-)
                $id_detail = 'DBK-' . time() . $index . rand(10, 99); 
                
                // Insert Detail
                $stmtDetail->bind_param("sss", $id_detail, $id_booking, $id_kucing);
                if (!$stmtDetail->execute()) throw new Exception("Gagal Simpan Detail");

                // Insert Lifecycle (Agar muncul di dashboard mitra)
                $stmtLife->bind_param("ss", $id_booking, $id_kucing);
                if (!$stmtLife->execute()) throw new Exception("Gagal Simpan Lifecycle");
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // 5. Update Bukti Transfer
    public function uploadPayment($id_booking, $filename) {
        $query = "UPDATE booking SET bukti_pembayaran = ?, status = 'Verifikasi DP' WHERE id_booking = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $filename, $id_booking);
        return $stmt->execute();
    }
}
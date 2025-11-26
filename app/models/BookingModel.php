<?php

class BookingModel {
    private $conn;
    private $table = 'booking'; 
    private $userTable = 'users'; 
    private $catTable = 'kucing'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBookings($id_mitra) {
        $query = "SELECT 
                b.id_booking, 
                u.nama_lengkap, 
                b.tgl_booking, 
                b.tgl_mulai, 
                b.tgl_selesai, 
                b.paket,
                b.total_harga,
                b.status
            FROM 
                booking b 
            LEFT JOIN 
                users u ON b.id_users = u.id_users 
            WHERE 
                b.id_mitra = ?
            ORDER BY 
                b.tgl_mulai DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$result) { 
            return [];
        }

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            if (empty($row['nama_lengkap'])) {
                $row['nama_lengkap'] = 'Pelanggan (Data User Hilang)';
            }
            $bookings[] = $row;
        }
        
        return $bookings;
    }
    
    public function getStatusCounts($id_mitra) {
        $query = "SELECT status, COUNT(*) as count FROM {$this->table} WHERE id_mitra = ? GROUP BY status";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $counts = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['status']] = $row['count'];
            }
        }
        
        $default_statuses = [
            'Menunggu Konfirmasi' => 0, 
            'Menunggu DP' => 0, 
            'Verifikasi DP' => 0,
            'Aktif' => 0, 
            'Selesai' => 0, 
            'Dibatalkan' => 0 
        ];
        
        return array_merge($default_statuses, $counts);
    }

    public function getMitraIdByUserId($id_user) {
        $query = "SELECT id_mitra FROM mitra WHERE id_users = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['id_mitra'];
        }
        return null;
    }

    public function getPackagesByMitra($id_mitra) {
        $query = "SELECT * FROM mitra_paket WHERE id_mitra = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $packages = [];
        while ($row = $result->fetch_assoc()) {
            $packages[] = $row;
        }
        return $packages;
    }

    public function createOfflineBooking($userData, $catsData, $bookingData) {
        
        $this->conn->begin_transaction();

        try {
            // ==========================
            // 1. SIMPAN USER
            // ==========================
            $id_users_baru = 'USR-' . time() . rand(10, 99); 
            $email_dummy = 'offline_' . uniqid() . '@catcare.local';
            $password_dummy = password_hash('12345', PASSWORD_DEFAULT);

            $queryUser = "INSERT INTO users (id_users, nama_lengkap, email, password, no_hp, role) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtUser = $this->conn->prepare($queryUser);
            $stmtUser->bind_param("ssssss", $id_users_baru, $userData['nama_lengkap'], $email_dummy, $password_dummy, $userData['no_telp'], $userData['role']);
            if (!$stmtUser->execute()) throw new Exception("Gagal Simpan User: " . $stmtUser->error);
            $stmtUser->close();

            // ==========================
            // 2. SIMPAN KUCING
            // ==========================
            $list_id_kucing_tersimpan = []; // Array untuk menampung ID kucing yang sukses disimpan

            foreach ($catsData as $index => $cat) {
                $id_kucing = 'CAT-' . time() . $index . rand(10, 99); 
                $list_id_kucing_tersimpan[] = $id_kucing; // Simpan ID ke array untuk dipakai di langkah 4

                $nama = $cat['nama'] ?? 'Tanpa Nama';
                $ras = $cat['ras'] ?? '-';
                $jk = $cat['jenis_kelamin'] ?? 'Jantan';
                $umur = $cat['umur'] ?? 0;
                $ket = $cat['keterangan'] ?? '';

                $queryCat = "INSERT INTO kucing (id_kucing, id_users, nama_kucing, ras, jenis_kelamin, umur, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtCat = $this->conn->prepare($queryCat);
                $stmtCat->bind_param("sssssis", $id_kucing, $id_users_baru, $nama, $ras, $jk, $umur, $ket);
                
                if (!$stmtCat->execute()) throw new Exception("Gagal Simpan Kucing: " . $stmtCat->error);
                $stmtCat->close();
            }

            // ==========================
            // 3. SIMPAN BOOKING (HEADER)
            // ==========================
            // Perhatikan: Kolom id_kucing SUDAH DIHAPUS dari query ini
            $id_booking = 'BKG-' . time() . rand(100, 999);
            $id_mitra_session = $bookingData['id_mitra'];

            $queryBooking = "INSERT INTO booking (id_booking, id_users, id_mitra, tgl_booking, tgl_mulai, tgl_selesai, paket, total_harga, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmtBooking = $this->conn->prepare($queryBooking);
            $stmtBooking->bind_param("sssssssss", 
                $id_booking,
                $id_users_baru, 
                $id_mitra_session, 
                $bookingData['tgl_booking'], 
                $bookingData['tgl_mulai'], 
                $bookingData['tgl_selesai'], 
                $bookingData['paket'], 
                $bookingData['total_harga'], 
                $bookingData['status']
            );

            if (!$stmtBooking->execute()) throw new Exception("Gagal Simpan Booking: " . $stmtBooking->error);
            $stmtBooking->close();

            // ==========================
            // 4. SIMPAN DETAIL BOOKING
            // ==========================
            // Di sini kita masukkan data ke tabel baru 'detail_booking'
            
            $queryDetail = "INSERT INTO detail_booking (id_booking, id_kucing) VALUES (?, ?)";
            $stmtDetail = $this->conn->prepare($queryDetail);

            foreach ($list_id_kucing_tersimpan as $kucing_id) {
                // Masukkan ID Booking yang baru dibuat, dan ID Kucing dari array
                $stmtDetail->bind_param("ss", $id_booking, $kucing_id);
                
                if (!$stmtDetail->execute()) throw new Exception("Gagal Simpan Detail Booking: " . $stmtDetail->error);
            }
            $stmtDetail->close();

            // Jika semua lancar, Commit transaksi
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error CreateOfflineBooking: " . $e->getMessage());
            return false;
        }
    }

    public function getBookingDetail($id_booking) {
        // 1. Ambil Data Header Booking & User
        $queryBooking = "SELECT b.*, u.nama_lengkap, u.no_hp 
                        FROM booking b 
                        JOIN users u ON b.id_users = u.id_users 
                        WHERE b.id_booking = ?";
        
        $stmt = $this->conn->prepare($queryBooking);
        $stmt->bind_param("s", $id_booking);
        $stmt->execute();
        $resultBooking = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$resultBooking) return null; // Jika tidak ditemukan

        // 2. Ambil Data Kucing (Lewat tabel detail_booking)
        $queryCats = "SELECT k.* FROM kucing k 
                    JOIN detail_booking db ON k.id_kucing = db.id_kucing 
                    WHERE db.id_booking = ?";
                    
        $stmt2 = $this->conn->prepare($queryCats);
        $stmt2->bind_param("s", $id_booking);
        $stmt2->execute();
        $resultCats = $stmt2->get_result();
        
        $cats = [];
        while ($row = $resultCats->fetch_assoc()) {
            $cats[] = $row;
        }
        $stmt2->close();

        // Gabungkan data
        return [
            'booking' => $resultBooking,
            'kucing'  => $cats
        ];
    }

    public function updateStatusBooking($id_booking, $status_baru, $id_mitra)   {
        // Query update status, TAPI pastikan id_mitra-nya cocok (Security)
        $query = "UPDATE booking SET status = ? WHERE id_booking = ? AND id_mitra = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $status_baru, $id_booking, $id_mitra);
        
        if ($stmt->execute()) {
            // Cek apakah ada baris yang berubah (berarti ID booking & Mitra cocok)
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return true; 
            } else {
                // Jika 0, mungkin ID booking salah atau itu bukan milik mitra ini
                $stmt->close();
                return false;
            }
        } else {
            $stmt->close();
            return false;
        }
    }
}
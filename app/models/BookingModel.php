<?php

class BookingModel {
    private $conn;
    private $table = 'booking'; 
    private $userTable = 'users'; 
    private $catTable = 'kucing'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBookings() {
        $query = "
            SELECT 
                b.id_booking, 
                u.nama_lengkap,
                b.tgl_booking, 
                b.tgl_mulai, 
                b.tgl_selesai, 
                b.jumlah_kucing,
                b.paket,
                b.total_harga,
                b.id_kucing,
                b.status
            FROM 
                {$this->table} b
            JOIN 
                {$this->userTable} u ON b.id_users = u.id_users 
            ORDER BY 
                b.tgl_mulai DESC";

        $result = $this->conn->query($query);
        
        if (!$result) { 
            return [];
        }

        $bookings = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
            $result->free();
        }
        
        return $bookings;
    }
    
    public function getStatusCounts() {
        $query = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $result = $this->conn->query($query);
        
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

    //
    // --- METHOD BARU DI BAWAH INI ---
    //

    /**
     * Membuat ID unik (contoh sederhana)
     */
    private function generateId($prefix = '') {
        return $prefix . uniqid() . rand(100, 999);
    }

    /**
     * Menyimpan data booking offline lengkap dengan user baru dan kucing baru.
     * Menggunakan Transaksi Database.
     * @param array $userData Data untuk tabel 'users'
     * @param array $catsData Array berisi data kucing untuk tabel 'kucing'
     * @param array $bookingData Data untuk tabel 'booking'
     */
    public function createOfflineBooking($userData, $catsData, $bookingData) {
        
        // Mulai Transaksi
        $this->conn->begin_transaction();

        try {
            // 1. Buat User Baru (Dummy)
            $newUserId = $this->generateId('USR_');
            
            $stmtUser = $this->conn->prepare(
                "INSERT INTO {$this->userTable} (id_users, nama_lengkap, no_telp, email, password, role) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmtUser->bind_param("ssssss", 
                $newUserId, 
                $userData['nama_lengkap'], 
                $userData['no_telp'],
                $userData['email'],
                $userData['password'],
                $userData['role']
            );
            
            if (!$stmtUser->execute()) {
                throw new Exception("Gagal membuat user: " . $stmtUser->error);
            }
            $stmtUser->close();


            // 2. Simpan Data Kucing
            $catIds = [];
            $stmtCat = $this->conn->prepare(
                "INSERT INTO {$this->catTable} (id_kucing, id_users, nama_kucing, ras, umur, jenis_kelamin, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            
            foreach ($catsData as $cat) {
                $newCatId = $this->generateId('KUC_');
                $umur = !empty($cat['umur']) ? (int)$cat['umur'] : null;
                $ras = !empty($cat['ras']) ? $cat['ras'] : null;
                $ket = !empty($cat['keterangan']) ? $cat['keterangan'] : null;

                // Tipe data: id_kucing(s), id_users(s), nama_kucing(s), ras(s), umur(i), jenis_kelamin(s), keterangan(s)
                $stmtCat->bind_param("ssssiss",
                    $newCatId,
                    $newUserId, // Link ke user yang baru dibuat
                    $cat['nama_kucing'],
                    $ras,
                    $umur,
                    $cat['jenis_kelamin'],
                    $ket
                );

                if (!$stmtCat->execute()) {
                    throw new Exception("Gagal menyimpan kucing: " . $stmtCat->error);
                }
                $catIds[] = $newCatId; // Simpan ID kucing
            }
            $stmtCat->close();

            // 3. Simpan Data Booking
            $newBookingId = $this->generateId('BOK_');
            $firstCatId = $catIds[0] ?? null; // Ambil ID kucing pertama

            $stmtBooking = $this->conn->prepare(
                "INSERT INTO {$this->table} (id_booking, id_users, id_mitra, id_kucing, tgl_mulai, tgl_selesai, jumlah_kucing, paket, total_harga, status, tgl_booking) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            
            // Tipe data: id_booking(s), id_users(s), id_mitra(s), id_kucing(s), tgl_mulai(s), tgl_selesai(s), jumlah_kucing(i), paket(s), total_harga(d), status(s), tgl_booking(s)
            $stmtBooking->bind_param("ssssssisdss",
                $newBookingId,
                $newUserId, // Link ke user yang baru dibuat
                $bookingData['id_mitra'],
                $firstCatId, // Link ke kucing pertama
                $bookingData['tgl_mulai'],
                $bookingData['tgl_selesai'],
                $bookingData['jumlah_kucing'],
                $bookingData['paket'],
                $bookingData['total_harga'],
                $bookingData['status'],
                $bookingData['tgl_booking']
            );

            if (!$stmtBooking->execute()) {
                throw new Exception("Gagal menyimpan booking: "." ".$stmtBooking->error);
            }
            $stmtBooking->close();

            // Jika semua berhasil, commit transaksi
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Jika ada error, rollback
            $this->conn->rollback();
            // error_log($e->getMessage()); // Catat error untuk debugging
            return false;
        }
    }
}
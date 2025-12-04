<?php

class BookingModel {
    private $conn;
    private $table = 'booking'; 
    private $userTable = 'users'; 
    private $catTable = 'kucing'; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBookings($id_mitra, $search = null, $filterPayment = null, $limit = 10, $offset = 0) {
        
        // Query dasar (sama seperti sebelumnya)
        $query = "SELECT b.id_booking, u.nama_lengkap, b.tgl_booking, b.tgl_mulai, b.tgl_selesai, b.paket, b.total_harga, b.status,
                (SELECT COUNT(*) FROM pembayaran p WHERE p.id_booking = b.id_booking AND p.status_pembayaran = 'Lunas') as count_lunas
                FROM booking b 
                LEFT JOIN users u ON b.id_users = u.id_users 
                WHERE b.id_mitra = ?";

        $params = [$id_mitra];
        $types  = "s";

        // Logika Search
        if (!empty($search)) {
            $query .= " AND (u.nama_lengkap LIKE ? OR b.id_booking LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params[] = $searchTerm; $params[] = $searchTerm; $types .= "ss";
        }

        // Logika Filter
        if ($filterPayment === 'lunas') {
            $query .= " HAVING count_lunas > 0";
        } elseif ($filterPayment === 'dp') {
            $query .= " HAVING count_lunas = 0";
        }

        // Sorting
        $query .= " ORDER BY b.tgl_booking DESC";

        // --- TAMBAHAN PAGINATION (LIMIT & OFFSET) ---
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii"; // integer integer

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            if (empty($row['nama_lengkap'])) $row['nama_lengkap'] = 'Pelanggan (Offline/Data Hilang)';
            $row['status_bayar_text'] = ($row['count_lunas'] > 0) ? 'Lunas' : 'Belum Lunas';
            $bookings[] = $row;
        }
        return $bookings;
    }

    public function countAllBookings($id_mitra, $search = null, $filterPayment = null) {
        // Query hampir sama, tapi SELECT COUNT(*)
        // Note: Karena ada HAVING (filter lunas), kita bungkus query utamanya
        
        $query = "SELECT COUNT(*) as total FROM (
                    SELECT b.id_booking,
                    (SELECT COUNT(*) FROM pembayaran p WHERE p.id_booking = b.id_booking AND p.status_pembayaran = 'Lunas') as count_lunas
                    FROM booking b 
                    LEFT JOIN users u ON b.id_users = u.id_users 
                    WHERE b.id_mitra = ? ";

        $params = [$id_mitra];
        $types  = "s";

        if (!empty($search)) {
            $query .= " AND (u.nama_lengkap LIKE ? OR b.id_booking LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params[] = $searchTerm; $params[] = $searchTerm; $types .= "ss";
        }
        
        $query .= ") as subquery"; // Tutup subquery utama

        if ($filterPayment === 'lunas') {
            $query .= " WHERE count_lunas > 0";
        } elseif ($filterPayment === 'dp') {
            $query .= " WHERE count_lunas = 0";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    public function createPelunasan($id_booking) {
        // Ambil info tagihan
        $stmtInfo = $this->conn->prepare("SELECT total_harga FROM booking WHERE id_booking = ?");
        $stmtInfo->bind_param("s", $id_booking);
        $stmtInfo->execute();
        $info = $stmtInfo->get_result()->fetch_assoc();
        $total = $info['total_harga'] ?? 0;
        $stmtInfo->close();

        // Data Insert
        $id_pembayaran = 'PAY-' . time() . rand(100, 999);
        $jenis = 'Pelunasan';
        $status_pem = 'Lunas'; // <--- Sesuai Kolom status_pembayaran
        $tgl = date('Y-m-d H:i:s');
        $bukti = ''; // Kosong string

        // PERHATIKAN: Kolomnya 'status_pembayaran' (sesuai gambar Anda)
        $query = "INSERT INTO pembayaran (id_pembayaran, id_booking, jumlah, jenis_pembayaran, bukti_transfer, status_pembayaran, tgl_bayar) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssdssss", $id_pembayaran, $id_booking, $total, $jenis, $bukti, $status_pem, $tgl);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function createOfflineBooking($userData, $catsData, $bookingData) {
        $this->conn->begin_transaction();
        try {
            // A. User Dummy
            $id_users_baru = 'USR-' . time() . rand(10, 99); 
            $email_dummy = 'off_' . uniqid() . '@cat.local'; 
            $pass = password_hash('123', PASSWORD_DEFAULT);
            $stmtUser = $this->conn->prepare("INSERT INTO users (id_users, nama_lengkap, email, password, no_hp, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmtUser->bind_param("ssssss", $id_users_baru, $userData['nama_lengkap'], $email_dummy, $pass, $userData['no_telp'], $userData['role']);
            $stmtUser->execute(); $stmtUser->close();

            // B. Kucing
            $list_id_kucing = [];
            $stmtCat = $this->conn->prepare("INSERT INTO kucing (id_kucing, id_users, nama_kucing, ras, jenis_kelamin, umur, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            foreach ($catsData as $i => $cat) {
                $id_kucing = 'CAT-' . time() . $i . rand(10, 99);
                $list_id_kucing[] = $id_kucing;
                
                // --- LOGIKA BARU: GABUNGKAN ANGKA & SATUAN ---
                // Cek apakah ada input angka & satuan, jika tidak set default '-'
                $angka = $cat['umur_angka'] ?? '0';
                $satuan = $cat['umur_satuan'] ?? 'Tahun';
                
                // Gabungkan jadi string (Contoh: "2 Tahun" atau "6 Bulan")
                $umur_fix = $angka . ' ' . $satuan; 
                
                $ras_kucing = $cat['ras'] ?? '-'; 
                
                // Bind parameter (Perhatikan variabel $umur_fix)
                $stmtCat->bind_param("sssssis", $id_kucing, $id_users_baru, $cat['nama'], $ras_kucing, $cat['jenis_kelamin'], $umur_fix, $cat['keterangan']);
                $stmtCat->execute();
            }
            $stmtCat->close();

            // C. Booking (Status Proses: Aktif)
            $id_booking = 'BKG-' . time() . rand(100, 999);
            $stmtBook = $this->conn->prepare("INSERT INTO booking (id_booking, id_users, id_mitra, tgl_booking, tgl_mulai, tgl_selesai, paket, total_harga, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtBook->bind_param("sssssssss", $id_booking, $id_users_baru, $bookingData['id_mitra'], $bookingData['tgl_booking'], $bookingData['tgl_mulai'], $bookingData['tgl_selesai'], $bookingData['paket'], $bookingData['total_harga'], $bookingData['status']);
            $stmtBook->execute(); $stmtBook->close();

            // D. Pembayaran (Status Pembayaran: Lunas)
            $id_pay = 'PAY-' . time() . rand(100, 999);
            $jenis = 'Pelunasan';
            $status_pem = 'Lunas'; // <--- Sesuai Kolom status_pembayaran
            $tgl = date('Y-m-d H:i:s');
            $bukti = '';

            // PERHATIKAN: Kolomnya 'status_pembayaran'
            $stmtPay = $this->conn->prepare("INSERT INTO pembayaran (id_pembayaran, id_booking, jumlah, jenis_pembayaran, bukti_transfer, status_pembayaran, tgl_bayar) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtPay->bind_param("ssdssss", $id_pay, $id_booking, $bookingData['total_harga'], $jenis, $bukti, $status_pem, $tgl);
            $stmtPay->execute(); $stmtPay->close();

            // E. Detail & Lifecycle
            $stmtDet = $this->conn->prepare("INSERT INTO detail_booking (id_detail, id_booking, id_kucing) VALUES (?, ?, ?)");
            $stmtLif = $this->conn->prepare("INSERT INTO booking_lifecycle (id_booking, id_kucing, status) VALUES (?, ?, ?)");
            $stmtLog = $this->conn->prepare("INSERT INTO activity_log (id_booking, id_kucing, jenis_aktivitas) VALUES (?, ?, ?)");
            $statInit = 'Check-In';

            foreach ($list_id_kucing as $i => $kid) {
                $id_det = 'DBK-' . time() . $i . rand(10,99);
                $stmtDet->bind_param("sss", $id_det, $id_booking, $kid); $stmtDet->execute();
                $stmtLif->bind_param("sss", $id_booking, $kid, $statInit); $stmtLif->execute();
                $stmtLog->bind_param("sss", $id_booking, $kid, $statInit); $stmtLog->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
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
            'DP Ditolak' => 0,
            'Aktif' => 0,
            'Selesai' => 0,
            'Dibatalkan' => 0,
            'Booking Ditolak' => 0
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

    public function getBookingDetail($id_booking) {
        // 1. Ambil Data Booking & User
        $queryBooking = "SELECT b.*, u.nama_lengkap, u.no_hp 
                     FROM booking b 
                     LEFT JOIN users u ON b.id_users = u.id_users 
                     WHERE b.id_booking = ?";
        
        $stmt = $this->conn->prepare($queryBooking);
        $stmt->bind_param("s", $id_booking);
        $stmt->execute();
        $resultBooking = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$resultBooking) return null;

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

        // Gabungkan jadi satu array
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
<?php

class BookingCustModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- GET DATA (Tidak Berubah) ---
    public function getMyBookings($id_user) {
        $query = "SELECT b.*, m.nama_petshop as nama_mitra, m.alamat as alamat_mitra,
                          GROUP_CONCAT(k.foto_kucing SEPARATOR ',') as foto_kucing_list
                  FROM booking b
                  JOIN mitra m ON b.id_mitra = m.id_mitra 
                  LEFT JOIN detail_booking db ON b.id_booking = db.id_booking
                  LEFT JOIN kucing k ON db.id_kucing = k.id_kucing
                  WHERE b.id_users = ? AND b.status != 'Dibatalkan' 
                  GROUP BY b.id_booking ORDER BY b.tgl_booking DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function getBookingById($id_booking) {
        $qHead = "SELECT b.*, m.nama_petshop, m.alamat FROM booking b JOIN mitra m ON b.id_mitra = m.id_mitra WHERE b.id_booking = ?";
        $stmt = $this->conn->prepare($qHead);
        $stmt->bind_param("s", $id_booking);
        $stmt->execute();
        $booking = $stmt->get_result()->fetch_assoc();

        $qCat = "SELECT k.* FROM kucing k JOIN detail_booking db ON k.id_kucing = db.id_kucing WHERE db.id_booking = ?";
        $stmt2 = $this->conn->prepare($qCat);
        $stmt2->bind_param("s", $id_booking);
        $stmt2->execute();
        $resCat = $stmt2->get_result();
        $cats = [];
        while($row = $resCat->fetch_assoc()) $cats[] = $row;

        return ['booking' => $booking, 'cats' => $cats];
    }

    public function getAllMitra() {
        return $this->conn->query("SELECT id_mitra, nama_petshop, alamat FROM mitra")->fetch_all(MYSQLI_ASSOC);
    }

    public function getPackagesByMitra($id_mitra) {
        $stmt = $this->conn->prepare("SELECT * FROM mitra_paket WHERE id_mitra = ?");
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getMyCats($id_user) {
        $stmt = $this->conn->prepare("SELECT * FROM kucing WHERE id_users = ?");
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // --- TRANSAKSI ---

    // 1. CREATE BOOKING (AWAL)
    public function createOnlineBooking($id_user, $bookingData, $catsArray) {
        $this->conn->begin_transaction();
        try {
            // A. Insert Booking Header
            $id_booking = 'BKG-' . time() . rand(100, 999);
            $status = 'Menunggu Konfirmasi'; 

            $stmtH = $this->conn->prepare("INSERT INTO booking (id_booking, id_users, id_mitra, tgl_booking, tgl_mulai, tgl_selesai, paket, total_harga, status) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?)");
            $stmtH->bind_param("ssssssis", $id_booking, $id_user, $bookingData['id_mitra'], $bookingData['tgl_mulai'], $bookingData['tgl_selesai'], $bookingData['paket'], $bookingData['total_harga'], $status);
            $stmtH->execute();

            // B. Insert Kucing (WAJIB DISINI agar Admin bisa lihat datanya nanti)
            $stmtCat = $this->conn->prepare("INSERT INTO kucing (id_kucing, id_users, nama_kucing, ras, jenis_kelamin, umur, keterangan, foto_kucing) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtDet = $this->conn->prepare("INSERT INTO detail_booking (id_detail, id_booking, id_kucing) VALUES (?, ?, ?)");
            $stmtLif = $this->conn->prepare("INSERT INTO booking_lifecycle (id_booking, id_kucing, status) VALUES (?, ?, 'Menunggu Kedatangan')");

            foreach ($catsArray as $i => $cat) {
                // Generate ID Unik
                $id_kucing = 'CAT-' . time() . $i . rand(10, 99);
                $foto = $cat['foto'] ?? 'default_cat.png';
                
                // Simpan ke Tabel Kucing
                $stmtCat->bind_param("ssssssss", $id_kucing, $id_user, $cat['nama'], $cat['ras'], $cat['gender'], $cat['umur'], $cat['keterangan'], $foto);
                $stmtCat->execute();

                // Hubungkan di Detail Booking
                $id_detail = 'DBK-' . time() . $i . rand(10, 99);
                $stmtDet->bind_param("sss", $id_detail, $id_booking, $id_kucing);
                $stmtDet->execute();

                $stmtLif->bind_param("ss", $id_booking, $id_kucing);
                $stmtLif->execute();
            }

            // C. Placeholder Pembayaran
            $id_pembayaran = 'PAY-' . time() . rand(100, 999);
            $status_bayar_awal = 'Belum Bayar'; 
            $nol = 0; $null_val = null;

            $stmtPay = $this->conn->prepare("INSERT INTO pembayaran (id_pembayaran, id_booking, jumlah, jenis_pembayaran, bukti_transfer, status_pembayaran, tgl_bayar) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtPay->bind_param("ssdssss", $id_pembayaran, $id_booking, $nol, $null_val, $null_val, $status_bayar_awal, $null_val);
            $stmtPay->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) { $this->conn->rollback(); return false; }
    }

    // 2. CANCEL BOOKING (INI KUNCINYA: HAPUS KUCING JIKA BATAL)
    public function cancelBooking($id_booking) {
        $this->conn->begin_transaction();
        try {
            // 1. Ambil daftar ID Kucing yang ada di booking ini
            $ids = [];
            $res = $this->conn->query("SELECT id_kucing FROM detail_booking WHERE id_booking = '$id_booking'");
            while($row = $res->fetch_assoc()) {
                $ids[] = $row['id_kucing'];
            }

            // 2. Update Status Booking jadi Dibatalkan
            $stmt = $this->conn->prepare("UPDATE booking SET status = 'Dibatalkan' WHERE id_booking = ?");
            $stmt->bind_param("s", $id_booking);
            $stmt->execute();

            // 3. HAPUS Data Kucing agar database tidak kotor
            if (!empty($ids)) {
                // Siapkan query hapus
                $stmtDelLif = $this->conn->prepare("DELETE FROM booking_lifecycle WHERE id_booking = ? AND id_kucing = ?");
                $stmtDelDet = $this->conn->prepare("DELETE FROM detail_booking WHERE id_booking = ? AND id_kucing = ?");
                $stmtDelCat = $this->conn->prepare("DELETE FROM kucing WHERE id_kucing = ?");

                foreach($ids as $id_kucing) {
                    // Hapus Lifecycle
                    $stmtDelLif->bind_param("ss", $id_booking, $id_kucing);
                    $stmtDelLif->execute();
                    
                    // Hapus Detail Booking
                    $stmtDelDet->bind_param("ss", $id_booking, $id_kucing);
                    $stmtDelDet->execute();

                    // Hapus Master Kucing (Agar tidak jadi sampah)
                    // PENTING: Pastikan ini hanya menghapus kucing yang baru dibuat untuk booking ini
                    $stmtDelCat->bind_param("s", $id_kucing);
                    $stmtDelCat->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // Tambahkan method ini di dalam BookingCustModel
    public function getKapasitasMitra($id_mitra) {
        $query = "SELECT kapasitas, nama_petshop FROM mitra WHERE id_mitra = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result; // Mengembalikan array ['kapasitas' => 10, 'nama_petshop' => '...']
    }
    // 3. UPLOAD / PROCESS PAYMENT (Hanya Update Status)
    public function processPayment($id_booking, $data, $filename) {
        $this->conn->begin_transaction();
        try {
            $tgl_bayar = date('Y-m-d H:i:s');
            // Logika Pelunasan/DP
            $status_pembayaran = ($data['jenis_pembayaran'] === 'Pelunasan') ? 'Lunas' : 'Belum Lunas';

            // Cek apakah data pembayaran sudah ada placeholder-nya
            $check = $this->conn->query("SELECT id_pembayaran FROM pembayaran WHERE id_booking = '$id_booking'");
            
            if ($check->num_rows > 0) {
                // Update
                $sql = "UPDATE pembayaran SET jumlah=?, jenis_pembayaran=?, bukti_transfer=?, status_pembayaran=?, tgl_bayar=? WHERE id_booking=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("dsssss", $data['jumlah_bayar'], $data['jenis_pembayaran'], $filename, $status_pembayaran, $tgl_bayar, $id_booking);
            } else {
                // Insert Baru (jika belum ada)
                $id_pay = 'PAY-' . time() . rand(100, 999);
                $sql = "INSERT INTO pembayaran (id_pembayaran, id_booking, jumlah, jenis_pembayaran, bukti_transfer, status_pembayaran, tgl_bayar) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ssdssss", $id_pay, $id_booking, $data['jumlah_bayar'], $data['jenis_pembayaran'], $filename, $status_pembayaran, $tgl_bayar);
            }
            $stmt->execute();

            // Update Status Booking agar Admin tahu perlu verifikasi
            $stmtBook = $this->conn->prepare("UPDATE booking SET status = 'Verifikasi DP' WHERE id_booking = ?");
            $stmtBook->bind_param("s", $id_booking);
            $stmtBook->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) { $this->conn->rollback(); return false; }
    }

    // 2. UPDATE BOOKING
    public function updateBooking($id_booking, $bookingData, $catsArray) {
        $this->conn->begin_transaction();
        try {
            $stmtH = $this->conn->prepare("UPDATE booking SET tgl_mulai=?, tgl_selesai=?, paket=?, total_harga=? WHERE id_booking=?");
            $stmtH->bind_param("sssis", $bookingData['tgl_mulai'], $bookingData['tgl_selesai'], $bookingData['paket'], $bookingData['total_harga'], $id_booking);
            $stmtH->execute();

            // Logic Hapus Kucing yang Dibuang
            $existingIds = [];
            $resCheck = $this->conn->query("SELECT id_kucing FROM detail_booking WHERE id_booking = '$id_booking'");
            while ($row = $resCheck->fetch_assoc()) $existingIds[] = $row['id_kucing'];

            $submittedIds = [];
            foreach ($catsArray as $cat) if (!empty($cat['id_kucing'])) $submittedIds[] = $cat['id_kucing'];

            $idsToDelete = array_diff($existingIds, $submittedIds);
            if (!empty($idsToDelete)) {
                $stmtDelLife = $this->conn->prepare("DELETE FROM booking_lifecycle WHERE id_booking = ? AND id_kucing = ?");
                $stmtDelDet  = $this->conn->prepare("DELETE FROM detail_booking WHERE id_booking = ? AND id_kucing = ?");
                $stmtDelCat  = $this->conn->prepare("DELETE FROM kucing WHERE id_kucing = ?"); 
                foreach ($idsToDelete as $delId) {
                    $stmtDelLife->bind_param("ss", $id_booking, $delId); $stmtDelLife->execute();
                    $stmtDelDet->bind_param("ss", $id_booking, $delId); $stmtDelDet->execute();
                    $stmtDelCat->bind_param("s", $delId); $stmtDelCat->execute();
                }
            }

            // Update/Insert Kucing
            $stmtCatUpd = $this->conn->prepare("UPDATE kucing SET nama_kucing=?, ras=?, jenis_kelamin=?, umur=?, keterangan=? WHERE id_kucing=?");
            $stmtFoto = $this->conn->prepare("UPDATE kucing SET foto_kucing=? WHERE id_kucing=?");
            $stmtNewCat = $this->conn->prepare("INSERT INTO kucing (id_kucing, id_users, nama_kucing, ras, jenis_kelamin, umur, keterangan, foto_kucing) VALUES (?, (SELECT id_users FROM booking WHERE id_booking=?), ?, ?, ?, ?, ?, ?)");
            $stmtNewDet = $this->conn->prepare("INSERT INTO detail_booking (id_detail, id_booking, id_kucing) VALUES (?, ?, ?)");
            $stmtNewLif = $this->conn->prepare("INSERT INTO booking_lifecycle (id_booking, id_kucing, status) VALUES (?, ?, 'Menunggu Kedatangan')");

            foreach ($catsArray as $index => $cat) {
                if (!empty($cat['id_kucing'])) {
                    $stmtCatUpd->bind_param("ssssss", $cat['nama'], $cat['ras'], $cat['gender'], $cat['umur'], $cat['keterangan'], $cat['id_kucing']);
                    $stmtCatUpd->execute();
                    if (!empty($cat['foto'])) {
                        $stmtFoto->bind_param("ss", $cat['foto'], $cat['id_kucing']);
                        $stmtFoto->execute();
                    }
                } else {
                    $newIdKucing = 'CAT-' . time() . $index . rand(100, 999);
                    $newIdDetail = 'DBK-' . time() . $index . rand(100, 999);
                    $foto = $cat['foto'] ?? 'default_cat.png';
                    $stmtNewCat->bind_param("ssssssss", $newIdKucing, $id_booking, $cat['nama'], $cat['ras'], $cat['gender'], $cat['umur'], $cat['keterangan'], $foto);
                    $stmtNewCat->execute();
                    $stmtNewDet->bind_param("sss", $newIdDetail, $id_booking, $newIdKucing);
                    $stmtNewDet->execute();
                    $stmtNewLif->bind_param("ss", $id_booking, $newIdKucing);
                    $stmtNewLif->execute();
                }
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) { $this->conn->rollback(); return false; }
    }


    public function uploadPayment($id_booking, $filename) {
        $stmt = $this->conn->prepare("UPDATE booking SET bukti_pembayaran = ?, status = 'Verifikasi DP' WHERE id_booking = ?");
        $stmt->bind_param("ss", $filename, $id_booking);
        return $stmt->execute();
    }

    
}
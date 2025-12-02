<?php
class BookingCustModel {
    private $conn;
    private $table = 'booking';
    private $userTable = 'users';
    private $mitraTable = 'mitra';
    private $catTable = 'kucing';
    private $detailBookingTable = 'detail_booking';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Ambil semua booking user (untuk halaman customer)
     */
    public function getBookingsByUser($id_user) {
        $query = "
            SELECT 
                b.id_booking,
                m.nama_petshop AS tempat_penitipan,
                b.tgl_mulai AS check_in,
                b.tgl_selesai AS check_out,
                mp.nama_paket AS paket,
                b.total_harga,
                b.status,
                COUNT(db.id_kucing) AS cats,
                GROUP_CONCAT(k.nama_kucing SEPARATOR ', ') AS nama_kucing_list
            FROM 
                {$this->table} b
            JOIN 
                {$this->mitraTable} m ON b.id_mitra = m.id_mitra
            LEFT JOIN 
                mitra_paket mp ON b.id_paket = mp.id_paket  -- Pastikan kolom `id_paket` ada di tabel `booking`
            LEFT JOIN 
                {$this->detailBookingTable} db ON b.id_booking = db.id_booking
            LEFT JOIN 
                {$this->catTable} k ON db.id_kucing = k.id_kucing
            WHERE 
                b.id_users = ?
            GROUP BY 
                b.id_booking
            ORDER BY 
                b.tgl_mulai DESC";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) return [];

        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $row['total_harga_formatted'] = 'Rp ' . number_format($row['total_harga'], 0, ',', '.');
            $bookings[] = $row;
        }

        $stmt->close();
        return $bookings;
    }

    // ... di dalam class BookingCustModel

// ⬇⬇ Tambahkan fungsi ini di dalam class BookingCustModel ⬇⬇
public function getActiveMitras() {
    $query = "SELECT id_mitra, nama_petshop 
              FROM {$this->mitraTable} 
              WHERE status = 'active' OR status = 1
              ORDER BY nama_petshop ASC"; // Tambahkan ORDER BY agar rapi

    $stmt = $this->conn->prepare($query);
    if (!$stmt) return [];

    $stmt->execute();
    $result = $stmt->get_result();

    $mitras = [];
    while ($row = $result->fetch_assoc()) {
        $mitras[] = $row;
    }

    $stmt->close();
    return $mitras;
}

// ... fungsi-fungsi lain yang sudah ada

    /**
     * Ambil paket berdasarkan mitra
     */
    public function getPackagesByMitra($id_mitra) {
        $query = "SELECT id_paket, nama_paket, harga FROM mitra_paket WHERE id_mitra = ? ORDER BY harga ASC";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return [];

        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result();

        $packages = [];
        while ($row = $result->fetch_assoc()) {
            $row['harga_formatted'] = 'Rp ' . number_format($row['harga'], 0, ',', '.');
            $packages[] = $row;
        }

        $stmt->close();
        return $packages;
    }

    /**
     * Ambil harga paket
     */
    public function getPriceByPackageId($id_paket) {
        $query = "SELECT harga FROM mitra_paket WHERE id_paket = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return 0;

        $stmt->bind_param("s", $id_paket);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['harga'] ?? 0;
    }

    /**
     * Buat booking baru (transaksional)
     */
    public function createBooking($id_user, $id_mitra, $tgl_mulai, $tgl_selesai, $id_paket, $total_harga, $cats) {
        $this->conn->begin_transaction();

        try {
            $id_booking = $this->generateUniqueId('BK');

            // Ambil harga aktual dari database untuk validasi
            $harga_db = $this->getPriceByPackageId($id_paket);
            if ($harga_db <= 0) {
                throw new Exception("Paket tidak valid atau harga tidak ditemukan.");
            }

            // Hitung hari (opsional: validasi)
            $days = max(1, (strtotime($tgl_selesai) - strtotime($tgl_mulai)) / (60 * 60 * 24));
            $expected_total = $days * $harga_db;
            // Anda bisa bandingkan $total_harga vs $expected_total jika perlu

            $query = "
                INSERT INTO {$this->table} 
                (id_booking, id_users, id_mitra, id_paket, tgl_mulai, tgl_selesai, paket, total_harga, status, tgl_booking) 
                VALUES (?, ?, ?, ?, ?, ?, (SELECT nama_paket FROM mitra_paket WHERE id_paket = ?), ?, 'Menunggu Konfirmasi', NOW())";

            $stmt = $this->conn->prepare($query);
            $paket_name = '';
            $stmt->bind_param(
                "ssssssds",
                $id_booking,
                $id_user,
                $id_mitra,
                $id_paket,
                $tgl_mulai,
                $tgl_selesai,
                $id_paket, // untuk subquery
                $total_harga
            );
            $stmt->execute();
            $stmt->close();

            // Simpan kucing
            foreach ($cats as $cat) {
                // Cek duplikat
                $existing = $this->findCatByName($cat['nama'], $id_user);
                $id_kucing = $existing ? $existing['id_kucing'] : $this->generateUniqueId('CT');

                if (!$existing) {
                    $query_cat = "
                        INSERT INTO {$this->catTable} 
                        (id_kucing, id_users, nama_kucing, ras, umur, jenis_kelamin, keterangan) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt_cat = $this->conn->prepare($query_cat);
                    $stmt_cat->bind_param(
                        "ssssiss",
                        $id_kucing,
                        $id_user,
                        $cat['nama'],
                        $cat['ras'],
                        $cat['umur'],
                        $cat['jenis_kelamin'],
                        $cat['keterangan']
                    );
                    $stmt_cat->execute();
                    $stmt_cat->close();
                }

                // Simpan ke detail_booking
                $id_detail = $this->generateUniqueId('DB');
                $query_db = "
                    INSERT INTO {$this->detailBookingTable} 
                    (id_detail, id_booking, id_kucing) 
                    VALUES (?, ?, ?)";
                $stmt_db = $this->conn->prepare($query_db);
                $stmt_db->bind_param("sss", $id_detail, $id_booking, $id_kucing);
                $stmt_db->execute();
                $stmt_db->close();
            }

            $this->conn->commit();
            return ['success' => true, 'id_booking' => $id_booking];

        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function findCatByName($nama, $id_user) {
        $query = "SELECT id_kucing FROM {$this->catTable} WHERE nama_kucing = ? AND id_users = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $nama, $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    private function generateUniqueId($prefix) {
        return $prefix . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }

    public function getTotalSpending($id_user) {
        $query = "SELECT COALESCE(SUM(total_harga), 0) FROM {$this->table} WHERE id_users = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        $stmt->close();
        return $row[0] ?? 0;
    }

    public function getTotalBookings($id_user) {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE id_users = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        $stmt->close();
        return $row[0] ?? 0;
    }
}
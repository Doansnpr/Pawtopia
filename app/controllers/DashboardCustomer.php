<?php

class DashboardCustomer extends Controller {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['user']['id_users'] ?? null;

        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $koneksi = new mysqli("localhost", "root", "", "pawtopia");
        if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);

        // sanitize id_user untuk query
        $id_user_safe = $koneksi->real_escape_string($id_user);

        // Ambil nama user (cek hasil dulu)
        $query_user = $koneksi->query("SELECT nama_lengkap FROM users WHERE id_users = '$id_user_safe'");
        $user = null;
        if ($query_user && $query_user->num_rows > 0) {
            $user = $query_user->fetch_assoc();
        }

        // Query booking terakhir yang BELUM selesai
       $query_booking = $koneksi->query("
    SELECT b.id_kucing, m.nama_mitra AS tempat_penitipan, 
           b.tgl_booking AS tanggal_penitipan, b.status
    FROM booking b
    JOIN mitra m ON b.id_mitra = m.id_mitra
    WHERE b.id_users = '$id_user'
      AND TRIM(LOWER(b.status)) NOT IN ('selesai', 'dibatalkan')
    ORDER BY b.tgl_booking DESC
    LIMIT 1
");

        $booking = null;
        if ($query_booking && $query_booking->num_rows > 0) {
            $booking = $query_booking->fetch_assoc();
        }

        // TOTAL PENGELUARAN
        $query_pengeluaran = $koneksi->query("
            SELECT SUM(total_harga) AS total_pengeluaran
            FROM booking
            WHERE id_users = '$id_user_safe'
        ");
        $pengeluaran = null;
        $total_pengeluaran = 0;
        if ($query_pengeluaran && $query_pengeluaran->num_rows >= 0) {
            $pengeluaran = $query_pengeluaran->fetch_assoc();
            $total_pengeluaran = $pengeluaran['total_pengeluaran'] ?? 0;
        }

        // JUMLAH KUCING YANG SEDANG DITITIPKAN (status != Selesai)
        $query_jumlah_kucing = $koneksi->query("
    SELECT COUNT(*) AS jumlah
    FROM booking
    WHERE id_users = '$id_user'
      AND TRIM(LOWER(status)) NOT IN ('selesai', 'dibatalkan')
");
$jumlah_kucing = $query_jumlah_kucing->fetch_assoc()['jumlah'] ?? 0;


        if ($query_jumlah_kucing && $query_jumlah_kucing->num_rows > 0) {
            $countRow = $query_jumlah_kucing->fetch_assoc();
            $jumlah_kucing = (int)($countRow['jumlah'] ?? 0);
        }

        $data = [
            'title' => 'Dashboard',
            'content' => 'dashboard_customer/index',
            'nama_pengguna' => $user['nama_lengkap'] ?? 'Pengguna',
            'nama_kucing' => $booking['id_kucing'] ?? '-',
            'tgl_booking' => $booking['tanggal_penitipan'] ?? '-',
            'status' => $booking['status'] ?? '-',
            'pengeluaran' => 'Rp ' . number_format($total_pengeluaran, 0, ',', '.'),
            'id_user' => $id_user,
            'jumlah_kucing' => $jumlah_kucing
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    // Halaman Ulasan
    public function ulasan() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $id_user = $_SESSION['user']['id_users'] ?? null;
        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $koneksi = new mysqli("localhost", "root", "", "pawtopia");
        if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);

        $id_user_safe = $koneksi->real_escape_string($id_user);

        // Ambil semua ulasan user
        $ulasan = [];
        $result = $koneksi->query("SELECT * FROM ulasan WHERE id_users = '$id_user_safe' ORDER BY id_ulasan DESC");

        if ($result && $result->num_rows > 0) {
            $ulasan = $result->fetch_all(MYSQLI_ASSOC);
        }

        // Jika form dikirim
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $rating = (int)($_POST['rating'] ?? 0);
            $komentar = trim($koneksi->real_escape_string($_POST['komentar'] ?? ''));
            $mode = $_POST['mode'] ?? 'baru';
            $id_ulasan_post = $koneksi->real_escape_string($_POST['id_ulasan'] ?? '');

            // Ambil booking terakhir user (cek hasil)
            $resultBooking = $koneksi->query("SELECT id_booking FROM booking WHERE id_users = '$id_user_safe' ORDER BY id_booking DESC LIMIT 1");
            $booking = null;
            $id_booking = null;
            if ($resultBooking && $resultBooking->num_rows > 0) {
                $booking = $resultBooking->fetch_assoc();
                $id_booking = $booking['id_booking'] ?? null;
            }

            // PROSES UPDATE berdasarkan id_ulasan (bukan id_user)
            if ($mode === 'perbarui' && $id_ulasan_post !== '') {

                $updateSql = "
                    UPDATE ulasan
                    SET rating = '$rating', komentar = '$komentar'
                    WHERE id_ulasan = '$id_ulasan_post'
                ";
                $koneksi->query($updateSql);

                // Optional: cek error query
                if ($koneksi->error) {
                    $_SESSION['flash'] = ['pesan' => 'Terjadi error saat memperbarui: ' . $koneksi->error];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Ulasan berhasil diperbarui'];
                }

            } else {
                // Generate ID baru dengan aman
                $resultMax = $koneksi->query("SELECT MAX(id_ulasan) AS last_id FROM ulasan");
                $lastIdRow = $resultMax && $resultMax->num_rows > 0 ? $resultMax->fetch_assoc() : null;

                if (!empty($lastIdRow['last_id'])) {
                    $lastNumber = (int) substr($lastIdRow['last_id'], 6); // ambil angka setelah "Ulasan"
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }

                $id_ulasan = "Ulasan" . str_pad($newNumber, 3, "0", STR_PAD_LEFT);

                $insertSql = "
                    INSERT INTO ulasan (id_ulasan, id_users, id_booking, rating, komentar, tgl_ulasan)
                    VALUES ('$id_ulasan', '$id_user_safe', " . ($id_booking !== null ? "'$id_booking'" : "NULL") . ", '$rating', '$komentar', NOW())
                ";
                $koneksi->query($insertSql);

                if ($koneksi->error) {
                    $_SESSION['flash'] = ['pesan' => 'Terjadi error saat menyimpan ulasan: ' . $koneksi->error];
                } else {
                    $_SESSION['flash'] = ['pesan' => 'Ulasan berhasil dikirim! Terima kasih'];
                }
            }

            header('Location: ' . BASEURL . '/DashboardCustomer/ulasan');
            exit;
        }

        $data = [
            'title' => 'Beri Ulasan',
            'content' => 'dashboard_customer/ulasan',
            'id_user' => $id_user,
            'ulasan' => $ulasan,
            'flash' => $_SESSION['flash'] ?? null
        ];

        unset($_SESSION['flash']);
        $this->view('layouts/dashboard_layoutCus', $data);
    }
}

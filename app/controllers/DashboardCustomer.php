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

        $query_user = $koneksi->query("SELECT nama_lengkap FROM users WHERE id_users = '$id_user'");
        $user = $query_user ? $query_user->fetch_assoc() : null;

        $query_kucing = $koneksi->query("
            SELECT db.id_kucing
            FROM detail_booking db
            JOIN booking b ON db.id_booking = b.id_booking
            WHERE b.id_users = '$id_user' 
            ORDER BY b.tgl_booking DESC, db.id_detail DESC  /* Urutkan berdasarkan booking terbaru */
            LIMIT 1 /* Ambil ID Kucing dari baris terakhir */
        ");

        $id_kucing_terakhir = $query_kucing ? $query_kucing->fetch_assoc()['id_kucing'] : null;

        $query_booking = $koneksi->query("
            SELECT m.nama_petshop AS tempat_penitipan, b.tgl_booking AS tanggal_penitipan, b.status
            FROM booking b
            JOIN mitra m ON b.id_mitra = m.id_mitra
            WHERE b.id_users = '$id_user'
            ORDER BY b.tgl_booking DESC
            LIMIT 1
        ");
        $booking = $query_booking ? $query_booking->fetch_assoc() : null;

        $query_pengeluaran = $koneksi->query("
            SELECT SUM(total_harga) AS total_pengeluaran
            FROM booking
            WHERE id_users = '$id_user'
        ");
        $pengeluaran = $query_pengeluaran ? $query_pengeluaran->fetch_assoc() : null;
        $total_pengeluaran = $pengeluaran['total_pengeluaran'] ?? 0;

        $data = [
            'title' => 'Dashboard',
            'content' => 'dashboard_customer/index',
            'nama_pengguna' => $user['nama_lengkap'] ?? 'Pengguna',
            //'nama_kucing' => $booking['id_kucing'] ?? '-',
            'tgl_booking' => $booking['tanggal_penitipan'] ?? '-',
            'status' => $booking['status'] ?? '-',
            'pengeluaran' => 'Rp ' . number_format($total_pengeluaran, 0, ',', '.'),
            'id_user' => $id_user,
            'jumlah_kucing' => $jumlah_kucing
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    public function Booking() {
        $data = [
            'title' => 'Booking',
            'content' => 'dashboard_customer/booking/booking'
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    public function Penitipan() {
        $data = [
            'title' => 'Cari Penitipan',
            'content' => 'dashboard_customer/pilih_penitipan/penitipan'
        ];

        $this->view('layouts/dashboard_layoutCus', $data);
    }

    public function ulasan() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $id_user = $_SESSION['user']['id_users'] ?? null;
        if (!$id_user) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $koneksi = new mysqli("localhost", "root", "", "pawtopia");
        if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);

        // Ambil ulasan terakhir (jika ada)
        $ulasan = null;
        $result = $koneksi->query("SELECT * FROM ulasan WHERE id_users = '$id_user' ORDER BY id_ulasan DESC LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $ulasan = $result->fetch_assoc();
        }

        // Jika form dikirim
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = (int)($_POST['rating'] ?? 0);
            $komentar = trim($koneksi->real_escape_string($_POST['komentar'] ?? ''));
            $mode = $_POST['mode'] ?? 'baru';

            if ($mode === 'perbarui' && $ulasan) {
                $koneksi->query("
                    UPDATE ulasan 
                    SET rating = '$rating', komentar = '$komentar' 
                    WHERE id_users = '$id_user'
                ");
                $_SESSION['flash'] = ['pesan' => 'Ulasan berhasil diperbarui 💛'];
            } else {
                $koneksi->query("
                    INSERT INTO ulasan (id_users, rating, komentar, created_at)
                    VALUES ('$id_user', '$rating', '$komentar', NOW())
                ");
                $_SESSION['flash'] = ['pesan' => 'Ulasan berhasil dikirim! Terima kasih 💛'];
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

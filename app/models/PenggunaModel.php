<?php

class PenggunaModel {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM {$this->table} ORDER BY nama_lengkap ASC";
        $result = $this->conn->query($query);
        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    public function getUserById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id_users = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Fungsi Helper: Normalisasi Role agar sesuai format Database
    private function normalizeRole($roleInput) {
        $role = strtolower($roleInput);
        if ($role == 'user') return 'Customer'; // Ubah 'user' jadi 'Customer'
        return ucfirst($role); // Ubah 'mitra' -> 'Mitra', 'admin' -> 'Admin'
    }

    public function tambahUser($data) {
        $id_users = 'USR-' . time() . rand(10, 99);
        $password = !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : password_hash('123456', PASSWORD_DEFAULT);
        $foto = !empty($data['foto_profil']) ? $data['foto_profil'] : 'default.png';
        
        // Normalisasi role sebelum simpan
        $roleFix = $this->normalizeRole($data['role']);

        $query = "INSERT INTO {$this->table} (id_users, nama_lengkap, email, password, no_hp, role, foto_profil) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        // Debugging jika prepare gagal
        if (!$stmt) die("Error Prepare: " . $this->conn->error);

        $stmt->bind_param("sssssss", $id_users, $data['nama_lengkap'], $data['email'], $password, $data['no_hp'], $roleFix, $foto);

        if (!$stmt->execute()) {
            die("Error Execute Tambah: " . $stmt->error); // Tampilkan error jika gagal
        }
        return true;
    }

    public function updateUser($data) {
        // Normalisasi role sebelum update
        $roleFix = $this->normalizeRole($data['role']);

        if (!empty($data['password'])) {
            $query = "UPDATE {$this->table} SET nama_lengkap=?, email=?, password=?, no_hp=?, role=? WHERE id_users=?";
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssss", $data['nama_lengkap'], $data['email'], $password, $data['no_hp'], $roleFix, $data['id_users']);
        } else {
            $query = "UPDATE {$this->table} SET nama_lengkap=?, email=?, no_hp=?, role=? WHERE id_users=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssss", $data['nama_lengkap'], $data['email'], $data['no_hp'], $roleFix, $data['id_users']);
        }

        if (!$stmt->execute()) {
            die("Error Execute Update: " . $stmt->error); // Tampilkan error jika gagal
        }
        return true;
    }

    // --- FITUR HAPUS USER (CASCADING DELETE) ---
    public function hapusUser($id) {
        $this->conn->begin_transaction();

        try {
            // 1. Ambil ID Mitra jika ada
            $mitraQuery = $this->conn->query("SELECT id_mitra FROM mitra WHERE id_users = '$id'");
            $mitraData = $mitraQuery->fetch_assoc();
            $id_mitra = $mitraData['id_mitra'] ?? null;

            // 2. Bersihkan Data Mitra
            if ($id_mitra) {
                $this->conn->query("DELETE FROM mitra_paket WHERE id_mitra = '$id_mitra'");
                // Hapus data booking terkait mitra (ignore foreign key sementara jika perlu, atau hapus child dulu)
                $this->conn->query("DELETE u FROM ulasan u JOIN booking b ON u.id_booking = b.id_booking WHERE b.id_mitra = '$id_mitra'");
                $this->conn->query("DELETE db FROM detail_booking db JOIN booking b ON db.id_booking = b.id_booking WHERE b.id_mitra = '$id_mitra'");
                $this->conn->query("DELETE FROM booking WHERE id_mitra = '$id_mitra'");
                $this->conn->query("DELETE FROM mitra WHERE id_mitra = '$id_mitra'");
            }

            // 3. Bersihkan Data User (Customer)
            $this->conn->query("DELETE u FROM ulasan u JOIN booking b ON u.id_booking = b.id_booking WHERE b.id_users = '$id'");
            $this->conn->query("DELETE db FROM detail_booking db JOIN booking b ON db.id_booking = b.id_booking WHERE b.id_users = '$id'");
            $this->conn->query("DELETE FROM booking WHERE id_users = '$id'");
            
            // Hapus Kucing
            $this->conn->query("DELETE db FROM detail_booking db JOIN kucing k ON db.id_kucing = k.id_kucing WHERE k.id_users = '$id'");
            $this->conn->query("DELETE FROM kucing WHERE id_users = '$id'");

            // 4. Hapus User Utama
            $stmtUser = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_users = ?");
            $stmtUser->bind_param("s", $id);
            
            if (!$stmtUser->execute()) {
                throw new Exception("Gagal menghapus user: " . $stmtUser->error);
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            die("GAGAL HAPUS DATA: " . $e->getMessage()); 
            return false;
        }
    }
}
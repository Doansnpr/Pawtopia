<?php
class AuthModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // LOGIN
    public function loginUser($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // ✅ Cek password hash terlebih dahulu
            if (password_verify($password, $user['password'])) {
                return $user;
            }

            // ⚠️ Fallback: jika data lama masih plaintext (sementara)
            if ($password === $user['password']) {
                // otomatis update jadi hash biar next login aman
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $update = $this->conn->prepare("UPDATE users SET password = ? WHERE id_users = ?");
                $update->bind_param("ss", $newHash, $user['id_users']);
                $update->execute();
                return $user;
            }
        }

        return false;
    }

    // REGISTER
    public function registerUser($nama, $nohp, $email, $password, $role, $mitraData = null) {
        // cek duplikat email
        $check = $this->conn->prepare("SELECT email FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) return false;

        $id_users = uniqid('USR');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("
            INSERT INTO users (id_users, nama_lengkap, email, password, no_hp, role, tgl_daftar)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("ssssss", $id_users, $nama, $email, $hash, $nohp, $role);
        if (!$stmt->execute()) {
            return false;
        }

        // kalau bukan mitra
        if (strtolower($role) !== 'mitra') {
            return true;
        }

        // Data Mitra
        $id_mitra = uniqid('MIT');
        $nama_petshop = $mitraData['nama_petshop'] ?? '';
        $alamat = $mitraData['alamat_petshop'] ?? '';
        $no_hp_petshop = $mitraData['no_hp_petshop'] ?? $nohp;
        $deskripsi = $mitraData['deskripsi'] ?? '';
        $kapasitas = (int)($mitraData['kapasitas'] ?? 0);
        $harga_paket1 = (double)($mitraData['harga_paket1'] ?? 0);
        $harga_paket2 = (double)($mitraData['harga_paket2'] ?? 0);
        $harga_paket3 = (double)($mitraData['harga_paket3'] ?? 0);
        $lokasi_lat = $mitraData['lokasi_lat'] ?? '';
        $lokasi_lng = $mitraData['lokasi_lng'] ?? '';

        $foto_profil = null;
        if (isset($mitraData['foto_petshop']) && $mitraData['foto_petshop']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/images/mitra/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileTmp = $mitraData['foto_petshop']['tmp_name'];
            $fileName = uniqid('PET_') . '_' . basename($mitraData['foto_petshop']['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmp, $targetFile)) {
                $foto_profil = 'images/mitra/' . $fileName;
            }
        }

        $stmtMitra = $this->conn->prepare("
            INSERT INTO mitra (
                id_mitra, id_users, nama_petshop, alamat, no_hp, deskripsi, kapasitas,
                foto_profil, harga_paket1, harga_paket2, harga_paket3,
                lokasi_lat, lokasi_lng, tgl_daftar
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmtMitra->bind_param(
            "ssssssisdddss",
            $id_mitra,
            $id_users,
            $nama_petshop,
            $alamat,
            $no_hp_petshop,
            $deskripsi,
            $kapasitas,
            $foto_profil,
            $harga_paket1,
            $harga_paket2,
            $harga_paket3,
            $lokasi_lat,
            $lokasi_lng
        );

        return $stmtMitra->execute();
    }

    // UPDATE PASSWORD
    public function updatePasswordByEmail($email, $new_password) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hash, $email);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }


    // Cek apakah user sudah pernah kasih ulasan
    public function getUlasanByUser($id_user) {
        $query = "SELECT * FROM ulasan WHERE id_users = '$id_user' LIMIT 1";
        $result = $this->db->query($query);
        return $result ? $result->fetch_assoc() : null;
    }

    // Simpan ulasan baru
    public function insertUlasan($id_user, $rating, $komentar) {
        $stmt = $this->db->prepare("INSERT INTO ulasan (id_users, rating, komentar) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id_user, $rating, $komentar);
        return $stmt->execute();
    }

    // Update ulasan
    public function updateUlasan($id_user, $rating, $komentar) {
        $stmt = $this->db->prepare("UPDATE ulasan SET rating = ?, komentar = ? WHERE id_users = ?");
        $stmt->bind_param("isi", $rating, $komentar, $id_user);
        return $stmt->execute();
    }
}

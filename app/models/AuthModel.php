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

            if (password_verify($password, $user['password'])) {
                return $user;
            }

            if ($password === $user['password']) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $update = $this->conn->prepare("UPDATE users SET password = ? WHERE id_users = ?");
                $update->bind_param("ss", $newHash, $user['id_users']);
                $update->execute();
                return $user;
            }
        }
        return false;
    }

    // REGISTER (TRANSACTIONAL)
    public function registerUser($nama, $nohp, $email, $password, $role, $mitraData = null) {
        $this->conn->begin_transaction();

        try {
            // 1. Cek Duplikat Email
            $check = $this->conn->prepare("SELECT email FROM users WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                $this->conn->rollback();
                return false;
            }

            // 2. Insert Users
            $id_users = uniqid('USR');
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare("
                INSERT INTO users (id_users, nama_lengkap, email, password, no_hp, role, tgl_daftar)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->bind_param("ssssss", $id_users, $nama, $email, $hash, $nohp, $role);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal Insert User: " . $stmt->error);
            }

            if (strtolower($role) !== 'mitra') {
                $this->conn->commit();
                return true;
            }

            // 3. Insert Mitra
            if (!$mitraData) {
                throw new Exception("Data mitra kosong.");
            }

            $id_mitra = uniqid('MIT');
            $nama_petshop = $mitraData['nama_petshop'] ?? '';
            $alamat       = $mitraData['alamat'] ?? '';
            $no_hp_ps     = $mitraData['no_hp_petshop'] ?? '';
            $deskripsi    = $mitraData['deskripsi'] ?? '';
            $kapasitas    = (int) ($mitraData['kapasitas'] ?? 0);
            $foto_profil  = $mitraData['foto_profil'] ?? '';
            $foto_ktp     = $mitraData['foto_ktp'] ?? ''; // Ambil foto KTP
            $lat          = $mitraData['lokasi_lat'] ?? '0';
            $lng          = $mitraData['lokasi_lng'] ?? '0';
            $status_awal  = 'Menunggu Pembayaran'; 

            // FIXED: Menambahkan foto_ktp ke Query
            $stmtMitra = $this->conn->prepare("
                INSERT INTO mitra (
                    id_mitra, id_users, nama_petshop, alamat, no_hp, deskripsi, 
                    kapasitas, foto_profil, foto_ktp, lokasi_lat, lokasi_lng, tgl_daftar, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
            ");

            if (!$stmtMitra) {
                throw new Exception("Prepare Mitra Error: " . $this->conn->error);
            }

            // FIXED: Bind Param (12 items now)
            // s(id), s(id), s(nm), s(almt), s(hp), s(desc), i(kap), s(foto), s(ktp), s(lat), s(lng), s(status)
            $stmtMitra->bind_param(
                "ssssssisssss", 
                $id_mitra, $id_users, $nama_petshop, $alamat, $no_hp_ps, $deskripsi, 
                $kapasitas, $foto_profil, $foto_ktp, $lat, $lng, $status_awal
            );

            if (!$stmtMitra->execute()) {
                throw new Exception("Gagal Insert Mitra: " . $stmtMitra->error);
            }

            // 4. Insert Paket
            if (!empty($mitraData['data_paket']) && is_array($mitraData['data_paket'])) {
                $stmtPaket = $this->conn->prepare("
                    INSERT INTO mitra_paket (id_paket, id_mitra, nama_paket, harga)
                    VALUES (?, ?, ?, ?)
                ");

                foreach ($mitraData['data_paket'] as $pak) {
                    $nama_paket = trim($pak['nama']);
                    $harga_paket = (int)$pak['harga'];
                    $id_paket = uniqid('PKT');

                    if($nama_paket && $harga_paket > 0) {
                        $stmtPaket->bind_param("sssi", $id_paket, $id_mitra, $nama_paket, $harga_paket);
                        if (!$stmtPaket->execute()) {
                            throw new Exception("Gagal Insert Paket: " . $stmtPaket->error);
                        }
                    }
                }
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Register Error: " . $e->getMessage());
            return false;
        }
    }

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
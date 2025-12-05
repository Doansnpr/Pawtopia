<?php
class AuthModel {
    private $conn;

    public function __construct($db) {
        // $db harus berupa objek koneksi mysqli
        $this->conn = $db;
    }

    // ===========================================
    // MARK: AUTENTIKASI (LOGIN & FORGOT)
    // ===========================================

    public function loginUser($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        
        // Cek jika prepare gagal
        if (!$stmt) {
            error_log("Login prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // 1. Verifikasi Password
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function updatePasswordByEmail($email, $new_password) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hash, $email);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
    
    // ===========================================
    // MARK: REGISTER (TRANSACTIONAL)
    // ===========================================

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
                return false; // Email Duplikat
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
            if (empty($mitraData) || !is_array($mitraData)) {
                throw new Exception("Data mitra kosong atau format salah.");
            }

            $id_mitra = uniqid('MIT');
            $nama_petshop = $mitraData['nama_petshop'] ?? '';
            $alamat       = $mitraData['alamat'] ?? '';
            $no_hp_ps     = $mitraData['no_hp_petshop'] ?? '';
            $deskripsi    = $mitraData['deskripsi'] ?? '';
            $kapasitas    = (int) ($mitraData['kapasitas'] ?? 0);
            $foto_profil  = $mitraData['foto_profil'] ?? '';
            $foto_ktp     = $mitraData['foto_ktp'] ?? '';
            $lat          = $mitraData['lokasi_lat'] ?? '0';
            $lng          = $mitraData['lokasi_lng'] ?? '0';

            // --- PERBAIKAN PENTING DISINI ---
            // Status awal diubah agar memicu pop-up pembayaran di Auth.php
            $status_awal  = 'Menunggu Pembayaran'; 

            $stmtMitra = $this->conn->prepare("
                INSERT INTO mitra (
                    id_mitra, id_users, nama_petshop, alamat, no_hp, deskripsi, 
                    kapasitas, foto_profil, foto_ktp, lokasi_lat, lokasi_lng, tgl_daftar, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
            ");

            if (!$stmtMitra) {
                throw new Exception("Prepare Mitra Error: " . $this->conn->error);
            }

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
                
                if (!$stmtPaket) {
                    throw new Exception("Prepare Paket Error: " . $this->conn->error);
                }

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

    // ===========================================
    // MARK: FUNGSI MITRA
    // ===========================================
    
    public function getAllMitra() {
        $query = "SELECT m.*, u.email 
                  FROM mitra m 
                  JOIN users u ON u.id_users = m.id_users 
                  ORDER BY m.tgl_daftar DESC";
                  
        $result = $this->conn->query($query);
        if ($result === false) {
             error_log("Query failed: " . $this->conn->error);
             return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMitraById($id) {
        $sql = "SELECT m.*, u.email 
                FROM mitra m
                JOIN users u ON u.id_users = m.id_users
                WHERE m.id_mitra = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getMitraStatus($id_users) {
        $stmt = $this->conn->prepare("SELECT status FROM mitra WHERE id_users = ? LIMIT 1");
        $stmt->bind_param("s", $id_users);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateMitraStatus($id_mitra, $status) {
        $stmt = $this->conn->prepare("UPDATE mitra SET status = ? WHERE id_mitra = ?");
        $stmt->bind_param("ss", $status, $id_mitra);
        return $stmt->execute();
    }
    
    // ===========================================
    // MARK: FUNGSI ULASAN
    // ===========================================
    
    public function getUlasanByUser($id_user) {
        $stmt = $this->conn->prepare("SELECT * FROM ulasan WHERE id_users = ? LIMIT 1");
        $stmt->bind_param("s", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function insertUlasan($id_user, $rating, $komentar) {
        $stmt = $this->conn->prepare("INSERT INTO ulasan (id_users, rating, komentar) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $id_user, $rating, $komentar); 
        return $stmt->execute();
    }

    public function updateUlasan($id_user, $rating, $komentar) {
        $stmt = $this->conn->prepare("UPDATE ulasan SET rating = ?, komentar = ? WHERE id_users = ?");
        $stmt->bind_param("sis", $rating, $komentar, $id_user); 
        return $stmt->execute();
    }
}
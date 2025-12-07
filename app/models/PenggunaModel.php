<?php

class PenggunaModel {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllUsers() {
        // SELECT * sudah otomatis mengambil kolom status jika ada di database
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

    public function tambahUser($data) {
        $id_users = 'USR-' . time() . rand(10, 99);
        $password = !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : password_hash('123456', PASSWORD_DEFAULT);
        
        // Default user baru = aktif
        $status = 'aktif'; 
        
        $query = "INSERT INTO {$this->table} (id_users, nama_lengkap, email, password, no_hp, role, foto_profil, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $foto = !empty($data['foto_profil']) ? $data['foto_profil'] : 'default.png';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssss", 
            $id_users, 
            $data['nama_lengkap'], 
            $data['email'], 
            $password, 
            $data['no_hp'], 
            $data['role'],
            $foto,
            $status // Parameter status ditambahkan
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // --- BAGIAN INI YANG DIUBAH ---
    public function updateUser($data) {
        // Pastikan status ada di array $data, jika tidak default ke status lama atau aktif
        $status = isset($data['status']) ? $data['status'] : 'aktif';

        if (!empty($data['password'])) {
            // Update dengan password dan status
            $query = "UPDATE {$this->table} SET nama_lengkap=?, email=?, password=?, no_hp=?, role=?, status=? WHERE id_users=?";
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssssss", $data['nama_lengkap'], $data['email'], $password, $data['no_hp'], $data['role'], $status, $data['id_users']);
        } else {
            // Update tanpa password tapi DENGAN status
            $query = "UPDATE {$this->table} SET nama_lengkap=?, email=?, no_hp=?, role=?, status=? WHERE id_users=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssss", $data['nama_lengkap'], $data['email'], $data['no_hp'], $data['role'], $status, $data['id_users']);
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function hapusUser($id) {
        $query = "DELETE FROM {$this->table} WHERE id_users = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
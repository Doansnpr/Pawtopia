<?php

class ProfilCustomer
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getCustomerByUserId($user_id)
    {
        // 1. Ambil HANYA kolom yang ada di database Anda
        $query = "SELECT 
                    id_users, nama_lengkap, email, no_hp, 
                    role, tgl_daftar, password 
                  FROM users 
                  WHERE id_users = ? AND role = 'Customer'";
        
        $stmt  = $this->db->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // 2. Manipulasi data agar View tidak error (karena View butuh kolom ini)
        if ($data) {
            $data['id_customer'] = $data['id_users'];
            // Kita kasih nilai default karena di DB tidak ada kolomnya
            $data['alamat'] = 'Belum diatur'; 
            $data['tanggal_lahir'] = '-';
            // Kita kasih nama file default untuk foto
            $data['foto_profil'] = 'default_cat.png'; 
        }

        return $data;
    }

    public function updateCustomer($user_id, $data)
    {
        // Update HANYA kolom yang ada
        $query = "UPDATE users SET
            nama_lengkap = ?,
            no_hp = ?,
            email = ?
            WHERE id_users = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssss", $data['nama_lengkap'], $data['no_hp'], $data['email'], $user_id);

        return $stmt->execute();
    }

    public function updatePassword($user_id, $hash)
    {
        $query = "UPDATE users SET password = ? WHERE id_users = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $hash, $user_id);
        return $stmt->execute();
    }

    public function getCurrentPasswordHash($user_id)
    {
        $query = "SELECT password FROM users WHERE id_users = ?";
        $stmt  = $this->db->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['password'] ?? null;
    }

    public function getRiwayatPemesananByCustomer($id_customer)
    {
        // Cek riwayat (return kosong jika error/tabel tidak ada)
        try {
            $query = "SELECT * FROM pemesanan WHERE id_customer = ? ORDER BY tanggal_pesan DESC LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $id_customer);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
} 


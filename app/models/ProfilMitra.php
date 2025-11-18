<?php

class ProfilMitra
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Ambil data mitra berdasarkan id_users
    public function getMitraByUserId($user_id)
    {
        $query = "SELECT * FROM mitra WHERE id_users = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update data mitra
    public function updateMitra($id_mitra, $data)
    {
        $query = "UPDATE mitra SET
            nama_petshop = ?,
            alamat = ?,
            no_hp = ?,
            deskripsi = ?,
            kapasitas = ?,
            harga_paket1 = ?,
            harga_paket2 = ?,
            harga_paket3 = ?,
            lokasi_lat = ?,
            lokasi_lng = ?,
            foto_profil = ?
        WHERE id_mitra = ?";

        $stmt = $this->db->prepare($query);

        // FIX: Type definition yang benar
        // s = string, i = integer, d = decimal/double
        $stmt->bind_param(
            "ssssiiiiddss",  // 12 parameters: 6 string, 3 int, 2 decimal, 1 string, 1 string
            $data['nama_petshop'],    // s
            $data['alamat'],          // s
            $data['no_hp'],           // s
            $data['deskripsi'],       // s
            $data['kapasitas'],       // i
            $data['harga_paket1'],    // i
            $data['harga_paket2'],    // i
            $data['harga_paket3'],    // i
            $data['lokasi_lat'],      // d
            $data['lokasi_lng'],      // d
            $data['foto_profil'],     // s
            $id_mitra                 // s
        );

        return $stmt->execute();
    }
}
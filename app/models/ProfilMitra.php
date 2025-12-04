<?php
class ProfilMitra
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getMitraByUserId($user_id)
    {
        $query = "SELECT * FROM mitra WHERE id_users = ?";
        $stmt  = $this->db->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // --- TAMBAHKAN FUNGSI INI UNTUK CETAK LAPORAN ---
    public function getMitraById($id_mitra)
    {
        $query = "SELECT * FROM mitra WHERE id_mitra = ?";
        $stmt  = $this->db->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getPaketByMitra($id_mitra)
    {
        $query = "SELECT * FROM mitra_paket WHERE id_mitra = ?";
        $stmt  = $this->db->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateMitra($id_mitra, $data)
    {
        $query = "UPDATE mitra SET
            nama_petshop = ?,
            alamat = ?,
            no_hp = ?,
            deskripsi = ?,
            kapasitas = ?,
            lokasi_lat = ?,
            lokasi_lng = ?,
            foto_profil = ?
        WHERE id_mitra = ?";

        $stmt = $this->db->prepare($query);

        // 'd' untuk decimal/double (lokasi)
        $stmt->bind_param(
            "ssssiddss", 
            $data['nama_petshop'], 
            $data['alamat'], 
            $data['no_hp'], 
            $data['deskripsi'], 
            $data['kapasitas'], 
            $data['lokasi_lat'], 
            $data['lokasi_lng'], 
            $data['foto_profil'],
            $id_mitra
        );

        return $stmt->execute();
    }

    // Hapus paket lama
    public function deletePaketByMitra($id_mitra)
    {
        $query = "DELETE FROM mitra_paket WHERE id_mitra = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $id_mitra);
        return $stmt->execute();
    }

    // Insert paket baru
    public function insertPaket($id_mitra, $nama_paket, $harga)
    {
        $id_paket = uniqid("PKT_"); 
        $query = "INSERT INTO mitra_paket (id_paket, id_mitra, nama_paket, harga) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssi", $id_paket, $id_mitra, $nama_paket, $harga);
        return $stmt->execute();
    }
}
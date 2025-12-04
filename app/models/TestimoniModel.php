<?php
class TestimoniModel {
    private $conn;
    private $table = 'testimoni';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua testimoni terbaru di atas

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT id_testimoni, nama_lengkap, role, komentar_t, rating_t, tgl_testimoni FROM " . $this->table . " ORDER BY tgl_testimoni DESC");
        
        // Perhatikan: Kita hanya mengecek execute dan result yang mungkin gagal
        if (!$stmt || !$stmt->execute()) {
            // Jika Anda ingin error yang lebih graceful daripada die(), Anda bisa log error dan return [];
            // Contoh: error_log("SQL Error: " . $stmt->error);
            return []; 
        }
        
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }

    // Tambah testimoni baru
    public function add($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO " . $this->table . " (nama_lengkap, role, komentar_t, rating_t, tgl_testimoni) VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param("sssi", $data['nama_lengkap'], $data['role'], $data['komentar_t'], $data['rating_t']);

        if ($stmt->execute()) {
            // kembalikan id terakhir untuk ambil data terbaru
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    // Ambil testimoni berdasarkan ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id_testimoni, nama_lengkap, role, komentar_t, rating_t, tgl_testimoni FROM " . $this->table . " WHERE id_testimoni = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>

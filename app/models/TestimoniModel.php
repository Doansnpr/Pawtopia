<?php
class TestimoniModel {
    private $conn;
    private $table = 'testimoni';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua testimoni, terbaru di atas
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY tgl_testimoni DESC";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC); // pastikan fetch_all
        }
        return [];
    }

    // Tambah testimoni baru
    public function add($data) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (nama_lengkap, role, komentar_t, rating_t, tgl_testimoni) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssi", $data['nama_lengkap'], $data['role'], $data['komentar_t'], $data['rating_t']);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error;
        }
    }
}
?>

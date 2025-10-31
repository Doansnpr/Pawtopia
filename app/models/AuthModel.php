<?php
class AuthModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function loginUser($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function registerUser($nama, $nohp, $email, $password, $role) {
        $id_users = uniqid('USR');
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO users (id_users, nama_lengkap, email, password, no_hp, role, tgl_daftar)
                                      VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $id_users, $nama, $email, $hash, $nohp, $role);
        return $stmt->execute();
    }
}

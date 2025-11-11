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

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    // REGISTER
    public function registerUser($nama, $nohp, $email, $password, $role) {
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
        return $stmt->execute();
    }

    // UPDATE PASSWORD
    public function updatePasswordByEmail($email, $new_password) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hash, $email);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}

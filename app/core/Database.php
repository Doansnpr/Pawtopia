<?php

class Database {
    // langsung isi value, jangan pakai DB_HOST, DB_USER, dst
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';          // isi password MySQL kamu
    private $name = 'pawtopia';  // isi nama database kamu

    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

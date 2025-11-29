<?php
class StatusModel{
    private $conn;
    private $table = 'status'; 
    private $userTable = 'users'; 
    private $catTable = 'kucing'; 

    public function __construct($db) {
        $this->conn = $db;
    }

}
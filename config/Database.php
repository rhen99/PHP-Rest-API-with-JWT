<?php 
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "12345678";
    private $db_name = "rest api";
    private $conn;

    public function connect(){
        $this->conn = null;
    try {
            $this->conn = new PDO('mysql:host='. $this->host .';dbname='. $this->db_name, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error:'. $e->getMessage();
        }
        return $this->conn;

    }
}
<?php
class Database {
    private $host = "localhost";
    private $username = "empoweraitradefx_user";
    private $password = "^aDEW)XGVtZk";
    private $database = "empoweraitradefx_db";
    private $conn;

    public function connect() {
        if (!$this->conn) {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            if ($this->conn->connect_error) {
                throw new Exception("Database connection failed: " . $this->conn->connect_error);
            }
        }
        return $this->conn;
    }

    public function disconnect() {
        if ($this->conn) {
            $this->conn->close();
            $this->conn = null;
        }
    }
}

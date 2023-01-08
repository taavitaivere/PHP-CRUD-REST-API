<?php
    class DbConnect
    {
        private $servername = "localhost";
        private $dbname = "reactdb";
        private $username = "root";
        private $password = "";

        public function connect() {
        try {
            $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (\Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        }
    }

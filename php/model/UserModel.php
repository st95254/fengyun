<?php
require_once "../config.php";

class UserModel {
    private $db;

    public function __construct() {
        $this->initializeDatabaseConnection();
    }

    private function initializeDatabaseConnection() {
        $this->db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->db->connect_error) {
            die("連接失敗: " . $this->db->connect_error);
        }
    }

    public function findUserByAccount($account) {
        $sql = "SELECT * FROM user WHERE account = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $account);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row;
        } else {
            return null;
        }
    }

    public function addUser($account, $password) {
        $sql = "INSERT INTO user (account, password) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $account, $password);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function closeConnection() {
        mysqli_close($this->db);
    }
}
?>

<?php
require_once "../config.php";

class ProductModel {
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

    public function getProductsByType($type) {
        $sql = "SELECT * FROM `product` WHERE `product_type` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result;
    }   

    public function getProductIdsByType($type) {
        $sql = "SELECT id FROM `product` WHERE `product_type` = ? ORDER BY `id`";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $productIds = [];
        while ($row = $result->fetch_assoc()) {
            $productIds[] = $row['id'];
        }
        $stmt->close();
        return $productIds;
    }

    public function shouldUpdatePrice($id, $newPrice) {
        $currentPriceQuery = "SELECT price FROM product WHERE id = ?";
        $currentPriceStmt = $this->db->prepare($currentPriceQuery);
        $currentPriceStmt->bind_param("i", $id);
        $currentPriceStmt->execute();
        $result = $currentPriceStmt->get_result();
        $currentPriceStmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentPrice = $row['price'];
            return $currentPrice != $newPrice;  // Return true if prices are different
        }
        return false;  // No record found or other issues, return false to avoid update
    }

    public function updateProductPrice($id, $newPrice) {
        if ($this->shouldUpdatePrice($id, $newPrice)) {
            $sql = "UPDATE product SET price = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $newPrice, $id);
            $stmt->execute();
            $success = $stmt->affected_rows > 0;
            $stmt->close();
            return $success;
        }
        return false;  // Prices are the same, no update needed
    }

    public function getProductById($id) {
        $id = $this->db->real_escape_string($id); // 防止 SQL 注入
        $sql = "SELECT * FROM `product` WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        if ($row = $result->fetch_assoc()) {
            return $row;
        } else {
            return null;
        }
    }   

    public function closeConnection() {
        mysqli_close($this->db);
    }
}

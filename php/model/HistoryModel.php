<?php
require_once "../config.php";

class HistoryModel {
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

    public function getHistoryByUserId($user_id) {
        $sql = "SELECT h.id, h.trade_no, h.date, h.total, h.status
                FROM history h
                WHERE h.user_id = ?
                ORDER BY h.date DESC";
        $stmt = mysqli_prepare($this->db, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $history_items = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $history_items[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $history_items;
        } else {
            return null; // 或拋出一個錯誤
        }
    }

    public function getHistoryItemsById($history_id) {
        $sql = "SELECT hi.id, hi.history_id, hi.product_id, hi.quantity, p.name, hi.price, p.image, h.shipping_fee
                FROM history_item hi
                JOIN product p ON hi.product_id = p.id
                JOIN history h ON hi.history_id = h.id
                WHERE hi.history_id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $history_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $history_items = [];
            $shipping_fee = 0;
            while ($row = $result->fetch_assoc()) {
                if ($shipping_fee === 0) {
                    $shipping_fee = $row['shipping_fee'] ? 60 : 0;
                }
                $row['total'] = $row['price'] * $row['quantity'];
                $history_items[] = $row;
            }
            $stmt->close();
            return ['items' => $history_items, 'shipping_fee' => $shipping_fee];
        } else {
            return null; // 或拋出一個錯誤
        }
    }

    public function closeConnection() {
        mysqli_close($this->db);
    }
}
?>

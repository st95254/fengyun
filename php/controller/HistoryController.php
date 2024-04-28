<?php
require_once '../model/HistoryModel.php';

class HistoryController {
    private $historyModel;

    public function __construct() {
        $this->historyModel = new HistoryModel();
    }

    public function getHistory($user_id) {
        return $this->historyModel->getHistoryByUserId($user_id);
    }

    public function getHistoryItems($history_id) {
        return $this->historyModel->getHistoryItemsById($history_id);
    }
    
    public function close() {
        $this->historyModel->closeConnection();
    }
}
?>

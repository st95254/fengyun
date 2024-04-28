<?php
require_once "ProductController.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $price = $_POST['price'];

    $controller = new ProductController();
    if ($controller->updateProductPrice($id, $price)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record";
    }
}
?>

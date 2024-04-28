<?php
require_once "../model/ProductModel.php";

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function getTeaProducts() {
        return $this->productModel->getProductsByType('tea');
    }

    public function getGoldProducts() {
        return $this->productModel->getProductsByType('gold');
    }

    public function getGoldProductIds() {
        return $this->productModel->getProductIdsByType('gold');
    }

    public function updateProductPrice($id, $price) {
        return $this->productModel->updateProductPrice($id, $price);
    }

    public function getProductDetails($productId) {
        return $this->productModel->getProductById($productId);
    }
}

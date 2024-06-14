<?php
require_once '../config/dbaccess.php';
require_once '../models/products.php';

class CartLogic
{
    public function addToCart($userId, $productId)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $sql = "INSERT INTO cart (user_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return ['success' => true, 'message' => 'Product added to cart'];
        } else {
            $stmt->close();
            $conn->close();
            return ['success' => false, 'message' => 'Failed to add product to cart'];
        }
    }
}

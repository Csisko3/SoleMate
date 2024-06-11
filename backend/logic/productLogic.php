<?php
require_once '../config/dbaccess.php';
require_once '../models/products.php';

class ProductLogic
{
    public function load_products($category = '')
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        if ($category) {
            $sql = "SELECT id, name, price, picture FROM products WHERE category = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $category);
        } else {
            $sql = "SELECT id, name, price, picture FROM products";
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }

        $stmt->close();
        $conn->close();
        return ['success' => true, 'data' => $products];
    }

}

<?php
require_once '../config/dbaccess.php';
require_once '../models/products.php';

class adminProduct
{
/*     public function admin_load_products()
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $sql = "SELECT id, name, price, category, picture FROM products WHERE category = ?";
        $stmt = $conn->prepare($sql);

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                error_log("Fetched product: " . print_r($row, true));
                $products[] = $row;
            }
        }

        $stmt->close();
        $conn->close();

        // Log the products array for debugging
        error_log(print_r($products, true));

        return ['success' => true, 'data' => $products];
    } */
}
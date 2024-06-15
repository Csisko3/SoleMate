<?php
require_once '../config/dbaccess.php';
require_once '../models/cart.php';

class CartLogic
{
    public function addToCart($userId, $productId)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $sql = "SELECT * FROM cart WHERE user_id=? AND product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + 1;
            $update_sql = "UPDATE cart SET quantity=? WHERE id=?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ii", $new_quantity, $row['id']);
            $update_stmt->execute();
        } else {
            $quantity = 1;
            $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iii", $userId, $productId, $quantity);
            $insert_stmt->execute();
        }

        $stmt->close();
        $conn->close();

        return ['success' => true, 'message' => 'Product added to cart'];
    }

    public function updateCartItem($userId, $data)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $productId = $data['product_id'];
        $quantity = $data['quantity'];

        if ($quantity > 0) {
            $sql = "UPDATE cart SET quantity=? WHERE user_id=? AND product_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $quantity, $userId, $productId);
            $stmt->execute();
        } else {
            $sql = "DELETE FROM cart WHERE user_id=? AND product_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();

        return ['success' => true, 'message' => 'Cart item updated'];
    }

    public function getCart($userId)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $sql = "SELECT c.*, p.name, p.price, p.picture FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $cart = [];
        while ($row = $result->fetch_assoc()) {
            $cart[] = $row;
        }

        $stmt->close();
        $conn->close();

        return ['success' => true, 'cart' => $cart, 'total_quantity' => array_sum(array_column($cart, 'quantity'))];
    }

    public function placeOrder($userId, $data)
{
    global $host, $db_user, $db_password, $database;
    $conn = new mysqli($host, $db_user, $db_password, $database);

    if ($conn->connect_error) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    $name = isset($data['name']) ? $data['name'] : null;
    $address = isset($data['address']) ? $data['address'] : null;
    $paymentMethod = isset($data['paymentMethod']) ? $data['paymentMethod'] : null;

    if (is_null($name) || is_null($address) || is_null($paymentMethod)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }

    $conn->begin_transaction();

    try {
        // Insert into orders table
        $sql = "INSERT INTO orders (user_id, name, address, payment_method) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $userId, $name, $address, $paymentMethod);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();

        // Update cart items to link them to the order
        $sql = "UPDATE cart SET order_id = ? WHERE user_id = ? AND order_id IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $orderId, $userId);
        $stmt->execute();
        $stmt->close();

        // Delete cart items after placing the order
        $sql = "DELETE FROM cart WHERE user_id = ? AND order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $orderId);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        return ['success' => true, 'message' => 'Order placed successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        return ['success' => false, 'message' => 'Failed to place order: ' . $e->getMessage()];
    } finally {
        $conn->close();
    }
}


}

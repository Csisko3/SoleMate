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

        $name = $data['name'];
        $address = $data['address'];
        $paymentMethod = $data['paymentMethod'];

        $conn->begin_transaction();

        try {
            // Insert into orders table
            $sql = "INSERT INTO orders (user_id, name, address, payment_method) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $userId, $name, $address, $paymentMethod);
            $stmt->execute();
            $orderId = $stmt->insert_id;
            $stmt->close();

            // Fetch cart items for the user
            $sql = "SELECT * FROM cart WHERE user_id = ? AND order_id IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $cartItems = $stmt->get_result();

            if ($cartItems->num_rows == 0) {
                throw new Exception("No items in cart to place order");
            }

            // Create an array to store order details
            $orderDetails = [];

            while ($item = $cartItems->fetch_assoc()) {
                // Fetch product details
                $productSql = "SELECT name, price FROM products WHERE id = ?";
                $productStmt = $conn->prepare($productSql);
                $productStmt->bind_param("i", $item['product_id']);
                $productStmt->execute();
                $productResult = $productStmt->get_result();
                $product = $productResult->fetch_assoc();
                $productStmt->close();

                // Append product details to order details array
                $orderDetails[] = [
                    'product_name' => $product['name'],
                    'product_price' => $product['price'],
                    'quantity' => $item['quantity']
                ];
            }

            // Convert order details array to JSON
            $orderDetailsJson = json_encode($orderDetails);

            // Update orders table with order details
            $sql = "UPDATE orders SET order_details = ? WHERE order_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $orderDetailsJson, $orderId);
            $stmt->execute();
            $stmt->close();

            // Update cart items to link them to the order
            $sql = "UPDATE cart SET order_id = ? WHERE user_id = ? AND order_id IS NOT NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $orderId, $userId);
            $stmt->execute();
            $stmt->close();

            $conn->commit();

            // Clear the cart after placing the order
            $sql = "DELETE FROM cart WHERE user_id = ? AND order_id IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();

            return ['success' => true, 'message' => 'Order placed successfully'];
        } catch (Exception $e) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to place order: ' . $e->getMessage()];
        } finally {
            $conn->close();
        }
    }

    public function getOrders($userId)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        $stmt->close();
        $conn->close();

        return ['success' => true, 'orders' => $orders];
    }

    public function getOrderDetails($userId, $orderId)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $sql = "SELECT o.*, c.product_id, c.quantity, p.name AS product_name, p.price AS product_price 
            FROM orders o 
            JOIN cart c ON o.id = c.order_id 
            JOIN products p ON c.product_id = p.id 
            WHERE o.user_id = ? AND o.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        $order = [];
        $orderItems = [];
        while ($row = $result->fetch_assoc()) {
            if (empty($order)) {
                $order = [
                    'ID' => $row['ID'],
                    'user_id' => $row['user_id'],
                    'name' => $row['name'],
                    'address' => $row['address'],
                    'payment_method' => $row['payment_method'],
                    'order_date' => $row['order_date'],
                    'items' => []
                ];
            }
            $orderItems[] = [
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'product_price' => $row['product_price'],
                'quantity' => $row['quantity']
            ];
        }

        $order['items'] = $orderItems;

        $stmt->close();
        $conn->close();

        return ['success' => true, 'order' => $order];
    }
//---------------------Admin Funktions---------------------
public function getOrdersCustomer($customerId)
{
    global $host, $db_user, $db_password, $database;
    $conn = new mysqli($host, $db_user, $db_password, $database);

    if ($conn->connect_error) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    $sql = "SELECT * FROM orders WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $row['order_details'] = json_decode($row['order_details'], true);
        $orders[] = $row;
    }

    $stmt->close();
    $conn->close();

    return ['success' => true, 'orders' => $orders];
}

public function removeOrderItem($userId, $orderId, $productId)
{
    global $host, $db_user, $db_password, $database;
    $conn = new mysqli($host, $db_user, $db_password, $database);

    if ($conn->connect_error) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    // Retrieve the order details
    $sql = "SELECT order_details FROM orders WHERE order_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    if (!$order) {
        $conn->close();
        return ['success' => false, 'message' => 'Order not found'];
    }

    $orderDetails = json_decode($order['order_details'], true);

    // Remove the product from the order details
    $orderDetails = array_filter($orderDetails, function($item) use ($productId) {
        return $item['product_id'] != $productId;
    });

    // Update the order details in the database
    $updatedOrderDetails = json_encode(array_values($orderDetails));
    $sql = "UPDATE orders SET order_details = ? WHERE order_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $updatedOrderDetails, $orderId, $userId);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    return ['success' => true, 'message' => 'Order item removed successfully'];
}


}

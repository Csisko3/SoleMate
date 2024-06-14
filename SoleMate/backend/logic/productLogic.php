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
            $sql = "SELECT id, name, price, category, picture FROM products WHERE category = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $category);
        } else {
            $sql = "SELECT id, name, price, category, picture FROM products";
            $stmt = $conn->prepare($sql);
        }

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

        return ['success' => true, 'data' => $products];
    }



    public function searchProducts($query)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        // Bereiten Sie die Abfrage für LIKE vor
        $searchQuery = '%' . $query . '%'; // Suche nach Begriffen, die die Abfrage enthalten
        $sql = "SELECT * FROM products WHERE name LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();
        $conn->close();

        return ['success' => true, 'data' => $products];

    }

    //----------------------------Adminpanel--------------------------------

    public function getProduct($productId)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        $product = null;
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        }

        $stmt->close();
        $conn->close();

        return ['success' => true, 'data' => $product];
    }

    public function addProduct()
{
    global $host, $db_user, $db_password, $database;
    $conn = new mysqli($host, $db_user, $db_password, $database);

    if ($conn->connect_error) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';
    $picture = '';

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $picture = basename($_FILES['picture']['name']);
        $targetFilePath = '../productpictures/' . $picture;
        move_uploaded_file($_FILES['picture']['tmp_name'], $targetFilePath);
    }

    $sql = "INSERT INTO products (name, price, category, picture) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $price, $category, $picture);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return ['success' => true, 'message' => 'Product added successfully'];
    } else {
        $stmt->close();
        $conn->close();
        return ['success' => false, 'message' => 'Failed to add product'];
    }
}


    public function editProduct($productId)
{
    global $host, $db_user, $db_password, $database;
    $conn = new mysqli($host, $db_user, $db_password, $database);

    if ($conn->connect_error) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';
    $picture = '';

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $picture = basename($_FILES['picture']['name']);
        $targetFilePath = '../productpictures/' . $picture;
        move_uploaded_file($_FILES['picture']['tmp_name'], $targetFilePath);
    }

    if ($picture) {
        $sql = "UPDATE products SET name = ?, price = ?, category = ?, picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $price, $category, $picture, $productId);
    } else {
        $sql = "UPDATE products SET name = ?, price = ?, category = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $price, $category, $productId);
    }

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return ['success' => true, 'message' => 'Product updated successfully'];
    } else {
        $stmt->close();
        $conn->close();
        return ['success' => false, 'message' => 'Failed to update product'];
    }
}





    public function deleteProduct($productId)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return ['success' => true, 'message' => 'Product deleted successfully'];
        } else {
            $stmt->close();
            $conn->close();
            return ['success' => false, 'message' => 'Failed to delete product'];
        }
    }
}


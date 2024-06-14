<?php
require_once '../config/dbaccess.php';
require_once '../models/user.php';
session_start();

class userLogic{
    private $conn;

    public function __construct()
    {
        global $host, $db_user, $db_password, $database;
        $this->conn = new mysqli($host, $db_user, $db_password, $database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function saveUser($data)
    {
        $anrede = $data['anrede'] ?? '';
        $vorname = $data['firstname'] ?? '';
        $nachname = $data['lastname'] ?? '';
        $adress = $data['adress'] ?? '';
        $postcode = $data['postcode'] ?? '';
        $city = $data['city'] ?? '';
        $email = $data['email'] ?? '';
        $payment_info = $data['payment_info'] ?? '';
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        // $confirmPassword = $data['confirmPassword'] ?? ''; check ob passwörter übereinstimmen (optional)

        switch ($anrede) {
            case "Herr":
                $anrede = 0;
                break;
            case "Frau":
                $anrede = 1;
                break;
            case "diverse":
                $anrede = 2;
                break;
        }

        /**
         *   if($password  !== $confirmPassword) {
         *   echo 'Passwörter müssen übereinstimmen!';
         *   exit();
         *     }
         *
         *   if ($_POST["anrede"] == null || empty($vorname) || empty($nachname) || empty($email) || empty($username)  || empty($password) ) {
         *      die("Alle Felder müssen ausgefüllt sein.");
         * }
         */

        // Hashen Sie das Passwort, bevor Sie es in die Datenbank einfügen
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL-Abfrage zum Einfügen von Benutzerdaten
        $sql = "INSERT INTO user (gender, firstname, lastname, adress, postcode, city, email, username, password, payment_info) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $this->conn->prepare($sql)) {
            // Bind the parameters to the prepared statement
            $stmt->bind_param(
                "isssisssss",
                $anrede,
                $vorname,
                $nachname,
                $adress,
                $postcode,
                $city,
                $email,
                $username,
                $hashed_password,
                $payment_info,
            );

            if ($stmt->execute()) {
                // echo "Benutzer erfolgreich registriert" ; gibt Fehler

                /*    In Arbeit          
                Sessions setzten für neuen User und zeigt dann "Hallo User" + was user sehen sollten
                $_SESSION['firstname'] = $vorname;
                $_SESSION['lastname'] = $nachname;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email; */
                //header("location: ../sites/index.php");
            } else {
                $this->conn->close();
            }
        }
    }

    public function autoLogin($data)
    {
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
            $userId = $_COOKIE['user_id'];
            $sql = "SELECT * FROM user WHERE ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

                $row = $result->fetch_assoc();
                $_SESSION['user_id'] = $row['ID'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['is_admin'] = $row['is_admin'];

                $stmt->close();
                return true;
            }

            $stmt->close();
        }

        return false;
    }


    // Checking if user is logged in so they can make purchases
    public function checkLoginStatus()
    {
        $isLoggedIn = isset($_SESSION['user_id']);
        return ['success' => true, 'isLoggedIn' => $isLoggedIn];
    }

    //----------------------------Admin Funktionen--------------------------------
    public function loadCustomers()
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $sql = "SELECT id, username, firstname, lastname, adress, email, is_active FROM user where is_admin = 0";
        $result = $conn->query($sql);

        $customers = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $customers[] = $row;
            }
        }

        $conn->close();
        return ['success' => true, 'data' => $customers];
    }

    public function changeCustomerStatus($customerId, $action)
    {
        global $host, $db_user, $db_password, $database;
        $conn = new mysqli($host, $db_user, $db_password, $database);

        if ($conn->connect_error) {
            return ['success' => false, 'message' => 'Database connection failed'];
        }

        $isActive = ($action === 'activate') ? 1 : 0;
        $sql = "UPDATE user SET is_active = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $isActive, $customerId);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return ['success' => true, 'message' => 'Customer status changed successfully'];
        } else {
            $stmt->close();
            $conn->close();
            return ['success' => false, 'message' => 'Failed to change customer status'];
        }
    }
}



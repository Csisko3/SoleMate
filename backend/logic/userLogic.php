<?php

require_once '../config/dbaccess.php';
require_once '../models/user.php';

class userLogic
{
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
        $sql = "INSERT INTO user (gender, firstname, lastname, adress, postcode, city, email, password, payment_info) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $this->conn->prepare($sql)) {
            // Bind the parameters to the prepared statement
            $stmt->bind_param(
                "isssissss",
                $anrede,
                $vorname,
                $nachname,
                $adress,
                $postcode,
                $city,
                $email,
                $hashed_password,
                $payment_info,
            );


            if ($stmt->execute()) {
                echo "Benutzer erfolgreich registriert";
/*                 $_SESSION['firstname'] = $vorname;
                $_SESSION['lastname'] = $nachname;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email; */
                //header("location: ../sites/index.php");
            } else {
                echo "Fehler beim Einfügen des Benutzers: " .  $this->conn->close();
            }
        }
    }

}


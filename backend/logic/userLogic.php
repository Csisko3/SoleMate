<?php
include '../config/dbaccess.php';
include '../models/user.php';



class userLogic
{
    public function saveUser($data, $conn)
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
        // $confirmPassword = $data['confirmPassword'] ?? ''; check ob passwörter übereinstimmen (wird nicht benötigt)

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
        $sql = "INSERT INTO user (gender, firstname, lastname, adress, postcode, city, email, password, payment_info, is_admin) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind the parameters to the prepared statement
            $stmt->bind_param(
                "isssissssi",
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


            if ($conn->query($sql) === TRUE) {
                echo "Benutzer erfolgreich registriert";
                $_SESSION['firstname'] = $vorname;
                $_SESSION['lastname'] = $nachname;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                //header("location: ../sites/index.php");
            } else {
                echo "Fehler beim Einfügen des Benutzers: " . $conn->error;
            }
        }
    }

}


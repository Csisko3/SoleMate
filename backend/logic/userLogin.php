<?php

require_once '../config/dbaccess.php';

class userLogin
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

    public function loginUser($data)
    {
        // if (!isset($data['username'])) {
        //     die('Kein Benutzername angegeben');
        // }

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $remember = $data['remember'] ?? false;

        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();


            
            if (password_verify($password, $row['password'])) {
                // Setzen der Sessions
                $_SESSION['user_id'] = $row['ID'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['is_admin'] = $row['is_admin'];

                if ($row['is_active'] == 0) {
                    return [
                        'success' => false,
                        'message' => 'Ihr Account wurde deaktiviert!'
                    ];
                }

                // Setzen des Cookies, wenn "Login merken" aktiviert ist
                if ($remember) {
                    setcookie('user_id', $row['ID'], time() + 31536000, "/"); // 1 year - "/" means the cookie is available in the whole domains
                }

                return [
                    'success' => true,
                    'message' => 'Login erfolgreich',
                    'user' => [
                        'id' => $row['ID'],
                        'username' => $row['username'],
                        'is_admin' => $row['is_admin']
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Falsches Passwort!'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Benutzername nicht gefunden!'
            ];
        }
    }

    private function checkLoginStatus()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $isLoggedIn = isset($_SESSION['user_id']);
        return ['success' => true, 'isLoggedIn' => $isLoggedIn];
    }

}

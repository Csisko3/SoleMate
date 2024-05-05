<?php
require_once "./config/dbaccess.php"; 

class LoginService {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function login($requestData) {
        $username = $requestData['username'] ?? '';
        $password = $requestData['password'] ?? '';
        $remember = $requestData['remember'] ?? false;

        $userData = $this->fetchUser($username);
        if ($userData) {
            return $this->verifyUser($userData, $password, $remember);
        }
        return ['loginStatus' => 'failed', 'errorCode' => 2]; // User not found
    }

    private function fetchUser($username) {
        $query = "SELECT id, username, email, password, role FROM `customers` WHERE (`username` = :username OR `email` = :username) AND `enabled` = 1";
        $params = [':username' => $username];
        return $this->database->executeQuery($query, $params)[0] ?? null;
    }

    private function verifyUser($user, $password, $remember) {
        if (password_verify($password, $user["password"])) {
            $this->setUpSession($user);
            $this->updateLoginTime($user["id"]);
            if ($remember) {
                $this->setUpRememberMe($user["id"]);
            }
            return ['loginStatus' => 'success'];
        }
        return ['loginStatus' => 'failed', 'errorCode' => 1]; // Incorrect password
    }

    private function setUpSession($user) {
        session_start();
        $_SESSION["id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        $_SESSION['loginTime'] = time();
    }

    private function setUpRememberMe($userId) {
        $cookieDuration = 31536000; // Valid for 1 year
        setcookie('id', $userId, time() + $cookieDuration, "/", null, true, true); // HttpOnly and Secure flags
        setcookie('loginCookie', $userId, time() + $cookieDuration, "/", null, true, true);
    }

    private function updateLoginTime($id) {
        $query = "UPDATE `customers` SET `logintime` = CURRENT_TIMESTAMP WHERE `id` = :userId";
        $params = [':userId' => $id];
        $this->database->executeQuery($query, $params);
    }
}

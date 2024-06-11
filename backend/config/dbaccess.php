<?php
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $database = "solemate";

    $conn = new mysqli($host, $db_user, $db_password, $database);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
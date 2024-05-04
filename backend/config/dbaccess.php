<?php
    $host = "localhost";
    $db_user = "wi22b003";
    $db_password = "DRjPO]A8mo.3FjLc";
    $database = "solemate";

    $conn = new mysqli($host, $db_user, $db_password, $database);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
<?php

require_once '../config/dbaccess.php';
require_once '../models/coupon.php';

class CouponLogic
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

    public function getAllCoupons(): ?array
    {
        $sql = "SELECT * FROM coupons";
        $result = $this->conn->query($sql);

        $coupons = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $coupon = new Coupon(
                    $row['id'],
                    $row['code'],
                    $row['amount'],
                    $row['residual_value'],
                    $row['expiration_date'],
                    boolval($row['expired'])
                );
                $coupons[] = $coupon;
            }
        }

        return $coupons;
    }

    public function saveCoupon($requestData): ?Coupon
    {
        $code = $requestData['couponCode'] ?? '';
        $amount = $requestData['couponValue'] ?? '';
        $expirydate = $requestData['couponExpiration'] ?? '';

        $sql = "INSERT INTO coupons (code, amount, residual_value, expiration_date) 
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param("ssss", $code, $amount, $amount, $expirydate);

        if ($stmt->execute()) {
            $couponId = $stmt->insert_id;
            $stmt->close();
            if ($couponId) {
                return $this->getCouponById($couponId);
            }
        } else {
            throw new Exception("SQL error: " . $stmt->error);
        }

        return null;
    }

    private function getCouponById(int $couponId): ?Coupon
    {
        $sql = "SELECT * FROM coupons WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $couponId);
        $stmt->execute();
        $result = $stmt->get_result();
        $couponData = $result->fetch_assoc();

        if ($couponData) {
            $coupon = new Coupon(
                $couponData['id'],
                $couponData['code'],
                $couponData['amount'],
                $couponData['residual_value'],
                $couponData['expiration_date'],
                boolval($couponData['expired'])
            );
            $stmt->close();
            return $coupon;
        }

        $stmt->close();
        return null;
    }

    public function deleteCoupon(int $couponId): bool
    {
        $sql = "DELETE FROM coupons WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $couponId);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}

?>

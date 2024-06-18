<?php include "../res/layout/header.php";
      include "../res/layout/navbar.php";  ?>


<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <form id="couponForm" action="" method="POST">
                <!-- Coupon Code -->
                <div class="mb-3">
                    <label for="couponCode" class="form-label fw-bold">Gutschein Code</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="couponCode" name="couponCode" placeholder="Gutschein Code generieren -->" required>
                        <button class="btn btn-secondary" type="button" id="generateCodeButton">Code generieren</button>
                    </div>
                </div>

                <!-- Coupon Value -->
                <div class="mb-3">
                    <label for="couponValue" class="form-label fw-bold">Gutschein Wert</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="number" class="form-control" id="couponValue" step="0.01" name="couponValue" placeholder="0.00" required>
                    </div>
                </div>

                <!-- Coupon Expiration Date -->
                <div class="mb-3">
                    <label for="couponExpiration" class="form-label fw-bold">Ablaufdatum</label>
                    <input type="date" class="form-control" id="couponExpiration" name="couponExpiration" required>
                </div>

                <button id="addCouponButton" type="submit" class="btn btn-registrieren">Coupon hinzufügen</button>
            </form>
        </div>
    </div>
</div>

<div id="alertPlaceholder" class="container mt-4"></div>

<script src="../js/coupon.js"></script>
<?php include "../res/layout/footer.php"; ?>

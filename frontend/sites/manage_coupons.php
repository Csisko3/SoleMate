<?php include "../res/layout/header.php";
      include "../res/layout/navbar.php";   ?>

<div class="row container-fluid justify-content-center mt-4">
    <table class="table table-fit">
        <thead>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Wert</th>
            <th>Restwert</th>
            <th>Ablaufdatum</th>
            <th>Abgelaufen</th>
        </tr>
        </thead>
        <tbody id="couponTable">
        </tbody>
    </table>
</div>

<script src="../js/coupon.js"></script>
<?php include "../res/layout/footer.php"; ?>

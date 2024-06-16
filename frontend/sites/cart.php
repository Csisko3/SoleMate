<?php
//header 
include '../res/layout/header.php';
include '../res/layout/navbar.php';
?>

<body>
<div class="container">
    <h2 class="my-4" style="text-align: center">Warenkorb</h2>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Preis</th>
                        <th>Menge</th>
                        <th>Gesamt</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody id="cartPageItems">
                    <!-- Cart items will be dynamically loaded here -->
                </tbody>
            </table>
            <div class="text-end">
                <button class="btn btn-primary" id="proceedToCheckout">Zum Checkout</button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="checkoutForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Zahlungsmethode</label>
                        <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                            <option value="">Bitte wählen</option>
                            <option value="credit_card">Kreditkarte</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Bestellung abschließen</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="../js/cart.js"></script>

</body>

</html>
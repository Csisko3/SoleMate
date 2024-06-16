<?php
include '../res/layout/header.php';
include '../res/layout/navbar.php';
?>


<body>
    <div class="container mt-5">
        <h2>Kundenverwaltung</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Benutzername</th>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody id="customerList">
                <!-- Kunden werden hier durch JS geladen -->
            </tbody>
        </table>
    </div>

     <!-- order details -->
     <div id="orderDetailsModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bestelldetails</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Schließen">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produkt</th>
                                    <th>Preis</th>
                                    <th>Menge</th>
                                    <th>Gesamt</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody id="orderDetails">
                                <!-- Bestelldetails werden hier durch JS geladen -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/users.js"></script>
    <script src="../js/orders.js"></script>
</body>

</html>
<?php include '../res/layout/footer.php'; ?>
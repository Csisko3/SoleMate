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

    <script src="../js/users.js"></script>
</body>

</html>
<?php include '../res/layout/footer.php'; ?>
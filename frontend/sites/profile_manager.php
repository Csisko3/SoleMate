<?php
session_start(); // Start the session at the top of the file. 
include '../res/layout/header.php';
include '../res/layout/navbar.php';
?>

<body>
    <div class="container mt-5">
        <h2>Mein Konto</h2>
        <form id="profileForm">
            <div class="mb-3">
                <label for="username" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="username" name="username" disabled>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="firstname" class="form-label">Vorname</label>
                <input type="text" class="form-control" id="firstname" name="firstname">
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Nachname</label>
                <input type="text" class="form-control" id="lastname" name="lastname">
            </div>
            <div class="mb-3">
                <label for="adress" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="adress" name="adress">
            </div>
            <div class="mb-3">
                <label for="postcode" class="form-label">Postleitzahl</label>
                <input type="text" class="form-control" id="postcode" name="postcode">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">Stadt</label>
                <input type="text" class="form-control" id="city" name="city">
            </div>
            <div class="mb-3">
                <label for="payment_info" class="form-label">Zahlungsinformationen</label>
                <select class="form-control" id="payment_info" name="payment_info">
                    <option value="" selected disabled>Bitte wählen</option>
                    <option value="banküberweisung">Banküberweisung</option>
                    <option value="SEPA_Lastschrift">SEPA-Lastschrift</option>
                    <option value="PayPal">PayPal</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="new_password" class="form-label">Neues Passwort</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Passwort bestätigen</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Aktuelles Passwort (erforderlich zum Speichern von
                    Änderungen)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary">Speichern</button>
        </form>
    </div>

    <script src="../js/users.js">
        //leert Passwordfeld
        document.querySelector('form').addEventListener('submit', function (event) {
            document.getElementById('password').value = '';
        }); 
    </script>
</body>
<?php
include '../res/layout/footer.php';
?>
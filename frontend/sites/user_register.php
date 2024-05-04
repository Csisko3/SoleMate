<?php
include '../res/layout/header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<body>
    <div class="mt-5">
        <form class="formular" id="registrationForm" action="" method="post">
            <h1 class="h3 mb-3 font-weight-normal">Registrieren</h1>

            <div class="mb-3">
                <label for="anrede" class="form-label">Theme</label>
                <select class="form-control" id="anrede" name="anrede" required>
                    <option value="" selected disabled>Bitte wählen</option>
                    <option value="Herr">Herr</option>
                    <option value="Frau">Frau</option>
                    <option value="diverse">Divers</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="vorname" class="form-label">Vorname</label>
                    <input type="text" class="form-control" id="vorname" name="firstname" required>
                </div>
                <div class="col-md-6">
                    <label for="nachname" class="form-label">Nachname</label>
                    <input type="text" class="form-control" id="nachname" name="lastname" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adress" required>
            </div>

            <div class="mb-3">
                <label for="postleitzahl" class="form-label">Postleitzahl</label>
                <input type="text" class="form-control" id="postleitzahl" name="postcode" required>
            </div>

            <div class="mb-3">
                <label for="ort" class="form-label">Ort</label>
                <input type="text" class="form-control" id="ort" name="city" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail-Adresse</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="zahlungs_info" class="form-label">Zahlungsinformation</label>
                <select class="form-control" id="zahlungs_info" name="payment_info" required>
                    <option value="" selected disabled>Bitte wählen</option>
                    <option value="banküberweisung">Banküberweisung</option>
                    <option value="SEPA_Lastschrift">SEPA-Lastschrift</option>
                    <option value="PayPal">PayPal</option>
                </select>
            </div>


            <div class="mb-3">
                <label for="benutzername" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="Benutzername" name="username" required>
            </div>

            <div class="mb-3">
                <label for="passwort" class="form-label">Passwort</label>
                <input type="password" class="form-control" id="passwort" name="password" required>
            </div>

<!--             <div class="mb-3">
                <label for="passwortKontrolle" class="form-label">Passwort bestätigen</label>
                <input type="password" class="form-control" id="passwortKontrolle" name="confirmPassword" required>
            </div>
 -->

            <div>
                <a href="../sites/login.php" class="link-secondary">Sie haben bereits ein Konto? Hier anmelden</a>
            </div>

            <div class="mt-3 d-grid gap-2">
                <button class="btn btn-lg mb-5" formnovalidate type="submit" name="submit">Registrieren</button>
            </div>
        </form> 
    </div>
</body>
<script src="../js/users.js"></script> 


<?php
include '../res/layout/footer.php';
?>
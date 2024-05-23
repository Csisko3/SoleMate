<?php
session_start(); // Start the session at the top of the file.
include '../res/layout/header.php';
?>

<body>

    <div class="text-center mt-5">
        <form class="formular" method="post" id="loginForm">
            <img class="mt-4 mb-2" src="../res/img/logo.png" height="70" alt="Hotel Logo">
            <h1 class="h3 mb-3 font-weight-normal">Anmelden</h1>

            <label for="email" class="visually-hidden">Email Adresse</label>
            <input type="email" id="email" name="email" class="form-control login-em" placeholder="Email Adresse"
                required autofocus>

            <label for="password" class="visually-hidden">Passwort</label>
            <input type="password" id="password" name="password" class="form-control login-pw" placeholder="Passwort"
                required autofocus>

            <div class="mt-3 checkbox">
                <label>
                    <input class="checkbox mb-3" type="checkbox" value="angemeldet-bleiben"> Angemeldet bleiben
                </label>
            </div>
            <div>
                <a href="user_register.php" class="link-dark">Sie haben noch kein Konto? Jetzt Registrieren</a>
            </div>
            <div class="mt-3 mb-5 d-grid gap-2">
                <button type="submit" class="btn btn-lg">Anmelden</button>
            </div>
        </form>
    </div>

    <script src="../js/login.js"></script> <!-- Correct path to your JavaScript file -->
</body>

</html>

<?php
include '../res/layout/footer.php';
?>
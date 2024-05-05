<?php
session_start(); // Start the session at the top of the file.
include '../res/layout/header.php'; 


?>

<body>

<div class="text-center container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-5 col-lg-3">
            <main class="form-signin w-100 m-auto">
                 
                    <form id="loginForm" action="" method="POST">
                        <img class="mb-4" src="./res/images/logo.png" alt="Logo" width="250" height="130">
                        <h1 class="h3 mb-3 fw-normal">Bitte hier anmelden ...</h1>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Benutzername oder E-Mail">
                            <label for="floatingInput">Benutzername oder E-Mail</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Passwort">
                            <label for="floatingPassword">Passwort</label>
                        </div>
                        <div class="checkbox mb-3">
                            <label>
                                <input type="checkbox" name="remember" value="true"> Eingeloggt bleiben
                            </label>
                        </div>
                        <button class="w-100 btn btn-lg btn-primary" type="submit">anmelden</button>
                        <a class="w-100 btn btn-lg btn-secondary mt-2" href="../sites/user_register.php" role="button">registrieren</a>
                    </form>
               
            </main>
        </div>
    </div>
</div>

<script src="../js/login.js"></script> <!-- Correct path to your JavaScript file -->
</body>
</html>

<?php
include '../res/layout/footer.php';
?>

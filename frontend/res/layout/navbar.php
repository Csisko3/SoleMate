<!DOCTYPE html>
<html lang="de">


<nav class="navbar navbar-expand-sm">
    <div class="container">
        <a class="navbar-brand" href="./index.php"><img src="../res/img/logo.png" alt="Logo" width="30"
                height="24" class="d-inline-block align-text-top">
            SoleMate</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link mx-lg-2 bla" aria-current="page" href="./index.php">Startseite</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-lg-2 bla" href=" ">Unser Produkte</a> <!-- in Arbeit -->
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-lg-2 bla" href=" ">Warenkorb</a> <!-- in Arbeit -->
                </li>

                <li class="nav-item mx-lg-2 dropdown">
                    <a class="nav-link bla dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Weiteres
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="./impressum.php">Impressum</a></li>
                    </ul>
                </li>

                <?php 
                // Check if the user is logged in
                if (isset($_SESSION['username'])) {
                    echo '<li class="nav-item">
                            <a class="nav-link mx-lg-2 bla" href="sites/manager.php"> History </a>
                        </li>';
                    echo '<div class="nav-item">
                            <span class="nav-link mx-lg-2 hallo"> Hallo, ' . $_SESSION['username'] . '</span>
                        </div>';
                    echo '<li class="nav-item">
                            <a class="nav-link mx-lg-2 bla" href="?action=logout">Abmelden</a>
                        </li>';
                    echo '<li class="nav-item">
                        <a class="nav-link mx-lg-2 bla" href="sites/profile_manager.php">Profil bearbeiten</a>
                    </li>';

                    // Check if the user is an admin
                    if ($_SESSION['is_admin'] == 1) {
                        echo '<li class="nav-item">
                                <a class="nav-link mx-lg-2 bla" href="sites/adminpanel.php"> Adminpanel </a>
                            </li>';
                    }
                } else {
                    echo '<li class="nav-item">
                            <a class="nav-link mx-lg-2 bla" href="sites/login.php">Login</a>
                          </li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<?php
if (isset($_GET["action"]) && $_GET["action"] === "logout") {
    session_unset();
    session_destroy();

    header('Location: sites/index.php');
    exit();
}
?>
</body>
</html>
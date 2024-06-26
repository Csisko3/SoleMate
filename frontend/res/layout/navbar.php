<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index.php"><img src="../res/img/logo.jpeg" alt="Logo" width="50"
                height="50"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="./index.php">Startseite</a>
                </li>
                <li class="nav-item"><a class="nav-link mx-lg-2 bla" aria-current="page" href="./index.php">Unsere
                        Auswahl</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">Über uns</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="./impressum.php">Impressum</a></li>
                    </ul>
                </li>
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<div class="nav-item"><span class="nav-link mx-lg-2 hallo">Hallo, ' . $_SESSION['username'] . '</span></div>';
                    echo '<li class="nav-item"><a class="nav-link mx-lg-2 bla" href="../res/layout/logout.php">Abmelden</a></li>';
                    echo '<li class="nav-item dropdown">';
                    echo '<a class="nav-link dropdown-toggle mx-lg-2 bla" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Mein Konto</a>';
                    echo '<ul class="dropdown-menu">';
                    echo '<li class="nav-item"><a class="dropdown-item" href="./profile_manager.php">Konto verwalten</a></li>';
                    echo '<li class="nav-item"><a class="dropdown-item" href="./order_manager.php">Bestellungen ansehen</a></li>';
                    echo '</ul>';
                    echo '</li>';
                    if ($_SESSION['is_admin'] == 1) {
                        echo '<li class="nav-item dropdown">';
                        echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Adminpanel</a>';
                        echo '<ul class="dropdown-menu">';
                        echo '<li class="nav-item"><a class="nav-link mx-lg-2 bla" href="./admin_products.php">Produkte bearbeiten</a></li>';
                        echo '<li class="nav-item"><a class="nav-link mx-lg-2 bla" href="./admin_user.php">Kunden bearbeiten</a></li>';
                        echo '<li class="nav-item"><a class="nav-link mx-lg-2 bla" href="../sites/add_coupon.php">Gutschein erstellen</a></li>';
                        echo '<li class="nav-item"><a class="nav-link mx-lg-2 bla" href="../sites/manage_coupons.php">Gutscheine verwalten</a></li>';
                        echo '</ul>';
                        echo '</li>';
                    }
                } else {
                    echo '<li class="nav-item"><a class="nav-link mx-lg-2 bla" href="login.php">Login</a></li>';
                }
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" id="cartDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-solid fa-shopping-cart"></i> Warenkorb (<span id="cartCount">0</span>)
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end cart-dropdown" aria-labelledby="cartDropdown"
                        id="cartItems">
                        <li><a class="dropdown-item" href="#">Warenkorb ist leer</a></li>
                    </ul>
                </li>

            </ul>
            <form class="d-flex ms-auto position-relative" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                    id="searchInput">
                <!-- <button class="btn btn-dark" type="submit">Suchen</button> -->
                <div id="suggestions" class="list-group position-absolute"></div>
            </form>
        </div>
    </div>
</nav>
<?php
//header 
include '../res/layout/header.php';
include '../res/layout/navbar.php';
?>

<body>
<div class="container">
    <div class="filter-btn-container">
        <button class="btn btn-dark mt-3 filter-btn" data-category="">Alles anzeigen</button>
        <button class="btn btn-dark mt-3 filter-btn" data-category="Nike">Nike</button>
        <button class="btn btn-dark mt-3 filter-btn" data-category="Adidas">Adidas</button>
        <button class="btn btn-dark mt-3 filter-btn" data-category="Jordan">Jordan</button>
        <button class="btn btn-dark mt-3 filter-btn" data-category="Yeezy">Yeezy</button>
    </div>
    <h2 class="mb-3">Unsere Produkte:</h2>
    <div class="row" id="produktContainer">
        <!-- Produkte werden hier durch JS geladen -->
    </div>
</div>


<script src="../js/produkte.js"></script>
<script src="../js/login.js"></script> <!-- für autoLogin -->
</body>

<?php
//footer
include '../res/layout/footer.php';
?>
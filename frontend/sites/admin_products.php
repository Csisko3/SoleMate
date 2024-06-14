<?php
include '../res/layout/header.php';
include '../res/layout/navbar.php';

?>

<body>
    <div class="container mt-5">
        <h2>Neues Produkt hinzufügen</h2>
        <form id="productForm">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Kategorie</label>
                <input class="form-control" id="category" name="category"></input>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Preis</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01">
            </div>
            <div class="mb-3">
                <label for="picture" class="form-label">Produktbild</label>
                <input type="file" class="form-control" id="picture" name="picture">
            </div>
            <button type="submit" class="btn btn-primary">Speichern</button>
        </form>

        <h3 class="mt-5">Vorhandene Produkte</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Kategorie</th>
                    <th>Preis</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody id="productList">
                <!-- Produkte werden hier durch JS geladen -->
            </tbody>
        </table>
    </div>

    <!-----------------PopUp zum Produkte bearbeiten----------------->
    <div id="editProductModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Produkt bearbeiten</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editProductForm">
                    <div class="modal-body">
                        <input type="hidden" id="editProductId" name="id" >
                        <div class="form-group">
                            <label for="editProductName">Name</label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editProductPrice">Preis</label>
                            <input type="text" class="form-control" id="editProductPrice" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="editProductCategory">Kategorie</label>
                            <input type="text" class="form-control" id="editProductCategory" name="category" required>
                        </div>
                        <div class="form-group">
                            <label for="editProductPicture">Bild</label>
                            <input type="file" class="form-control" id="editProductPicture" name="picture">
                            <img id="currentProductPicture" src="" alt="Current Product Picture"
                                style="max-width: 100%; margin-top: 10px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="../js/users.js"></script>
</body>


<?php include '../res/layout/footer.php'; ?>

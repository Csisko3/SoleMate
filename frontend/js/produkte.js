$(document).ready(function() {
    console.log("AJAX-Aufruf startet");
    $.ajax({
        url: '../frontend/products.php', // Achten Sie darauf, dass die URL korrekt ist.
        dataType: 'json',
        success: function(data) {
            console.log("AJAX-Aufruf erfolgreich", data);
            var produktContainer = $('#produktContainer');
            produktContainer.empty(); // Bestehende Produkte löschen

            $.each(data, function(index, produkt) {
                produktContainer.append(`
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="../frontend/res/img/${produkt.bild}" width="200" height="200" class="card-img-top" alt="${produkt.name}">
                            <div class="card-body">
                                <h5 class="card-title">${produkt.name}</h5>
                                <p class="card-text">Preis: ${produkt.preis}</p>
                                <p class="card-text"><small class="text-muted">${produkt.bewertung} Sterne Bewertung</small></p>
                                <button class="btn btn-dark" onclick="addToCart(${produkt.id})">In den Warenkorb</button>
                            </div>
                        </div>
                    </div>
                `);
            });
        },
        error: function(xhr, status, error) {
            console.log('Fehlerstatus: ' + status);
            console.log('Fehlermeldung: ' + error);
            console.log('Serverantwort:', xhr.responseText);
        }
    });
});

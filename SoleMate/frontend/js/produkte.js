$(document).ready(function () {
  let isLoggedIn = false; // Variable zum Speichern des Login-Status
  checkLoginStatus(); // Überprüfen des Login-Status beim Laden der Seite für den Kauf
  // checkLoginStatus() ladet auch die Produkte auf die Seite

  // Klick-Event für Filter-Buttons
  $(".filter-btn").on("click", function () {
    const category = $(this).data("category");
    loadProducts(category);
  });

  // Event-Listener für das Suchfeld
  $('#searchInput').on('input', function () {
    const query = $(this).val();
    searchProducts(query);
  });

  // Funktion zum Laden der Produkte
  function loadProducts() {
    //console.log('Loading all products'); // Debugging-Ausgabe
    $.ajax({
      type: "GET",
      url: "/SoleMate/backend/logic/RequestHandler.php?resource=load_products",
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#produktContainer").empty();
          response.data.forEach(function (product) {
            const addToCartButton = isLoggedIn
              ? `<button class="btn btn-primary add-to-cart" data-id="${product.id}">In den Warenkorb</button>`
              : "";
            // Option zum kaufen soll erst dann aufscheinen, wenn der User eingeloggt ist
            $("#produktContainer").append(`
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <img src="/SoleMate/backend/logic/imageProxy.php?image=${product.picture}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.price} €</p>
                                ${addToCartButton}
                            </div>
                        </div>
                    </div>
                `);
          });
        } else {
          console.log("Fehler beim Laden der Produkte:", response.message);
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  }

  // Klick-Event für "In den Warenkorb" Buttons
  $(document).on("click", ".add-to-cart", function () {
    const productId = $(this).data("id");
    addToCart(productId);
  });

  // Funktion zum Hinzufügen von Produkten zum Warenkorb
  function addToCart(productId) {
    $.ajax({
      type: "POST",
      url: "/SoleMate/backend/logic/RequestHandler.php?resource=add_cart",
      data: JSON.stringify({ resource: "cart", product_id: productId }),
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        if (response.success) {
          //updateCartCount();
          alert("Produkt wurde dem Warenkorb hinzugefügt.");
        } else {
          console.log(
            "Fehler beim Hinzufügen zum Warenkorb:",
            response.message
          );
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  }

  // Funktion zum Aktualisieren der Warenkorb-Anzahl
  // Funktion zum Aktualisieren der Warenkorb-Anzahl
  /*
function updateCartCount() {
    $.ajax({
        type: "GET",
        url: "/SoleMate/backend/logic/RequestHandler.php",
        data: { resource: "cartCount" },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#cartCount").text(response.data.count);
            } else {
                console.log(
                    "Fehler beim Abrufen der Warenkorb-Anzahl:",
                    response.message
                );
            }
        },
        error: function (xhr, status, error) {
            console.log("Error:", error);
        },
    });
}
*/

  function checkLoginStatus() {
    $.ajax({
      type: "GET",
      url: "/SoleMate/backend/logic/RequestHandler.php",
      data: { resource: "checkLoginStatus" },
      dataType: "json",
      success: function (response) {
        if (response.success && response.isLoggedIn) {
          isLoggedIn = true;
        } else {
          isLoggedIn = false;
        }
        loadProducts(); // Produkte laden, nachdem der Anmeldestatus überprüft wurde
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
        loadProducts(); // Produkte laden, auch wenn der Anmeldestatus nicht überprüft werden konnte
      },
    });
  }

  function searchProducts(query) {
    $.ajax({
      type: "GET",
      url: "/SoleMate/backend/logic/RequestHandler.php",
      data: { resource: "search_products", query: query },
      dataType: "json",
      success: function (response) {
        console.log(query)
        console.log("Search Response:", response); // Debugging output
        $("#produktContainer").empty();
        if (response.success && response.data.length > 0) {
          response.data.forEach(function (product) {
            const addToCartButton = isLoggedIn
              ? `<button class="btn btn-primary add-to-cart" data-id="${product.id}">In den Warenkorb</button>`
              : "";
            $("#produktContainer").append(`
              <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                  <img src="/SoleMate/frontend/res/img/${product.picture}" class="card-img-top" alt="${product.name}">
                  <div class="card-body">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text">${product.price} €</p>
                    ${addToCartButton}
                  </div>
                </div>
              </div>
            `);
          });
        } else {
          $("#produktContainer").empty().append("<p>Kein Produkt gefunden</p>");
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
        console.log("Response Text:", xhr.responseText);
      },
    });
  }
 
  
});

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
  $("#searchInput").on("input", function () {
    const query = $(this).val();
    searchProducts(query);
  });
  function loadProducts() {
    $.ajax({
      type: "GET",
      url: "../../backend/logic/RequestHandler.php?resource=load_products",
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#produktContainer").empty();
          response.data.forEach(function (product) {
            let addToCartButton = "";
            if (isLoggedIn) {
              addToCartButton = `<button class="btn btn-dark add-to-cart" data-id="${product.id}">+ Hinzufügen</button>`;
            }
            $("#produktContainer").append(`
                        <div class="col-md-4">
                            <div class="card mb-3">
                                 <img src="../../backend/logic/imageProxy.php?image=${product.picture}"  
                                  width="200" height="200" class="card-img-top" alt="${product.name}"></img>
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text">Preis: ${product.price} €</p>
                                    ${addToCartButton}
                                </div>
                            </div>
                        </div>
                    `);
          });

          // Attach event handlers for "Add to Cart" buttons
          document.querySelectorAll(".add-to-cart").forEach((button) => {
            button.addEventListener("click", function () {
              const productId = this.dataset.id;
              addToCart(productId);
            });
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

  function checkLoginStatus() {
    $.ajax({
      type: "GET",
      url: "../../backend/logic/RequestHandler.php",
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
      url: "../../backend/logic/RequestHandler.php",
      data: { resource: "search_products", query: query },
      dataType: "json",
      success: function (response) {
        console.log(query);
        console.log("Search Response:", response); // Debugging output
        $("#produktContainer").empty();
        if (response.success && response.data.length > 0) {
          response.data.forEach(function (product) {
            let addToCartButton = "";
            if (isLoggedIn) {
              addToCartButton = `<button class="btn btn-dark add-to-cart" data-id="${product.id}">+ Hinzufügen</button>`;
            }
            $("#produktContainer").append(`
                        <div class="col-md-4">
                            <div class="card mb-3">
                                 <img src="../../backend/logic/imageProxy.php?image=${product.picture}"  
                                  width="200" height="200" class="card-img-top" alt="${product.name}"></img>
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text">Preis: ${product.price} €</p>
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

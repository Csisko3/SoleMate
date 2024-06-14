$(document).ready(function () {
  loadProducts();

  function loadProducts() {
    $.ajax({
      type: "GET",
      url: "/SoleMate/backend/logic/RequestHandler.php?resource=load_products",
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#productList").empty();
          response.data.forEach(function (product) {
            $("#productList").append(`
              <tr>
                <td>${product.name}</td>
                <td>${product.category}</td>
                <td>${product.price} €</td>
                <td>
                  <button class="btn btn-sm btn-primary edit-product" data-id="${product.id}">Bearbeiten</button>
                  <button class="btn btn-sm btn-danger delete-product" data-id="${product.id}">Löschen</button>
                </td>
              </tr>
            `);
          });

          $(".edit-product").on("click", function () {
            const productId = $(this).data("id");
            editProduct(productId);
          });

          $(".delete-product").on("click", function () {
            const productId = $(this).data("id");
            deleteProduct(productId);
          });
        } else {
          alert("Fehler beim Laden der Produkte: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  }

  $("#productForm").on("submit", function (e) {
    e.preventDefault(); // Verhindert das Standardformularverhalten
    const formData = new FormData(this);

    $.ajax({
      type: "POST",
      url: "/SoleMate/backend/logic/RequestHandler.php?resource=add_product",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          alert("Produkt erfolgreich gespeichert.");
          loadProducts();
          $("#productForm")[0].reset();
          $("#editProductModal").modal("hide"); // Schließt das Modal
        } else {
          alert("Fehler: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  });

  $("#editProductForm").on("submit", function (e) {
    e.preventDefault(); // Verhindert das Standardformularverhalten
    const formData = new FormData(this);
    const productId = $("#editProductId").val();


    let url = "/SoleMate/backend/logic/RequestHandler.php?resource=add_product";
    if (productId) {
      url =
        "/SoleMate/backend/logic/RequestHandler.php?resource=edit_product&id=" +
        productId;
    }

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          alert("Produkt erfolgreich gespeichert.");
          loadProducts();
          $("#productForm")[0].reset();
          $("#editProductModal").modal("hide");
        } else {
          alert("Fehler: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  });

  function editProduct(productId) {
    $.ajax({
      type: "GET",
      url:
        "/SoleMate/backend/logic/RequestHandler.php?resource=get_product&id=" +
        productId,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          const product = response.data;
          $("#editProductId").val(product.ID);
          $("#editProductName").val(product.name);
          $("#editProductPrice").val(product.price);
          $("#editProductCategory").val(product.category);
          $("#currentProductPicture").attr(
            "src",
            "/SoleMate/backend/logic/imageProxy.php?image=" + product.picture
          );
          $("#editProductModal").modal("show");
        } else {
          alert("Fehler beim Laden des Produkts: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  }

  function deleteProduct(productId) {
    if (confirm("Möchten Sie dieses Produkt wirklich löschen?")) {
      $.ajax({
        type: "DELETE",
        url: "/SoleMate/backend/logic/RequestHandler.php?resource=delete_product",
        data: JSON.stringify({ id: productId }),
        contentType: "application/json",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            alert("Produkt erfolgreich gelöscht.");
            loadProducts();
          } else {
            alert("Fehler beim Löschen des Produkts: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          console.log("ID:", productId);
          console.log("Error:", error);
        },
      });
    }
  }
});

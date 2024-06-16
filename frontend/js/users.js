document.addEventListener("DOMContentLoaded", function () {
  const registrationForm = document.getElementById("registrationForm");

  $("#registrationForm").on("submit", function (e) {
    e.preventDefault(); // verhindert Standardaktion
    const form = $(e.target);
    const json = getFormJSON(e.target);

    // validierungen hier ? oder als extra function und dann abrufen
    // console.log(json); Bugfixing
    registerUser(json);
  });

  function registerUser(json) {
    // console.log("Test", json);
    $.ajax({
      type: "POST",
      url: "/SoleMate/backend/logic/RequestHandler.php?resource=user",
      cache: false,
      data: JSON.stringify(json), // converts js object into string format
      dataType: "json",
      contentType: "application/json",
      success: function (response) {
        console.log("Successfully created user");
        alert("Benutzer erfolgreich registriert");

        setTimeout(function () {
          window.location.replace("index.php");
        }, 3000); // Redirect after 3 seconds
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
        console.log("Response Text:", xhr.responseText);
      },
    });
  }

  //--------------------Admin Funktionen--------------------

  $(document).ready(function () {
    loadCustomers();

    function loadCustomers() {
      $.ajax({
        type: "GET",
        url: "/SoleMate/backend/logic/RequestHandler.php?resource=loadCustomers",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#customerList").empty();
            response.data.forEach(function (customer) {
              $("#customerList").append(`
                              <tr>
                                  <td>${customer.username}</td>
                                  <td>${customer.firstname}</td>
                                  <td>${customer.lastname}</td>
                                  <td>${customer.adress}</td>
                                  <td>${customer.email}</td>
                                  <td>${
                                    customer.is_active == 1
                                      ? "Aktiv"
                                      : "Deaktiviert"
                                  }</td>
                                  <td>
                                   <button class="btn btn-sm btn-primary view-orders" data-id="${
                                     customer.id
                                   }">Bestellungen ansehen</button>
                                      ${
                                        customer.is_active == 1
                                          ? `<button class="btn btn-sm btn-danger change-customer-status" data-id="${customer.id}" data-status="deactivate">Deaktivieren</button>`
                                          : `<button class="btn btn-sm btn-success change-customer-status" data-id="${customer.id}" data-status="activate">Aktivieren</button>`
                                      }
                                  </td>
                              </tr>
                          `);
            });

            $(".view-orders").on("click", function () {
              const customerId = $(this).data("id");
              viewOrders(customerId);
            });

            $(".change-customer-status").on("click", function () {
              const customerId = $(this).data("id");
              const statusAction = $(this).data("status");
              changeCustomerStatus(customerId, statusAction);
            });
          } else {
            alert("Fehler beim Laden der Kunden: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          console.log("Error:", error);
        },
      });
    }

    function viewOrders(customerId) {
      $.ajax({
        type: "GET",
        url:
          "/SoleMate/backend/logic/RequestHandler.php?resource=get_orders_customer&customer_id=" +
          customerId,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#orderDetails").empty();
            response.orders.forEach(function (order) {
              order.order_details.forEach(function (item) {
                $("#orderDetails").append(`
                                <tr>
                                    <td>${item.product_name}</td>
                                    <td>${item.product_price} €</td>
                                    <td>${item.quantity}</td>
                                    <td>${(
                                      item.product_price * item.quantity
                                    ).toFixed(2)} €</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger remove-item" data-order-id="${
                                          order.order_id
                                        }" data-product-id="${item.product_id}">Entfernen</button>
                                    </td>
                                </tr>
                            `);
              });
            });

            $(".remove-item").on("click", function () {
              const orderId = $(this).data("order-id");
              const productId = $(this).data("product-id");
              removeItem(orderId, productId);
            });

            $("#orderDetailsModal").modal("show");
          } else {
            alert("Fehler beim Laden der Bestelldetails: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          console.log("Error:", error);
        },
      });
    }

    function changeCustomerStatus(customerId, action) {
      const confirmMessage =
        action === "deactivate"
          ? "Möchten Sie diesen Kunden wirklich deaktivieren?"
          : "Möchten Sie diesen Kunden wirklich aktivieren?";

      if (confirm(confirmMessage)) {
        $.ajax({
          type: "POST",
          url: "/SoleMate/backend/logic/RequestHandler.php?resource=change_customer_status",
          data: JSON.stringify({ id: customerId, action: action }),
          contentType: "application/json",
          dataType: "json",
          success: function (response) {
            if (response.success) {
              alert(
                "Kunde erfolgreich " +
                  (action === "deactivate" ? "deaktiviert" : "aktiviert") +
                  "."
              );
              loadCustomers();
            } else {
              alert(
                "Fehler beim " +
                  (action === "deactivate" ? "Deaktivieren" : "Aktivieren") +
                  " des Kunden: " +
                  response.message
              );
            }
          },
          error: function (xhr, status, error) {
            console.log("Error:", error);
          },
        });
      }
    }

    function removeItem(orderId, productId) {
      if (confirm("Möchten Sie dieses Produkt wirklich aus der Bestellung entfernen?")) {
          $.ajax({
              type: "POST",
              url: "/SoleMate/backend/logic/RequestHandler.php?resource=remove_order_item",
              data: JSON.stringify({ order_id: orderId, product_id: productId }),
              contentType: "application/json",
              dataType: "json",
              success: function (response) {
                  if (response.success) {
                      alert("Produkt erfolgreich entfernt.");
                      viewOrders(response.customer_id);
                  } else {
                      alert("Fehler beim Entfernen des Produkts: " + response.message);
                  }
              },
              error: function (xhr, status, error) {
                  console.log("Error:", error);
              }
          });
      }
  }

    //---------------Profile Management-----------------
    // Profil-Daten laden
    loadProfile();

    // Event-Handler für das Formular
    $("#profileForm").on("submit", function (e) {
      e.preventDefault(); // Verhindert das Standardformularverhalten
      const formData = getFormJSON(this);

      // Passwort validieren
      if (!formData.password) {
        alert("Bitte geben Sie Ihr Passwort ein, um Änderungen zu speichern.");
        return;
      }

      // Neues Passwort validieren
      if (
        formData.new_password &&
        formData.new_password !== formData.confirm_password
      ) {
        alert("Das neue Passwort und die Bestätigung stimmen nicht überein.");
        return;
      }

      // Bestätigung vor dem Speichern anzeigen
      if (confirm("Möchten Sie wirklich Ihre Profilinformationen ändern?")) {
        // Profil-Daten speichern
        saveProfile(formData);
      }
    });

    // Funktion zum Laden der Profil-Daten
    function loadProfile() {
      $.ajax({
        type: "GET",
        url: "/SoleMate/backend/logic/RequestHandler.php?resource=load_profile",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#username").val(response.data.username);
            $("#email").val(response.data.email);
            $("#firstname").val(response.data.firstname);
            $("#lastname").val(maskData(response.data.lastname));
            $("#adress").val(maskData(response.data.adress));
            $("#postcode").val(response.data.postcode);
            $("#city").val(response.data.city);
            $("#payment_info").val(response.data.payment_info);
          } else {
            console.log(
              "Fehler beim Laden der Profil-Daten:",
              response.message
            );
          }
        },
        error: function (xhr, status, error) {
          console.log("Error:", error);
        },
      });
    }

    // Funktion zum Maskieren von sensiblen Daten
    function maskData(data) {
      if (data && data.length > 2) {
        return data.substring(0, 2) + "*******";
      }
      return data;
    }

    // Funktion zum Speichern der Profil-Daten
    function saveProfile(formData) {
      // Ensure the formData does not contain masked data
      let originalData = {};
      if (formData.lastname && formData.lastname.includes("*******")) {
        formData.lastname = originalData.lastname;
      }
      if (formData.adress && formData.adress.includes("*******")) {
        formData.adress = originalData.adress;
      }
      // Ensure that only changed fields are sent
      let dataToSend = {};
      for (let key in formData) {
        if (formData[key] !== originalData[key]) {
          dataToSend[key] = formData[key];
        }
      }

      $.ajax({
        type: "POST",
        url: "/SoleMate/backend/logic/RequestHandler.php?resource=update_profile",
        data: JSON.stringify(formData),
        contentType: "application/json",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            alert("Profil erfolgreich aktualisiert.");
          } else {
            console.log(
              "Fehler beim Speichern der Profil-Daten:",
              response.message
            );
          }
        },
        error: function (xhr, status, error) {
          console.log("Error:", error);
        },
      });
    }
  });

  //---------------helper functions-----------------
  /**
   * Creates a json object including fields in the form
   *
   * @param {HTMLElement} form The form element to convert
   * @return {Object} The form data
   */
  const getFormJSON = (form) => {
    const data = new FormData(form);
    return Array.from(data.keys()).reduce((result, key) => {
      result[key] = data.get(key);
      return result;
    }, {});
  };
});

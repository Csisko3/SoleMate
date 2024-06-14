document.addEventListener("DOMContentLoaded", function () {
  const registrationForm = document.getElementById("registrationForm");

  $("#registrationForm").on("submit", function (e) {
    e.preventDefault(); //verhindert Standardaktion
    const form = $(e.target);
    const json = getFormJSON(e.target);

    // validierungen hier ? oder als extra function und dann abrufen
    // console.log(json); Bugfixing
    registerUser(json);
  });

  function registerUser(json) {
    //console.log("Test", json);
    $.ajax({
      type: "POST",
      url: "/SoleMate/backend/logic/RequestHandler.php?resource=user",
      cache: false,
      data: JSON.stringify(json), //converts js object into string format
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
                  <td>${customer.is_active == 1 ? "Aktiv" : "Deaktiviert"}</td>
                  <td>
                    ${
                      customer.is_active == 1
                        ? `<button class="btn btn-sm btn-danger change-customer-status" data-id="${customer.id}" data-status="deactivate">Deaktivieren</button>`
                        : `<button class="btn btn-sm btn-success change-customer-status" data-id="${customer.id}" data-status="activate">Aktivieren</button>`
                    }
                  </td>
                </tr>
              `);
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
  });
});

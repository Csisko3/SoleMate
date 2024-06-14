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

  //---------------profile Management-----------------
  $(document).ready(function () {
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
      // Ensure the formData does not contain masked data'
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
});

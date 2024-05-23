document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");

  $("#loginForm").on("submit", function (e) {
    e.preventDefault(); // verhindert die Standardaktion
    const form = $(e.target);
    const json = getFormJSON(e.target);

    // console.log(json); Bugfixing
    loginUser(json);
  });

  function loginUser(json) {
    // console.log("Test", json);
    $.ajax({
      type: "POST",
      url: "/SoleMate/backend/logic/RequestHandler.php?resource=login",
      cache: false,
      data: JSON.stringify(json), // konvertiert JS-Objekt in String-Format
      dataType: "json",
      contentType: "application/json",
      success: function (response) {
          console.log("Successfully logged in");
          alert("Login erfolgreich");

          setTimeout(function () {
            window.location.replace("index.php");
          }, 3000); // Redirect nach 3 Sekunden
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
        if (xhr.status === 401) {
          alert("Falscher Benutzername oder falsches Passwort.");
        } else {
          alert("Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.");
        }
        console.log("Response Text:", xhr.responseText);
      },
    });
  }

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

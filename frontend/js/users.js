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
      contentType: 'application/json',
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

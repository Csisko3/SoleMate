document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");

    // Überprüfen, ob der Benutzer-Cookie gesetzt ist
    if (document.cookie.split(';').some((item) => item.trim().startsWith('user_id='))) {
        autoLogin(); // Automatisches Login
    }

    $("#loginForm").on("submit", function (e) {
        e.preventDefault(); // verhindert die Standardaktion
        const form = $(e.target);
        const json = getFormJSON(e.target);

        // console.log(json); Bugfixing
        loginUser(json);
    });

    function loginUser(json) {
        console.log("Test", json);
        $.ajax({
            type: "POST",
            url: "../../backend/logic/RequestHandler.php?resource=login",
            cache: false,
            data: JSON.stringify(json), // konvertiert js-Objekt in String-Format
            dataType: "json",
            contentType: "application/json",
            success: function (response) {
                if (response.success) {
                    console.log("Successfully logged in");
                    setTimeout(function () {
                        window.location.replace("index.php");
                    });
                } else {
                    console.log("Login failed:", response.message);
                    alert("Login fehlgeschlagen: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error:", error);
                alert("Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.");
                console.log("Response Text:", xhr.responseText);
            },
        });
    }

    function autoLogin() {
        $.ajax({
            type: "GET",
            url: "../../backend/logic/RequestHandler.php?resource=autoLogin",
            cache: false,
            dataType: "json",
            contentType: "application/json",
            success: function (response) {
                if (response.success) {
                    console.log("Successfully auto-logged in");
                } else {
                    console.log("Auto-Login failed:", response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error:", error);
                alert("Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.");
                console.log("Response Text:", xhr.responseText);
            }
        });
    }


    /**
     * Erstellt ein JSON-Objekt einschließlich der Felder im Formular
     *
     * @param {HTMLElement} form Das Formular-Element zum Konvertieren
     * @return {Object} Die Formulardaten
     */
    const getFormJSON = (form) => {
        const data = new FormData(form);
        return Array.from(data.keys()).reduce((result, key) => {
            result[key] = data.get(key);
            return result;
        }, {});
    };
});

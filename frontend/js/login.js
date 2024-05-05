document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");

    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(loginForm);
        const json = Object.fromEntries(formData.entries());

        fetch('SoleMate/backend/logic/RequestHandler.php?resource=user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(json),
        })
        .then(response => response.json())
        .then(data => {
            if (data.loginStatus === 'success') {
                window.location.replace("index.php");
            } else {
                const errorMsg = data.errorCode === 1 ? 'Password ist falsch.' : 'Benutzer nicht gefunden.';
                alert(`Login fehlgeschlagen! Fehler: ${errorMsg}`);
            }
        })
        .catch(error => {
            console.error('Error during login:', error);
            alert('Fehler beim Login!');
        });
    });
});

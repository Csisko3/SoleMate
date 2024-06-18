$(document).ready(function() {
    load_couponList();

    function load_couponList() {
        $.ajax({
            url: "../../backend/logic/RequestHandler.php", 
            method: "GET",
            dataType: "json",
            data: { resource: "load_coupons" },
            success: function(response) {
                console.log(response);
                $('#couponTable').empty();
                let content = "";
                $.each(response, function(key, coupon) {
                    var formattedExpired = coupon.expired ? "Ja" : "Nein";
                    content += `
                    <tr>
                        <td>${coupon.id}</td>
                        <td>${coupon.code}</td>
                        <td>${coupon.amount}</td>
                        <td>${coupon.residual_value}</td>
                        <td>${coupon.expiration_date}</td>
                        <td>${formattedExpired}</td>
                    `;
                    content += "</tr>";
                });
                $('#couponTable').html(content);
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    $("#generateCodeButton").on("click", function() {
        var couponCode = generateCouponCode(5);
        $("#couponCode").val(couponCode);
    });

    $("#couponForm").on("submit", function(e) {
        e.preventDefault();
        const form = $(e.target);
        const json = convertFormToJSON(form);
        submitCoupon(json);
    });

    function generateCouponCode(length) {
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var couponCode = '';
        for (var i = 0; i < length; i++) {
            var randomIndex = Math.floor(Math.random() * characters.length);
            couponCode += characters.charAt(randomIndex);
        }
        return couponCode;
    }

    function submitCoupon(json) {
        $.ajax({
            url: '../../backend/logic/RequestHandler.php?resource=coupon', 
            method: 'POST',
            dataType: 'json',
            data: JSON.stringify(json),
            contentType: 'application/json',
            success: function(response) {
                showAlert('success', `Coupon ${response.code} erfolgreich erstellt!`);
                console.log("Successfully created coupon");
                load_couponList(); // Update the list after adding a new coupon
            },
            error: function(xhr, status, error) {
                showAlert('danger', 'Fehler beim Erstellen des Coupons!');
                console.log("There has been an error");
                console.log(error);
            }
        });

    }
    function convertFormToJSON(form) {
        const formData = new FormData(form[0]);
        const json = {};
        formData.forEach((value, key) => {
            json[key] = value;
        });
        return json;
    }

    function showAlert(type, message) {
        const alertPlaceholder = $('#alertPlaceholder');
        const alertHTML = `<div class="alert alert-${type}" role="alert">${message}</div>`;
        alertPlaceholder.html(alertHTML);
        setTimeout(() => {
            alertPlaceholder.empty();
        }, 3000);
    }
});
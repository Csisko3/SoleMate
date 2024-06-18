document.addEventListener('DOMContentLoaded', function () {

    // window.addToCart erlaubt das function global aufgerufen werden kann
    window.addToCart = function addToCart(productId) {
        fetch("../../backend/logic/RequestHandler.php?resource=add_cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ product_id: productId }),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                updateCart();
            } else {
                alert("Fehler beim Hinzufügen des Produkts zum Warenkorb.");
            }
        })
        .catch((error) => {
            console.error("Fehler:", error);
        });
    };

    // window.updateCartItem erlaubt das function global aufgerufen werden kann
    window.updateCartItem = function updateCartItem(productId, quantity) {
        fetch("../../backend/logic/RequestHandler.php?resource=update_cart_item", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity }),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                updateCartDisplay(productId, quantity);
                updateCartCount();
            } else {
                alert("Fehler beim Aktualisieren des Warenkorbs.");
            }
        })
        .catch((error) => {
            console.error("Fehler:", error);
        });
    };

    const proceedToCheckoutButton = document.getElementById('proceedToCheckout');
    if (proceedToCheckoutButton) {
        proceedToCheckoutButton.addEventListener('click', function() {
            var checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
            checkoutModal.show();
        });
    }

    // Function to update cart display
    function updateCartDisplay(productId, quantity) {
        const row = document.querySelector(`button[data-id="${productId}"]`).closest("tr");
        if (row) {
            if (quantity === 0) {
                row.remove();
            } else {
                const price = parseFloat(row.querySelector("td:nth-child(2)").innerText.replace(" €", ""));
                const itemTotal = price * quantity;
                row.querySelector(".item-total").innerText = itemTotal + " €";
                row.querySelector(".quantity").innerText = quantity;
            }
            updateTotalAmount();
        }
    }

    // Function to update total amount
    function updateTotalAmount() {
        let total = 0;
        document.querySelectorAll(".item-total").forEach((item) => {
            total += parseFloat(item.innerText.replace(" €", ""));
        });
        document.getElementById("totalAmount").innerText = total + " €";
    }

    // Function to update cart count in the navbar
    function updateCartCount() {
        fetch("../../backend/logic/RequestHandler.php?resource=get_cart", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
        .then((response) => response.json())
        .then((data) => {
            let totalItems = 0;
            if (data.cart.length > 0) {
                totalItems = data.cart.reduce((sum, item) => sum + item.quantity, 0);
            }
            document.getElementById("cartCount").textContent = totalItems;
        })
        .catch((error) => {
            console.log("Fehler:", error);
        });
    }

    // Function to update cart
    function updateCart() {
        fetch("../../backend/logic/RequestHandler.php?resource=get_cart", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
        .then((response) => response.json())
        .then((data) => {
            const cartItems = $("#cartItems");
            cartItems.empty();

            let totalItems = 0;

            if (data.cart.length === 0) {
                cartItems.append('<tr><td colspan="5">Warenkorb ist leer</td></tr>');
            } else {
                data.cart.forEach((item) => {
                    const itemTotal = item.price * item.quantity;
                    cartItems.append(`
                        <tr>
                            <td><img src="../../backend/logic/imageProxy.php?image=${item.picture}" width='50' height='50' alt='${item.name}'> ${item.name}</td>
                            <td>${item.price} €</td>
                            <td>
                                <div class="quantity-buttons">
                                    <button class="btn btn-secondary decrease-quantity" data-id='${item.product_id}'>-</button>
                                    <span class="quantity">${item.quantity}</span>
                                    <button class="btn btn-secondary increase-quantity" data-id='${item.product_id}'>+</button>
                                </div>
                            </td>
                            <td class='item-total'>${itemTotal} €</td>
                            <td><button class='btn btn-danger remove-from-cart' data-id='${item.product_id}'>Entfernen</button></td>
                        </tr>
                    `);
                    totalItems += item.quantity;
                });
                cartItems.append('<tr><th colspan="3">Gesamt</th><th id="totalAmount">0 €</th><th></th></tr>');
                cartItems.append('<tr><td colspan="5" class="text-center"><button class="btn btn-primary" id="checkoutButton">Zum Warenkorb</button></td></tr>');
                updateTotalAmount();
            }

            $("#cartCount").text(totalItems);

            // Reattach event handlers
            document.querySelectorAll(".increase-quantity").forEach((button) => {
                button.addEventListener("click", function () {
                    const productId = this.dataset.id;
                    const quantityElement = this.previousElementSibling;
                    let quantity = parseInt(quantityElement.innerText);
                    quantity++;
                    quantityElement.innerText = quantity;
                    updateCartItem(productId, quantity);
                });
            });

            document.querySelectorAll(".decrease-quantity").forEach((button) => {
                button.addEventListener("click", function () {
                    const productId = this.dataset.id;
                    const quantityElement = this.nextElementSibling;
                    let quantity = parseInt(quantityElement.innerText);
                    if (quantity > 1) {
                        quantity--;
                        quantityElement.innerText = quantity;
                        updateCartItem(productId, quantity);
                    }
                });
            });

            document.querySelectorAll(".remove-from-cart").forEach((button) => {
                button.addEventListener("click", function () {
                    const productId = this.dataset.id;
                    updateCartItem(productId, 0);
                });
            });

            const checkoutButton = document.getElementById("checkoutButton");
            if (checkoutButton) {
                checkoutButton.addEventListener("click", function() {
                    window.location.href = "./cart.php";
                });
            }

        })
        .catch((error) => {
            console.log("Fehler:", error);
        });
    }

    // Neue Funktion, um den Warenkorb auf cart.php anzuzeigen
    function displayCartOnPage() {
        fetch("../../backend/logic/RequestHandler.php?resource=get_cart", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
        .then((response) => response.json())
        .then((data) => {
            const cartPageItems = document.getElementById("cartPageItems");
            if (!cartPageItems) return;

            cartPageItems.innerHTML = '';

            let total = 0;

            if (data.cart.length === 0) {
                cartPageItems.innerHTML = '<tr><td colspan="5">Warenkorb ist leer</td></tr>';
            } else {
                data.cart.forEach((item) => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    cartPageItems.innerHTML += `
                        <tr>
                            <td><img src="../../backend/logic/imageProxy.php?image=${item.picture}" width='50' height='50' alt='${item.name}'> ${item.name}</td>
                            <td>${item.price} €</td>
                            <td><input type='number' class='form-control quantity' data-id='${item.product_id}' value='${item.quantity}' min='1'></td>
                            <td class='item-total'>${itemTotal} €</td>
                            <td><button class='btn btn-danger remove-from-cart' data-id='${item.product_id}'>Entfernen</button></td>
                        </tr>
                    `;
                });
                cartPageItems.innerHTML += `<tr><th colspan="3">Gesamt</th><th id="totalAmount">${total} €</th><th></th></tr>`;
            }

            document.querySelectorAll(".quantity").forEach((input) => {
                input.addEventListener("change", function () {
                    const productId = this.dataset.id;
                    const quantity = parseInt(this.value);
                    updateCartItem(productId, quantity);
                    updateCartDisplay(productId, quantity); // Ensure immediate display update
                    updateCartCount(); // Update cart count in navbar
                });
            });

            document.querySelectorAll(".remove-from-cart").forEach((button) => {
                button.addEventListener("click", function () {
                    const productId = this.dataset.id;
                    updateCartItem(productId, 0);
                    updateCartDisplay(productId, 0); // Ensure immediate display update
                    updateCartCount(); // Update cart count in navbar
                });
            });

            updateTotalAmount(); // Ensure the total amount is updated
        })
        .catch((error) => {
            console.log("Fehler:", error);
        });
    }

    // Check if we're on the cart.php page to update the cart items
    if (window.location.pathname.endsWith("cart.php")) {
        displayCartOnPage();
    }

    // Initialize cart on page load
    updateCart();
    const checkoutForm = document.getElementById("checkoutForm");
    if (checkoutForm) {
        checkoutForm.addEventListener("submit", function(event) {
            event.preventDefault();
            submitOrder();
        });
    }

    function submitOrder() {
        const formData = new FormData(document.getElementById("checkoutForm"));
        const data = Object.fromEntries(formData.entries());

        fetch("../../backend/logic/RequestHandler.php?resource=place_order", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("Order placed successfully!");
                window.location.href = "../sites/index.php";;
            } else {
                alert("Error placing order: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
    }
});
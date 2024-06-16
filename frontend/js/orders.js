document.addEventListener('DOMContentLoaded', function () {
    fetchOrders();

    function fetchOrders() {
        fetch('../../backend/logic/RequestHandler.php?resource=get_orders', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Fetched data:', data); // Log the fetched data
            if (data.success) {
                renderOrders(data.orders);
            } else {
                document.getElementById('ordersContainer').innerHTML = '<p>Keine Bestellungen gefunden.</p>';
            }
        })
        .catch(error => {
            console.error('Fehler:', error);
        });
    }

    function renderOrders(orders) {
        const ordersContainer = document.getElementById('ordersContainer');
        ordersContainer.innerHTML = '';

        if (orders.length === 0) {
            ordersContainer.innerHTML = '<p>Keine Bestellungen gefunden.</p>';
            return;
        }

        const accordion = document.createElement('div');
        accordion.classList.add('accordion');
        accordion.id = 'ordersAccordion';

        orders.forEach((order, index) => {
            console.log('Processing order:', order); // Log each order

            const orderDate = new Date(order.order_date);
            
            // Zeit und Datum formatieren
            const formattedDate = orderDate.toLocaleDateString('de-DE');
            const formattedTime = orderDate.toLocaleTimeString('de-DE', { hour: '2-digit', minute: '2-digit' });

            const accordionItem = document.createElement('div');
            accordionItem.classList.add('accordion-item');

            // Parse the order_details JSON string
            const orderDetails = JSON.parse(order.order_details);

            accordionItem.innerHTML = `
                <h2 class="accordion-header" id="heading${order.order_id}">
                    <button class="accordion-button ${index === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${order.order_id}" aria-expanded="${index === 0 ? 'true' : 'false'}" aria-controls="collapse${order.order_id}">
                        Bestellung vom ${formattedDate} um ${formattedTime}
                    </button>
                </h2>
                <div id="collapse${order.order_id}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" aria-labelledby="heading${order.order_id}" data-bs-parent="#ordersAccordion">
                    <div class="accordion-body">
                        <div class="order-info">
                            <p><strong>Name:</strong> ${order.name}</p>
                            <p><strong>Adresse:</strong> ${order.address}</p>
                            <p><strong>Zahlungsmethode:</strong> ${order.payment_method}</p>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produkt</th>
                                    <th>Preis</th>
                                    <th>Menge</th>
                                    <th>Gesamt</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${orderDetails.map(item => `
                                    <tr>
                                        <td>${item.product_name}</td>
                                        <td>${item.product_price} €</td>
                                        <td>${item.quantity}</td>
                                        <td>${item.product_price * item.quantity} €</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                        <div class="text-end">
                            <button class="btn btn-primary print-invoice" data-order-id="${order.order_id}">Rechnung drucken</button>
                        </div>
                    </div>
                </div>
            `;

            accordion.appendChild(accordionItem);
        });

        ordersContainer.appendChild(accordion);

        document.querySelectorAll('.print-invoice').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-order-id');
                window.open(`../../backend/logic/RequestHandler.php?resource=print_invoice&order_id=${orderId}`, '_blank');
            });
        });
    }
});

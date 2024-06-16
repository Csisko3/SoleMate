<?php
require_once '../../vendor/autoload.php';
require_once '../config/dbaccess.php';

use Dompdf\Dompdf;

$order_id = $_GET['order_id'];

global $host, $db_user, $db_password, $database;
$conn = new mysqli($host, $db_user, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders WHERE order_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

$order_details = json_decode($order['order_details'], true);

// HTML-Inhalt für die PDF-Rechnung
$html = '<html><head><style>
            body { font-family: DejaVu Sans, sans-serif; }
            .header { text-align: center; }
            .info { margin-bottom: 20px; }
            .table { width: 100%; border-collapse: collapse; }
            .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        </style></head><body>';
$html .= '<div class="header"><h1>Rechnung</h1></div>';
$html .= '<div class="info">
            <p>Bestellnummer: ' . $order['order_id'] . '</p>
            <p>Datum: ' . $order['order_date'] . '</p>
            <p>Name: ' . $order['name'] . '</p>
            <p>Adresse: ' . $order['address'] . '</p>
            <p>Zahlungsmethode: ' . $order['payment_method'] . '</p>
          </div>';
$html .= '<table class="table">
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Preis</th>
                    <th>Menge</th>
                    <th>Gesamt</th>
                </tr>
            </thead>
            <tbody>';

$total = 0;
foreach ($order_details as $item) {
    $total += $item['product_price'] * $item['quantity'];
    $html .= '<tr>
                <td>' . $item['product_name'] . '</td>
                <td>' . number_format($item['product_price'], 2) . ' €</td>
                <td>' . $item['quantity'] . '</td>
                <td>' . number_format($item['product_price'] * $item['quantity'], 2) . ' €</td>
              </tr>';
}

$html .= '</tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Gesamt</th>
                    <th>' . number_format($total, 2) . ' €</th>
                </tr>
            </tfoot>
          </table>';
$html .= '</body></html>';

// PDF generieren
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Rechnung_' . $order['order_id'] . '.pdf');

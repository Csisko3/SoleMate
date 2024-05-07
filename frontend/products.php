<?php
header('Content-Type: application/json');

// Pfad zur JSON-Datei
$jsonFilePath = __DIR__ . '/produkte.json';

// Überprüfen, ob die Datei existiert
if (!file_exists($jsonFilePath)) {
    echo json_encode(['error' => 'Datei nicht gefunden']);
    exit;
}

// JSON-Daten aus der Datei lesen
$jsonData = file_get_contents($jsonFilePath);

// Die JSON-Daten zurückgeben
echo $jsonData;

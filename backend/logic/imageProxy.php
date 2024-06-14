<?php
// Überprüfen, ob der Dateiname im Query-String übergeben wurde
if (isset($_GET['image'])) {
    $image = $_GET['image'];
    $imagePath = __DIR__ . '/../productpictures/' . $image;

    // Überprüfen, ob die Datei existiert
    if (file_exists($imagePath)) {
        // Den korrekten MIME-Typ ermitteln und den Header setzen
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $imagePath);
        finfo_close($finfo);
        header('Content-Type: ' . $mimeType);

        // Die Bilddatei ausgeben
        readfile($imagePath);
        exit;
    } else {
        // Fehler: Datei nicht gefunden
        http_response_code(404);
        echo "Bild nicht gefunden.";
        exit;
    }
} else {
    http_response_code(400);
    echo "Kein Bild angegeben.";
    exit;
}
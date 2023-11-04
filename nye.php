<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Test #1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="map" style="width: 100%; height: 60vh"></div>
    <?php
    require 'db/dbconn.php'; // Include the database connection class

    $database = Database::getInstance();

    $todaQuery = "SELECT toda, terminal FROM todalocation";
    $todaResult = $database->executeQuery($todaQuery);

    $todaLocations = [];

    while ($tl = $database->fetchSingleRow($todaResult)) {
        $todaLocations[] = [
            'toda' => $tl['toda'],
            'terminal' => json_decode($tl['terminal'], true)
        ];
    }

    $todalocationData = json_encode($todaLocations);
    ?>

    <script>
        var map = L.map("map", {
            zoomControl: false,
            doubleClickZoom: false,
        }).setView([14.954261516104285, 120.90080130961766], 16);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        const todalocations = <?php echo $todalocationData; ?>;
        const markersLayer = L.layerGroup().addTo(map);

        function displayMarkers() {
            todalocations.forEach((location) => {
                const { lat, lng } = location.terminal.latlng;
                const marker = L.marker([lat, lng]).addTo(markersLayer);
                marker.bindPopup(`${location.toda} Terminal`);
            });
        }

        displayMarkers();
    </script>
</body>

</html>

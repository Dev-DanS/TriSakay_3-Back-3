<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Test #1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/test.css" />
</head>

<body>
    <div id="map" style="width: 100%; height: 60vh"></div>
    <h5 id="dateUpdated"></h5>
    <script>
        var map = L.map("map", {
            zoomControl: false,
            doubleClickZoom: false,
        }).setView([14.954247166813438, 120.90079974777437], 16);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        // Initial marker, which will be updated with real data
        var marker = L.marker([14.954247166813438, 120.90079974777437]).addTo(map);

        // Function to fetch data from the server and update the map
        function fetchDataAndUpdateMap() {
            $.ajax({
                url: 'viewer.php', // Your PHP file that fetches data from the database
                type: 'POST',
                data: { 'liveID': 5 }, // Your hardcoded liveID
                dataType: 'json',
                success: function (response) {
                    // Assuming the response has the structure { LiveLat: '', LiveLng: '', DateUpdated: ''}
                    if (response.LiveLat && response.LiveLng) {
                        var newLatLng = new L.LatLng(response.LiveLat, response.LiveLng);
                        marker.setLatLng(newLatLng); // Update marker position
                        map.panTo(newLatLng); // Optionally pan the map to the new marker location
                        marker.bindPopup("Driver Test").openPopup(); // Add popup to the marker
                    }
                    if (response.DateUpdated) {
                        document.getElementById('dateUpdated').textContent = "Last Updated: " + response.DateUpdated; // Update the date display
                    }
                },
                error: function (xhr, status, error) {
                    console.error("An error occurred: " + error);
                }
            });
        }


        // Update the map every second
        setInterval(fetchDataAndUpdateMap, 1000);

    </script>
</body>

</html>
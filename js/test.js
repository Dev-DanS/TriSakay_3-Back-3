var map = L.map("map", {
  zoomControl: false,
  doubleClickZoom: false,
}).setView([0, 0], 15);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

var pickupPoint;

function updatePickupPoint(lat, lng, accuracy) {
  var signalStrength = accuracy;
  var signalStrengthCategory, textColor;

  if (signalStrength <= 40) {
    signalStrengthCategory = "Good";
    textColor = "green";
  } else if (signalStrength <= 80) {
    signalStrengthCategory = "Fair";
    textColor = "yellow";
  } else {
    signalStrengthCategory = "Bad";
    textColor = "red";
  }

  var coordinatesElement = document.getElementById("coordinates");
  coordinatesElement.textContent = `Latitude: ${lat}, Longitude: ${lng}`;

  if (pickupPoint) {
    pickupPoint.setLatLng([lat, lng]);
    pickupPoint
      .getPopup()
      .setContent(
        `Signal Strength: <span style="color: ${textColor};">${signalStrengthCategory}</span><br>You are here!`
      );
  } else {
    pickupPoint = L.marker([lat, lng]).addTo(map);
    pickupPoint
      .bindPopup(
        `Signal Strength: <span style="color: ${textColor};">${signalStrengthCategory}</span><br>You are here!`
      )
      .openPopup();
  }

  map.setView([lat, lng], 15);
}

function updateLocation(position) {
  var { latitude, longitude, accuracy } = position.coords;
  updatePickupPoint(latitude, longitude, accuracy);

  $.ajax({
    url: "https://nominatim.openstreetmap.org/reverse",
    method: "GET",
    dataType: "json",
    data: {
      format: "json",
      lat: latitude,
      lon: longitude,
      zoom: 18,
    },
    success: function (data) {
      var address = data.display_name.split(",").slice(0, -3).join(",");
      $(".address h5").text(address);
    },
    error: function (error) {
      console.error("Error getting address: " + error.statusText);
    },
  });
}

function handleError(error) {
  console.error("Error:", error.message);
}

var options = {
  enableHighAccuracy: true,
  timeout: 10000,
  maximumAge: 0,
};

var watchId = navigator.geolocation.watchPosition(
  updateLocation,
  handleError,
  options
);


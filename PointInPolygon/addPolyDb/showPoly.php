<?php
// Database connection
$servername = "localhost";
$username = "";
$password = "";
$database = "db name";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch polygons from database
$sql = "SELECT name, coordinates FROM polygons";
$result = $conn->query($sql);
$polygons = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $coordinates = json_decode($row['coordinates'], true);
        $polygons[] = array(
            'name' => $row['name'],
            'coordinates' => $coordinates
        );
    }
}
// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Google Maps - Draw Geofences</title>
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous"
    />
    <script src="https://maps.googleapis.com/maps/api/js?key=******************************=drawing" async defer></script>
    <script>
        var map;
        var polygons = <?php echo json_encode($polygons); ?>;

        function InitMap() {
            var location = new google.maps.LatLng(26.128133, 89.139163);
            var mapOptions = {
                zoom: 8,
                center: location,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            };
            map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

            // Draw polygons
            polygons.forEach(function(polygon) {
                var polygonCoords = [];
                polygon.coordinates.forEach(function(coord) {
                    polygonCoords.push({lat: parseFloat(coord[0]), lng: parseFloat(coord[1])});
                });
                var newPolygon = new google.maps.Polygon({
                    paths: polygonCoords,
                    map: map,
                    editable: false,
                    draggable: false,
                    fillColor: "#ADFF2F"
                });

                // Add label as a marker
                var polygonBounds = new google.maps.LatLngBounds();
                polygonCoords.forEach(function(coord) {
                    polygonBounds.extend(coord);
                });
                var labelPosition = polygonBounds.getCenter();
                var label = new google.maps.Marker({
                    position: labelPosition,
                    map: map,
                    label: polygon.name.toString(),
                    title: polygon.name.toString()
                });
            });
        }
    </script>
</head>
<body onload="InitMap()">
    <!-- <h2></h2>
    <div id="map-canvas" style="width: 100%; height: 500px"></div> -->

    <div class="container">
      <h1>Google Maps Available Geofences</h1>
      <center>
        <hr
          style="
            height: 2px;
            border: none;
            color: #ffffff;
            background-color: #ffffff;
            width: 35%;
            margin: 0 auto 0 auto;
          "
        />
      </center>
      <center>
        <div id="map-canvas" style="width: 100%; height: 500px"></div>
      </center>
    </div>
</body>
</html>

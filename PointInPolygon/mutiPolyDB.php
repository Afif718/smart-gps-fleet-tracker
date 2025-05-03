<?php
// check_point_in_polygon.php

// Function to check if a point is inside a polygon
function pointInPolygon($point, $polygon) {
    $x = $point[0];
    $y = $point[1];
    $inside = false;

    // Number of vertices in the polygon
    $vertices = count($polygon);

    // Iterate through each edge of the polygon
    for ($i = 0, $j = $vertices - 1; $i < $vertices; $j = $i++) {
        $xi = $polygon[$i][0];
        $yi = $polygon[$i][1];
        $xj = $polygon[$j][0];
        $yj = $polygon[$j][1];

        // Check if the point is on the same side of the edge as the polygon vertex
        $intersect = (($yi > $y) != ($yj > $y))
            && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);

        // If the point is on the edge, return true
        if ($intersect && ($xi != $xj)) {
            return true;
        }

        // Toggle the inside variable if the edge intersects with the horizontal line passing through the point
        if ($intersect) {
            $inside = !$inside;
        }
    }

    // Return true if the number of intersections is odd (point is inside), false otherwise
    return $inside;
}

// Connect to MySQL database
$servername = "localhost";
$username = "";
$password = "";
$dbname = "db name";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch polygons from the database
$sql = "SELECT name, coordinates FROM polygons";
$result = $conn->query($sql);

$polygons = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Attempt to decode JSON coordinates
        $coordinates = json_decode($row['coordinates'], true);

        // Check if decoding was successful
        if ($coordinates !== null) {
            $polygons[$row['name']] = $coordinates;
        } else {
            echo "Error decoding JSON for polygon " . $row['name'] . "<br>";
        }
    }
} else {
    echo "0 results";
}

$conn->close();

// Define the point
$point = [25.557797922465184,89.13438938889612];

// Check if the point is inside any of the polygons
$inside_polygon_names = [];
foreach ($polygons as $name => $polygon) {
    if (pointInPolygon($point, $polygon)) {
        $inside_polygon_names[] = $name;
    }
}

// Output result
if (count($inside_polygon_names) > 0) {
    echo "The point is inside polygon(s): " . implode(', ', $inside_polygon_names);
} else {
    echo "The point is outside all polygons.";
}
?>

<?php
// Include your original PHP code here

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
$servername = "localhost:4306";
$username = "root";
$password = "";
$dbname = "gps_tracking";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch polygons from the database
$sql = "SELECT name, coordinates FROM polygons";
$result = $conn->query($sql);

// Construct the SQL query to retrieve tracking data
$query2 = "SELECT track_id, latitude, longitude FROM truck_tracking ORDER BY id DESC LIMIT 1";
$result2 = mysqli_query($conn, $query2);

if (!$result2) {
    die("Error in SQL query (truck_tracking): " . mysqli_error($connection));
}

$polygons = [];
$inside_polygon_names = [];

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

// Fetch latitude and longitude from the last entered row
if (mysqli_num_rows($result2) > 0) {
    $row = mysqli_fetch_assoc($result2);
    $track_id = $row['track_id'];
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];

    // Store latitude and longitude in $point array
    $point = [$latitude, $longitude];

    // Check if the point is inside any of the polygons
    foreach ($polygons as $name => $polygon) {
        if (pointInPolygon($point, $polygon)) {
            $inside_polygon_names[] = $name;

            // Log data into the database using prepared statements
            $insert_query = $conn->prepare("INSERT INTO enter_polygon_log (track_id, polygon_name, time) VALUES (?, ?, NOW())");
            $insert_query->bind_param("ss", $track_id, $name);
            $insert_query->execute();

            if ($insert_query->errno) {
                echo "Error logging data: " . $insert_query->error;
            }
        }
    }

    // Output result
    if (count($inside_polygon_names) > 0) {
        echo "The truck with Track ID $track_id is inside: " . implode(', ', $inside_polygon_names);
    } else {
        echo "The point is outside all polygons.";
    }
} else {
    echo "No tracking data available.";
}

?>
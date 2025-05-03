<?php

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

// Define the polygons with names
$polygons = [
    "Polygon A" => [[26.128631399038277,89.11686919434634],[26.128939636416376,89.12184737427798],[26.12732138110693,89.12622473939028],[26.126319592775932,89.12133239014712],[26.1239306782414,89.127254707652],[26.122851797601008,89.12356398804751],[26.119152702630544,89.1283705066022],[26.120539876967623,89.12261985047427],[26.118382043105644,89.11849997742739],[26.120771071089468,89.11841414673891],[26.12100226475387,89.11618254883852],[26.12346830204402,89.11721251710024],[26.12400774076304,89.11429427369204],[26.12616547073174,89.11558173401919],[26.12647371461682,89.11232016785708]],
    // Add more polygons here
    "Polygon B" => [[26.091111125153652,89.14896066209792],[26.091265293416697,89.18020303270339],[26.069833956888278,89.17882974168776],[26.06829198283556,89.14913232347487]],
    "Polygon C" => [[26.104983877198954,89.00595377148439],[26.108717723273678,89.05888543616129],[26.076342760035597,89.0485857535441],[26.074800871690197,89.0097902823527]]
];

// Define the point
//live truck position
$point = [26.095152162547436,89.02420983801676];

// Check if the point is inside any of the polygons
$inside_polygon_name = null;
foreach ($polygons as $name => $polygon) {
    if (pointInPolygon($point, $polygon)) {
        $inside_polygon_name = $name;
        break;
    }
}

// Output result
if ($inside_polygon_name !== null) {
    echo "The point is inside $inside_polygon_name.";
} else {
    echo "The point is outside all polygons.";
}

?>

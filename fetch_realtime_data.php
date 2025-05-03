<?php
// Include the firebaseRDB class
include 'firebaseRDB.php';

// Initialize firebaseRDB class with your Firebase Realtime Database URL
$firebase = new firebaseRDB("https://gps-tracker-******************************.app/");

// Function to retrieve and display real-time data for all farms
function displayRealTimeData($firebase, $farmNames) {
    // Loop through each farm and retrieve/display data
    foreach ($farmNames as $farm) {
        // Retrieve real-time data from Firebase for each farm
        $data = $firebase->retrieve($farm);
        $dataArray = json_decode($data, true);

        // Check if data retrieval was successful
        if ($dataArray !== null) {
            // Data retrieval successful, display the farm details
            echo "<h2>Farm Name: $farm</h2>";
            echo "<ul>";
            echo "<li>Latitude: " . $dataArray['LAT'] . "</li>";
            echo "<li>Longitude: " . $dataArray['LNG'] . "</li>";
            echo "<li>Details: " . $dataArray['details'] . "</li>";
            echo "<li>User ID: " . $dataArray['userID'] . "</li>";
            echo "</ul>";
        } else {
            // Failed to retrieve data from Firebase
            echo "<p>Failed to retrieve data for $farm from Firebase. Check your database path or authentication.</p>";
        }
    }
}

// Function to retrieve farm names dynamically
function getFarmNames($firebase) {
    // Retrieve real-time data from Firebase to get farm names
    $data = $firebase->retrieve('');
    $dataArray = json_decode($data, true);
    if ($dataArray !== null) {
        return array_keys($dataArray); // Extract farm names from the keys of the associative array
    } else {
        return array(); // Return an empty array if data retrieval fails
    }
}

// Retrieve farm names dynamically
$farmNames = getFarmNames($firebase);

// Display real-time data
displayRealTimeData($firebase, $farmNames);
?>

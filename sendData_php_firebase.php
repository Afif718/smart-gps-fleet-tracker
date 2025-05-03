<?php
// Include the firebaseRDB class
require_once('firebaseRDB.php');

// Instantiate the firebaseRDB class with the Firebase Realtime Database URL
$firebase_url = 'https://gps-tracker-9691a-default-rtdb.asia-southeast1.firebasedatabase.app';
$firebaseDB = new firebaseRDB($firebase_url);

try {
    // Generate random values for humidity, pressure, temperature, and sensor
    $humidity = rand(0, 100); // Random value between 0 and 100
    $pressure = rand(800, 1200); // Random value between 800 and 1200
    $temperature = rand(-20, 50); // Random value between -20 and 50
    $sensor = 'plot1'; // Set the sensor value

    // Prepare the data to be sent to Firebase
    $data_to_send = array(
        'env_data' => array(
            'humidity' => $humidity,
            'pressure' => $pressure,
            'sensor' => $sensor,
            'temperature' => $temperature
        )
    );

    // Provide the unique identifier for the data (in this case, we'll use 'env_data')
   // $unique_id = 'env_data';

    // Send the data to Firebase
    $response = $firebaseDB->update('', '', $data_to_send);

    // Output response
    echo "Data sent to Firebase: " . $response . "\n";
} catch (Exception $e) {
    // Handle any exceptions
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>

<?php
/*
 * class name: firebaseRDB
 * version: 1.0
 * author: Devisty
 */

class firebaseRDB {
    function __construct($url=null) {
        if(isset($url)){
            $this->url = $url;
        } else {
            throw new Exception("Database URL must be specified");
        }
    }

    public function grab($url, $method, $par=null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(isset($par)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $par);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $html = curl_exec($ch);
        return $html;
        curl_close($ch);
    }

    public function retrieve($dbPath){
        $path = $this->url."/$dbPath.json";
        $grab = $this->grab($path, "GET");
        return $grab;
    }
}

// Initialize firebaseRDB class with your Firebase Realtime Database URL
$firebase = new firebaseRDB("https://gps-tracker-9691a-default-rtdb.asia-southeast1.firebasedatabase.app/");

// Function to retrieve farm names dynamically
function getFarmNames($firebase) {
    $data = $firebase->retrieve("");
    $dataArray = json_decode($data, true);
    return array_keys($dataArray);
}

// Function to retrieve and display real-time data for all farms
function displayRealTimeDataForAllFarms($firebase, $farmNames) {
    foreach ($farmNames as $farm) {
        $data = $firebase->retrieve($farm);
        $dataArray = json_decode($data, true);

        if ($dataArray !== null) {
            echo "<div class='card mb-4 shadow-sm'>";
            echo "<div class='card-header bg-primary text-white'><h4 class='mb-0'>Farm Name: $farm</h4></div>";
            echo "<div class='card-body'>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Latitude</th><td>" . $dataArray['LAT'] . "</td></tr>";
            echo "<tr><th>Longitude</th><td>" . $dataArray['LNG'] . "</td></tr>";
            echo "<tr><th>Details</th><td>" . $dataArray['details'] . "</td></tr>";
            echo "<tr><th>User ID</th><td>" . $dataArray['userID'] . "</td></tr>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to retrieve data for <strong>$farm</strong> from Firebase.</div>";
        }
    }
}

// Get farm names dynamically
$farmNames = getFarmNames($firebase);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase Realtime Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        h1 {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Real-time Data from Firebase</h1>
        <div id="realtimeData">
            <?php displayRealTimeDataForAllFarms($firebase, $farmNames); ?>
        </div>
    </div>

    <script>
    function updateRealTimeData() {
        $.ajax({
            url: 'update_tracking_info.php',
            success: function(response) {
                $('#realtimeData').html(response);
            }
        });
    }

    setInterval(updateRealTimeData, 10000);
    </script>
</body>
</html>

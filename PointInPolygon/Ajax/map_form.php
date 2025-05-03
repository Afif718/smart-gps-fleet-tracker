<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SESSION['form_submitted'])) {
    // Get form data
    $name = $_POST["name"];
    $coordinates = $_POST["coordinates"];

    // Database connection
    $servername = "localhost:4306";
    $username = "root";
    $password = "";
    $dbname = "gps_tracking";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO polygons (name, coordinates) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $coordinates);

    // Execute the statement
    if ($stmt->execute()) {
        // Store success message in session
        $_SESSION['success_message'] = "Polygon data saved successfully";
    } else {
        // Store error message in session
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();

    // Redirect to prevent form resubmission
    header("Location: {$_SERVER['PHP_SELF']}");
    $_SESSION['form_submitted'] = true;
    exit();
}

// Clear form submission flag on page load
unset($_SESSION['form_submitted']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Draw Geofences & Get Coordinates</title>
    <style>
        /* Style for the two-column layout */
        .container {
            display: flex;
        }

        .left {
            flex: 1;
            margin-right: 20px;
        }

        .right {
            flex: 1;
        }

        /* Adjust map size */
        #map-canvas {
            height: 400px;
            width: 100%;
            margin-bottom:10px;
        }

        /* Adjust form style */
        form {
            margin-top: 20px;
        }

        /* Style for success and error messages */
        .message {
            margin-top: 10px;
            padding: 10px;
        }

        .success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>

<body>
    <h2>Draw Geofences & Get Coordinates</h2>
    <div class="container">
        <div class="left">
            <div id="map-canvas"></div>
            <div id="info" style="position: absolute; color: red; font-family: Arial; height: 200px; font-size: 12px;"></div>
        </div>
        <div class="right">
            <h2>Insert Geofence Data</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                Geofence Name: <input type="text" name="name" required><br><br>
                Coordinates: <input type="text" name="coordinates" required><br><br>
                <input type="submit" name="submit" value="Submit">
            </form>

            <?php
            // Display success message if set
            if (isset($_SESSION['success_message'])) {
                echo '<div class="message success">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']); // Clear success message
            }

            // Display error message if set
            if (isset($_SESSION['error_message'])) {
                echo '<div class="message error">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']); // Clear error message
            }
            ?>
        </div>
    </div>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=*******************************=drawing"></script>
    <script src="drawMap.js"></script>
</body>
</html>

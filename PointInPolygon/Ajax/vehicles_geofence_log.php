<?php
// Database connection
$servername = "localhost";
$username = "";
$password = "";
$dbname = "db name";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT * FROM enter_polygon_log ORDER BY time DESC LIMIT 20";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();

// Encode data to JSON format for JavaScript
$json_data = json_encode($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Vehicle Entry</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        table { margin: auto; border-collapse: collapse; width: 90%; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; }
        th { background: #222; color: white; }
    </style>
</head>
<body>

<h2>Vehicles Entered Area (Live)</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Vehicle</th>
            <th>Geofence </th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody id="data-body">
        <!-- Data comes here -->
    </tbody>
</table>

<script>
// Directly passing the PHP-generated data into JavaScript
const dataFromPHP = <?php echo $json_data; ?>;

function loadData() {
    const tbody = document.getElementById('data-body');
    tbody.innerHTML = ''; // Clear any existing rows
    if (dataFromPHP.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4">No data available</td></tr>`;
        return;
    }
    dataFromPHP.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.id}</td>
            <td>${row.track_id}</td>
            <td>${row.polygon_name}</td>
            <td>${new Date(row.time).toLocaleString()}</td>
        `;
        tbody.appendChild(tr);
    });
}

// Initial data load and subsequent refresh every 5 seconds
loadData();
setInterval(loadData, 5000);
</script>

</body>
</html>

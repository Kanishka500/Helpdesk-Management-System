<?php
// Database connection
$host = 'localhost';
$db = 'rpta';
$user = 'root';
$pass = ''; // Update with your database password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'];
$sql = "SELECT asset_id FROM equipment WHERE asset_id LIKE '%$query%' LIMIT 10";
$result = $conn->query($sql);

$assets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $assets[] = ['asset_no' => $row['asset_id']];
    }
}

echo json_encode($assets);
$conn->close();
?>

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

$assetNo = $_GET['asset_no'];
$sql = "SELECT serial_no FROM equipment WHERE asset_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $assetNo);
$stmt->execute();
$stmt->bind_result($serialNo);
$stmt->fetch();

$response = ['serial_no' => $serialNo];
echo json_encode($response);

$stmt->close();
$conn->close();
?>

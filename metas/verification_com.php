<?php

$success = false;

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rpta";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get complain_id from query parameter
$complain_id = isset($_GET['complain_id']) ? (int)$_GET['complain_id'] : 0;

// Fetch the corresponding data
$sql = "SELECT * FROM complain WHERE complain_id = $complain_id";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $date = $row["date"];
    $assetno = $row["asset_id"];
    $serialno = $row["serial_no"];
    $empid = $row["emp_id"];
    $regoffice = $row["office_id"];
    $observation = $row["observations"];
    $image = $row["image"];
} else {
    $date = $assetno = $serialno = $empid = $regoffice = $observation = $image = "";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recommendation = isset($_POST['checkbox']) ? 1 : 0;

    // Update the recommendation column in the database
    $update_sql = "UPDATE complain SET recommendation = $recommendation WHERE complain_id = $complain_id";
    if ($conn->query($update_sql) === TRUE) {
        $success = true;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complain Verification</title>
    <script src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup_profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/verification_com.css" />
    <link rel="stylesheet" href="css/media.css">
</head>
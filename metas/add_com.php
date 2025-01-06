<?php

$showPopup = false;

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Collect form data
    $date = $_POST['date'];
    $assetNo = $_POST['assetnoInput'];
    $serialNo = $_POST['serialno'];
    $employeeId = $_POST['empid'];
    $regionalOffice = $_POST['regOffice'];
    $observations = $_POST['subject'];

    // Define the directory where files will be stored
    $uploadDir = 'uploads/';

    // Check if the directory exists
    if (!is_dir($uploadDir)) {
        // Try to create the directory
        if (!mkdir($uploadDir, 0777, true)) {
            echo "Failed to create directory.";
            exit();
        }
    }

    // File upload handling
    $uploadFile = $uploadDir . basename($_FILES['filename']['name']);
    $uploadFileSize = $_FILES['filename']['size'] / 1024; // Size in KB

    // Check file size
    if ($uploadFileSize > 1000) {
        echo "Error: File size should be less than 1MB.";
        exit();
    }

    // Move file to upload directory
    if (!move_uploaded_file($_FILES['filename']['tmp_name'], $uploadFile)) {
        echo "Error: File upload failed.";
        exit();
    }

    // Database connection details
    $dsn = "mysql:host=localhost;dbname=rpta;charset=utf8mb4";
    $username = "root";
    $password = "";

    try {
        // Create PDO connection
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get the max complain_id and generate a new ID
        $sql = "SELECT MAX(complain_id) AS max_id FROM complain";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $max_id = $row['max_id'];

        if ($max_id) {
            $new_id_num = intval(substr($max_id, 3)) + 1;
            $new_com_id = 'Com' . str_pad($new_id_num, 3, '0', STR_PAD_LEFT);
        } else {
            $new_com_id = 'Com001';
        }

        // SQL query to insert data into database
        $sql = "INSERT INTO complain (complain_id, date, asset_id, serial_no, emp_id, office_id, observations, image)
                VALUES (:complain_id, :date, :asset_id, :serial_no, :emp_id, :office_id, :observations, :image)";

        // Prepare and execute the statement
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':complain_id' => $new_com_id,
            ':date' => $date,
            ':asset_id' => $assetNo,
            ':serial_no' => $serialNo,
            ':emp_id' => $employeeId,
            ':office_id' => $regionalOffice,
            ':observations' => $observations,
            ':image' => $uploadFile,
        ]);

        $showPopup = true; // Set the flag to show the popup

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Trigger the popup if the form submission was successful -->
<?php if ($showPopup): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            openPopup();
        });
    </script>
<?php endif; ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Complain-RPTA</title>
    <script src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup_profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/add_com.css" />
    <link rel="stylesheet" href="css/media.css">
</head>
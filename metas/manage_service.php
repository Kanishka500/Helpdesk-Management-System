<?php

$showPopup = false;

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Collect form data
    $officer = $_POST['officer'];
    $issue = $_POST['issue'];
    $solution = $_POST['solution'];
    $service_type = $_POST['service-type'];
    $date = $_POST['date'];
    $complain_id = $_POST['complain_id'];

    // Database connection details
    $serverName = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rpta";

    // Create connection
    $conn = mysqli_connect($serverName, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Generate the next job ID
    $result = mysqli_query($conn, "SELECT MAX(job_id) AS max_id FROM job");
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];

    // Increment the last job ID
    if ($max_id) {
        $new_id_num = intval(substr($max_id, 2)) + 1; // Extract the number and increment
        $new_job_id = 'jb' . str_pad($new_id_num, 3, '0', STR_PAD_LEFT); // Format to jbXXX
    } else {
        $new_job_id = 'jb001'; // If no records exist, start with jb001
    }

    // SQL query to insert data into database
    $sql = "INSERT INTO job (officer, issue, solution, service_type, date, complain_id)
            VALUES ('$officer', '$issue', '$solution', '$service_type', '$date', $complain_id)";

    // Execute query and check if it was successful
    if (mysqli_query($conn, $sql)) {
        $showPopup = true; // Set the flag to show the popup
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
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
    <title>Manage Service</title>
    <script src="js/jquery.min.js"></script>
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup_profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/manage_service.css" />
    <link rel="stylesheet" href="css/media.css">
</head>
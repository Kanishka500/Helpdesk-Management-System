<?php
ob_start();

if (isset($_POST['search'])) {
    $search_val = preg_replace(' #[^a-z0-9]#i', '', $_POST['search_val']);
    $value = $search_val;
    $mess = "<br><span class='search_mess'>" . "Search Result : " . "</span>" .
    "<span class='search_mess'>" . " Complain ID is " . "<span class='search_value'>" . $value . "</span>";
} elseif (isset($_GET['sv'])) {
    $search_val = preg_replace(' #[^a-z0-9]#i', '', $_GET['sv']);
    $value = $search_val;
} else {
    $value = "";
}
?>



<?php

$showPopup = false;

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Collect form data
    $fixed_date = $_POST['fixed_date'];
    $invoice_no = $_POST['invoice_no'];
    $invoice_amount = $_POST['invoice_amount'];
    $verification = $_POST['verification'];
    $certified = $_POST['certified'];
    $certify_date = $_POST['certify_date'];
    $sys_rec = $_POST['sys_recommendation'];
    $dgm_rec = $_POST['dgm_recommendation'];
    $approval = $_POST['approval'];

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

    // //Generate the next job ID
    // $result = mysqli_query($conn, "SELECT MAX(job_id) AS max_id FROM job");
    // $row = mysqli_fetch_assoc($result);
    // $max_id = $row['max_id'];
    
    // // Incerment the last job ID
    // if ($max_id) {
    //     $new_id_num = intval(substr($max_id, 2)) + 1;
    //     $new_job_id = 'jb' .str_pad($new_id_num, 3, '0', STR_PAD_LEFT);
    // }else{
    //     $new_job_id = 'jb001';
    // }

    // SQL query to insert data into database
    $sql = "INSERT INTO invoice (invoice_no, invoice_amount, fixed_date)
            VALUES ('$invoice_no', '$invoice_amount', '$fixed_date')";

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

<!-- Trigger the popup if the form submission was sucessful -->
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
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup_profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/manage_payment.css" />
    <link rel="stylesheet" href="css/media.css">
</head>
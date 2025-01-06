<?php
ob_start();
// --------------------page functions-------------------------
function get_office($office)
{
    switch ($office) {
        case "HO":
            return "Head Office";
        case "C1":
            return "Colombo 01";
        case "C2":
            return "Colombo 02";
        case "C3":
            return "Colombo 03";
        case "C4":
            return "Colombo 04";
        case "G1":
            return "Gampaha 01";
        case "G2":
            return "Gampaha 02";
        case "KLT":
            return "Kalutara";
    }
}
if (isset($_POST['search'])) {
    $date_val = $_POST['date_val'];
    $office_val = $_POST['office_val'];
    $mess = "<br><span class='search_mess'>" . "Search Result : " . "</span>" . "<span class='search_type'>" .
    get_office($office_val) . "</span>" . "<span class='search_mess'>" . " Of Value " . "<span class='search_value'>" . $date_val . "</span>";
} elseif (isset($_GET['d']) && isset($_GET['c'])) {
    $date_val = $_GET['d'];
    $office_val = $_GET['c'];
    $mess = "<br><span class='search_mess'>" . "Search Result : " . "</span>" . "<span class='search_type'>" .
    get_office($office_val) . "</span>" . "<span class='search_mess'>" . " Of Value " . "<span class='search_value'>" . $date_val . "</span>";
} else {
    $date_val = "";
    $search_val = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPTA</title>
    <script src="js/jquery.min.js"></script>
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup_profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/rm_recommendation.css" />
    <link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
</head>
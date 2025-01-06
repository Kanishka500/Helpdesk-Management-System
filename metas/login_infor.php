<?php
if (isset($_POST['search'])) {
    $date = $_POST['date'];
}
if (isset($_GET['date'])) {
    $date = $_GET['date'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPTA | Login Infor</title>
    <script src="js/jquery.min.js"></script>
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup_profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="css/login_infor.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/media.css">
</head>
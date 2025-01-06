<?php
if (!isset($_SESSION)) {
    session_start();
    $userid = $_SESSION['userid'];
}
if (isset($_GET['act'])) {
    $act = $_GET['act'];
}
include("includes/connect.php");
if (isset($_POST['reply'])) {
    $rep = $_POST['replyid'];
}
//-------------------------------message delete---------------------------------
if (isset($_POST['deloutbox'])) {
    $delete = $_POST['delid'];
    $sql = "DELETE FROM user_messages WHERE messages_id=:delete";
    $stmt = $pdom->prepare($sql);
    $stmt->execute(['delete' => $delete]);
    $result = $stmt->rowCount();
    if (!empty($result)) {
        $mess1 = '<i class="far fa-thumbs-up" style="color:#F00; font-size:12px"></i>Delete Message Successfuly';
    } else {
        $mess1 = '<i class="fa fa-times" style="color:#F00; font-size:12px"></i>Delete Message Not successfuly.Try Agin';
    }
}
//-------------------------------message read---------------------------------
if (isset($_POST['read'])) {
    $read = $_POST['readid'];
    $sql = "UPDATE user_messages  SET opened='1' WHERE messages_id=:read";
    $stmt = $pdom->prepare($sql);
    $stmt->execute(['read' => $read]);
    $result = $stmt->rowCount();
    if (!empty($result)) {
        $mess1 = '<i class="far fa-thumbs-up" style="color:#F00; font-size:12px" ></i>Update Successfuly';
    } else {
        $mess1 = '<i class="fa fa-times" style="color:#F00; font-size:12px"></i>Update Not successfuly.Try Agin';
    }
}
//-------------------------------message send---------------------------------
if (isset($_POST['sendmess'])) {
    $subject = $_POST["subject"];
    $subject = htmlspecialchars($subject);
    $message = $_POST["messcontent"];
    $message = htmlspecialchars($message);
    $senderid = $_POST["senderid"];
    $recid = $_POST["recid"];
    $sql = "SELECT user_fname,user_image FROM user_infor WHERE user_id=:senderid";
    $stmt = $pdom->prepare($sql);
    $stmt->execute(['senderid' => $senderid]);
    $result = $stmt->fetchall();
    if (!empty($result)) {
        foreach ($result as $row) {
            $senderimg = $row['user_image'];
            $sendername = $row['user_fname'];
        }
    }
    $sql = "SELECT user_fname,user_image FROM user_infor WHERE user_id=:recid";
    $stmt = $pdom->prepare($sql);
    $stmt->execute(['recid' => $recid]);
    $result = $stmt->fetchall();
    if (!empty($result)) {
        foreach ($result as $row) {
            $recimg = $row['user_image'];
            $recname = $row['user_fname'];
        }
    }
    date_default_timezone_set('Asia/Colombo');
    $date = date('d M Y @ H:i:s');
    $sql = "INSERT INTO user_messages (to_id,from_id,time_sent,subject,message,opened,sender_image,sender_name,reciver_image,reciver_name) VALUES (:recid,:senderid,:date, :subject, :message,:open,:senderimg, :sendername,:recimg, :recname)";
    $stmt = $pdom->prepare($sql);
    $stmt->execute(['recid' => $recid, 'senderid' => $senderid, 'date' => $date, 'subject' => $subject, 'message' => $message, 'open' => "0", 'senderimg' => $senderimg, 'sendername' => $sendername, 'recimg' => $recimg, 'recname' => $recname]);
    $result = $pdom->lastInsertId();
    if (!empty($result)) {
        $mess1 = '<i class="far fa-thumbs-up" style="color:#F00; font-size:12px" ></i>Message Sent Successfuly';
    } else {
        $mess1 = '<i class="fa fa-times" style="color:#F00; font-size:12px"></i>Message Not Sent Successfuly.Try Agin';
    }
    $pdom = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPTA | User Profile</title>
    <script src="js/jquery.min.js"></script>
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup_profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/user_profile.css" />
    <link rel="stylesheet" href="css/message.css" />
    <link rel="stylesheet" href="css/messtab.css" />
    <link rel="stylesheet" href="css/media.css">
</head>
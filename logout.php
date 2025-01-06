<?php
ob_start();
if (!isset($_SESSION)) {
	session_start();
}
include_once("includes/connect.php");
include_once("classes/User.php");
// Double check to see if their sessions exists
//$userid = $_SESSION['userid'];
$_SESSION = array();
session_destroy();
if (isset($_SESSION['userid'])) {
	header("location: message.php?msg=Error:_Logout_Failed");
} else {
	//$result = User::getUserinfor($pdo, $userid);
	//$username = $result[0];
	//$action = "Logout by System";
	//User::addRecord($pdom, $userid, $username, $action);
	//$pdo = null;
	header("location:login.php");
	exit();
}

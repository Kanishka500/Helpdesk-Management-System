<?php
ob_start();
if (!isset($_SESSION)) {session_start();}
include_once("includes/setting.php");
include_once("includes/connect.php");
include_once("classes/User.php");
$user_ok = false;
$token = "";
$serial = "";
$userid = "";
if (isset($_SESSION['userid']) && isset($_SESSION['token']) && isset($_SESSION['serial'])) {
	$userid = preg_replace('#[^a-z0-9]#i', '', $_SESSION['userid']);
	$token = preg_replace('#[^a-z0-9]#i', '', $_SESSION['token']);
	$serial = preg_replace('#[^a-z0-9]#i', '', $_SESSION['serial']);
	// Verify the user
	$user_ok = User::chechLoginState($pdo, $userid, $token, $serial);
}
if ($user_ok == false) {
  $pdo = null;
  header("location:login.php");
  exit();
}

include_once("includes/meta.php"); //meat
include_once("includes/topbar.php"); //topbar
include_once("includes/header.php"); //header
include_once("includes/content.php"); //contant
include_once("includes/footer.php"); //footer
?>
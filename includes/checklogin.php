
<?php
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
?>

<?php
ob_start();
function get_type($type)
{
		switch ($type) {
		case "fn":
		$ty = "First Name";
		break;
		case "ln":
		$ty = "Last Name";
		break;
		case "ti":
		$ty = "Office";
		break;
		case "of":
		$ty = "User Role";
		break;
		case "ad":
		$ty = "Active Date";
		break;
		case "ac":
		$ty = "Actived";
		break;
	}
	return $ty;
}
function get_table($type)
{
		switch ($type) {
		case "fn":
		$ta = "user_fname";
		break;
		case "ln":
		$ta = "user_lname";
		break;
		case "of":
		$ta = "user_office";
		break;
		case "ro":
		$ta = "user_role";
		break;
		case "ad":
		$ta = "user_actdate";
		break;
		case "ac":
		$ta = "user_actived";
		break;
	}
	return $ta;
}
if (isset($_POST['search'])) {
	$search_type = $_POST['search_type'];
	$search_val = preg_replace(' #[^a-z0-9]#i', '', $_POST['search_val']);
	$value=$search_val;
	$mess="<span class='edit_mess'>"."Search Result : "."</span>"."<span class='error_mess'>".
	get_type($search_type)."</span>"."<span class='edit_mess'>"." Of Value "."<span class='error_mess'>".$value."</span>";
	$table=get_table($search_type);

}elseif(isset($_GET['st']) && isset($_GET['sv'])){
	$search_type = $_GET['st'];
	$search_val = preg_replace(' #[^a-z0-9]#i', '', $_GET['sv']);
	$value=$search_val;
	$table=get_table($search_type);
}else{
	$value="";
	$table="";
}
if (isset($_POST['setid'])) {
    $setid = $_POST['setid'];
    $pag = $_POST['pag'];
	$userid = $_POST["userid"];
	$usno = $_POST["usno"];
    include("includes/connect.php");
    //----------------get image------------------------------
    $sql = "SELECT user_image FROM user_infor WHERE info_id=:userid";
    $stmt = $pdom->prepare($sql);
    $stmt->execute(['userid' => $setid]);
    $row1 = $stmt->fetchColumn();
    $img = "images/profileface/" . $row1;
    //----------------delete user_infor------------------------------
    $sql = "DELETE FROM user_infor WHERE info_id=:userid";
    $stmt = $pdom->prepare($sql);
    $stmt->execute(['userid' => $setid]);
    $result1 = $stmt->rowCount();
    //----------------delete user_login------------------------------
    $sql = "DELETE FROM user_login WHERE login_userid=:usno";
    $stmt = $pdom->prepare($sql);
    $stmt->execute(['usno' => $usno]);
    $result2 = $stmt->rowCount();
    if ($result1 == 1 and $result2 == 1) {
        //---------------delete image-------------------------
        $kaboom = explode(".", $row1);
        $imagname = $kaboom[0];
        $imagname = substr($kaboom[0], 0, 7);
        if ($imagname != "default") {
            unlink($img);
        }
        //----------------------tracker service--------------------------------
        $action = "DeleteUser";
        $actid = $setid;
        $actidtype = "userinfor";
        User::tracker($pdo, $action,$userid, $actid, $actidtype);
        header("location:index.php?page=manage_staff&pn=" . $pag);
    } else {
        $mess = "Error deleting record ";
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
    <title>RPTA |View Staff</title>
    <script src="js/jquery.min.js"></script>
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup_profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link href="css/manage_staff.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/media.css">
</head>
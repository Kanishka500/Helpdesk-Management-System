<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
include_once("../includes/connect.php");
include_once("../classes/User.php");
//----------------infor table------------------------ 
$role = $_POST['role'];
//--------------------get userid------------------------
$sql="SELECT MAX(login_id) AS login_id  FROM user_login";
$stmt=$pdo -> prepare($sql);
$stmt -> execute();
$result=$stmt -> fetchall();
if(!empty($result)){
    foreach($result as $row){
    $useridvalue=$row['login_id']+1;
    $rowlen=strlen($useridvalue);
    switch($rowlen){
		case 1:
		$usid="00".$useridvalue;
		break;
		case 2:
		$usid="0".$useridvalue;
		break;
		case 3:
		$usid="".$useridvalue;
		break;	
    } }}
if($role=='3'){
    $usid="us".$usid;
}elseif($role=='2'){
    $usid="ad".$usid;
}elseif($role=='1'){
    $usid="sa".$usid;
}
//--------------------get userid------------------------
$office = $_POST['office'];
$userid = $_POST['userid'];
$fname =$_POST["fname"];
$lname =$_POST["lname"];
$address = $_POST["address"];
$bday = $_POST['bday'];
$email = $_POST["email"];
$tell = $_POST["tell"];
$image ="default1.jpg";
date_default_timezone_set('Asia/Colombo');
$actdate=date('d M Y @ H:i:s');
$role = $_POST['role'];
//----------------login table------------------------
$loginfo='ok';
$password = $_POST['password'];
$username = $_POST['username'];
//-------------chech username and password---------
//-------------chech username----------------------
$sql = "SELECT * FROM user_login WHERE login_name=:uname";
$stmt = $pdo->prepare($sql);
$stmt->execute(['uname' => $username]);
$result = $stmt->fetchall();
if (!empty($result)) {
$loginfo='notok';
echo  '<label class="messadd"><i class="fa fa-times"></i>Your User Name Allredy in Our Data Base.</label>';
echo '<a href="index.php?page=add_staff" target="_parent" class="uploadimage">Add Agin</a>';
}
//-------------chech password----------------------
$sql = "SELECT login_password FROM user_login";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchall();
if (!empty($result)) {
foreach ($result as $row) {
$pass_value = $row['login_password'];
if (password_verify($password,$pass_value)) {
$loginfo='notok';
echo  '<label class="messadd"><i class="fa fa-times"></i>Your Password Allredy in Our Data Base.</label>';
echo '<a href="index.php?page=add_staff" target="_parent" class="uploadimage">Add Agin</a>';
break;
}
}
}
if($loginfo=='ok'){
$password = password_hash($password, PASSWORD_DEFAULT);
$sql1 = "INSERT INTO user_infor  (user_id,
                                user_fname,
                                user_lname,
                                user_address,
                                user_bday,
                                user_email,
                                user_tell,
                                user_image,
                                user_actdate,
                                user_role,
                                user_office) 
                                VALUES 
                                (:userid,
                                 :fname, 
                                 :lname, 
                                 :useraddress, 
                                 :userbday, 
                                 :useremail, 
                                 :usertell,
                                 :userimage, 
                                 :useractdate, 
                                 :userrole,
                                 :useroffice)";
$stmt = $pdo->prepare($sql1);
$stmt->execute(['userid' => $usid, 
                'fname' => $fname,
                'lname' => $lname, 
                'useraddress' => $address, 
                'userbday' => $bday,
                'useremail' => $email, 
                'usertell' => $tell,
                'userimage' => $image, 
                'useractdate' => $actdate, 
                'userrole' => $role,
                'useroffice' => $office]);
$result1 = $pdo->lastInsertId();
$sql2 = "INSERT INTO user_login (login_userid,
                                login_name,
                                login_password ,
                                login_add ) 
                                VALUES 
                                (:userid, 
                                :username, 
                                :userpassword,
                                :useractdate)";
$stmt = $pdo->prepare($sql2);
$stmt->execute(['userid' => $usid, 
                'username' => $username,
                'userpassword' => $password, 
                'useractdate' => $actdate]);
$result2 = $pdo->lastInsertId();
if (!empty($result1) and !empty($result2)) {
    //----------------------tracker service--------------------------------
    $action = "addNewUser";
    $actid = $usid;
    $actidtype = "usertable";
    //User::tracker($pdo, $action,$userid, $actid, $actidtype);
    $pdo = null;
    echo '<div class="form_submit" align="center">';
    echo '<p class="messadd"><i class="far fa-thumbs-up"></i>User Add to Database successfuly.Please Upload Profile Image</p><br>';
    echo  '<a href="index.php?page=view_staff" target="_parent" class="uploadimage">I Dont Wont Image</a>';
    echo '&nbsp;&nbsp;<a href="index.php?page=upload_staffimage&usid=';
    echo $usid . '"';
    echo 'target="_parent" class="uploadimage">Upload</a>';
    echo "</div>";
} else {
    echo  '<label class="messadd">User Add to Database Not successfuly</label>';
    echo  '<a href="index.php?page=add_staff" target="_parent">back</a>';
} 
}
} else {
echo  '<label class="messadd">User Add to Database Not successfuly</label>';
echo  '<a href="index.php?page=add_staff" target="_parent">back</a>';
}
?>
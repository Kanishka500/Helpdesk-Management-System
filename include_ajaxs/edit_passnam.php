<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../includes/connect.php");
    include_once("../classes/User.php");
    //--------------------username------------------------------
    if (isset($_POST['uname'])) {
        //------get name ----------------------
        $uname = $_POST['uname'];
        $userid = $_POST["userid"];
        $setid = $_POST["setid"]; 
        //----------------------chech name--------------------------------
        $sql = "SELECT * FROM user_login WHERE login_name=:uname LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['uname' => $uname]);
        $result = $stmt->fetchall();
        if (!empty($result)) {
        echo  '<label class="error_mess"><i class="fa fa-times"></i>Your User Name Allredy in Our Data Base.</label>';
        }else{
        // output data of each row
        $sql = "UPDATE user_login  SET login_name=:uname WHERE login_userid=:setid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['uname' => $uname, 'setid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
        $pdo = null;
        //----------------------tracker service--------------------------------
        $action = "EditUserName";
        $actid = $setid;
        $actidtype = "userlogin";
        User::tracker($pdo, $action,$userid, $actid, $actidtype);
        $pdo = null;
        echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>User Name Update Successfuly.</label>';
        } else {
        echo '<label class="error_mess"><i class="fa fa-times"></i>User Name Update Not successfuly.Try Agin</label>';
        }
        }
    }
    //--------------------repassword------------------------------
    if (isset($_POST['pass'])) { 
        $pass = $_POST['pass'];
        $userid = $_POST["userid"];
        $setid = $_POST["setid"]; 
        //----------------------chech password--------------------------------
        $password = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "UPDATE user_login  SET login_password=:pass WHERE login_userid=:setid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['setid' => $setid, 'pass' => $password]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
        $pdo = null;
        //----------------------tracker service--------------------------------
        $action = "EditUserPass";
        $actid = $setid;
        $actidtype = "userlogin";
        User::tracker($pdo, $action,$userid, $actid, $actidtype);
        $pdo = null;
        echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>User Password Update Successfuly.</label>';
        } else {
        echo '<label class="error_mess"><i class="fa fa-times"></i>User Password Update Not successfuly.Try Agin</label>';
        }
        }
}

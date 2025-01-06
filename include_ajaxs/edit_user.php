<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../includes/connect.php");
    include_once("../classes/User.php");
    //--------------------fname------------------------------
    if (isset($_POST['fname'])) {
        $fname = $_POST['fname'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        $sql = "UPDATE user_infor  SET user_fname=:fname WHERE info_id=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['fname' => $fname, 'userid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
            //----------------------tracker service--------------------------------
            $action = "EditFirstName";
            $actid = $setid;
            $actidtype = "userinfor";
            //User::tracker($pdo, $action,$userid, $actid, $actidtype);
            echo ' <label class="error_mess"><i class="far fa-thumbs-up"></i>First Name Update Successfuly.</label>' . '<label class="edit_mess">&nbsp;<i class="fas fa-chevron-right"></i>&nbsp;' . $fname . '&nbsp;</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>First Name Update Not successfuly.Try Agin</label>';
        }
    }
    //--------------------lname------------------------------
    if (isset($_POST['lname'])) {
        $lname = $_POST['lname'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        $sql = "UPDATE user_infor  SET user_lname=:lname WHERE info_id=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['lname' => $lname, 'userid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
            //----------------------tracker service--------------------------------
            $action = "EditLastName";
            $actid = $setid;
            $actidtype = "userinfor";
            //User::tracker($pdo, $action,$userid, $actid, $actidtype);
            echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>Last Name Update Successfuly.</label>' . '<label class="edit_mess">&nbsp;<i class="fas fa-chevron-right"></i>&nbsp;' . $lname . '&nbsp;</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>Last Name Update Not successfuly.Try Agin</label>';
        }
    }
    //--------------------address------------------------------
    if (isset($_POST['address'])) {
        $address = $_POST['address'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        $sql = "UPDATE user_infor  SET user_address=:address WHERE info_id=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['address' => $address, 'userid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
            //----------------------tracker service--------------------------------
            $action = "EditAddress";
            $actid = $setid;
            $actidtype = "userinfor";
            //User::tracker($pdo, $action,$userid, $actid, $actidtype);
            echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>Address Update Successfuly.</label>' . '<label class="edit_mess">&nbsp;<i class="fas fa-chevron-right"></i>&nbsp;' . $address . '&nbsp;</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>Address Update Not successfuly.Try Agin</label>';
        }
    }
    //--------------------bday------------------------------
    if (isset($_POST['bday'])) {
        $bday = $_POST['bday'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        $sql = "UPDATE user_infor  SET user_bday=:bday WHERE info_id=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['bday' => $bday, 'userid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
            //----------------------tracker service--------------------------------
            $action = "EditBDay";
            $actid = $setid;
            $actidtype = "userinfor";
            //User::tracker($pdo, $action,$userid, $actid, $actidtype);
            echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>Birth Day Update Successfuly.</label>' . '<label class="edit_mess">&nbsp;<i class="fas fa-chevron-right"></i>&nbsp;' . $bday . '&nbsp;</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>Birth Day Update Not successfuly.Try Agin</label>';
        }
    }
    //--------------------email------------------------------
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        $sql = "UPDATE user_infor  SET user_email=:email WHERE info_id=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'userid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
            //----------------------tracker service--------------------------------
            $action = "EditEmail";
            $actid = $setid;
            $actidtype = "userinfor";
            //User::tracker($pdo, $action,$userid, $actid, $actidtype);
            echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>Email Update Successfuly.</label>' . '<label class="edit_mess">&nbsp;<i class="fas fa-chevron-right"></i>&nbsp;' . $email . '&nbsp;</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>Email Update Not successfuly.Try Agin</label>';
        }
    }
    //--------------------tell------------------------------
    if (isset($_POST['tell'])) {
        $tell = $_POST['tell'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        $sql = "UPDATE user_infor  SET user_tell=:tell WHERE info_id=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['tell' => $tell, 'userid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
            //----------------------tracker service--------------------------------
            $action = "EditTell";
            $actid = $setid;
            $actidtype = "userinfor";
            //User::tracker($pdo, $action,$userid, $actid, $actidtype);
            echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>Tell Number Update Successfuly.</label>' . '<label class="edit_mess">&nbsp;<i class="fas fa-chevron-right"></i>&nbsp;' . $tell . '&nbsp;</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>Tell Number Update Not successfuly.Try Agin</label>';
        }
    }
    //--------------------type------------------------------
    if (isset($_POST['office'])) {
        $office = $_POST['office'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        $sql = "UPDATE user_infor  SET user_office=:office WHERE info_id=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['office' => $office, 'userid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
            //----------------------tracker service--------------------------------
            $action = "EditTitle";
            $actid = $setid;
            $actidtype = "userinfor";
            //User::tracker($pdo, $action, $userid,$actid, $actidtype);
            echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>Office Update Successfuly.</label>' . '<label class="edit_mess">&nbsp;<i class="fas fa-chevron-right"></i>&nbsp;' . $office . '&nbsp;</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>Office Update Not successfuly.Try Agin</label>';
        }
    }
    //--------------------role------------------------------
    if (isset($_POST['role'])) {
        $role = $_POST['role'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        $sql = "UPDATE user_infor  SET  user_role=:role WHERE info_id=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['role' => $role, 'userid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
            //----------------------tracker service--------------------------------
            $action = "EditRole";
            $actid = $setid;
            $actidtype = "userinfor";
            //User::tracker($pdo, $action, $userid,$actid, $actidtype);
            echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>User Role Update Successfuly.</label>' . '<label class="edit_mess">&nbsp;<i class="fas fa-chevron-right"></i>&nbsp;' . $role . '&nbsp;</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>User Role Not successfuly.Try Agin</label>';
        }
    }
    $pdo = null;
}

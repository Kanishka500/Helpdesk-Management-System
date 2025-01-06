<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("../includes/connect.php");
    include_once("../includes/setting.php");
    include_once("../classes/User.php");
    $tracker = 'user_tracker';
    $status = 'Add Service Provider';
    //--------------------get data-------------------setid-----
    $cid = $_POST["setid"];
    $refno = $_POST["refno"];
    $company = $_POST["company"];
    $date = $_POST["date"];
    $con = $_POST["seconfirm"];
    //--------------------get user---------------------
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $userid = $_POST["userid"];
    $cby = "Add by " . $userid . " on " . $date;
    //--------------------insert------------------------
    $sql = "INSERT INTO service 
    (reference_no,company,date,confirm,complain_id) 
    VALUES 
    (:refno,:company,:date,:con,:cid)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'refno' => $refno,
        'company' => $company,
        'date' => $date,
        'con' => $con,
        'cid' => $cid
    ]);
    //--------------------job_position--------------------------
    $jpkey = 2;
    $jptitle = "Add by Helpdesk Officer";
    $lm = "Add by User id : " . $userid . " Date :" . $date;
    $result = $stmt->rowCount();
    if (!empty($result)) {
        //------------------------job_position-------------
        $sql = "INSERT INTO job_position 
                (jobre_date,jobre_key,jobre_title,jobre_status,complain_id) 
                VALUES 
                (:jpdate,:jpkey,:jptitle,:jpstatus,:comid)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'jpdate' => $date,
            'jpkey' => $jpkey,
            'jptitle' => $jptitle,
            'jpstatus' => $status,
            'comid' => $cid
        ]);
        $act = "Add";
        $actid = $cid;
        $user = $userid;
        $table = "service";
        User::tracker($pdo, $tracker, $act, $user, $table, $date, $actid);
        echo ' <label class="verify_mess"><i class="far fa-thumbs-up"></i>Successfully added service provider details.</label>';
    } else {
        echo '<label class="error_mess"><i class="fa fa-times"></i>Not Successfully added service provider details.Try Again</label>';
    }
    $pdo = null;
}

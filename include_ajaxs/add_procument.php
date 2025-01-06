<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("../includes/connect.php");
    include_once("../includes/setting.php");
    include_once("../classes/User.php");
    $tracker = 'user_tracker';
    $status = 'Job completed by issuing cheque';

    //--------------------get data-------------------setid-----
    $cid = $_POST["vsetid"]; 
    $pdate = $_POST["paiddate"];
    $invoiceno = $_POST["invoiceid"];
    $voucherno = $_POST["voucherno"];
    $chequeno = $_POST['chequeno'];
    $veri = $_POST['success'];
    //--------------------get user---------------------
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $userid = $_POST["userid"];
    $cby = "Add by " . $userid . " on " . $date;
    //--------------------insert------------------------
    $sql = "INSERT INTO payment 
    (voucher_no,cheque_no,paid_date,invoice_no,complain_id,py_success) 
    VALUES 
    (:vouno,:chqno,:pdate,:invno,:comid,:suc)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'vouno' => $voucherno,
        'chqno' => $chequeno,
        'pdate' => $pdate,
        'invno' => $invoiceno,
        'comid' => $cid,
        'suc' => $veri
    ]);
    //--------------------job_position--------------------------
    $jpkey = 2;
    $jptitle = "Verified by Helpdesk Officer";
    $lm = "Verified by User id : " . $userid . " Date :" . $date;
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
        $table = "payment";
        User::tracker($pdo, $tracker, $act, $user, $table, $date, $actid);
        echo ' <label class="verify_mess"><i class="far fa-thumbs-up"></i>Payment Detail Added Successfully.</label>';
    } else {
        echo '<label class="error_mess"><i class="fa fa-times"></i>Payment Detail Not Added Successfully. Try Again.</label>';
    }
    $pdo = null;
}
?>

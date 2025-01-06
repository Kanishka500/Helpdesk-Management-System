<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../includes/connect.php");
    include_once("../classes/User.php");
    //--------------------get user------------------------
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $tracker = 'user_tracker';
    //--------------------add job------------------------------
    if (isset($_POST['jobirepl'])) {
        $itemreplace = $_POST['jobirepl'];
        $setid = $_POST['setid'];
        $status = $_POST['status'];
        $userid = $_POST["userid"];
        //--------------------job_position--------------------------
        $jpkey=2;
        $jptitle="Item Replace Status edit by helpdesk officer";
        $lm = "Edit by User id : " . $userid . " Date :" . $date;
        $sql = "UPDATE job  SET itemreplace_status=:jobirepl  WHERE complain_id=:setid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['jobirepl' => $itemreplace, 'setid' => $setid]);
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
                        'comid' => $setid
                             ]);
            //----------------------tracker service-----------------------------
            $act = "Item Replace";
            $actid = $setid;
            $user = $userid;
            $table = "job";
            User::tracker($pdo, $tracker, $act, $user, $table, $date, $actid);
            echo ' <label class="verify_mess"><i class="far fa-thumbs-up"></i>Item Replace Status Update Successfully.</label>' ;
        } else {
            echo '<label class="error_mess"><i class="far fa-thumbs-up"></i>Item Replace Status Update Unsuccessful. Try Again.</label>';
        }
    }
    //-------------------------------------------------------
    $pdo = null;
}

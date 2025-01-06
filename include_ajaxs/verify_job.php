<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../includes/connect.php");
    include_once("../classes/User.php");
    //--------------------get user------------------------
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $tracker = 'user_tracker';
    //--------------------comrec------------------------------
    if (isset($_POST['jobveri'])) {
        $comverify = $_POST['jobveri'];
        $status = $_POST['status_vr'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        //--------------------job_position--------------------------
        $jpkey=3;
        $jptitle="Verification of System Administrator";
        $lm = "Verify by User id : " . $userid . " Date :" . $date;
        $sql = "UPDATE job  SET verification=:jobveri WHERE complain_id=:setid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['jobveri' => $comverify, 'setid' => $setid]);
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
            $act = "Verification";
            $actid = $setid;
            $user = $userid;
            $table = "job";
            User::tracker($pdo, $tracker, $act, $user, $table, $date, $actid);
            echo ' <label class="verify_mess"><i class="far fa-thumbs-up"></i>Verification Successfully.</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>Verification Not successfully.Try Again</label>';
        }
    }
    //-------------------------------------------------------
    $pdo = null;
}

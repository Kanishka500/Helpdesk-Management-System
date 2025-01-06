<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../includes/connect.php");
    include_once("../classes/User.php");
    //--------------------get user------------------------
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $tracker = 'user_tracker';
    //--------------------jobconf------------------------------
    if (isset($_POST['invsysrec'])) {
        $invsysrec = $_POST['invsysrec'];
        $status = $_POST['statussys'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];

        //--------------------job_position--------------------------
        $jpkey=3;
        $jptitle="Recomendation by System Administrator";
        $lm = "Recomendation by User id : " . $userid . " Date :" . $date;
        $sql = "UPDATE invoice  SET sys_recommendation=:invsysrec  WHERE complain_id=:setid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['invsysrec' => $invsysrec, 'setid' => $setid]);
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
            $act = "Recommendation";
            $actid = $setid;
            $user = $userid;
            $table = "invoice";
            User::tracker($pdo, $tracker, $act, $user, $table, $date, $actid);
            echo ' <label class="verify_mess"><i class="far fa-thumbs-up"></i>Recommendation Successfully.</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>Recommendation Not successfully. Try Again.</label>';
        }
    }
    //-------------------------------------------------------
    $pdo = null;
}

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../includes/connect.php");
    include_once("../classes/User.php");
    //--------------------get user------------------------
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $tracker = 'user_tracker';
    //--------------------jobconf------------------------------
    if (isset($_POST['invcer'])) {
        $invcer = $_POST['invcer'];
        $certify_date = $_POST['certify_date'];
        $status = $_POST['statusit'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        //--------------------job_position--------------------------
        $jpkey=4;
        $jptitle="Recomendation by Information Technology Officer";
        $lm = "Recomendation by User id : " . $userid . " Date :" . $date;
        $sql = "UPDATE invoice  SET certified=:invcer, certify_date=:certify_date  WHERE complain_id=:setid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['invcer' => $invcer, 'certify_date' => $certify_date, 'setid' => $setid]);
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
            $act = "Recomendation";
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

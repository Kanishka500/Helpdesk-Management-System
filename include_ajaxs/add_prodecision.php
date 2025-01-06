<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../includes/connect.php");
    include_once("../classes/User.php");
    //--------------------get user------------------------
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $tracker = 'user_tracker';
    //--------------------procurement------------------------------
    if (isset($_POST['dec_con'])) {
        $dec_con = $_POST['dec_con'];
        $prodec = $_POST['prodec'];
        $status = $_POST['status_manager'];
        $setid = $_POST['setid'];
        $userid = $_POST["userid"];
        //--------------------job_position--------------------------
        $jpkey=1;
        $jptitle="Recommendation by Procurement Manager";
        $lm = "Recommendation by User id : " . $userid . " Date :" . $date;
        $sql = "UPDATE quotation  SET decision=:prodec, decision_confirm=:dec_con  WHERE complain_id=:setid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['prodec' => $prodec, 'dec_con' => $dec_con, 'setid' => $setid]);
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
            $act = "Recommendation ";
            $actid = $setid;
            $user = $userid;
            $table = "quotation";
            User::tracker($pdo, $tracker, $act, $user, $table, $date, $actid);
            echo ' <label class="verify_mess"><i class="far fa-thumbs-up"></i>Procurement decision submitted Successfully.</label>';
        } else {
            echo '<label class="error_mess"><i class="fa fa-times"></i>Procurement decision submitted Not successfully. Try Again</label>';
        }
    }
    //-------------------------------------------------------
    $pdo = null;
}

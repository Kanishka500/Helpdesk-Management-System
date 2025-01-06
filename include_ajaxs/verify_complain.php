<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("../includes/connect.php");
    include_once("../includes/setting.php");
    include_once("../classes/User.php");
    $tracker = 'user_tracker';
    //--------------------get data-------------------
    $cid = $_POST["setid"];
    $officer = $_POST["officer"];
    $issue = $_POST["issue"];
    $solution = $_POST["solution"];
    $services = $_POST["services"];
    $job_date = $_POST["job_date"];
    $jobconf = $_POST["jobconf"];
    $status = $_POST['status'];
    $veri=0;
    //--------------------get user---------------------
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $userid = $_POST["userid"];
    $cby = "Add by " . $userid . " on " . $date;
    $sql = "SELECT MAX(job_id) AS max_id FROM job";
    $result = $pdo->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $max_id = $row['max_id'];
    //--------------------get number------------------------
    if ($max_id) {
        $new_id_num = intval(substr($max_id, 2)) + 1;
        $new_job_id = 'jb' . str_pad($new_id_num, 3, '0', STR_PAD_LEFT);
    } else {
        $new_job_id = 'jb001';
    }
    //--------------------insert------------------------
    $sql = "INSERT INTO job 
    (job_id,officer,issue,solution,service_type,job_date,complain_id,accept,verification) 
    VALUES 
    (:newjobid,:officer,:issue,:solution,:services,:jobdate,:cid,:jobconf,:veri)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'newjobid' => $new_job_id,
        'officer' => $officer,
        'issue' => $issue,
        'solution' => $solution,
        'services' => $services,
        'jobdate' => $job_date,
        'cid' => $cid,
        'jobconf' => $jobconf,
        'veri' => $veri
    ]);

    //--------------------job_position--------------------------
    $jpkey = 2;
    $jptitle = "Accept by Helpdesk Officer";
    $lm = "Accept by User id : " . $userid . " Date :" . $date;
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
        $act = "Accept";
        $actid = $cid;
        $user = $userid;
        $table = "job";
        User::tracker($pdo, $tracker, $act, $user, $table, $date, $actid);
        echo ' <label class="verify_mess"><i class="far fa-thumbs-up"></i>Acceptance Successfuly.</label>';
    } else {
        echo '<label class="error_mess"><i class="fa fa-times"></i>Acceptance Not successfuly.Try Again</label>';
    }
    $pdo = null;
}

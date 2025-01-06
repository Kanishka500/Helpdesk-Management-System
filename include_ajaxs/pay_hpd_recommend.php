<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("../includes/connect.php");
    include_once("../includes/setting.php");
    include_once("../classes/User.php");
    $tracker = 'user_tracker';
    //--------------------get data-------------------
    $cid = $_POST["setid"];
    $qutno = $_POST["quotationid"];
    $fixeddate = $_POST["fixeddate"];
    $invoiceno = $_POST["invoiceno"];
    $invoiceamount = $_POST["invoiceamount"];
    $status = $_POST['status'];
    $veri=$_POST['invver'];
    $certify=0;
    $certifydate = '1000-01-01';
    $sysreco=0;
    $dgmreco=0;
    $approval=0;

    // Define the directory where files will be stored
    $uploadDir = '../images/uploads/invoice/';

    // Check if the directory exists
    if (!is_dir($uploadDir)) {
        // Try to create the directory
        if (!mkdir($uploadDir, 0777, true)) {
            echo "Failed to create directory.";
            exit();
        }
    }

    // File upload handling
    $uploadFile = $uploadDir . basename($_FILES['filename']['name']);
    $uploadFileSize = $_FILES['filename']['size'] / 1024; // Size in KB

    // Check file size and type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image types

    // Check file size
    if ($uploadFileSize > 1000) {
        echo "Error: File size should be less than 1MB.";
        exit();
    }

    // Move file to upload directory
    if (!move_uploaded_file($_FILES['filename']['tmp_name'], $uploadFile)) {
        echo "Error: File upload failed.";
        exit();
    }
    //--------------------get user---------------------
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $userid = $_POST["userid"];
    $cby = "Add by " . $userid . " on " . $date;
    //--------------------insert------------------------
    $sql = "INSERT INTO invoice 
    (invoice_no,quotation_no,complain_id,invoice_amount,invoice_image,fixed_date,verification_hd,certified,certify_date,sys_recommendation,dgm_recommendation,approval) 
    VALUES 
    (:invno,:qutno,:comid,:invamount,:invimage,:fixdate,:ver,:cer,:cerdate,:sysrec,:dgmrec,:aprv)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'invno' => $invoiceno,
        'qutno' => $qutno,
        'comid' => $cid, 
        'invamount' => $invoiceamount,
        'invimage' => $uploadFile,
        'fixdate' => $fixeddate,
        'ver' => $veri,
        'cer' => $certify,
        'cerdate' => $certifydate,
        'sysrec' => $sysreco,
        'dgmrec' => $dgmreco,
        'aprv' => $approval
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
        $table = "invoice";
        User::tracker($pdo, $tracker, $act, $user, $table, $date, $actid);
        echo ' <label class="verify_mess"><i class="far fa-thumbs-up"></i>Invoice Detail Added Successfully.</label>';
    } else {
        echo '<label class="error_mess"><i class="fa fa-times"></i>Invoice Detail Not Added Successfully. Try Again.</label>';
    }
    $pdo = null;
}

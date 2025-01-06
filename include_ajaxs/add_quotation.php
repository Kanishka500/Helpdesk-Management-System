<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("../includes/connect.php");
    include_once("../includes/setting.php");
    include_once("../classes/User.php");
    date_default_timezone_set('Asia/Colombo');
    $date = date('Y-m-d H:i:s');
    $tracker = 'user_tracker';
    $status="Quotation added";

    // --------------------get data-------------------
    $seref = $_POST["refid"];
    $cid = $_POST["setid"];
    $qutno = $_POST["qutno"];
    $replace_items = $_POST["replace_items"];
    $qutamu = $_POST["qutamu"];
    $qutconfirm = isset($_POST["qutconfirm"]) ? $_POST["qutconfirm"] : 0;
    $userid = $_POST["userid"];
    
    // Define the directory where files will be stored
    $uploadDir = '../images/uploads/quotation/';

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
    
    // --------------------validate reference_no exists in service---------------------
    $sqlCheck = "SELECT reference_no FROM service WHERE reference_no = :seref";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute(['seref' => $seref]);
    
    if ($stmtCheck->rowCount() == 0) {
        echo '<label class="error_mess"><i class="fa fa-times"></i>Invalid Reference Number. Please check and try again.</label>';
        die(); // Stop execution if reference_no is invalid
    }

    // --------------------insert into quotation------------------------
    $sql = "INSERT INTO quotation 
            (quotation_no, replace_items, quotation_amount, quotation_confirm, quotation_image, reference_no, complain_id) 
            VALUES 
            (:qutno, :replace_items, :qutamu, :qutconfirm, :qutimage, :seref, :comid)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'qutno' => $qutno,
        'replace_items' => $replace_items,
        'qutamu' => $qutamu,
        'qutconfirm' => $qutconfirm,
        'qutimage' => $uploadFile,
        'seref' => $seref,
        'comid' => $cid
    ]);

    $result = $stmt->rowCount();

    // --------------------job_position--------------------------
    $jpkey = 2;
    $jptitle = "Add by Helpdesk Officer";
    $lm = "Add by User id : " . $userid . " Date :" . $date;

    if (!empty($result)) {
        $sql = "INSERT INTO job_position 
                (jobre_date, jobre_key, jobre_title, jobre_status, complain_id) 
                VALUES 
                (:jpdate, :jpkey, :jptitle, :jpstatus, :comid)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'jpdate' => $date,
            'jpkey' => $jpkey,
            'jptitle' => $jptitle,
            'jpstatus' => $status,
            'comid' => $cid
        ]);

        // Log the action
        $act = "Add";
        $actid = $cid;
        $user = $userid;
        $table = "quotation";
        User::tracker($pdo, $tracker, $act, $user, $table, $date, $actid);

        echo '<label class="verify_mess"><i class="far fa-thumbs-up"></i>Successfully added quotation details.</label>';
    } else {
        echo '<label class="error_mess"><i class="fa fa-times"></i>Not Successfully added quotation details. Try Again</label>';
    }
    $pdo = null;
}



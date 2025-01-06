<?php
// --------------------page configs-------------------------
$pag = "index.php?page=manage_service";
$btnrecon = array(2);
$btnrecsys = array(3);
$maxvalue = 10;
?>
<div id="main_content">
    <!--------------------content here----------------------------->
    <div id="heding_section">
        <!--------------------heading_section----------------------------->
        <div class="page_title">
            <p>Service<span class="page_name"> > Manage Service</span></p>
        </div>
        <!--------------------heading_section----------------------------->
    </div>
    <div id="body_section">
        <div class="page_subtitle">
            <p class="title">Manage Complain</p>
        </div>

        <!---------------------pagination------------------------------>
        <?php
        include("includes/connect.php");

        if (!empty($value) && !empty($table)) {
            $url = $pag . '&st=' . $search_type . '&sv=' . $value;
            $statement = "SELECT complain.*, 
           job.complain_id AS job_complain_id, 
           job.officer, 
           job.issue, 
           job.solution, 
           job.service_type, 
           job.job_date, 
           job.accept, 
           job.verification, 
           job.itemreplace_status
    FROM complain 
    LEFT JOIN job ON job.complain_id = complain.complain_id 
    WHERE {$table} LIKE :value 
    ORDER BY complain_id ASC";
            $stmt = $pdo->prepare($statement);

            try {
                $stmt->execute(['value' => '%' . $value . '%']);
            } catch (PDOException $e) {
                echo "Execution error: " . $e->getMessage();
            }
        } else {
            $url = $pag;
            $statement = "SELECT complain.*, 
            job.complain_id AS job_complain_id, 
            job.officer, 
            job.issue, 
            job.solution, 
            job.service_type, 
            job.job_date, 
            job.accept, 
            job.verification, 
            job.itemreplace_status
     FROM complain 
     LEFT JOIN job ON job.complain_id = complain.complain_id
     ORDER BY complain_id ASC";
            try {
                $stmt->execute();

                // Get the number of rows returned
                $rows = $stmt->rowCount();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        $rows = $stmt->rowCount();
        if ($rows == 0) {
            $max = 0;
            $totalpages = 0;
        } else {
            $max = $maxvalue;
            $totalpages = ceil($rows / $max);
        }
        $lastpage = $totalpages;
        if (isset($_GET['pn']) && $_GET['pn'] != '') {
            $pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
            if ($pn < 1) {
                $pn = 1;
            } else if ($pn > $lastpage) {
                $pn = $lastpage;
            }
        } else {
            $pn = 1;
        }
        $limite = ($pn - 1) * $max;
        // -----------------------------------------------------
        $textline1 = "Numbers of Records : <b>$rows</b>";
        $textline2 = "View Page <b>$pn</b> of <b>$lastpage</b> Pages";
        $paginationCtrls = '';
        if ($lastpage != 1) {
            if ($pn > 1) {
                $previous = $pn - 1;
                $paginationCtrls .= '<a href="' . $url . '&pn=' . $previous . '">Previous</a>';
                for ($i = $pn - 4; $i < $pn; $i++) {
                    if ($i > 0) {
                        $paginationCtrls .= '<a href="' . $url . '&pn=' . $i . '">' . $i . '</a>';
                    }
                }
            }
            $paginationCtrls .= '<a href="' . $url . '&pn=' . $pn . '"' . 'class=active' . '>' . $pn . '</a>';

            for ($i = $pn + 1; $i <= $lastpage; $i++) {
                $paginationCtrls .= '<a href="' . $url . '&pn=' . $i . '">' . $i . '</a>';
                if ($i >= $pn + 4) {
                    break;
                }
            }

            if ($pn != $lastpage) {
                $next = $pn + 1;
                $paginationCtrls .= '<a href="' . $url . '&pn=' . $next . '">Next</a> ';
            }
        }
        ?>
        <!-- -------------------pagination---------------------------- -->

        <div class="content" style="padding-right:10px; padding: left 10px;">
            <table id="manage-service">
                <thead>
                    <tr>
                        <th>Complain ID</th>
                        <th>Date</th>
                        <th>Regional Office</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "{$statement}  LIMIT :limite, :max";
                    $stmt = $pdo->prepare($sql);

                    $limite = isset($limite) ? (int)$limite : 0;
                    $max = isset($max) ? (int)$max : 10;

                    // Bind parameters for pagination
                    $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
                    $stmt->bindParam(':max', $max, PDO::PARAM_INT);

                    if (!empty($service_val) && !empty($office_val)) {
                        // Bind 'service' and 'office' parameters for filtered search
                        $stmt->bindValue(':value', '%' . $value . '%', PDO::PARAM_STR);
                        $stmt->execute();
                    } else {
                        // Execute without additional parameters for full data
                        $stmt->execute();
                    }

                    $result = $stmt->fetchAll();
                    if (!empty($result)) {
                        foreach ($result as $row) {
                    ?>

                            <tr role="row" id="<?php echo $row['complain_id']; ?>">

                                <td role="cell" data-target="comid" data-label="complain_id"><?php echo $row['complain_id'] ?></td>
                                <td role="cell" data-target="comdate" data-label="date"><?php echo $row['date'] ?></td>
                                <td role="cell" data-target="comoffice" data-label="office_id"><?php echo $row['office_id'] ?></td>
                                <!-------------------hidding-------------------->
                                <td role="cell" data-target="comasset" data-label="asset_id" style='display:none'><?php echo $row['asset_id'] ?></td>
                                <td role="cell" data-target="comserial" data-label="serial_no" style='display:none'><?php echo $row['serial_no'] ?></td>
                                <td role="cell" data-target="comobs" data-label="serial_no" style='display:none'><?php echo $row['observations'] ?></td>
                                <td role="cell" data-target="comempid" style='display:none'><?php echo $row['emp_id'] ?></td>
                                <td role="cell" data-target="comrec" style='display:none'><?php echo $row['recommendation'] ?></td>
                                <td role="cell" data-target="pag" style='display:none'><?php echo $pn ?></td>
                                <!-------------------job-------------------->
                                <td role="cell" data-target="jobofficer" data-label="officer" style='display:none'><?php echo $row['officer'] ?></td>
                                <td role="cell" data-target="jobissue" data-label="issue" style='display:none'><?php echo $row['issue'] ?></td>
                                <td role="cell" data-target="jobreco" data-label="solution" style='display:none'><?php echo $row['solution'] ?></td>
                                <td role="cell" data-target="jobsolu" data-label="service_type" style='display:none'><?php echo $row['service_type'] ?></td>
                                <td role="cell" data-target="jobsdate" data-label="job_date" style='display:none'><?php echo $row['job_date'] ?></td>
                                <td role="cell" data-target="jobconf" data-label="accept" style='display:none'><?php echo $row['accept'] ?></td>
                                <td role="cell" data-target="jobveri" data-label="verification" style='display:none'><?php echo $row['verification'] ?></td>
                                <td data-label="Option" width="30%">
                                    <!-------------------view-------------------->
                                    <a href="#" data-role="upload" data-id="<?php echo $row['complain_id']; ?>" class="view">
                                        <i class="fa fa-search"></i>View</a>
                                    &nbsp&nbsp
                                    <!-------------------accept-------------------->
                                    <?php
                                    if ($role == 2) {
                                        if ($row['recommendation'] == 0) {
                                            echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until RM provides recommendation, the button is disabled.</span>';
                                        } elseif ($row['accept'] == 1) {
                                            echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Accept</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                        } else {
                                            echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" title="Helpdesk Officer" class="proceed"><i class="fa fa-search"></i>Proceed</a>';
                                        }
                                    } elseif ($row['accept'] == 1) {
                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Accept</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                    } else {
                                        echo '<a href="#" title="Helpdesk Officer" class="proceed" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Proceed</a>';
                                    }
                                    ?>
                                    <!-------------------verification-------------------->
                                    &nbsp&nbsp
                                    <?php
                                    if ($role == 3) {
                                        if ($row['accept'] == 0) {
                                            echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until HDO fill the service details, the button is disabled.</span>';
                                        } elseif ($row['verification'] == 1) {
                                            echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Verified</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                        } else {
                                            echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" title="Helpdesk Officer" class="verify"><i class="fa fa-search"></i>Verify System Admin</a>';
                                        }
                                    } elseif ($row['verification'] == 1) {
                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Verified</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                    } else {
                                        echo '<a href="#" title="Helpdesk Officer" class="verify" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Verify System Admin</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                </tbody>
        <?php }
                    } else {
                        echo '<tbody><tr><td colspan="5">';
                        echo '<label class="message">There was Not Data</label>';
                        echo '</td>';
                        echo '</tr>';
                        echo '</tbody>';
                    }
        ?>
            </table>

            <div class="item_sec4">
                <!--------------------pagination table----------------------->
                <div>
                    <p class="txt_t4"><?php echo $textline1; ?></p>
                    <p class="txt_t4"><?php echo $textline2; ?></p>
                </div>
                <div id="pagination" align="right">
                    <?php if (!empty($result)) {
                        echo $paginationCtrls;
                    } ?>
                </div>
                <!--------------------pagination table----------------------->
            </div>
        </div>
    </div>
</div>

<!---------------------content here------------------------------>
</div>

<!-------------------popup1----------------------->
<div id="popup_edit" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="pid"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="pcomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset :</b></span>
                    <span class="infor_t2" id="pcomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="pcomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No:</b></span>
                    <span class="infor_t2" id="pcomemp"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Office :</b></span>
                    <span class="infor_t2" id="pcomoffice"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Observations :</b></span>
                    <span class="infor_t2" id="pcomobs"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Recomendation :</b></span>
                    <span class="infor_t2" id="pcomrec"></span>
                </div>
                <div class="popup_footer">
                </div>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Service Verification</h1>
            <p class="page_heding4">IT Department</p>
            <!-----------important data------------->
            <input type="hidden" id="eid" name="setid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Assigned Officer :</b></span>
                </div>
                <div class="pro1">
                    <select id="officer" name="officer">
                        <option value="select">Select Assigned Officer</option>
                        <option value="helpdeskOfficer">Helpdesk Officer</option>
                        <option value="itOfficer">Information Technology Officer</option>
                        <option value="systemAdmin">System Administrator</option>
                    </select>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Issue :</b></span>
                </div>
                <div class="pro1">
                    <textarea class="formtxtare" name="issue" id="issue"></textarea>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Solution :</b></span>
                </div>
                <div class="pro1">
                    <textarea class="formtxtare" name="solution" id="solution"></textarea>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Service Provider :</b></span>
                </div>
                <div class="pro1">
                    <select id="services" name="services">
                        <option value="select">Select Solution Provider</option>
                        <option value="inhouse">Inhouse</option>
                        <option value="outsource">Outsource</option>
                    </select>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Date :</b></span>
                </div>
                <div class="pro1">
                    <input type="date" id="job_date" name="job_date" required="required">
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Helpdesk Officer Acceptation :</b></span>
                </div>
                <div class="pro1">
                    <input type="radio" name="ecomaccept" value="1">
                    <label class='form_radio'>Accept</label>
                    &nbsp;&nbsp;<input type="radio" name="ecomaccept" value="0">
                    <label class='form_radio'>Not Accept</label>
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendcomaccept();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="ecomaccept-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Accept Status :</b></span>
                </div>
                <div class="pro1">
                    <textarea placeholder="Write Accept Status.." class="formtxtare" name="status" id="status"></textarea>
                </div>
                <div class="pro1">
                </div>
            </div>
        </div>
        <div class="popup_footer">
            <span id="status_message"></span>
        </div>

    </div>
</div>
<!-------------------popup1----------------------->

<!-------------------popup2----------------------->
<div id="popup_editverify" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------complain----------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="vid"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="vcomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset :</b></span>
                    <span class="infor_t2" id="vcomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="vcomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No:</b></span>
                    <span class="infor_t2" id="vcomemp"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Office :</b></span>
                    <span class="infor_t2" id="vcomoffice"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Observations :</b></span>
                    <span class="infor_t2" id="vcomobs"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Recomendation :</b></span>
                    <span class="infor_t2" id="vcomrec"></span>
                </div>
            </div>
        </div>
        <div class="popup_footer">
        </div>
        <!-------------------service----------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Service Information</h1>
            <p class="page_heding4">IT Department</p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Assigned Officer :</b></span>
                    <span class="infor_t2" id="vofficer"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Issue Description :</b></span>
                    <span class="infor_t2" id="vissue"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Recommendations :</b></span>
                    <span class="infor_t2" id="vreco"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Solution Description:</b></span>
                    <span class="infor_t2" id="vsolu"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Job Start Date :</b></span>
                    <span class="infor_t2" id="vsdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Helpdesk Officer Confirmation :</b></span>
                    <span class="infor_t2" id="vconf"></span>
                </div>
            </div>
            <div class="popup_footer">
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>System Admin Verification :</b></span>
                </div>
                <div class="pro1">
                    <input type="radio" name="ecomverify" value="1">
                    <label class='form_radio'>Verify</label>
                    &nbsp;&nbsp;<input type="radio" name="ecomverify" value="0">
                    <label class='form_radio'>Not Verify</label>
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendcomverify();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="ecomverify-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Verification Status :</b></span>
                </div>
                <div class="pro1">
                    <textarea placeholder="Write Verification Status.." class="formtxtare" name="status_vr" id="status_vr"></textarea>
                </div>
                <div class="pro1">
                </div>
            </div>
        </div>
        <div class="popup_footer">
            <span id="status_message_vr"></span>
        </div>
    </div>
</div>
<!-------------------popup2----------------------->

<!-------------------popup3----------------------->
<div id="popup_view" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------complain----------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="viewid"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="viewcomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset :</b></span>
                    <span class="infor_t2" id="viewcomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="viewcomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No:</b></span>
                    <span class="infor_t2" id="viewcomemp"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Office :</b></span>
                    <span class="infor_t2" id="viewcomoffice"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Observations :</b></span>
                    <span class="infor_t2" id="viewcomobs"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Recomendation :</b></span>
                    <span class="infor_t2" id="viewcomrec"></span>
                </div>
            </div>
        </div>
        <div class="popup_footer">
        </div>
        <!-------------------service----------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Service Information</h1>
            <p class="page_heding4">IT Department</p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Assigned Officer :</b></span>
                    <span class="infor_t2" id="viewofficer"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Issue Description :</b></span>
                    <span class="infor_t2" id="viewissue"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Recommendations :</b></span>
                    <span class="infor_t2" id="viewreco"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Solution Description:</b></span>
                    <span class="infor_t2" id="viewsolu"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Job Start Date :</b></span>
                    <span class="infor_t2" id="viewsdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Helpdesk Officer Confirmation :</b></span>
                    <span class="infor_t2" id="viewconf"></span>
                </div>
            </div>
        </div>
        <div class="popup_footer">
        </div>
    </div>
</div>
<!-------------------popup3----------------------->

<!----------------------javascript--------------------------->

<!-------------------edit----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".proceed").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comemp = $('#' + id).children('td[data-target=comempid]').text();
            var comoffice = $('#' + id).children('td[data-target=comoffice]').text();
            var comobs = $('#' + id).children('td[data-target=comobs]').text();
            var comimg = $('#' + id).children('td[data-target=comimg]').text();
            var comrec = $('#' + id).children('td[data-target=comrec]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#pid').text(comid);
            $('#eid').val(comid);
            $('#ppag').text(pag);
            $('#pcomdate').text(comdate);
            $('#pcomasset').text(comasset);
            $('#pcomserial').text(comserial);
            $('#pcomemp').text(comemp);
            $('#pcomoffice').text(comoffice);
            $('#pcomobs').text(comobs);
            $('#pcomrec').text(comrec);
            document.getElementById('popup_edit').style.display = 'block';
        })

    });
</script>
<script type="text/javascript">
    //----------------------comrec-----------------------------
    function sendcomaccept() {
        var valid;
        valid = validatescomaccept();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/verify_complain.php",
                data: 'jobconf=' + $('input[name="ecomaccept"]:checked').val() +
                    '&officer=' + $('#officer').val() +
                    '&issue=' + $('#issue').val() +
                    '&solution=' + $('#solution').val() +
                    '&services=' + $('#services').val() +
                    '&job_date=' + $('#job_date').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val() +
                    '&status=' + $('#status').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatescomaccept() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='ecomaccept']:checked").length == 0) {
            $("#ecomaccept-info").html("<i class='fa fa-times'></i>Acceptance is Required");
            valid = false;
        } else {
            $("#ecomaccept-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<!-------------------edit----------------------->

<!-------------------view----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".view").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comemp = $('#' + id).children('td[data-target=comempid]').text();
            var comoffice = $('#' + id).children('td[data-target=comoffice]').text();
            var comobs = $('#' + id).children('td[data-target=comobs]').text();
            var comimg = $('#' + id).children('td[data-target=comimg]').text();
            var comrec = $('#' + id).children('td[data-target=comrec]').text();
            var jobofficer = $('#' + id).children('td[data-target=jobofficer]').text();
            var jobissue = $('#' + id).children('td[data-target=jobissue]').text();
            var jobreco = $('#' + id).children('td[data-target=jobreco]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobsdate = $('#' + id).children('td[data-target=jobsdate]').text();
            var jobconf = $('#' + id).children('td[data-target=jobconf]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#viewid').text(comid);
            $('#eid').val(comid);
            $('#viewpag').text(pag);
            $('#viewcomdate').text(comdate);
            $('#viewcomasset').text(comasset);
            $('#viewcomserial').text(comserial);
            $('#viewcomemp').text(comemp);
            $('#viewcomoffice').text(comoffice);
            $('#viewcomobs').text(comobs);
            $('#viewcomrec').text(comrec);
            $('#viewofficer').text(jobofficer);
            $('#viewissue').text(jobissue);
            $('#viewreco').text(jobreco);
            $('#viewsolu').text(jobsolu);
            $('#viewsdate').text(jobsdate);
            $('#viewconf').text(jobconf);
            document.getElementById('popup_view').style.display = 'block';
        })
    });
</script>
<!-------------------view----------------------->

<!-------------------edit_verify----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".verify").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comemp = $('#' + id).children('td[data-target=comempid]').text();
            var comoffice = $('#' + id).children('td[data-target=comoffice]').text();
            var comobs = $('#' + id).children('td[data-target=comobs]').text();
            var comimg = $('#' + id).children('td[data-target=comimg]').text();
            var comrec = $('#' + id).children('td[data-target=comrec]').text();
            var jobofficer = $('#' + id).children('td[data-target=jobofficer]').text();
            var jobissue = $('#' + id).children('td[data-target=jobissue]').text();
            var jobreco = $('#' + id).children('td[data-target=jobreco]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobsdate = $('#' + id).children('td[data-target=jobsdate]').text();
            var jobconf = $('#' + id).children('td[data-target=jobconf]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#vid').text(comid);
            $('#eid').val(comid);
            $('#vpag').text(pag);
            $('#vcomdate').text(comdate);
            $('#vcomasset').text(comasset);
            $('#vcomserial').text(comserial);
            $('#vcomemp').text(comemp);
            $('#vcomoffice').text(comoffice);
            $('#vcomobs').text(comobs);
            $('#vcomrec').text(comrec);
            $('#vofficer').text(jobofficer);
            $('#vissue').text(jobissue);
            $('#vreco').text(jobreco);
            $('#vsolu').text(jobsolu);
            $('#vsdate').text(jobsdate);
            $('#vconf').text(jobconf);
            document.getElementById('popup_editverify').style.display = 'block';
        })
    });
</script>
<script type="text/javascript">
    //----------------------comrec-----------------------------
    function sendcomverify() {
        var valid;
        valid = validatescomverify();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/verify_job.php",
                data: 'jobveri=' + $('input[name="ecomverify"]:checked').val() +
                    '&status_vr=' + $('#status_vr').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_vr").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatescomverify() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='ecomverify']:checked").length == 0) {
            $("#ecomverify-info").html("<i class='fa fa-times'></i>Verification is Required");
            valid = false;
        } else {
            $("#ecomverify-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<!-------------------edit_verify----------------------->
<!-------------------cancel btns----------------------->
<script>
    function cancel_view() {
        document.getElementById('popup_view').style.display = 'none';
        location.reload();
    }

    function cancel_edit() {
        document.getElementById('popup_proceed').style.display = 'none';
        location.reload();
    }

    function cancel_del() {
        document.getElementById('popup_position').style.display = 'none';
        location.reload();
    }

    function relaod() {
        window.open('index.php?page=rm_recommen', '_parent');
    }
</script>
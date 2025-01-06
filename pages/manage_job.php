<?php
// --------------------page configs-------------------------
$pag = "index.php?page=manage_job";
$maxvalue = 10;
?>
<div id="main_content">
    <!--------------------content here----------------------------->
    <div id="heding_section">
        <!--------------------heading_section----------------------------->
        <div class="page_title">
            <p>Service<span class="page_name"> > Manage Job</span></p>
        </div>
        <!--------------------heading_section----------------------------->
    </div>
    <div id="body_section">
        <div class="page_subtitle">
            <p class="title">Manage Job</p>
        </div>

        <div class="content" style="padding-right:10px; padding: left 10px;">
            <div class="form-group row">
                <div class="well well-sm">

                    <form action="#" method="POST" class="col">
                        <div class="col-1">
                            <label class="input-md" style="padding-right: 1px;">
                                <b>Solution Provider</b>
                            </label>
                            <div>
                                <select id="sprovider" name="service_val">
                                    <option>Solution Provider</option>
                                    <option value="inhouse">Inhouse</option>
                                    <option value="outsource">Outsource</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-2">
                            <label class="input-md" style="padding-right: 1px;">
                                <b>Regional Office</b>
                            </label>
                            <div>
                                <select id="regional_office" name="office_val">
                                    <option value="select">Select Regional Office</option>
                                    <option value="HO">Head Office</option>
                                    <option value="C1">Colombo 01</option>
                                    <option value="C2">Colombo 02</option>
                                    <option value="C3">Colombo 03</option>
                                    <option value="C4">Colombo 04</option>
                                    <option value="G1">Gampaha 01</option>
                                    <option value="G2">Gampaha 02</option>
                                    <option value="KLT">Kalutara</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-3">
                            <button type="submit" name="search" class="search_btn">
                                <i class="fa fa-search"></i>
                            </button>
                            <button id="reload" onClick="relaod();" class="search_btn">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div> <?php
                        if (!empty($mess)) {
                            echo $mess;
                        }
                        ?></div>
            </div>

            <!---------------------pagination------------------------------>
            <?php
            include("includes/connect.php");

            if (!empty($service_val) && !empty($office_val)) {
                $url = $pag . '&s=' . $service_val . '&c=' . $office_val;
                $statement = "
    SELECT complain.*, 
           job.complain_id AS job_complain_id, 
           job.officer, 
           job.issue, 
           job.solution, 
           job.service_type, 
           job.job_date, 
           job.accept, 
           job.verification, 
           job.itemreplace_status, 
           service.complain_id AS service_complain_id, 
           service.reference_no, 
           service.company, 
           service.date 
    FROM complain 
    LEFT JOIN job ON job.complain_id = complain.complain_id 
    LEFT JOIN service ON service.complain_id = complain.complain_id 
    WHERE job.service_type LIKE :service AND complain.office_id LIKE :office 
    ORDER BY complain_id ASC";

                $stmt = $pdo->prepare($statement);

                try {
                    $stmt->execute(['service' => '%' . $service_val . '%', 'office' => '%' . $office_val . '%']);
                } catch (PDOException $e) {
                    echo "Execution error: " . $e->getMessage();
                }
            } else {
                $url = $pag;
                // Use the basic table without WHERE clause
                $statement = "
    SELECT complain.*,
           job.complain_id AS job_complain_id,
           job.officer,
           job.issue,
           job.solution,
           job.service_type,
           job.job_date,
           job.accept,
           job.verification,
           job.itemreplace_status,
           service.complain_id AS service_complain_id,
           service.reference_no,
           service.company,
           service.date
    FROM complain
    INNER JOIN job ON job.complain_id = complain.complain_id
    LEFT JOIN service ON service.complain_id = complain.complain_id
    ORDER BY complain_id ASC";

                try {
                    $stmt->execute();

                    // Get the number of rows returned
                    $rows = $stmt->rowCount();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }

            // Determine pagination limits
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

            // Pagination controls setup
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
                $paginationCtrls .= '<a href="' . $url . '&pn=' . $pn . '" class="active">' . $pn . '</a>';
                for ($i = $pn + 1; $i <= $lastpage; $i++) {
                    $paginationCtrls .= '<a href="' . $url . '&pn=' . $i . '">' . $i . '</a>';
                    if ($i >= $pn + 4) {
                        break;
                    }
                }

                if ($pn != $lastpage) {
                    $next = $pn + 1;
                    $paginationCtrls .= '<a href="' . $url . '&pn=' . $next . '">Next</a>';
                }
            }
            ?>

            <!-- -------------------pagination---------------------------- -->
            <table id="job">
                <thead>
                    <tr>
                        <th>Complain ID</th>
                        <th>Asset No</th>
                        <th>Regional Office</th>
                        <th>Issue</th>
                        <th>Solution</th>
                        <th>Solution Provider</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch paginated results
                    $sql = "{$statement}  LIMIT :limite, :max";
                    $stmt = $pdo->prepare($sql);

                    $limite = isset($limite) ? (int)$limite : 0;
                    $max = isset($max) ? (int)$max : 10;

                    // Bind parameters for pagination
                    $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
                    $stmt->bindParam(':max', $max, PDO::PARAM_INT);

                    if (!empty($service_val) && !empty($office_val)) {
                        // Bind 'service' and 'office' parameters for filtered search
                        $stmt->bindValue(':service', '%' . $service_val . '%', PDO::PARAM_STR);
                        $stmt->bindValue(':office', '%' . $office_val . '%', PDO::PARAM_STR);
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
                                <td role="cell" data-target="comid"><?php echo $row['complain_id'] ?></td>
                                <td role="cell" data-target="comasset"><?php echo $row['asset_id'] ?></td>
                                <td role="cell" data-target="comoffice"><?php echo $row['office_id'] ?></td>
                                <td role="cell" data-target="jobissue"><?php echo $row['issue'] ?></td>
                                <td role="cell" data-target="jobsolu"><?php echo $row['solution'] ?></td>
                                <td role="cell" data-target="jobtype"><?php echo $row['service_type'] ?></td>
                                <!-- Additional hidden columns -->
                                <td role="cell" data-target="comserial" style='display:none'><?php echo $row['serial_no'] ?></td>
                                <td role="cell" data-target="comempid" style='display:none'><?php echo $row['emp_id'] ?></td>
                                <td role="cell" data-target="comrec" style='display:none'><?php echo $row['recommendation'] ?></td>
                                <!-- Hidden job and service fields as needed -->
                                <td role="cell" data-target="jobofficer" data-label="officer" style='display:none'><?php echo $row['officer'] ?></td>
                                <td role="cell" data-target="jobsdate" data-label="job_date" style='display:none'><?php echo $row['job_date'] ?></td>
                                <td role="cell" data-target="jobconf" data-label="accept" style='display:none'><?php echo $row['accept'] ?></td>
                                <td role="cell" data-target="jobveri" data-label="verification" style='display:none'><?php echo $row['verification'] ?></td>
                                <td role="cell" data-target="jobirepl" data-label="itemreplace_status" style='display:none'><?php echo $row['itemreplace_status'] ?></td>
                                <!-------------------service-------------------->
                                <td role="cell" data-target="seref" data-label="reference_no" style='display:none'><?php echo $row['reference_no'] ?></td>
                                <td role="cell" data-target="secom" data-label="company" style='display:none'><?php echo $row['company'] ?></td>
                                <td role="cell" data-target="sedate" data-label="date" style='display:none'><?php echo $row['date'] ?></td>
                                <td role="cell" data-target="seconfirm" data-label="confirm" style='display:none'><?php echo $row['confirm'] ?></td>
                                <td data-label="Option">
                                    <a href="#" data-role="upload" data-id="<?php echo $row['complain_id']; ?>" class="view">
                                        <i class="fa fa-search"></i>View</a>
                                    &nbsp;&nbsp;
                                    <?php
                                    if ($row['verification'] == 0) {
                                    ?>
                                        <span class="search_mess"><i class="far fa-thumbs-up"></i>Until System Admin provide recommend to service the button is disabled.</span>
                                    <?php
                                    } elseif ($row['itemreplace_status'] == 2) {
                                    ?>
                                        <a href="#" data-role="upload" data-id="<?php echo $row['complain_id']; ?>" class="proceed"><i class="fa fa-cog"></i>Proceed</a>
                                    <?php
                                    } elseif ($row['itemreplace_status'] == 0) {
                                    ?>
                                        <span class="search_mess"><i class="far fa-thumbs-up"></i>Item Not Replace</span>
                                    <?php
                                    } else {
                                    ?>
                                        <span class="search_mess"><i class="far fa-thumbs-up"></i>Item Replaced</span>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                    <?php }
                    } else {
                        echo '<tbody><tr><td colspan="5">No Data Available</td></tr></tbody>';
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="item_sec4">
                <p class="txt_t4"><?php echo $textline1; ?></p>
                <p class="txt_t4"><?php echo $textline2; ?></p>
                <div id="pagination" align="right">
                    <?php if (!empty($result)) {
                        echo $paginationCtrls;
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!---------------------content here------------------------------>

<!-------------------popup1----------------------->
<div id="popup_edit" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_edit();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div id="popup-content-1">
            <div class="popup_head">
                <h1 class="page_heding2">Item Replacement Information</h1>
                <p class="page_heding4">Information about Complain Id :
                    <span class="infor_t4" id="pid"></span>
                </p>
                <!-----------important data------------->
                <input type="hidden" id="eid" name="setid">
                <input type="hidden" name="pag" id="epag">
                <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
            </div>
            <div class="popup_container">
                <div class="pro1_wapper">
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
                            <b>Issue :</b></span>
                        <span class="infor_t2" id="pjissue"></span>
                    </div>
                    <div class="pro1" align="center">
                        <span class="infor_t2">
                            <b>Solution :</b></span>
                        <span class="infor_t2" id="pjsolu"></span>
                    </div>
                    <div class="pro1_warp">
                        <div class="pro1" align="right">
                            <span class="infor_t2"><b>Item Replace :</b></span>
                        </div>
                        <div class="pro1">
                            <input type="radio" name="replacement" id="replace" value="1">
                            <label class='form_radio'>Yes</label>
                            &nbsp;&nbsp;<input type="radio" name="replacement" id="not_replace" value="0">
                            <label class='form_radio'>No</label>
                        </div>
                        <div class="pro1">
                            <button class="edit_btn" onclick="senditemreplace();">
                                <i class="fas fa-plus-square"></i>
                            </button>
                            <span id="itemreplace-info" class="error"></span>
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
                    <button class="next_btn" id="next_button" onclick="showNextContent()">Next</button>
                    <button class="finish_btn">Finish</button>
                    <div class="popup_footer">
                        <span id="status_message"></span>
                    </div>
                </div>
            </div>
        </div>
        <div id="popup-content-2" style="display: none;">
            <div class="popup_head">
                <h1 class="page_heding2">Service Provider Information</h1>
                <!-----------important data------------->
                <input type="hidden" id="eid" name="setid">
                <input type="hidden" name="pag" id="epag">
                <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
            </div>
            <div class="popup_container">
                <div class="pro1_wapper">
                    <div class="pro1_warp">
                        <div class="pro1" align="right">
                            <span class="infor_t2"><b>Job Reference No :</b></span>
                        </div>
                        <div class="pro1">
                            <input type="text" id="refno" name="refno">
                        </div>
                        <div class="pro1">
                        </div>
                    </div>
                    <div class="pro1_warp">
                        <div class="pro1" align="right">
                            <span class="infor_t2"><b>Job Outsourced Company :</b></span>
                        </div>
                        <div class="pro1">
                            <input type="text" id="company" name="company">
                        </div>
                        <div class="pro1">
                        </div>
                    </div>
                    <div class="pro1_warp">
                        <div class="pro1" align="right">
                            <span class="infor_t2"><b>Date :</b></span>
                        </div>
                        <div class="pro1">
                            <input type="date" id="date" name="date">
                        </div>
                        <div class="pro1">
                        </div>
                    </div>
                    <div class="pro1_warp">
                        <div class="pro1" align="right">
                            <span class="infor_t2"></span>
                        </div>
                        <div class="pro1">
                            <input type="checkbox" name="check" value="1">
                            <label class='form_radio'>I confirmed above details are correct.</label>
                        </div>
                        <div class="pro1">
                            <button class="edit_btn" onclick="sendcompany();">
                                <i class="fas fa-plus-square"></i>
                            </button>
                            <span id="confirmse-info" class="error"></span>
                        </div>
                    </div>
                    <!-- <button class="save_submit_btn" onclick="sendcompany()">Save & Submit</button> -->
                    <button class="prev_btn" onclick="showPreviousContent()">Previous</button>
                    <div class="popup_footer">
                        <span id="status_message_com"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-------------------popup1----------------------->
<!-------------------popup2----------------------->
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
            <h1 class="page_heding2">Item Replace Information</h1>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
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
                        <b>Issue :</b></span>
                    <span class="infor_t2" id="viewissue"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Solution:</b></span>
                    <span class="infor_t2" id="viewsolu"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Item Replace Status :</b></span>
                    <span class="infor_t2" id="viewstatus"></span><br>
                    <span align="center" style="color:#d3542d; margin-top:10px; font-size:13.5px;">Note: Item Replace status two means the status value not updated yet. It's a default value.</span>
                </div>
            </div>
        </div>
        <div class="popup_footer">
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Service Provider Information</h1>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Job Reference No :</b></span>
                    <span class="infor_t2" id="viewrefno"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Company :</b></span>
                    <span class="infor_t2" id="viewcompany"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Date :</b></span>
                    <span class="infor_t2" id="viewdate"></span>
                </div>
            </div>
        </div>
        <div class="popup_footer">
        </div>
    </div>
</div>
<!-------------------popup2----------------------->
<!-------------------edit(popup1)----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".proceed").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var jissue = $('#' + id).children('td[data-target=jobissue]').text();
            var jsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#pid').text(comid);
            $('#eid').val(comid);
            $('#ppag').text(pag);
            $('#pcomasset').text(comasset);
            $('#pcomserial').text(comserial);
            $('#pjissue').text(jissue);
            $('#pjsolu').text(jsolu);
            document.getElementById('popup_edit').style.display = 'block';
        })

        $('.next_btn').prop('disabled', true);
        $('.finish_btn').prop('disabled', true);

        $('input[name="replacement"]').change(function() {
            if ($('#replace').is(':checked')) {
                $('.next_btn').prop('disabled', false);
                $('.finish_btn').prop('disabled', true);
            } else if ($('#not_replace').is(':checked')) {
                $('.next_btn').prop('disabled', true);
                $('.finish_btn').prop('disabled', false);
            }
        });

    });
</script>
<script type="text/javascript">
    //----------------------itemreplace-----------------------------
    function senditemreplace() {
        var valid;
        valid = validateitemreplace();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/add_itemreplacement.php",
                data: 'jobirepl=' + $('input[name="replacement"]:checked').val() +
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
    function validateitemreplace() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='replacement']:checked").length == 0) {
            $("#itemreplace-info").html("<i class='fa fa-times'></i>Replacement status is Required");
            valid = false;
        } else {
            $("#itemreplace-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
    //--------------------------------------------------------
    function showNextContent() {
        document.getElementById('popup-content-1').style.display = 'none';
        document.getElementById('popup-content-2').style.display = 'block';
    }
    //--------------------------------------------------------
    function showPreviousContent() {
        // Hide the next content
        document.getElementById('popup-content-2').style.display = 'none';
        // Show the initial content
        document.getElementById('popup-content-1').style.display = 'block';
        confirmNext();
    }
</script>

<script type="text/javascript">
    //----------------------add company detail-----------------------------
    function sendcompany() {
        var valid;
        valid = validatescompanyadd();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/add_service.php",
                data: 'seconfirm=' + $('input[name="check"]:checked').val() +
                    '&refno=' + $('#refno').val() +
                    '&company=' + $('#company').val() +
                    '&date=' + $('#date').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_com").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatescompanyadd() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='check']:checked").length == 0) {
            $("#confirmse-info").html("<i class='fa fa-times'></i>Confirmation is Required");
            valid = false;
        } else {
            $("#confirmse-info").html('<i class="fas fa-check"></i>');
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
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var jobissue = $('#' + id).children('td[data-target=jobissue]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobirepl = $('#' + id).children('td[data-target=jobirepl]').text();
            var seref = $('#' + id).children('td[data-target=seref]').text();
            var secom = $('#' + id).children('td[data-target=secom]').text();
            var sedate = $('#' + id).children('td[data-target=sedate]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#viewpag').text(pag);
            $('#viewcomasset').text(comasset);
            $('#viewcomserial').text(comserial);
            $('#viewissue').text(jobissue);
            $('#viewsolu').text(jobsolu);
            $('#viewstatus').text(jobirepl);
            $('#viewrefno').text(seref);
            $('#viewcompany').text(secom);
            $('#viewdate').text(sedate);
            document.getElementById('popup_view').style.display = 'block';
        })
    });
</script>
<!-------------------view----------------------->
<!-------------------cancel btns----------------------->
<script>
    function cancel_view() {
        document.getElementById('popup_view').style.display = 'none';
        location.reload();
    }

    function cancel_edit() {
        document.getElementById('popup_edit').style.display = 'none';
        location.reload();
    }

    function cancel_del() {
        document.getElementById('popup_position').style.display = 'none';
        location.reload();
    }

    function relaod() {
        window.open('index.php?page=manage_job', '_parent');
    }
</script>
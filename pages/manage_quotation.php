<?php
// --------------------page configs-------------------------
$pag = "index.php?page=manage_quotation";
$btnrecon = array(3);
$btnrecdgm = array(5);
$btnrecgm = array(6);
$maxvalue = 10;
?>
<div id="main_content">
    <!--------------------content here----------------------------->
    <div id="heding_section">
        <!--------------------heading_section----------------------------->
        <div class="page_title">
            <p>Quotation<span class="page_name"> > Manage Quotation</span></p>
        </div>
        <!--------------------heading_section----------------------------->
    </div>
    <div id="body_section">
        <div class="page_subtitle">
            <p class="title">Manage Quotation</p>
        </div>

        <div class="content" style="padding-right:10px;">

            <!---------------------pagination------------------------------>
            <?php
            include("includes/connect.php");
            $baseQuery =
                "SELECT complain.*,
           service.complain_id AS service_complain_id,
           service.reference_no,
           service.company,
           service.date,
           service.confirm,
           quotation.complain_id AS quotation_complain_id,
           quotation.quotation_no,
           quotation.replace_items,
           quotation.quotation_amount,
           quotation.quotation_image,
           quotation.quotation_confirm,
           quotation.fin_recommendation,
           quotation.gm_approval
    FROM complain
    INNER JOIN service ON service.complain_id = complain.complain_id
    LEFT JOIN quotation ON quotation.complain_id = complain.complain_id
    ORDER BY complain.complain_id ASC";

            if (!empty($value) && !empty($table)) {
                $url = $pag . '&st=' . $search_type . '&sv=' . $value;
                $baseQuery .= " WHERE s.{$table} LIKE :value";
            } else {
                $url = $pag;
            }
            $stmtCount = $pdo->prepare($baseQuery);
            if (!empty($value) && !empty($table)) {
                $stmtCount->execute(['value' => '%' . $value . '%']);
            } else {
                $stmtCount->execute();
            }
            $rows = $stmtCount->rowCount();
            $max = $maxvalue;
            $totalpages = ceil($rows / $max);
            $lastpage = $totalpages;
            $pn = isset($_GET['pn']) ? max(1, min($lastpage, (int)$_GET['pn'])) : 1;
            $limite = ($pn - 1) * $max;
            $baseQuery .= " LIMIT {$limite}, {$max}";
            $stmt = $pdo->prepare($baseQuery);
            if (!empty($value) && !empty($table)) {
                $stmt->execute(['value' => '%' . $value . '%']);
            } else {
                $stmt->execute();
            }
            $result = $stmt->fetchAll();
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

            <table id="job_detail">
                <thead>
                    <tr>
                        <th>Reference No</th>
                        <th>Company</th>
                        <th>Date</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($result)) {
                        foreach ($result as $row) {
                    ?>

                            <tr role="row" id="<?php echo $row['complain_id']; ?>">

                                <td role="cell" data-target="refno" data-label="reference_no"><?php echo $row['reference_no']; ?></td>
                                <td role="cell" data-target="secom" data-label="company"><?php echo $row['company']; ?></td>
                                <td role="cell" data-target="sedate" data-label="date"><?php echo $row['date']; ?></td>
                                <td role="cell" data-target="comid" style="display: none;"><?php echo $row['complain_id']; ?></td>
                                <!------------------------ quotation----------------- -->
                                <td role="cell" data-target="qid" style="display: none;"><?php echo $row['quotation_no']; ?></td>
                                <td role="cell" data-target="qitem" style="display: none;"><?php echo $row['replace_items']; ?></td>
                                <td role="cell" data-target="qamount" style="display: none;"><?php echo $row['quotation_amount']; ?></td>
                                <td role="cell" data-target="qcom" style="display: none;"><?php echo $row['quotation_confirm']; ?></td>
                                <td role="cell" data-target="qimg" style="display: none;"><?php echo $row['quotation_image']; ?></td>
                                <td role="cell" data-target="finrec" style="display: none;"><?php echo $row['fin_recommendation']; ?></td>
                                <td role="cell" data-target="aprv" style="display: none;"><?php echo $row['gm_approval']; ?></td>
                                <td data-label="Option" width="30%">

                                    <!---------------------view------------------------->
                                    <a href="#" data-role="upload" data-id="<?php echo $row['complain_id']; ?>" class="view"><i
                                            class="fa fa-cog"></i>View</a>
                                    <!----------------Add Quotation------------------->
                                    <?php
                                    if ($role == 3) {
                                        if ($row['confirm'] == 0) {
                                            echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until HDO add company details, the button is disabled.</span>';
                                        } elseif ($row['quotation_confirm'] == 1) {
                                            echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Quotation Added</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                        } else {
                                            echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" class="proceed"><i class="fa fa-search"></i>Add Quotation</a>';
                                        }
                                    } elseif ($row['quotation_confirm'] == 1) {
                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Quotation Added</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                    } else {
                                        echo '<a href="#" class="proceed" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Add Quotation</a>';
                                    }
                                    ?>

                                    <!-------------------finance_btn-------------------->
                                    <?php
                                    if ($role == 5) {
                                        if ($row['quotation_confirm'] == 0) {
                                            echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until System Admin add quotation details, the button is disabled.</span>';
                                        } elseif ($row['fin_recommendation'] == 1) {
                                            echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>DGM Finance Recommended</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                        } else {
                                            echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" title="DGM Finance" class="finance_btn"><i class="fa fa-search"></i>Verify</a>';
                                        }
                                    } elseif ($row['gm_approval'] == 1) {
                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>DGM Finance Recommended</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                    } else {
                                        echo '<a href="#" title="General Manager" class="finance_btn" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Verify</a>';
                                    }
                                    ?>

                                    <!-------------------approve_btn-------------------->
                                    <?php
                                    if ($role == 6) {
                                        if ($row['fin_recommendation'] == 0) {
                                            echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until DGM Finance recommended the quotation, the button is disabled.</span>';
                                        } elseif ($row['gm_approval'] == 1) {
                                            echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>GM Approved</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                        } else {
                                            echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" title="General Manager" class="approve_btn"><i class="fa fa-search"></i>Verify</a>';
                                        }
                                    } elseif ($row['gm_approval'] == 1) {
                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>GM Approved</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                    } else {
                                        echo '<a href="#" title="General Manager" class="approve_btn" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Verify</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                    <?php }
                    } else {
                        echo '<tr><td colspan="4"><label class="message">There was No Data</label></td></tr>';
                    }
                    ?>
                </tbody>

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

<!-------------------popup1----------------------->
<div id="popup_edit" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_edit();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Quotation Information</h1>
            <p class="page_heding4">Information about reference no :
                <span class="infor_t4" id="vrefid"></span>
            </p>
            <!-----------important data------------->
            <input type="hidden" id="eid" name="setid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>Quotation No :</b></span>
                    </div>
                    <div class="pro1">
                        <input type="text" id="qutno" name="qutno">
                    </div>
                    <div class="pro1">
                    </div>
                </div>
                <div class="pro1_warp">
                    <div class="container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>List of items to be replaced</th>
                                    <th>Amount</th>
                                    <th width="50px">
                                        <div class="action_container">
                                            <button class="success" onclick="create_tr('table_body')">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table_body">
                                <tr>
                                    <td>
                                        <span class="row-number">1</span>
                                    </td>
                                    <td>
                                        <input type="text" class="form_control" />
                                    </td>
                                    <td>
                                        <input type="number" class="form_control amt-field" oninput="calculateTotal()" />
                                    </td>
                                    <td>
                                        <div class="action_container">
                                            <button class="danger" onclick="remove_tr(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="pro1_warp">
                    <div class="pro1" align="right" style="background-color: white;">
                    </div>
                    <div class="pro1" style="background-color: white;">
                    </div>
                    <div class="pro1" style="background-color: white;">
                        <div class="row">
                            <div class="col-8">
                                <div style="padding-bottom: 5px;">
                                    <span class="input-group-text">Total</span>
                                    <input type="number" class="form-control" id="FTotal" name="FTotal" disabled="" style="width: 150px; height:20px; margin-left:30px;">
                                </div>
                                <div style="padding-bottom: 5px;">
                                    <span class="input-group-text">VAT</span>
                                    <input type="number" class="form-control" id="FGST" name="FGST" onchange="GetTotal()" style="width: 150px; height:20px; margin-left:35px;">
                                </div>
                                <div style="padding-bottom: 5px;">
                                    <span class="input-group-text">Net Amt</span>
                                    <input type="number" class="form-control" id="FNet" name="FNet" disabled="" style="width: 150px; height:20px; margin-left:10px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add QUOTATION iMAGE -->
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2">Upload Image</span>
                    </div>
                    <div class="pro1">
                        <input type="file" id="myFile" name="filename" multiple>
                        <p class="comment">Image size should be less than 1MB</p>
                    </div>
                    <div class="pro1">
                        <span id="confirm-info" class="error"></span>
                    </div>
                </div>
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"></span>
                    </div>
                    <div class="pro1">
                        <input type="checkbox" name="check" value="1">
                        <label class='form_radio'>Agree and confirm above details are correct.</label>
                    </div>
                    <div class="pro1">
                        <span id="confirm-info" class="error"></span>
                    </div>
                </div>
                <div class="pro1_warp">
                    <div class="pro1" align="right" style="background-color: white;">
                    </div>
                    <div class="pro1" style="background-color: white;">
                    </div>
                    <div class="pro1" style="background-color: white;">
                        <input type="hidden" id="refid">
                        <button class=" save_submit_btn" onclick="sendqutinfo()">Save & Submit</button>
                    </div>
                </div>
            </div>
            <div class="popup_footer">
                <span id="status_message"></span>
            </div>
        </div>
    </div>
</div>

<!-------------------edit----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".proceed").click(function() {
            var id = $(this).data('id');
            var id = id;
            var refno = $('#' + id).children('td[data-target=refno]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#vrefid').text(refno);
            $('#refid').val(refno);
            $('#eid').val(comid);
            $('#epag').text(pag);
            document.getElementById('popup_edit').style.display = 'block';
        })

    });
</script>
<script type="text/javascript">
    //----------------------addquotation-----------------------------
    function sendqutinfo() {
        var valid;
        valid = validatesqutinfo();
        if (valid) {
            var itemsArray = [];
            $("#table_body tr:not(.d-none)").each(function() {
                var item = $(this).find("td input[type='text']").val();
                var amount = $(this).find("td input[type='number']").val();
                if (item && amount) {
                    itemsArray.push({
                        item: item,
                        amount: amount
                    });
                }
            });
            var itemsJSON = JSON.stringify(itemsArray);

            var formData = new FormData();
            formData.append('qutconfirm', $('input[name="check"]:checked').val() || 0);
            formData.append('qutno', $('#qutno').val());
            formData.append('replace_items', itemsJSON);
            formData.append('qutamu', $('#FNet').val());
            formData.append('filename', $('#myFile')[0].files[0]); // Append the file
            formData.append('refid', $('#refid').val());
            formData.append('userid', $('#userid').val());
            formData.append('setid', $('#eid').val());

            jQuery.ajax({
                url: "include_ajaxs/add_quotation.php",
                data: formData,
                type: "POST",
                processData: false, // Prevent jQuery from processing data
                contentType: false, // Set contentType to false for file upload
                success: function(data) {
                    $("#status_message").html(data);
                },
                error: function() {
                    $("#status_message").html('<label class="error_mess">An error occurred while uploading.</label>');
                }
            });
        }
    }
    //--------------------------------------------------------
    function validatesqutinfo() {
        var valid = true;
        var qutno = document.getElementById('qutno').value;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='check']:checked").length == 0) {
            $("#confirm-info").html("<i class='fa fa-times'></i>Confirmation is Required");
            valid = false;
        } else if (!qutno.trim()) {
            alert('Please enter quotation no');
            valid = false;
        } else {
            $("#confirm-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }

    function calculateTotal() {
        let sum = 0;
        document.querySelectorAll(".amt-field").forEach(input => {
            let value = parseFloat(input.value) || 0;
            sum += value;
        });

        document.getElementById("FTotal").value = sum;

        // Recalculate the net amount with VAT
        let vat = parseFloat(document.getElementById("FGST").value) || 0;
        document.getElementById("FNet").value = sum + vat;
    }

    document.getElementById("FGST").addEventListener("input", calculateTotal);
    //--------------------------------------------------------

    function create_tr(table_id) {
        let table_body = document.getElementById(table_id);
        let first_tr = table_body.firstElementChild;
        let tr_clone = first_tr.cloneNode(true);

        // Clear input values in the cloned row
        let inputs = tr_clone.getElementsByTagName("input");
        for (let input of inputs) {
            input.value = "";
        }

        table_body.appendChild(tr_clone);
        updateRowNumbers();
    }
    //--------------------------------------------------------

    function remove_tr(element) {
        let table_body = element.closest("tbody");
        if (table_body.childElementCount === 1) {
            alert("You don't have permission to delete this.");
        } else {
            element.closest("tr").remove();
            updateRowNumbers(); // Update row numbers after a row is removed
            calculateTotal(); // Recalculate total
        }
    }
    //--------------------------------------------------------

    function updateRowNumbers() {
        let rows = document.querySelectorAll("#table_body tr");
        rows.forEach((row, index) => {
            row.querySelector(".row-number").innerText = index + 1;
        });
    }
</script>
<!-------------------edit----------------------->

<!--------------------------popup2 window (Verify btn - DGM(Finance))----------------------->
<div id="popup_finance_btn" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_edit();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------Complain-------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Quotation Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="vfcid"></span>
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
                        <b>Quotation No :</b></span>
                    <span class="infor_t2" id="vfqid"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Replaced Items :</b></span>
                    <span class="infor_t2" id="vfqitem"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Amount :</b></span>
                    <span class="infor_t2" id="vfqamu"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Quotation Image :</b></span>
                    <!-- <span class="infor_t2" id="vfqimg"></span> -->
                    <img id="vfqimg" alt="Image Preview" class="infor_t2" />
                </div>

                <br><br><br>

                <!------------------Confirmation--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Officer Confirmation</h1>
                    <p class="page_heding4">Confirmation</p>
                </div>
                <div class="pro1_wapper">
                    <div class="pro1" align="center">
                        <span class="infor_t2">
                            <b>System Admin Recommendation :</b></span>
                        <span class="infor_t2" id="vsysrec"></span>
                    </div>
                    <div class="popup_container">
                        <div class="pro1_warp">
                            <div class="pro1" align="right">
                                <span class="infor_t2"><b>Recommendation :</b></span>
                            </div>
                            <div class="pro1">
                                <input type="radio" name="dgmrec" value="1">
                                <label class='form_radio'>Recommendation</label>
                                &nbsp;&nbsp;<input type="radio" name="dgmrec" value="0">
                                <label class='form_radio'>Not Recommendation</label>
                            </div>
                            <div class="pro1">
                                <button class="edit_btn" onclick="senddgmrec();">
                                    <i class="fas fa-plus-square"></i>
                                </button>
                                <span id="dgmrec-info" class="error"></span>
                            </div>
                        </div>
                    </div>
                    <!--------------------------------------->
                    <div class="pro1_warp">
                        <div class="pro1" align="right">
                            <span class="infor_t2"><b>Recommendation Status :</b></span>
                        </div>
                        <div class="pro1">
                            <textarea placeholder="Write Recommendation Status.." class="formtxtare" name="statusdgm" id="statusdgm"></textarea>
                        </div>
                        <div class="pro1">
                        </div>
                    </div>
                </div>
                <!--------------------------------------->
                <div class="popup_footer">
                    <span id="status_message_dgm"></span>
                </div>
            </div>
        </div>
    </div>

</div>
<!-------------------finance(DGM - finance) (JS)----------------------->
<script type="text/javascript">
    //----------------------dgm rec-----------------------------
    function senddgmrec() {
        var valid;
        valid = validatesdgmrec();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/qut_dgm_recommend.php",
                data: 'finrec=' + $('input[name="dgmrec"]:checked').val() +
                    '&statusdgm=' + $('#statusdgm').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_dgm").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatesdgmrec() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='dgmrec']:checked").length == 0) {
            $("#dgmrec-info").html("<i class='fa fa-times'></i>Recommendation is Required");
            valid = false;
        } else {
            $("#dgmrec-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".finance_btn").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var qid = $('#' + id).children('td[data-target=qid]').text();
            var qitem = $('#' + id).children('td[data-target=qitem]').text();
            var qamount = $('#' + id).children('td[data-target=qamount]').text();
            var qimg = $('#' + id).children('td[data-target=qimg]').text();
            var qcom = $('#' + id).children('td[data-target=qcom]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#vfcid').text(comid);
            $('#eid').val(comid);
            $('#vfqid').text(qid);
            $('#fpag').text(pag);
            $('#vfqitem').text(qitem);
            $('#vfqamu').text(qamount);
            $('#vfqimg').attr('src', qimg);
            $('#vsysrec').text(qcom);
            document.getElementById('popup_finance_btn').style.display = 'block';
        })

    });
</script>

<!--------------------------popup3 window (Verify btn - GM )----------------------->
<div id="popup_approve_btn" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_edit();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------Complain-------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Quotation Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="vacid"></span>
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
                        <b>Quotation No :</b></span>
                    <span class="infor_t2" id="vaqid"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Replaced Items :</b></span>
                    <span class="infor_t2" id="vaqitem"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Amount :</b></span>
                    <span class="infor_t2" id="vaqamu"></span>
                </div>

                <br><br><br>

                <!------------------Confirmation--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Officer Confirmation</h1>
                    <p class="page_heding4">Confirmation</p>
                </div>
                <div class="pro1_wapper">
                    <div class="pro1" align="center">
                        <span class="infor_t2">
                            <b>System Admin Recommendation :</b></span>
                        <span class="infor_t2" id="vasysrec"></span>
                    </div>
                    <div class="pro1" align="center">
                        <span class="infor_t2">
                            <b>DGM Finance Officer Recommendation :</b></span>
                        <span class="infor_t2" id="vadgmrec"></span>
                    </div>
                    <div class="popup_container">
                        <div class="pro1_warp">
                            <div class="pro1" align="right">
                                <span class="infor_t2"><b>Approval :</b></span>
                            </div>
                            <div class="pro1">
                                <input type="radio" name="gmrec" value="1">
                                <label class='form_radio'>Approved</label>
                                &nbsp;&nbsp;<input type="radio" name="gmrec" value="0">
                                <label class='form_radio'>Not Approved</label>
                            </div>
                            <div class="pro1">
                                <button class="edit_btn" onclick="sendgmrec();">
                                    <i class="fas fa-plus-square"></i>
                                </button>
                                <span id="gmrec-info" class="error"></span>
                            </div>
                        </div>
                    </div>
                    <!--------------------------------------->
                    <div class="pro1_warp">
                        <div class="pro1" align="right">
                            <span class="infor_t2"><b>Approval Status :</b></span>
                        </div>
                        <div class="pro1">
                            <textarea placeholder="Write Approval Status.." class="formtxtare" name="statusgm" id="statusgm"></textarea>
                        </div>
                        <div class="pro1">
                        </div>
                    </div>
                </div>
                <!--------------------------------------->
                <div class="popup_footer">
                    <span id="status_message_gm"></span>
                </div>
            </div>
        </div>
    </div>

</div>
<!-------------------finance(GM) (JS)----------------------->
<script type="text/javascript">
    //----------------------gm rec-----------------------------
    function sendgmrec() {
        var valid;
        valid = validatesgmrec();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/qut_gm_recommend.php",
                data: 'aprv=' + $('input[name="gmrec"]:checked').val() +
                    '&statusgm=' + $('#statusgm').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_gm").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatesgmrec() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='gmrec']:checked").length == 0) {
            $("#gmrec-info").html("<i class='fa fa-times'></i>Recommendation is Required");
            valid = false;
        } else {
            $("#gmrec-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".approve_btn").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var qid = $('#' + id).children('td[data-target=qid]').text();
            var qitem = $('#' + id).children('td[data-target=qitem]').text();
            var qamount = $('#' + id).children('td[data-target=qamount]').text();
            var qcom = $('#' + id).children('td[data-target=qcom]').text();
            var finrec = $('#' + id).children('td[data-target=finrec]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#vacid').text(comid);
            $('#eid').val(comid);
            $('#vaqid').text(qid);
            $('#fpag').text(pag);
            $('#vaqitem').text(qitem);
            $('#vaqamu').text(qamount);
            $('#vasysrec').text(qcom);
            $('#vadgmrec').text(finrec);
            document.getElementById('popup_approve_btn').style.display = 'block';
        })

    });
</script>

<!-------------------popup View----------------------->
<div id="popup_view" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_edit();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Quotation Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="vcid"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Quotation No :</b></span>
                    <span class="infor_t2" id="vqid"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Replace Items :</b></span>
                    <span class="infor_t2" id="vqitem"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Amount :</b></span>
                    <span class="infor_t2" id="vqamount"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Quotation Image :</b></span><br><br>
                    <img id="vimage" src="" alt="Quotation Image" style="max-width: 50%; height: auto;">
                </div>
            </div>
        </div>
        <br><br><br>

        <!------------------Confirmation--------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Officer Confirmation</h1>
            <p class="page_heding4">Confirmation</p>
        </div>
        <div class="pro1_wapper">
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>System Administrator Confirmation :</b></span>
                <span class="infor_t2" id="vqsyscom"></span>
            </div>
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>DGM Finance Officer Confirmation :</b></span>
                <span class="infor_t2" id="vqfinrec"></span>
            </div>
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>General Manager Confirmation :</b></span>
                <span class="infor_t2" id="vqgmrec"></span>
            </div>
        </div>
        <div class="popup_footer">
        </div>
    </div>
</div>
<!----------------------javascript--------------------------->
<!-------------------view----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".view").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var qid = $('#' + id).children('td[data-target=qid]').text();
            var qitem = $('#' + id).children('td[data-target=qitem]').text();
            var qamount = $('#' + id).children('td[data-target=qamount]').text();
            var qcom = $('#' + id).children('td[data-target=qcom]').text();
            var finrec = $('#' + id).children('td[data-target=finrec]').text();
            var aprv = $('#' + id).children('td[data-target=aprv]').text();
            var quoimg = $('#' + id).children('td[data-target=qimg]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#vcid').text(comid);
            $('#vqid').text(qid);
            $('#vpag').text(pag);
            $('#vqitem').text(qitem);
            $('#vqamount').text(qamount);
            $('#vqsyscom').text(qcom);
            $('#vqfinrec').text(finrec);
            $('#vqgmrec').text(aprv);
            $('#vimage').attr('src', '/quotation/' + quoimg);
            document.getElementById('popup_view').style.display = 'block';
        })

    });
</script>



<!-------------------cancel btn----------------------->
<script>
    function cancel_edit() {
        document.getElementById('popup_edit').style.display = 'none';
        location.reload();
    }

    function relaod() {
        window.open('index.php?page=manage_quotation', '_parent');
    }
</script>
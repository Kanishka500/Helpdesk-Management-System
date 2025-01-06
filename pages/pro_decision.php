<?php
// --------------------page configs-------------------------
$pag = "index.php?page=pro_decision";
$maintable = "quotation";
$maxvalue = 10;
?>
<div id="main_content">
    <!--------------------content here----------------------------->
    <div id="heding_section">
        <!--------------------heading_section----------------------------->
        <div class="page_title">
            <p>Quotation<span class="page_name"> > Procurement Decision</span></p>
        </div>
        <!--------------------heading_section----------------------------->
    </div>
    <div id="body_section">
        <div class="page_subtitle">
            <p class="title">Procurement Decision</p>
        </div>

        <div class="content" style="padding-right:10px; padding: left 10px;">
            <!---------------------pagination------------------------------>
            <?php
            include("includes/connect.php");

            $url = $pag;
            $statement = $maintable;
            $sql = "SELECT * FROM {$statement}";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

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
            $limit = ($pn - 1) * $max;
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
            <!---------------------pagination------------------------------>

            <table id="quotation_tbl">
                <thead>
                    <tr>
                        <th>Quotation No</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM {$statement} LIMIT {$limit},{$max}";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $result = $stmt->fetchAll();
                    if (!empty($result)) {
                        foreach ($result as $row) {
                    ?>

                            <tr role="row" id="<?php echo $row['complain_id']; ?>">

                                <td role="cell" data-target="comid" data-label="complain_id" style='display:none'><?php echo $row['complain_id'] ?></td>
                                <!-------------------hiding-------------------->
                                <td role="cell" data-target="qutno" data-label="quotation_no"><?php echo $row['quotation_no'] ?></td>
                                <td role="cell" data-target="replaceitems" data-label="replace_items" style='display:none'><?php echo $row['replace_items'] ?></td>
                                <td role="cell" data-target="qutamu" data-label="quotation_amount" style='display:none'><?php echo $row['quotation_amount'] ?></td>
                                <td role="cell" data-target="dec" data-label="decision" style='display:none'><?php echo $row['decision'] ?></td>
                                <td role="cell" data-target="dec_con" data-label="decision_confirm" style='display:none'><?php echo $row['decision_confirm'] ?></td>
                                <td role="cell" data-target="admin_aprv" data-label="admin_approval" style='display:none'><?php echo $row['admin_approval'] ?></td>
                                <td role="cell" data-target="pag" style='display:none'><?php echo $pn ?></td>
                                <td data-label="Option" width="30%">
                                    <!---------------------view------------------------->
                                    <a href="#" data-role="upload" data-id="<?php echo $row['complain_id']; ?>" class="view"><i class="fa fa-cog"></i>View</a>

                                    <!-------------------Proceed-------------------->
                                    <?php
                                    if ($role == 9) {
                                        if ($row['gm_approval'] == 0) {
                                            echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until GM recommend the quotation, the button is disabled.</span>';
                                        } elseif (trim(strtolower($row['decision'])) != "still not add procurement decision") {
                                            echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Verified</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                        } else {
                                            echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" class="proceed"><i class="fa fa-list-alt"></i>Proceed</a>';
                                        }
                                    } elseif (trim(strtolower($row['decision'])) == "still not add procurement decision") {
                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Verified</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                    } else {
                                        echo '<a href="#" class="proceed" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-list-alt"></i>Proceed</a>';
                                    }
                                    ?>
                                    <!-------------------Verified-------------------->
                                    <?php
                                    if ($role == 8) {
                                        if (trim(strtolower($row['decision'])) == "still not add procurement decision") {
                                            echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until PM add procument decision the button is disabled.</span>';
                                        } elseif ($row['admin_approval'] == 1) {
                                            echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Approved</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                        } else {
                                            echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" class="verify"><i class="fa fa-list-alt"></i>Verify</a>';
                                        }
                                    } elseif ($row['admin_approval'] == 1) {
                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Approved</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                    } else {
                                        echo '<a href="#" class="verify" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-list-alt"></i>Verify</a>';
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
        </div>
    </div>
</div>

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
            </div>
        </div>
        <br><br><br>

        <!------------------Confirmation--------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Procurement Decision</h1>
        </div>
        <div class="pro1_wapper">
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>Procurement Committee Decision :</b></span>
                <span class="infor_t2" id="vprodes"></span>
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
            var qid = $('#' + id).children('td[data-target=qutno]').text();
            var qitem = $('#' + id).children('td[data-target=replaceitems]').text();
            var qamount = $('#' + id).children('td[data-target=qutamu]').text();
            var dec = $('#' + id).children('td[data-target=dec]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#vcid').text(comid);
            $('#vqid').text(qid);
            $('#vpag').text(pag);
            $('#vqitem').text(qitem);
            $('#vqamount').text(qamount);
            $('#vprodes').text(dec);
            document.getElementById('popup_view').style.display = 'block';
        })

    });
</script>

<!-----------------proceed btn------------------>
<div id="popup_proceed" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_edit();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Procument Decision</h1>
            <p class="page_heding4">Information about Quotation Id :
                <span class="infor_t4" id="viewqid"></span>
            </p>
            <!-----------important data------------->
            <input type="hidden" id="eid" name="setid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1_warp">
                    <div class="pro1" align="center">
                        <span class="infor_t2">
                            <b>Procument Committee Decision :</b></span>
                    </div>
                    <div class="pro1">
                        <textarea placeholder="Write Recommendation Status.." class="formtxtare" name="prodec" id="prodec"></textarea>
                    </div>
                    <div class="pro1">
                    </div>
                </div>
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>Recommendation Status :</b></span>
                    </div>
                    <div class="pro1">
                        <textarea placeholder="Write Recommendation Status.." class="formtxtare" name="status_manager" id="status_manager"></textarea>
                    </div>
                    <div class="pro1">
                        <button class="edit_btn" onclick="sendprorecom();">
                            <i class="fas fa-plus-square"></i>
                        </button>
                        <span id="prorec-info" class="error"></span>
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
                        <span id="prorec-info" class="error"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup_footer">
            <span id="status_message"></span>
        </div>
    </div>
</div>

<!-------------------edit----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".proceed").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var qid = $('#' + id).children('td[data-target=qutno]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#eid').val(comid);
            $('#viewqid').text(qid);
            $('#ppag').text(pag);
            document.getElementById('popup_proceed').style.display = 'block';
        })

    });
</script>
<script type="text/javascript">
    //----------------------prorecom-----------------------------
    function sendprorecom() {
        var valid;
        valid = validatesprorecom();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/add_prodecision.php",
                data: 'dec_con=' + $('input[name="check"]:checked').val() +
                    '&prodec=' + $('#prodec').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val() +
                    '&status_manager=' + $('#status_manager').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatesprorecom() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='check']:checked").length == 0) {
            $("#prorec-info").html("<i class='fa fa-times'></i>Acceptance is Required");
            valid = false;
        } else {
            $("#prorec-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<!-------------------edit----------------------->

<!-----------------verify btn------------------>
<div id="popup_verify" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_edit();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Procument Decision</h1>
            <p class="page_heding4">Information about Quotation Id :
                <span class="infor_t4" id="viewqno"></span>
            </p>
            <!-----------important data------------->
            <input type="hidden" id="eid" name="setid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1_wapper">
                    <div class="pro1" align="center">
                        <span class="infor_t2">
                            <b>Procurement Committee Decision :</b></span>
                        <span class="infor_t2" id="viewprodec"></span>
                    </div>
                </div>
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>Recommendation Status :</b></span>
                    </div>
                    <div class="pro1">
                        <textarea placeholder="Write Recommendation Status.." class="formtxtare" name="status_admin" id="status_admin"></textarea>
                    </div>
                    <div class="pro1">
                        <button class="edit_btn" onclick="sendprorec();">
                            <i class="fas fa-plus-square"></i>
                        </button>
                        <span id="proreco-info" class="error"></span>
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
                        <span id="proreco-info" class="error"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup_footer">
            <span id="status_message_admin"></span>
        </div>
    </div>
</div>

<!-------------------edit----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".verify").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var qid = $('#' + id).children('td[data-target=qutno]').text();
            var dec = $('#' + id).children('td[data-target=dec]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#eid').val(comid);
            $('#viewqno').text(qid);
            $('#viewprodec').text(dec);
            $('#ppag').text(pag);
            document.getElementById('popup_verify').style.display = 'block';
        })

    });
</script>
<script type="text/javascript">
    //----------------------prorecomAdmin-----------------------------
    function sendprorec() {
        var valid;
        valid = validatesprorec();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/add_pro_recommendation.php",
                data: 'admin_aprv=' + $('input[name="check"]:checked').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val() +
                    '&status_admin=' + $('#status_admin').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_admin").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatesprorec() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='check']:checked").length == 0) {
            $("#proreo-info").html("<i class='fa fa-times'></i>Acceptance is Required");
            valid = false;
        } else {
            $("#proreco-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<!-------------------edit----------------------->

<!-------------------cancel btn----------------------->
<script>
    function cancel_edit() {
        document.getElementById('popup_view').style.display = 'none';
        location.reload();
    }

    function relaod() {
        window.open('index.php?page=manage_quotation', '_parent');
    }
</script>
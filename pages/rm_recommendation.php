<?php
// --------------------page configs-------------------------
$pag = "index.php?page=rm_recommendation";
$maintable = "complain";
$maxvalue = 10;
$btnrecon = array(1);
?>
<div id="main_content">
    <!--------------------content here----------------------------->
    <div id="heding_section">
        <!--------------------heading_section----------------------------->
        <div class="page_title">
            <p>RM Recommendation</p>
        </div>
        <!--------------------heading_section----------------------------->
    </div>

    <div id="body_section">

        <div class="content" style="padding-right:10px; padding: left 10px;">
            <div class="form-group row">
                <div class="well well-sm">

                    <form action="#" method="POST" class="col">
                        <div class="col-1">
                            <label class="input-md" style="padding-right: 1px;">
                                <b>Date</b>
                            </label>
                            <div>
                                <section>
                                    <label class="input">
                                        <input type="date" id="date" name="date_val" required name="date">
                                    </label>
                                </section>
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
            <br>
            <!---------------------pagination------------------------------>
            <?php
            include("includes/connect.php");
            if (!empty($date_val) && !empty($office_val)) {
                $url = $pag . '&d=' . $date_val . '&c=' . $office_val;
                $statement = "{$maintable} WHERE date LIKE :date AND office_id LIKE :office ORDER BY complain_id ASC";
                $sql = "SELECT * FROM {$statement}";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['date' => '%' . $date_val . '%', 'office' => '%' . $office_val . '%']);
            } else {
                $url = $pag;
                $statement = $maintable;
                $sql = "SELECT * FROM {$statement}";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
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

            <!---------------------------table-------------------------->
            <div class="table">
                <table id="job">
                    <thead>
                        <tr>
                            <th>Complain ID</th>
                            <th>Date</th>
                            <th>Regional Office</th>
                            <th>Asset No</th>
                            <th>Serial No</th>
                            <th>Observations</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM {$statement} LIMIT {$limite},{$max}";
                        $stmt = $pdo->prepare($sql);
                        if (!empty($date_val) && !empty($office_val)) {
                            $stmt->execute(['date' => '%' . $date_val . '%', 'office' => '%' . $office_val . '%']);
                        } else {
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
                                    <td role="cell" data-target="comasset" data-label="asset_id"><?php echo $row['asset_id'] ?></td>
                                    <td role="cell" data-target="comserial" data-label="serial_no"><?php echo $row['serial_no'] ?></td>
                                    <td role="cell" data-target="comobs" data-label="observations"><?php echo $row['observations'] ?></td>
                                    <!-------------------hidding-------------------->
                                    <td role="cell" data-target="comempid" style='display:none'><?php echo $row['emp_id'] ?></td>
                                    <td role="cell" data-target="comrec" style='display:none'><?php echo $row['recommendation'] ?></td>
                                    <td role="cell" data-target="comimg" style='display:none'><?php echo $row['image'] ?></td>
                                    <td role="cell" data-target="pag" style='display:none'><?php echo $pn ?></td>
                                    <td data-label="Option" width="30%">
                                        <!-------------------view-------------------->
                                        <a href="#" data-role="upload" data-id="<?php echo $row['complain_id']; ?>" class="view">
                                            <i class="fa fa-search"></i>View</a>
                                        <!-------------------edit-------------------->
                                        <a href="#" data-role="upload" data-id="<?php echo $row['complain_id']; ?>" class="position"><i class="fa fa-cog"></i>Track</a>
                                        <!-------------------delete------------------>
                                        <?php if ($row['recommendation'] == 0) { ?>
                                            <a href="#"
                                                data-role="upload"
                                                data-id="<?php echo $row['complain_id']; ?>"
                                                class="proceed"
                                                <?php if (!in_array($role, $btnrecon)) {
                                                    echo 'style="pointer-events: none; background-color: gray; border:none;"';
                                                } ?>>
                                                <i class="fa fa-cog"></i>Proceed
                                            </a>
                                        <?php } else {
                                            echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>';
                                            echo "Recommended";
                                            echo '</span>';
                                        } ?>
                                    </td>
                                </tr>
                    </tbody>
            <?php }
                        } else {
                            echo '<tbody><tr><td colspan="7">';
                            echo '<label class="message">There was Not Data</label>';
                            echo '</td>';
                            echo '</tr>';
                            echo '</tbody>';
                        }
            ?>
                </table>
            </div>

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
<div id="popup_view" class="modal">
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
    </div>
</div>
<!-------------------popup1----------------------->
<!-------------------popup2----------------------->
<div id="popup_proceed" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_edit();" class="cancelbtn">
                    <i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Recomendation Manager</h1>
            <p class="page_heding4">Complain Recomendation</p>
            <!-----------important data------------->
            <input type="hidden" id="eid" name="setid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Complain Date :</b></span>
                </div>
                <div class="pro1">
                    <span class="infor_t2" id="ecomdate"></span>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Asset No :</b></span>
                </div>
                <div class="pro1">
                    <span class="infor_t2" id="ecomasset"></span>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Serial Number:</b></span>
                </div>
                <div class="pro1">
                    <span class="infor_t2" id="ecomserial"></span>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Employee No :</b></span>
                </div>
                <div class="pro1">
                    <span class="infor_t2" id="ecomemp"></span>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Office :</b></span>
                </div>
                <div class="pro1">
                    <span class="infor_t2" id="ecomoffice"></span>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Observations :</b></span>
                </div>
                <div class="pro1">
                    <span class="infor_t2" id="ecomobs"></span>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Recomendation :</b></span>
                </div>
                <div class="pro1">
                    <input type="radio" name="ecomrec" value="1">
                    <label class='form_radio'>Recomendation</label>
                    &nbsp;&nbsp;<input type="radio" name="ecomrec" value="0">
                    <label class='form_radio'>Not Recomendation</label>
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendcomrec();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="ecomrec-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Recomendation Status :</b></span>
                </div>
                <div class="pro1">
                    <textarea placeholder="Write Recomendation Status.." class="formtxtare" name="status" id="status"></textarea>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
        </div>
        <div class="popup_footer">
            <span id="status_message"></span>
        </div>
    </div>

</div>
<!-------------------popup2----------------------->
<!-------------------popup3----------------------->
<div id="popup_position" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Complain Position</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="did"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center" id="status_box">


                </div>
            </div>
        </div>
        <div class="popup_footer">
        </div>
    </div>
</div>
<!-------------------popup3----------------------->

<!----------------------javascript--------------------------->
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
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#vid').text(comid);
            $('#vpag').text(pag);
            $('#vcomdate').text(comdate);
            $('#vcomasset').text(comasset);
            $('#vcomserial').text(comserial);
            $('#vcomemp').text(comemp);
            $('#vcomoffice').text(comoffice);
            $('#vcomobs').text(comobs);
            $('#vcomrec').text(comrec);
            document.getElementById('popup_view').style.display = 'block';
        })

    });
</script>
<!-------------------view----------------------->
<!-------------------edit----------------------->
<script type="text/javascript">
    //----------------------comrec-----------------------------
    function sendcomrec() {
        var valid;
        valid = validatescomrec();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/add_recommend.php",
                data: 'comrec=' + $('input[name="ecomrec"]:checked').val() +
                    '&status=' + $('#status').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatescomrec() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='ecomrec']:checked").length == 0) {
            $("#ecomrec-info").html("<i class='fa fa-times'></i>Recomendation is Required");
            valid = false;
        } else {
            $("#ecomrec-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".proceed").click(function() {
            $('#eid').val('');
            $('#epag').val('');
            $('#ecomrec').val('');
            $('#ecomasset').text('');
            $('#ecomdate').text('');
            $('#ecomserial').text('');
            $('#ecomemp').text('');
            $('#ecomoffice').text('');
            $('#ecomobs').text('');
            $("#ecomrec-info").text('');
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
            $('#eid').val(comid);
            $('#epag').val(pag);
            $('#ecomdate').text(comdate);
            $('#ecomasset').text(comasset);
            $('#ecomserial').text(comserial);
            $('#ecomemp').text(comemp);
            $('#ecomoffice').text(comoffice);
            $('#ecomobs').text(comobs);
            $('#ecomrec').val(comrec);
            document.getElementById('popup_proceed').style.display = 'block';
        })

    });
</script>
<!-------------------edit----------------------->
<!-------------------delete----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".position").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#did').text(comid);
            jQuery.ajax({
                url: "include_ajaxs/get_position.php",
                data: 'setid=' + comid,
                type: "POST",
                success: function(data) {
                    $("#status_box").html(data);
                },
                error: function() {}
            });
            document.getElementById('popup_position').style.display = 'block';
        })

    });
</script>
<!-------------------delete----------------------->
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
        window.open('index.php?page=rm_recommendation', '_parent');
    }
</script>
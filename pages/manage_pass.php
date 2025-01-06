<!-------------------container----------------------->
<div id="main_content">
            <!--------------------contant here----------------------------->
            <div id="heding_section">
                <!--------------------heding_section----------------------------->
                <h1 class="txt_t1">Manage System Staff Password</h1>
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">System Staff</a></li>
                    <li><a href="#">Staff Information</a></li>
                    <li>Manage Staff Password</li>
                </ul>
                <!--------------------heding_section----------------------------->
            </div>
            <div id="body_section">
                <!--------------------body_section----------------------------->
                <div class="div_section">
                    <h1 class="txt_t2">Manage Staff Password</h1>
                </div>
                <div class="div_section">
                    <div align="left" class="export_butons">
                        <a href="#">Copy</a>
                        <a href="#">Excel</a>
                        <a href="#">CSV</a>
                        <a href="#">PDF</a>
                        <a href="#">Print</a>
                    </div>
                    <div  id="search_div" >
                        <div class="txt_search"><span >Search By&nbsp;</span></div>
                        <div align="left">
                        <form action="#" method="post" >
                        <select name="search_type" class="search_inpout" required>
						    <option value="" selected>Search Type</option>
						    <option value="fn">First Name</option>
						    <option value="of">Office</option>
                            <option value="ui">User Id</option>
					    </select>
                        <input type="text" placeholder="Search Value" name="search_val" required class="search_inpout">
                        <button type="submit" name="search" class="search_btn">
                            <i class="fa fa-search"></i>
                        </button>
                        </form>
                        </div>
                        <div >
                        <button id="reload" onClick="relaod();" class="search_btn">
                            <i class="fas fa-undo"></i>
                        </button>
                        </div>
                    </div>
                </div>
                <div class="div_section" >
                    <div>
                    </div>
                    
                </div>
                <div class="div_section">
                    <!--------------------body_main----------------------------->
                    <!---------------------pagination------------------------------>
                    <?php
                    function area_office($area_office)
                    {
                        switch ($area_office) {
                            case "c1":
                                $area = "Colombo 01";
                                break;
                            case "c2":
                                $area = "Colombo 02";
                                break;
                            case "c3":
                                $area = "Colombo 03";
                                break;
                            case "c4":
                                $area = "Colombo 04";
                                break;
                            case "g1":
                                $area = "Gampaha 01";
                                break;
                            case "g2":
                                $area = "Gampaha 02";
                                break;
                            case "klt":
                                $area = "Kaluthara";
                                break;
                            case "ho":
                                $area = "Head Office";
                                break;
                        }
                        return $area;
                    }
                    include("includes/connect.php");
                    if (!empty($value) && !empty($table)) {
                        $url = 'index.php?page=manage_pass&st='.$search_type.'&sv='.$value;
                        $statement = "user_infor WHERE {$table} LIKE :value";
                        $sql = "SELECT * FROM {$statement}";
                        $stmt = $pdom->prepare($sql);
                        $stmt->execute(['value' => '%' . $value . '%']);
                    }else{
                        $url = 'index.php?page=manage_pass';
                        $statement = "user_infor";
                        $sql = "SELECT * FROM {$statement}";
                        $stmt = $pdom->prepare($sql);
                        $stmt->execute();
                    }
                    $rows = $stmt->rowCount();
                    if($rows==0){$max =0;$totalpages = 0;}else{$max = 5;$totalpages = ceil($rows / $max);}
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
                    <!-- -------------------geting data---------------------------- -->
                    <div class="table_contaner">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Face</th>
                                    <th>First Name</th>
                                    <th>User Number</th>
                                    <th>Office</th>
                                    <th>User Tell</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM {$statement} LIMIT {$limite},{$max}";
                                $stmt = $pdom->prepare($sql);
                                if (!empty($value) && !empty($table)) {
                                    $stmt->execute(['value' => '%' . $value . '%']);
                                }else{
                                    $stmt->execute();
                                }
                                $result = $stmt->fetchAll();
                                if (!empty($result)) {
                                    foreach ($result as $row) {
                                        //---------get user name/password----------------
                                        $setid = $row['user_id'];
                                        $sql = "SELECT login_name,login_password FROM user_login WHERE login_userid=:userid LIMIT 1";
                                        $stmt = $pdom->prepare($sql);
                                        $stmt->execute(['userid' => $setid]);
                                        foreach ($stmt->fetchall() as $r) {
                                            $name = $r['login_name'];
                                            $pass = $r['login_password'];
                                        }
                                ?>

                                <tr role="row" id="<?php echo $row['info_id']; ?>">
                                            <td role="cell" data-label="Face"><img src="images/profileface/<?php echo $row['user_image']; ?>" class="avatar1">
                                            </td>
                                            <td role="cell" data-target="image" style='display:none'><?php echo $row['user_image'] ?></td>
                                            <td role="cell" data-target="pag" style='display:none'><?php echo $pn ?></td>
                                            <td role="cell" data-target="fname" data-label="First Name"><?php echo $row['user_fname'] ?></td>
                                            <td role="cell" data-target="usno" data-label="User Id"><?php echo $row['user_id'] ?></td>
                                            <td role="cell" data-target="office" data-label="User Office"><?php echo area_office($row['user_office']) ;?></td>
                                            <td role="cell" data-target="tell" data-label="User Tell"><?php echo $row['user_tell'] ?></td>
                                            <td role="cell" data-target="idtb" style='display:none'><?php echo $row['info_id']; ?></td>
                                            <td data-label="Option">
                                                <!-------------edit------------------>
                                                <a href="#" data-role="upload" data-id="<?php echo $row['info_id']; ?>" class="edit">
                                                    <i class="fa fa-cog"></i>Edit</a>
                                                <!-------------edit----------------->
                                            </td>
                                </tr>
                            </tbody>
                    <?php }
                                } else {

                                    echo '<tbody><tr><td colspan="6">';
                                    echo '<label class="message">There was Not Data</label>';
                                    echo '</td>';
                                    echo '</tr>';
                                    echo '</tbody>';
                                }
                    ?>



                        </table>

                    </div>
                    <!-- -------------------geting data---------------------------- -->
                </div>
                <div class="div_section">
                    <p class="txt_t3"><?php echo $textline1; ?></p>
                    <p class="txt_t3"><?php echo $textline2; ?></p>
                </div>
                <div class="div_section" id="pagination" align="right">
                    <?php if (!empty($result)) {
                        echo $paginationCtrls;
                    } ?>
                </div>
            </div>

            <!--------------------body_main----------------------------->

            <!--------------------body_section----------------------------->
            <div id="footer_section">
                <!--------------------footer_section----------------------------->
                <h1 class="txt_t4" align="right">
                    <?php
                    $pdo = null;
                    echo $footer_pag1;
                    ?>
                </h1>
                <!--------------------footer_section----------------------------->
            </div>
            <!---------------------contant here------------------------------>
        </div>
        <!-------------------container----------------------->
    </div>
</div>
<!-------------------popup1----------------------->
<div id="popup_edit" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_edit();" class="cancelbtn">
                    <i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Edit User Passwords/User Name</h1>
            <p class="page_heding4">Edit Passwords/Name Here</p>
        </div>
        <div class="popup_container">
            <!--------------------------------------->
            <div class="proimage_warp">
                <div class="proimage" align="center">
                    <img class="profileMain" id="eimage">
                    <input type="hidden" id="eid" name="setid">
                    <input type="hidden" id="eusno" name="eusno">
                    <input type="hidden" name="pag" id="epag">
                    <input type="hidden" name="userid" id="userid" value="<?php echo $userid ;?>">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>First Name :</b></span>
                </div>
                <div class="pro1">
                    <span id="efname" class="infor_t2"></span>
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
                    <span id="eoffice" class="infor_t2"></span>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>User Name :</b></span>
                </div>
                <div class="pro1">
                <input type="text" class="form_input" id="euname" name="euname">
                </div>
                <div class="pro1">
                <button class="edit_btn" onclick="senduname();">
                        <i class="fa fa-cog"></i></button>
                <span id="euname-info" class="error"></span>
                </div>
            </div>
            <!-----------------Password---------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>New Password :</b></span>
                </div>
                <div class="pro1">
                    <input type="text" class="form_input" id="epass">
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendpassword();">
                        <i class="fa fa-cog"></i></button>
                    <span id="epass-info" class="error"></span>
                </div>
            </div>
            <!-------------------Password Confirm-------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Password Confirm :</b></span>
                </div>
                <div class="pro1">
                    <input type="text" class="form_input" id="erepass">
                </div>
                <div class="pro1">
                    <span id="erepass-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Tell Nimber :</b></span>
                </div>
                <div class="pro1">
                    <span id="etell" class="infor_t2"></span>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
        </div>
        <div class="popup_footer">
            <?php 
            echo'<span id="status_mess"></span>';
            ?>
        </div>
    </div>

</div>

<!-------------------popup1----------------------->
<!-------------------content----------------------->
<!-------------------javascript----------------------->
<script type="text/javascript">
    //--------------------------username------------------------------
    function senduname() {
        var valid;
        valid = validateuname();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_passnam.php",
                data: 'uname=' + $('#euname').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eusno').val(),
                type: "POST",
                success: function(data) {
                    $("#status_mess").html(data);
                },
                error: function() {}
            });
        }
    }
    //------------------------------validate---------------------
    function validateuname() {
        var valid = true;
        $(".error").html('');
        //--------------------------User Name-----------------------------
        if (!$("#euname").val()) {
            $("#euname-info").html("<i class='fa fa-times'></i>User Name is Required");
            //		$("#username").css('background-color','#FFFFDF');
            valid = false;
        } else if (/[^a-zA-Z]/.test($("#euname").val())) {
            $("#euname-info").html("<i class='fa fa-times'></i>User Name Only Letters Allowed No Space");
            valid = false;
        } else if ($("#euname").val().length > 10) {
            $("#euname-info").html("<i class='fa fa-times'></i>User Name Only Below 10 Characters");
            valid = false;
        } else if ($("#euname").val().length < 3) {
            $("#euname-info").html("<i class='fa fa-times'></i>User Name Only More Then 3 Characters");
            valid = false;
        } else {
            $("#euname-info").html('<i class="fas fa-check"></i>');
        }
        //------------------------------------------------------------
        return valid;
    }
    //------------------------username-----------------------------
    //--------------------------password------------------------------
    function sendpassword() {
        var valid;
        valid = validatepassword();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_passnam.php",
                data: 'pass=' + $('#epass').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eusno').val(),
                type: "POST",
                success: function(data) {
                    $("#status_mess").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatepassword() {
        var valid = true;
        $(".error").html('');
        //--------------------------UserPassword-----------------------------
        if (!$("#epass").val()) {
            $("#epass-info").html("<i class='fa fa-times'></i>User Password is Required");
            //		$("#password").css('background-color','#FFFFDF');
            valid = false;
        } else {
            $("#epass-info").html('<i class="fas fa-check"></i>');
        }
        //--------------------------rePassword------------------------
        if (!$("#erepass").val()) {
            $("#erepass-info").html("<i class='fa fa-times'></i>User Confirm Password is Required");
            //		$("#repassword").css('background-color','#FFFFDF');
            valid = false;
        } else if ($("#erepass").val() != $("#epass").val()) {
            $("#erepass-info").html("<i class='fa fa-times'></i>Confirm Password Not Match");
            valid = false;
        } else {
            $("#erepass-info").html('<i class="fas fa-check"></i>');
        }
        //------------------------------------------------------------
        return valid;
    }
    //------------------------repassword-----------------------------
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".edit").click(function() {
            var id = $(this).data('id');
            var id = id;
            var pag = $('#' + id).children('td[data-target=pag]').text();
            var usno = $('#' + id).children('td[data-target=usno]').text();
            var image = $('#' + id).children('td[data-target=image]').text();
            var fname = $('#' + id).children('td[data-target=fname]').text();
            var tell = $('#' + id).children('td[data-target=tell]').text();
            var office = $('#' + id).children('td[data-target=office]').text();
            var idtb = $('#' + id).children('td[data-target=idtb]').text();
            $('#eimage').attr('src', 'images/profileface/' + image);
            $('#eusno').val(usno);
            $('#eid').val(idtb);
            $('#efname').text(fname);
            $('#etell').text(tell);
            $('#eoffice').text(office);
            document.getElementById('popup_edit').style.display = 'block';
        })

    });
</script>
<script>
    function cancel_edit() {
        document.getElementById('popup_edit').style.display = 'none';
        location.reload();
    }
    function relaod() {
        window.open('index.php?page=manage_pass', '_parent');
    }
</script>
<!-------------------cancel btns----------------------->
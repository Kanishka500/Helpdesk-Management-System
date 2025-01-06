<!-------------------container----------------------->
<div id="main_content">
            <!--------------------contant here----------------------------->
            <div id="heding_section">
                <!--------------------heding_section----------------------------->
                <h1 class="txt_t1">Manage System Staff Information</h1>
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">System Staff</a></li>
                    <li><a href="#">Staff Information</a></li>
                    <li>Manage Staff Information</li>
                </ul>
                <!--------------------heding_section----------------------------->
            </div>
            <div id="body_section">
                <!--------------------body_section----------------------------->
                <div class="div_section">
                    <h1 class="txt_t2">Manage Staff Information</h1>
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
						    <option value="ln">Last Name</option>
						    <option value="of">Office</option>
						    <option value="ro">User Role</option>
                            <option value="ac">Actived</option>
                            <option value="ad">Activedate</option>
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
                <div id="div_section" >
                    <div>
                        <?php 
                        if (!empty($mess)) {
                            echo $mess;
                        } 
                        ?>
                    </div>
                    <div align="right">
                        <a href="index.php?page=add_staff" class="add_btn" target="_parent">
                        <i class="fas fa-plus-square"></i>&nbsp;Add User
                        </a>
                    </div>
                </div>
                <div class="div_section">
                    <!--------------------body_main----------------------------->
                    <!---------------------pagination------------------------------>
                    <?php
                    function area_office($area_office)
                    {
                        switch ($area_office) {
                            case "C1":
                                $area = "Colombo 01";
                                break;
                            case "C2":
                                $area = "Colombo 02";
                                break;
                            case "C3":
                                $area = "Colombo 03";
                                break;
                            case "C4":
                                $area = "Colombo 04";
                                break;
                            case "G1":
                                $area = "Gampaha 01";
                                break;
                            case "G2":
                                $area = "Gampaha 02";
                                break;
                            case "KLT":
                                $area = "Kaluthara";
                                break;
                            case "H":
                                $area = "Head Office";
                                break;
                        }
                        return $area;
                    }
                    include("includes/connect.php");
                    if (!empty($value) && !empty($table)) {
                        $url = 'index.php?page=manage_staff&st='.$search_type.'&sv='.$value;
                        $statement = "user_infor WHERE {$table} LIKE :value";
                        $sql = "SELECT * FROM {$statement}";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['value' => '%' . $value . '%']);
                    }else{
                        $url = 'index.php?page=manage_staff';
                        $statement = "user_infor";
                        $sql = "SELECT * FROM {$statement}";
                        $stmt = $pdo->prepare($sql);
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
                                    <th>Last Name</th>
                                    <th>Office</th>
                                    <th>User Tell</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM {$statement} LIMIT {$limite},{$max}";
                                $stmt = $pdo->prepare($sql);
                                if (!empty($value) && !empty($table)) {
                                    $stmt->execute(['value' => '%' . $value . '%']);
                                }else{
                                    $stmt->execute();
                                }
                                $result = $stmt->fetchAll();
                                if (!empty($result)) {
                                    foreach ($result as $row) {
                                ?>

                                        <tr role="row" id="<?php echo $row['info_id']; ?>">
                                            <td role="cell" data-target="user_id" style='display:none'><?php echo $row['user_id'] ?></td>
                                            <td role="cell" data-label="Face"><img src="images/profileface/<?php echo $row['user_image']; ?>" class="avatar1">
                                            </td>
                                            <td role="cell" data-target="image" style='display:none'><?php echo $row['user_image'] ?></td>
                                            <td role="cell" data-target="pag" style='display:none'><?php echo $pn ?></td>
                                            <td role="cell" data-target="fname" data-label="First Name"><?php echo $row['user_fname'] ?></td>
                                            <td role="cell" data-target="lname" data-label="Last Name"><?php echo $row['user_lname'] ?></td>
                                            <td role="cell" data-target="address" style='display:none'><?php echo $row['user_address'] ?></td>
                                            <td role="cell" data-target="bday" style='display:none'><?php echo $row['user_bday'] ?></td>
                                            <td role="cell" data-target="email" style='display:none'><?php echo $row['user_email'] ?></td>
                                            <td role="cell" data-target="office" data-label="User Office"><?php echo area_office($row['user_office']) ;?></td>
                                            <td role="cell" data-target="tell" data-label="User Tell"><?php echo $row['user_tell'] ?></td>
                                            <td role="cell" data-target="role" style='display:none'><?php echo $row['user_role'] ?></td>
                                            <td role="cell" data-target="branch" style='display:none'><?php echo $row['branch'] ?></td>
                                            <td role="cell" data-target="usid" style='display:none'><?php echo $row['info_id'] ?></td>
                                            <td role="cell" data-target="actdate" style='display:none'><?php echo $row['user_actdate'] ?></td>
                                            <td role="cell" data-target="pag" style='display:none'><?php echo $pn ?></td>
                                            <td data-label="Option">
                                                <!-------------------view-------------------->
                                                <a href="#" data-role="upload" data-id="<?php echo $row['info_id']; ?>" class="view">
                                                <i class="fa fa-search"></i>View</a>
                                                <!-------------------edit-------------------->
                                                <a href="#" data-role="upload" data-id="<?php echo $row['info_id']; ?>" class="edit">
                                                    <i class="fa fa-cog"></i>Edit</a>
                                                <!-------------------delete------------------>
                                                <a href="#" data-role="upload" data-id="<?php echo $row['info_id']; ?>" class="delete">
                                                    <i class="far fa-trash-alt"></i>Delet</a>
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
            <h1 class="page_heding2">Edit User Profile Information</h1>
            <p class="page_heding4">Edit Important Information about User</p>
            <input type="hidden" id="eid" name="usid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid ;?>">
        </div>
        <div class="popup_container">
            <!--------------------------------------->
            <div class="proimage_warp">
                <div class="proimage" align="right">
                    <img class="profileMain" id="useredit_image">
                </div>
                <div class="proimage">
                    <table class="btn_table">
                        <tr>
                            <td>
                            <a id="idedit_image" target="_parent" class="uploadimage"><i class="fas fa-upload"></i>Upload</a>
                            <input type="text" id="useredit_id" style="display: none;">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>First Name :</b></span>
                </div>
                <div class="pro1">
                    <input type="text" id="efname" class="form_input">
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendfname();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="efname-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Last Name :</b></span>
                </div>
                <div class="pro1">
                    <input type="text" id="elname" class="form_input">
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendlname();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="elname-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Address :</b></span>
                </div>
                <div class="pro1">
                    <input type="text" id="eaddress" class="form_input">
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendaddress();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="eaddress-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Birith Day :</b></span>
                </div>
                <div class="pro1">
                    <input type="date" id="ebday" class="form_input">
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendbday();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="ebday-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Email :</b></span>
                </div>
                <div class="pro1">
                    <input type="text" id="eemail" class="form_input">
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendemail();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="eemail-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Tell Nimber :</b></span>
                </div>
                <div class="pro1">
                    <input type="text" id="etell" class="form_input">
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendtell();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="etell-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Working Office :</b></span>
                </div>
                <div class="pro1">
                    <select   id="eoffice"  class="form_input">
                    <option >Select User Office</option>
                    <option value="ho">Head Office</option>
                    <option value="c1">Colombo 01</option>
                    <option value="c2">Colombo 02</option>
                    <option value="c3">Colombo 03</option>
                    <option value="c4">Colombo 04</option>
                    <option value="g1">Gampaha 01</option>
                    <option value="g2">Gampaha 02</option>
                    <option value="klt">Kaluthara</option>
                    </select>
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendoffice();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="eoffice-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Branch :</b></span>
                </div>
                <div class="pro1" align="left">
                <select  name="erole"  id="erole"  class="form_input">
                    <option value="">Select Title</option>
                    <option value="6">General Manager</option>
                    <option value="8">DGM Admin</option>
                    <option value="5">DGM Finance</option>
                    <option value="1">Regional Manager</option>
                    <option value="3">System Administrator</option>
                    <option value="4">IT Officer</option>
                    <option value="2">Helpdesk Officer</option>
                    <option value="7">Employee</option>
                </select>
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendrole();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="erole-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
        </div>
        <div class="popup_footer">
            <span id="status_message"></span>
        </div>
    </div>

</div>
<!-------------------popup1----------------------->
<!-------------------popup2----------------------->
<div id="popup_delete" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_del();" class="cancelbtn">
                    <i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Delete User Profile</h1>
            <p class="page_heding4">Dissolve User in This System</p>
        </div>
        <form action="" method="POST" onSubmit="return delete_user();">
            <div class="popup_container">
                <!--------------------------------------->
                <div class="proimage_warp">
                    <div class="proimage" align="center">
                        <img class="profileMain" id="dimage">
                        <input type="hidden" id="did"  name="setid">
                        <input type="hidden" id="dpag"  name="pag" >
                        <input type="hidden" id="dusno"  name="usno" >
                        <input type="hidden" name="userid"  value="<?php echo $userid ;?>">
                    </div>
                </div>
                <!--------------------------------------->
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>User First Name :</b></span>
                    </div>
                    <div class="pro1">
                        <span id="dfname" class="infor_t2"></span>
                    </div>
                </div>
                <!--------------------------------------->
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>User Last Name :</b></span>
                    </div>
                    <div class="pro1">
                        <span id="dlname" class="infor_t2"></span>
                    </div>
                </div>
                <!--------------------------------------->
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>User Office :</b></span>
                    </div>
                    <div class="pro1">
                        <span id="doffice" class="infor_t2"></span>
                    </div>
                </div>
                <!--------------------------------------->
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>User Role :</b></span>
                    </div>
                    <div class="pro1">
                        <span id="drole" class="infor_t2"></span>
                    </div>
                </div>
                <!--------------------------------------->
            </div>
            <div class="popup_footer">
                <button type="submit" class="del_user" onclick="delete_data();" >
                    <i class="far fa-trash-alt"></i>Delete This User</button>
                <span id="status_message"></span>
            </div>
        </form>
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
        <div class="popup_head">
        <h1 class="page_heding2">User Information</h1>
            <p class="page_heding4">Information about User Id :
            <span class="infor_t4" id="vuser"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="proimage" align="center">
                <img class="profileMain" id="vimage">
            </div>
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>User First Name :</b></span>
                    <span class="infor_t2" id="vfname"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>User Last Name :</b></span>
                    <span class="infor_t2" id="vlname"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>User Address :</b></span>
                    <span class="infor_t2" id="vaddress"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>User Birith Day :</b></span>
                    <span class="infor_t2" id="vbday"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>User Email :</b></span>
                    <span class="infor_t2" id="vemail"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>User Tell Nimber :</b></span>
                    <span class="infor_t2" id="vtell"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>User Office :</b></span>
                    <span class="infor_t2" id="voffice"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>User User Role :</b></span>
                    <span class="infor_t2" id="vrole"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>User Activedate :</b></span>
                    <span class="infor_t2" id="vactdate"></span>
                </div>
            </div>
        </div>

    </div>
</div>
<!-------------------popup3----------------------->
<!-------------------content----------------------->
<!-------------------javascript----------------------->
<!-------------------view----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".view").click(function() {
            var id = $(this).data('id');
            var id = id;
            var image = $('#' + id).children('td[data-target=image]').text();
            var fname = $('#' + id).children('td[data-target=fname]').text();
            var lname = $('#' + id).children('td[data-target=lname]').text();
            var address = $('#' + id).children('td[data-target=address]').text();
            var bday = $('#' + id).children('td[data-target=bday]').text();
            var email = $('#' + id).children('td[data-target=email]').text();
            var tell = $('#' + id).children('td[data-target=tell]').text();
            var office = $('#' + id).children('td[data-target=office]').text();
            var role = $('#' + id).children('td[data-target=role]').text();
            var actdate = $('#' + id).children('td[data-target=actdate]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            var usid = $('#' + id).children('td[data-target=usid]').text();
            var usno = $('#' + id).children('td[data-target=user_id]').text();
            $('#vid').text(usid);
            $('#vuser').text(usno);
            $('#vimage').attr('src', 'images/profileface/' + image);
            $('#vfname').text(fname);
            $('#vlname').text(lname);
            $('#vaddress').text(address);
            $('#vbday').text(bday);
            $('#vemail').text(email);
            $('#vtell').text(tell);
            $('#voffice').text(office);
            $('#vrole').text(role);    
            $('#vactdate').text(actdate);
            document.getElementById('popup_view').style.display = 'block';
        })

    });
</script>
<!-------------------edit----------------------->
<script type="text/javascript">
    //---------------------------fname------------------------------
    function sendfname() {
        var valid;
        valid = validatefname();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_user.php",
                data: 'fname=' + $('#efname').val() +
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
    function validatefname() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if (!$("#efname").val()) {
            $("#efname-info").html("<i class='fa fa-times'></i>First Name is Required");
            //		$("#lname").css('background-color','#FFFFDF');
            valid = false;
        } else if (!$("#efname").val().match(/^[a-zA-Z][a-zA-Z\s]*$/)) {
            $("#efname-info").html("<i class='fa fa-times'></i>First Name Only Letters and White Space Allowed");
            valid = false;
        } else {
            $("#efname-info").html('<i class="fas fa-check"></i>');
        }
        //------------------------------------------------------------
        return valid;
    }
    //--------------------------fname------------------------------
    //---------------------------lname------------------------------
    function sendlname() {
        var valid;
        valid = validatelname();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_user.php",
                data: 'lname=' + $('#elname').val() +
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
    function validatelname() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if (!$("#elname").val()) {
            $("#elname-info").html("<i class='fa fa-times'></i>Last Name is Required");
            //		$("#lname").css('background-color','#FFFFDF');
            valid = false;
        } else if (!$("#elname").val().match(/^[a-zA-Z][a-zA-Z\s]*$/)) {
            $("#elname-info").html("<i class='fa fa-times'></i>Last Name Only Letters and White Space Allowed");
            valid = false;
        } else {
            $("#elname-info").html('<i class="fas fa-check"></i>');
        }
        //------------------------------------------------------------
        return valid;
    }
    //--------------------------lname------------------------------
    //---------------------------address------------------------------
    function sendaddress() {
        var valid;
        valid = validateaddress();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_user.php",
                data: 'address=' + $('#eaddress').val() +
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
    function validateaddress() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if (!$("#eaddress").val()) {
            $("#eaddress-info").html("<i class='fa fa-times'></i>Address is Required");
            //		$("#lname").css('background-color','#FFFFDF');
            valid = false;
        } else if (!$("#eaddress").val().match(/^[a-zA-Z0-9,.!? ]*$/)) {
            $("#eaddress-info").html("<i class='fa fa-times'></i>Address Only Letters,Numbers and White Space Allowed");
            valid = false;
        } else {
            $("#eaddress-info").html('<i class="fas fa-check"></i>');
        }
        //------------------------------------------------------------
        return valid;
    }
    //--------------------------address------------------------------
    //---------------------------bday------------------------------
    function sendbday() {
        var valid;
        valid = validatebday();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_user.php",
                data: 'bday=' + $('#ebday').val() +
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
    function validatebday() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if (!$("#ebday").val()) {
            $("#ebday-info").html("<i class='fa fa-times'></i>Birth day is Required");
            //		$("#bday").css('background-color','#FFFFDF');
            valid = false;
        } else {
            $("#ebday-info").html('<i class="fas fa-check"></i>');
        }
        //------------------------------------------------------------
        return valid;
    }
    //--------------------------bday------------------------------
    //--------------------------email------------------------------
    function sendemail() {
        var valid;
        valid = validateemail();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_user.php",
                data: 'email=' + $('#eemail').val() +
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
    function validateemail() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if (!$("#eemail").val()) {
            $("#eemail-info").html("<i class='fa fa-times'></i>Email is required");
            //		$("#email").css('background-color','#FFFFDF');
            valid = false;
        } else if (!$("#eemail").val().match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
            $("#eemail-info").html("<i class='fa fa-times'></i>Invalid Email Format");
            //		$("#email").css('background-color','#FFFFDF');
            valid = false;

        } else {
            $("#eemail-info").html('<i class="fas fa-check"></i>');
        }

        //------------------------------------------------------------
        return valid;
    }
    //-------------------------email------------------------------
    //--------------------------tell------------------------------
    function sendtell() {
        var valid;
        valid = validatetell();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_user.php",
                data: 'tell=' + $('#etell').val() +
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
    function validatetell() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if (!$("#etell").val()) {
            $("#etell-info").html("<i class='fa fa-times'></i>Tell Number is Required");
            //		$("#tell").css('background-color','#FFFFDF');
            valid = false;
        } else if (!/^[0-9]+$/.test($("#etell").val())) {
            $("#etell-info").html("<i class='fa fa-times'></i>Invalid Tell Format There are Letters");
            valid = false;
        } else {
            $("#etell-info").html('<i class="fas fa-check"></i>');
        }
        //------------------------------------------------------------
        return valid;
    }
    //-------------------------tell------------------------------
    //--------------------------type------------------------------
    function sendoffice() {
        var valid;
        valid = validateoffice();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_user.php",
                data: 'office=' + $('#eoffice').val() +
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
    function validateoffice() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if (!$("#eoffice").val()) {
            $("#eoffice-info").html("<i class='fa fa-times'></i>User Office is Required");
            valid = false;
        } else {
            $("#eoffice-info").html('<i class="fas fa-check"></i>');
        }
        //------------------------------------------------------------
        return valid;
    }
    //------------------------active-----------------------------
    //--------------------------role------------------------------
    function sendrole() {
        var valid;
        valid = validaterole();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/edit_user.php",
                data: 'role=' + $('#erole').val() +
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
    function validaterole() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if (!$("#erole").val()) {
            $("#erole-info").html("<i class='fa fa-times'></i>User Role is Required");
            valid = false;
        } else {
            $("#erole-info").html('<i class="fas fa-check"></i>');
        }
        //------------------------------------------------------------
        return valid;
    }
    //------------------------role-----------------------------
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".edit").click(function() {
            $('#eid').val('');
            $('#efname').val('');
            $('#elname').val('');
            $('#eaddress').val('');
            $('#ebday').val('');
            $('#eemail').val('');
            $('#etell').val('');
            $('#eoffice').val('');
            $('#ebranch').val('');
            $('#pag').text('');
            $('#user_id').text('');
            $('#efname-info').text('');
            $('#elname-info').text('');
            $('#eaddress-info').text('');
            $('#ebday-info').text('');
            $('#eemail-info').text('');
            $('#etell-info').text('');
            $('#eoffice-info').text('');
            $('#erole-info').text('');
            var id = $(this).data('id');
            var id = id;
            var image = $('#' + id).children('td[data-target=image]').text();
            var fname = $('#' + id).children('td[data-target=fname]').text();
            var lname = $('#' + id).children('td[data-target=lname]').text();
            var address = $('#' + id).children('td[data-target=address]').text();
            var bday = $('#' + id).children('td[data-target=bday]').text();
            var email = $('#' + id).children('td[data-target=email]').text();
            var tell = $('#' + id).children('td[data-target=tell]').text();
            var office = $('#' + id).children('td[data-target=office]').text();
            var role = $('#' + id).children('td[data-target=role]').text();
            var actdate = $('#' + id).children('td[data-target=actdate]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            var usid = $('#' + id).children('td[data-target=usid]').text();
            var usno = $('#' + id).children('td[data-target=user_id]').text();
            
            $('#idedit_image').attr("href", 'index.php?page=edit_staffimage&usid=' + usno + '&pag=' + pag);
            $('#useredit_image').attr('src', 'images/profileface/' + image);
            $('#eid').val(usid);
            $('#efname').val(fname);
            $('#elname').val(lname);
            $('#eaddress').val(address);
            $('#ebday').val(bday);
            $('#eemail').val(email);
            $('#etell').val(tell);
            $('#eoffice').val(office);
            $('#epag').val(pag);
            $('#erole').val(role);
            document.getElementById('popup_edit').style.display = 'block';
        })

    });
</script>
<!-------------------delete----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".delete").click(function() {
            $('#dpag').text('');
            $('#did').text('');
            var id = $(this).data('id');
            var id = id;
            var image = $('#' + id).children('td[data-target=image]').text();
            var fname = $('#' + id).children('td[data-target=fname]').text();
            var lname = $('#' + id).children('td[data-target=lname]').text();
            var office = $('#' + id).children('td[data-target=office]').text();
            var role = $('#' + id).children('td[data-target=role]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            var usid = $('#' + id).children('td[data-target=usid]').text();
            var usno = $('#' + id).children('td[data-target=user_id]').text();
            $('#dimage').attr('src', 'images/profileface/' + image);
            $('#did').val(usid);
            $('#dusno').val(usno);
            $('#dpag').val(pag);
            $('#dfname').text(fname);
            $('#dlname').text(lname);
            $('#doffice').text(office);
            $('#drole').text(role);
            document.getElementById('popup_delete').style.display = 'block';
        })

    });
</script>
<script>
    function delete_user() {
    var valid = false;;
    if (confirm('Are you sure to remove this record ?')) {
    valid = true;
    }
    //------------------------------------------------------------
    return valid;
}
    
</script>
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
        document.getElementById('popup_delete').style.display = 'none';
        location.reload();
    }
    function relaod() {
        window.open('index.php?page=manage_staff', '_parent');
    }
</script>
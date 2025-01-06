
        <!-------------------container----------------------->
        <div id="main_content">
            <!--------------------contant here----------------------------->
            <div id="heding_section">
                <!--------------------heding_section----------------------------->
                <h1 class="txt_t1">Edit System Staff Profile Image</h1>
                <h2 class="font_txt">Edit Profile Image</h2>

                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Add System Staff</a></li>
                    <li><a href="#">Edit Profile Image</a></li>
                    <li>Profile Imag</li>
                </ul>
                <!--------------------heding_section----------------------------->
            </div>
            <div id="body_section">
                <!--------------------body_section----------------------------->
                <div class="div_section">
                    <h1 class="txt_t2">Edit System Staff Form</h1>
                    <h2 class="txt_t3">Please Fill the Form by Definite Instruction</h2>
                </div>
                <div class="div_section">
                    <!--------------------upload_table----------------------------->
                    <div class="upload_table">
                        <!--------------------tab menu----------------------------->
                        <div class="tab">
                            <button class="tablinks active" onclick="openCity(event, 'tab1')">New Image</button>
                            <button class="tablinks" onclick="openCity(event, 'tab2')">Defualt Image</button>
                            <div id="btn_space">
                            </div>
                        </div>
                        <!--------------------upload_new----------------------------->
                        <div id="tab1" class="tabcontent" style="display: block;">
                            <div class="upload_new">
                                <!--------------------add user------------------------>
                                <div class="container_adimg">
                                    <div id="colum1">
                                        <h1 class="txt_t2">Select and Cropped Image</h1>
                                    </div>
                                    <div id="colum2">
                                        <h1 class="form_txt">Upload Image and Cropped Image</h1><br>
                                        <div id="form">
                                            <div id="insert">
                                                <label id="input">
                                                    <i class="fas fa-folder-open"></i>Select Image
                                                    <input type="file" name="img_file" id="img_file" class="inputfile" accept="image/*">
                                                </label>
                                                <div id="reload">
                                                    <button id="reload" onClick="relaod();" class="buttonimage">
                                                        <i class="fas fa-undo"></i>ReLoad
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="crop" align="right">
                                                <button id="crop" class="buttonimage">
                                                    <i class="fas fa-crop"></i>Crop</button>
                                            </div>
                                        </div>
                                        <div>
                                            <canvas id="canvas_crop">
                                                Your browser does not support HTML5 Canvas
                                            </canvas>
                                        </div>
                                    </div>
                                    <div id="colum3">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                    <div id="colum4">
                                        <h1 class="form_txt">
                                            Your Cropped Image
                                        </h1><br>
                                        <div id="reswapper" align="center">
                                            <div id="result">
                                            </div>
                                        </div>
                                        <div id="colum4_warp">
                                            <div>
                                                <form action="#" method="post" name="image">
                                                <input type="hidden" name="userid" id="userid" value="<?php echo $userid ;?>">
                                                    <input type="hidden" name="file_name" id="file_name">
                                                    <input type="hidden" name="cropped_img" id="cropped_img">
                                                    <button id="upload_img" name="upload_img" disabled class="buttonimage">
                                                        <i class="fas fa-tags"></i>Save Profile Image</button>
                                                </form>
                                            </div>
                                            <div>
                                                <button id="reload" onClick="relaod();" class="buttonimage">
                                                    <i class="fas fa-undo"></i>ReLoad
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="colum5">
                                        <div id="status">
                                            <?php if (!empty($adimmess)) {
                                                echo $adimmess;
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <!--------------------------------add user------------------------------------------------>
                            </div>
                        </div>
                        <div id="tab2" class="tabcontent">
                            <!--------------------upload_defult----------------------------->
                            <div class="upload_defult">
                                <div id="defult_colum1">
                                    <h1 class="txt_t2">Select Default Image</h1>
                                </div>
                                <div id="defult_colum1">
                                    <label class="form_txt">Select Default Image</label><br />
                                    <span id="defimg-info" class="error"></span>
                                </div>
                                <form action="" method="POST" onSubmit="return validate_form();">
                                    <div id="defult_colum2">
                                        <div align="center">
                                            <img src="images/profileface/default1.JPG" class="defult_img"><br>
                                            <input type="radio" id="defimg" name="defimg" value="default1"><span class="name_txt">&nbsp;Petter</span>
                                        </div>
                                        <div align="center">
                                            <img src="images/profileface/default2.JPG" class="defult_img"><br>
                                            <input type="radio" id="defimg" name="defimg" value="default2"><span class="name_txt">&nbsp;Jackie</span>
                                        </div>
                                        <div align="center">
                                            <img src="images/profileface/default3.JPG" class="defult_img"><br>
                                            <input type="radio" id="defimg" name="defimg" value="default3"><span class="name_txt">&nbsp;John</span>
                                        </div>
                                        <div align="center">
                                            <img src="images/profileface/default4.JPG" class="defult_img"><br>
                                            <input type="radio" id="defimg" name="defimg" value="default4"><span class="name_txt">&nbsp;Sonia</span>
                                        </div>
                                        <div align="center">
                                            <img src="images/profileface/default5.JPG" class="defult_img"><br>
                                            <input type="radio" id="defimg" name="defimg" value="default5"><span class="name_txt">&nbsp;Paul</span>
                                        </div>
                                        <div align="center">
                                            <img src="images/profileface/default6.JPG" class="defult_img"><br>
                                            <input type="radio" id="defimg" name="defimg" value="default6"><span class="name_txt">&nbsp;Tina</span>
                                        </div>
                                    </div>
                                    <div id="defult_colum1">
                                        <?php
                                        if (!empty($_GET['usid'])) {
                                            $setid = $_GET['usid'];
                                            echo '<input type="hidden" name="selectid" id="selectid" value="';
                                            echo $setid;
                                            echo '">';
                                        }
                                        ?>
                                        <input type="hidden" name="userid" id="userid" value="<?php echo $userid ;?>">
                                        <button type="submit" class="buttonimage" /><i class="fas fa-tags"></i>Save Profile Image</button>

                                    </div>
                                </form>
                                <div id="defult_colum1">
                                    <span id="status_data"></span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--------------------body_section----------------------------->
            </div>
            <div id="footer_section">
                <!--------------------footer_section----------------------------->
                <h1 class="txt_t4" align="right">
                    <?php
                    echo $footer_pag1;
                    ?>
                </h1>
                <!--------------------footer_section----------------------------->
            </div>
            <!---------------------contant here------------------------------>
        </div>
        <!-------------------container----------------------->

<script>
    function openCity(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>
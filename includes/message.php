<?php
include("connect.php");
?>
<!-----------------------------output here-------------------------------->
<span id="recid-info" class="message"></span>
<span id="subject-info" class="message"></span>
<span id="messcontent-info" class="message"></span>
<span id="status_sendmess" class="message"></span>
<span id="search_mess1" class="message"></span>
<span id="mess1" class="message">
        <?php if (!empty($mess1)) {
                echo $mess1;
        } ?>
</span>
<!-----------------------------output here-------------------------------->
<div id="message_div">
        <div class="tabs">
                <div>
                        <ul class="tabs_list">
                                <li <?php if (!empty($act) and $act == "tab1") {
                                                echo 'class="active"';
                                        } ?>>
                                        <a href="#tab1"><span>
                                                        <i class="fas fa-envelope"></i>Inbox</span></a>
                                </li>
                                <li <?php if (!empty($act) and $act == "tab2") {
                                                echo 'class="active"';
                                        } ?>>
                                        <a href="#tab2"><span>
                                                        <i class="fas fa-inbox"></i>Outbox</span></a>
                                </li>
                        </ul>
                </div>
                <div align="right">
                        <a href="#" class="buttcompose" onclick="openForm();"><span><i class="fa fa-clone"></i>&nbsp;Compose Message</span></a>
                </div>
                <!---------------------------tab1------------------------------------->
                <div id="tab1" <?php if (!empty($act) and $act == "tab1") {
                                        echo 'class="tab active"';
                                } else {
                                        echo 'class="tab"';
                                } ?>>
                        <?php
                        include("mess_inbox.php");
                        ?>
                </div>
                <!---------------------------tab2------------------------------------->
                <div id="tab2" <?php if (!empty($act) and $act == "tab2") {
                                        echo 'class="tab active"';
                                } else {
                                        echo 'class="tab"';
                                } ?>>
                        <?php
                        include("mess_outbox.php");
                        ?>
                </div>
                <!--------------------------------------------------------------------->
        </div>
        <!---------------------------------popup---------------------------------->
        <div class="chat-popup" id="myForm" <?php if (!empty($rep)) {
                                                        echo 'style="display:block"';
                                                } ?>>
                <!------------------write Message------------------>
                <!-----------creating message--------------------->
                <div class="form-container" id="form-container">
                        <div align="right">
                                <button type="button" class="close" onclick="closeForm();">
                                        <i class="fas fa-times"></i>
                                </button>
                        </div>
                        <form action="" method="POST" onSubmit="return sendmessage();">
                                <div>
                                        <h1 class="txt_t2" align="center">
                                                Compose Message</h1><br>
                                        <!------------------select sender-------------->
                                        <?php if (empty($rep)) { ?>
                                                <input type="hidden" id="recid" name="recid">
                                                <input type="text" id="search" placeholder="Search" name="name" autocomplete="off" class="forminpout" />
                                                <span id="search_display"></span>
                                        <?php
                                        } else { ?>
                                                <input type="hidden" <?php if (!empty($rep)) {
                                                                                echo 'value="' . $rep . '"';
                                                                        } ?> name="recid" id="recid">
                                        <?php }
                                        ?>
                                        <!------------------select sender--------------------------->
                                        <label class="chat_m3">Enter Subjest</label><br>
                                        <input type="text" id="subject" name="subject" class="forminpout"><br>
                                        <label class="chat_m3">Enter Message</label><br>
                                        <textarea id="messcontent" name="messcontent"></textarea><br>
                                        <input type="hidden" <?php if (!empty($userid)) {
                                                                        echo 'value="';
                                                                        echo $userid;
                                                                        echo '"';
                                                                } ?> name="senderid">
                                </div>
                                <div>
                                        <button type="submit" name="sendmess" class="btn">
                                                Send Message
                                        </button>
                                </div>
                        </form>
                </div>
                <!---------------creating message--------------------->
                <!-------------------write Message--------------->
        </div>
</div>
<script type="text/javascript">
        //-------------------------------search box-------------------------
        function fill(name, id) {
                $('#search').val(name);
                $('#recid').val(id);
                $('#search_display').hide();
        }
        $(document).ready(function() {
                $("#search").keyup(function() {
                        var name = $('#search').val();
                        if (name == "") {
                                $("#search_mess1").html('<i class="fa fa-times" style="color:#F00; font-size:12px"></i>Please Select Sender Name').fadeOut(4000);
                        } else {
                                jQuery.ajax({
                                        url: "includes/message_search.php",
                                        data: 'search=' + name,
                                        type: "POST",
                                        success: function(data) {
                                                $("#search_display").html(data).show();
                                        },
                                        error: function() {}
                                });
                        }
                });
        });
        //-------------------------------search box-------------------------
        $("#mess1").fadeOut(6000);
        //--------------------------tab----------------------------------
        $(document).ready(function() {
                $(".tabs-list li a").click(function(e) {
                        e.preventDefault();
                });
                //---------------------get current page----------------------------
                //---------------------get page click----------------------------
                $(".tabs_list li").click(function() {
                        var tabid = $(this).find("a").attr("href");
                        $(".tabs_list li,.tabs div.tab").removeClass("active"); // removing active class from tab and tab content
                        $(".tab").hide(); // hiding open tab
                        $(tabid).show(); // show tab
                        $(this).addClass("active"); //  adding active class to clicked tab
                });
        });
        //--------------------------tab----------------------------------
        //--------------------------send message--------------------------------
        function sendmessage() {
                var valid = true;
                $(".error").html('');
                //--------------------------Receiver-----------------------------
                if (!$("#recid").val()) {
                        $("#recid-info").html("<i class='fa fa-times' style='color:#F00; font-size:12px'></i>Select Message Receiver<br>").show().fadeOut(6000);
                        valid = false;
                }
                //--------------------------subject-----------------------------
                if (!$("#subject").val()) {
                        $("#subject-info").html("<i class='fa fa-times' style='color:#F00; font-size:12px'></i>Subject is Required<br>").show().fadeOut(6000);
                        valid = false;
                } else if ($("#subject").val().length > 30) {
                        $("#subject-info").html("<i class='fa fa-times' style='color:#F00; font-size:12px'></i>Subject is Too Large<br>").show().fadeOut(6000);
                        valid = false;
                } else if (!$("#subject").val().match(/^[a-zA-Z0-9,.!? ]*$/)) {
                        $("#subject-info").html("<i class='fa fa-times'></i>Subject Only Letters and White Space Allowed").show().fadeOut(6000);
                        valid = false;
                }
                //--------------------------message-----------------------------
                if (!$("#messcontent").val()) {
                        $("#messcontent-info").html("<i class='fa fa-times' style='color:#F00; font-size:12px'></i>Message is Required<br>").show().fadeOut(6000);
                        valid = false;
                } else if ($("#messcontent").val().length > 390) {
                        $("#messcontent-info").html("<i class='fa fa-times' style='color:#F00; font-size:12px'></i>Message is Too Large<br>").show().fadeOut(6000);
                        valid = false;
                } else if (!$("#messcontent").val().match(/^[a-zA-Z0-9,.!? ]*$/)) {
                        $("#messcontent-info").html("<i class='fa fa-times'></i>Message Only Letters and White Space Allowed").show().fadeOut(6000);
                        valid = false;
                }

                //---------------------------validatemessage---------------------------------
                return valid;
        }
        //--------------------------send message--------------------------------   
</script>
<script>
        function openForm() {
                document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
                document.getElementById("myForm").style.display = "none";
        }
</script>
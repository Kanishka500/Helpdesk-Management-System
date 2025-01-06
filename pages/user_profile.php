
        <!-------------------container----------------------->
        <div id="main_content">
            <!--------------------contant here----------------------------->
            <div id="heding_section">
                <!--------------------heding_section----------------------------->
                <h1 class="txt_t1">User Profile Page</h1>
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">User Profile</a></li>
                    <li>Profile Information</li>
                </ul>
                <!--------------------heding_section----------------------------->
            </div>
            <div id="body_section">
                <!--------------------body_section----------------------------->
                <div class="div_section">
                    <div align="center">
                        <h1 class="txt_t2">Profile Information</h1>
                    </div>
                    <div>
                        <?php
                        include("includes/connect.php");
                        if (isset($_SESSION['userid'])) {
                            $userid = $_SESSION['userid'];
                            $result = User::getUserinfor($pdo, $userid);
                            $username = $result[0];
                            $usertype = $result[1];
                        }
                        $sql = "SELECT * FROM user_infor WHERE user_id=:userid";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['userid' => $userid]);
                        $result = $stmt->fetchall();
                        if (!empty($result)) {
                            foreach ($result as $row) {
                        ?>
                                <table border="0" class="infor_image">
                                    <tr>
                                        <td align="center" valign="middle">
                                            <img src="images/profileface/<?php echo $row['user_image']; ?>" class="proimage1">
                                        </td>
                                    </tr>
                                </table>
                                <div class="pro_wapper">
                                    <div class="pro" align="center">
                                        <span class="infor_t2">
                                            <b>User First Name :</b></span>
                                        <span class="infor_t2"><?php echo $row['user_fname']; ?></span>
                                    </div>
                                    <div class="pro">
                                        <span class="infor_t2" align="center">
                                            <b>User Last Name :</b></span>
                                        <span class="infor_t2"><?php echo $row['user_lname']; ?></span>
                                    </div>
                                    <div class="pro">
                                        <span class="infor_t2" align="center">
                                            <b>User Address :</b></span>
                                        <span class="infor_t2"><?php echo $row['user_address']; ?></span>
                                    </div>
                                    <div class="pro">
                                        <span class="infor_t2" align="center">
                                            <b>User Birith Day :</b></span>
                                        <span class="infor_t2"><?php echo $row['user_bday']; ?></span>
                                    </div>
                                    <div class="pro">
                                        <span class="infor_t2" align="center">
                                            <b>User Email :</b></span>
                                        <span class="infor_t2"><?php echo $row['user_email']; ?></span>
                                    </div>
                                    <div class="pro">
                                        <span class="infor_t2" align="center">
                                            <b>User Tell Nimber :</b></span>
                                        <span class="infor_t2"><?php echo $row['user_tell']; ?></span>
                                    </div>
                                    <div class="pro">
                                        <span class="infor_t2" align="center">
                                            <b>User Activedate :</b></span>
                                        <span class="infor_t2"><?php echo $row['user_actdate']; ?></span>
                                    </div>
                                </div>
                        <?php }
                        }
                        $pdo = null;
                        ?>

                    </div>
                </div>
                <div class="div_section">
                    <div>
                        <h1 class="txt_t2">Chat Messages</h1>
                        <h2 class="txt_t3">Important Chat Messages about User</h2>
                    </div>
                    <div>
                        <?php
                        include_once("includes/message.php");
                        ?>
                    </div>
                </div>
                <!--------------------body_section----------------------------->
            </div>
            <div id="footer_section">
                <!--------------------footer_section----------------------------->
                <h1 class="txt_t4">
                    <?php
                    echo $footer_pag1;
                    ?>
                </h1>
                <!--------------------footer_section----------------------------->
            </div>
            <!---------------------contant here------------------------------>
        </div>
        <!-------------------container----------------------->
<?php
$pdom = null;
?>

<!-------------------header----------------------->
<div id="header">
  <div id="header_wapper">
    <!-------------------toggle----------------------->
    <div id="toggle_wapper">
      <div id="toggle_div">
        <span style="cursor:pointer">
          <div class="container" onclick="togglebtn(this)">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
          </div>
        </span>
      </div>
    </div>
    <!-------------------toggle----------------------->
    <div id="logo">
      <div id="logo_wapper">
        <img <?php
              echo 'src="' . $header_logo . '"';
              ?> id="logoimg" align="left">
        <h1 id="logo_t1">
          <?php
          echo $header_txt1;
          ?>
        </h1>
        <p id="logo_t2">
          <?php
          echo $header_txt2;
          ?>
        </p>
      </div>
    </div>
    <!-------------------logout----------------------->
    <?php
    include("connect.php");
    if (isset($_SESSION['userid'])) {
      $userid = $_SESSION['userid'];
      $result = User::getUserinfor($pdo, $userid);
      $username = $result[0];
      $usertype = $result[1];
      $userimage = $result[2];
      $pdo = null;
    }
    ?>
    <div id="infor">
      <!-- <div id="Search_bar">
        </div> -->
      <div id="logout_div">
        <img src="images/profileface/<?php echo $userimage; ?>" class="avatar" align="right">
        <p id="login_infort1">Welcom to Our System <br>
          <font style="font-weight:normal; color:#314559"><?php echo ucwords($usertype); ?></font>
          <font style="font-weight:bold; color:#314559"><?php echo ucwords($username); ?></font>
        </p>
        <!----------------------------popup--------------------------->
        <span id="login_infort1">
          <font style="color:#4686c5"><i class="far fa-hand-point-right"></i>Profile</font>
        </span>
        <div class="dropbtnpop_wapper">
          <a href="#" onClick="mypopupfun()">
            <img src="images/angle-down-solid.svg" width="20px" height="20px" class="dropbtnpop" align="left">
          </a>
          <div id="mypopup" class="popupdown-content">
            <a href="index.php?page=user_profile&act=tab1" target="_parent">
              <i class="fas fa-users-cog"></i>User Profile</a>
            <a href="#" target="_parent"><i class="fas fa-question-circle"></i>Get Help</a>
            <a href="logout.php" target="_parent"><i class="fas fa-sign-out-alt"></i>Sign Out</a>
          </div>
        </div>
        <!---------------------------popup----------------------------->

      </div>

    </div>
  </div>
<!-------------------header----------------------->
</div>
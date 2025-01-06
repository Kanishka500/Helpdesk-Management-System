<?php
// --------------------page configs-------------------------
$minhead1 = "Recomendation Manager";
$minlink1 = "Recomendation Manager";
$minlink2 = "Recomendation";
$pag = "index.php?page=rm_recommen";
$miantable = "complain";
$maxvalue = 10;
?>
<div id="main_content">
  <!--------------------content here----------------------------->
  <div id="heding_section">
    <!--------------------heading_section----------------------------->
    <div class="page_title">
      <p>Dashboard</p>
    </div>
    <!--------------------heading_section----------------------------->
  </div>

  <div id="body_section">
    <div class="content" style="padding-right:10px; padding: left 10px;">
      <div class="form-box">
        <div class="box">
          <div class="b1">
            <span class="img"><i class="fa fa-book" style="width: 50px; height: 50px; margin-top:22px;"></i></span><br>
            <div class="title">Complain</div>
            <br><br>
            <div class="sub-title">
              <a href="index.php?page=add_com" style="text-decoration: none;"><p>Add Complain</p></a>
            </div>
          </div>
          <div class="b1">
            <span class="img"><i class="fa fa-check-double" style="width: 50px; height: 50px; margin-top:22px;"></i></span><br>
            <div class="title">Recommendation</div>
            <br><br>
            <div class="sub-title">
              <a href="index.php?page=rm_recommendation" <?php if($role==7){echo 'style="pointer-events: none"';}else{echo 'style="text-decoration: none;"';}?> ><p>RM Recommendation</p></a>
            </div>
          </div>
          <div class="b1">
            <span class="img"><i class="fa fa-futbol" style="width: 50px; height: 50px; margin-top:22px;"></i></span><br>
            <div class="title">Service</div>
            <br><br>
            <div class="sub-title">
              <a href="index.php?page=manage_service" <?php if($role==7){echo 'style="pointer-events: none"';}else{echo 'style="text-decoration: none;"';}?>><p>Manage Service</p></a>
            </div>
          </div>
          <div class="b1">
            <span class="img"><i class="fa fa-cogs" style="width: 50px; height: 50px; margin-top:22px;"></i></span><br>
            <div class="title">job</div>
            <br><br>
            <div class="sub-title">
              <a href="index.php?page=manage_job" <?php if($role==7){echo 'style="pointer-events: none"';}else{echo 'style="text-decoration: none;"';}?>><p>Manage Job</p></a>
            </div>
          </div>
        </div>

        <div class="box">
          <div class="b2">
            <span class="img"><i class="fa fa-clipboard" style="width: 50px; height: 50px; margin-top:22px;"></i></span><br>
            <div class="title">Quotation</div>
            <br><br>
            <div class="sub-title">
              <a href="index.php?page=manage_quotation" <?php if($role==7){echo 'style="pointer-events: none"';}else{echo 'style="text-decoration: none;"';}?>><p>Manage Quotation</p></a>
            </div>
          </div>
          <div class="b2">
            <span class="img"><i class="fa fa-bars" style="width: 50px; height: 50px; margin-top:22px;"></i></span><br>
            <div class="title">View Quotation</div>
            <br><br>
            <div class="sub-title">
              <a href="index.php?page=view_quotation" <?php if($role==7){echo 'style="pointer-events: none"';}else{echo 'style="text-decoration: none;"';}?>><p>View Quotation</p></a>
            </div>
          </div>
          <div class="b2">
            <span class="img"><i class="fa fa-coins" style="width: 50px; height: 50px; margin-top:22px;"></i></span><br>
            <div class="title">Payment Status</div>
            <br><br>
            <div class="sub-title">
              <a href="index.php?page=manage_payment" <?php if($role==7){echo 'style="pointer-events: none"';}else{echo 'style="text-decoration: none;"';}?>><p>Manage Payment Status</p></a>
            </div>
          </div>
          <div class="b2">
            <span class="img"><i class="fa fa-user" style="width: 50px; height: 50px; margin-top:22px;"></i></span><br>
            <div class="title">Staff</div>
            <br><br>
            <div class="sub-title">
              <a href="index.php?page=manage_staff" <?php if($role==7){echo 'style="pointer-events: none"';}else{echo 'style="text-decoration: none;"';}?>><p>Manage Staff</p></a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>




<!---------------------content here------------------------------>
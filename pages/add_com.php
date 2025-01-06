<div id="overlay" class="overlay"></div>
<div id="main_content">
  <!--------------------content here----------------------------->
  <div id="heding_section">
    <!--------------------heading_section----------------------------->
    <div class="page_title">
      <p>Complain<span class="page_name"> > Add Complain</span></p>
    </div>
    <!--------------------heading_section----------------------------->
  </div>

  <div id="body_section">
    <div class="page_subtitle">
      <p class="title">Manage Complain</p>
    </div>

    <div class="add-complain-form">
      <form action="#" class="add-complain-form-card" method="post" id="complainForm" enctype="multipart/form-data"><br><br>
        <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        <div class="col-25">
          <label class="date">Date</label>
        </div>
        <div class="col-75">
          <input type="text" id="current-date" name="date" readonly>
        </div>

        <br><br><br>

        <div class="col-25">
          <label class="assetno">Asset No</label>
        </div>
        <div class="col-75">
          <input type="text" id="assetnoInput" name="assetnoInput" placeholder="Type asset number..." autocomplete="off">
          <ul id="suggestionBox" class="suggestion-box"></ul> <!-- Hidden suggestion box -->
        </div>

        <div class="col-25">
          <label class="serialno">Serial No</label>
        </div>
        <div class="col-75">
          <input type="text" id="serialno" name="serialno" placeholder="Serial no.." required="required" readonly>
        </div>

        <div class="col-25">
          <label class="empid">Employee ID</label>
        </div>
        <div class="col-75">
          <input type="text" id="empid" name="empid" placeholder="Enter your employee id.." required="required">
        </div>

        <div class="col-25">
          <label class="regoffice">Regional Office</label>
        </div>
        <div class="col-75">
          <select id="regional_office" name="regOffice">
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

        <div class="col-25">
          <label class="subject">Observations</label>
        </div>
        <div class="col-75">
          <textarea id="subject" name="subject" placeholder="Write observations.." required="required" style="height:100px"></textarea>
        </div>

        <div class="col-25">
          <label class="observation">Upload Image</label>
        </div>
        <div class="col-75">
          <input type="file" id="myFile" name="filename" multiple>
          <p class="comment">Image size should be less than 1MB</p>
        </div>

        <div id="info"></div>

        <br>
        <div class="row">
          <input type="submit" value="Submit" name="submit">
          <input type="cancel" value="Cancel">
        </div>
      </form>

      <div class="message-box">
        <div class="popup" id="popup">
          <img src="images/404-tick.png" alt="">
          <h2>Thank You!</h2>
          <p>Your complain has been successfully submitted. Thanks!</p>
          <button type="button" onclick="closePopup()" name="ok">OK</button>
        </div>
      </div>

    </div>
  </div>
  <!---------------------content here------------------------------>
</div>
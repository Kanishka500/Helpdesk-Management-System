<div id="main_content">
  <!-- -------------------contant here---------------------------- -->
<div id="hedingdiv">
    <h1 class="txt_t1">Add System Staff</h1>
    <ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">System Staff</a></li>
    <li><a href="#">Staff Information</a></li>
    <li>Add Sysyem Staff</li>
    </ul>
</div>
<div id="userad_div">
<h1 class="txt_t2">Add System Staff Form</h1><br>
<div class="formcont_wapper">
<div class="formcon1" >
<!----------------------1div-------------------------->
<div class="formrow">
<div class="formcolm">
<!----------------------1fname------------------------------------>
<label class="form_txt">First Name</label>
<span id="fname-info" class="error"></span><br/>
<input id="fname" name="fname" type="text" placeholder="First Name.." class="forminpout">
</div>
<!------------------------------------------------->
<div class="formcolm">
<!-----------------------2lname----------------------------------->
<label class="form_txt">Last Name</label>
<span id="lname-info" class="error"></span><br/>
<input id="lname" name="lname" type="text" placeholder="Last Name.." class="forminpout">
</div>
</div>
<!----------------------2div-------------------------->
<div class="formrow">
<div class="formcolm">
<!-----------------------3address----------------------------------->
<label class="form_txt">Address</label>
<span id="address-info" class="error"></span><br/>
<input id="address" name="address" type="text" placeholder="Address.." class="forminpout">
</div>
<!------------------------------------------------->
<div class="formcolm">
<!-----------------------4bday----------------------------------->
<label class="form_txt">Birth Day</label>
<span id="bday-info" class="error"></span><br/>
<input id="bday" name="bday" type="date" placeholder="Birth Day.." class="forminpout">
</div>
</div>
<!----------------------3div-------------------------->
<div class="formrow">
<div class="formcolm">
<!------------------------5email---------------------------------->
<label class="form_txt">Email</label>
<span id="email-info" class="error"></span><br/>
<input id="email" name="email" type="text" placeholder="Email.." class="forminpout">
</div>
<!------------------------------------------------->
<div class="formcolm">
<!------------------------6tell---------------------------------->
<label class="form_txt">Tell Number</label>
<span id="tell-info" class="error"></span><br/>
<input id="tell" name="tell" type="text" placeholder="Tell Number.." class="forminpout">
</div>
</div>
<!----------------------5div-------------------------->
<div class="formrow">
<div class="formcolm">
<!------------------------7username---------------------------------->
<label class="form_txt">User Name</label>
<span id="username-info" class="error"></span><br/>
<input id="username" name="username" type="text" placeholder="User Name.." class="forminpout">
</div>
<!------------------------------------------------->
<div class="formcolm">
<!------------------------8type----------------------------->
<label class="form_txt">User Office</label>
<span id="office-info" class="error"></span><br/>
<select  name="office"  id="office"  class="forminpout">
<option value="">Select User Office</option>
<option value="H">Head Office</option>
<option value="C1">Colombo 01</option>
<option value="C2">Colombo 02</option>
<option value="C3">Colombo 03</option>
<option value="C4">Colombo 04</option>
<option value="G1">Gampaha 01</option>
<option value="G2">Gampaha 02</option>
<option value="KLT">Kaluthara</option>
</select>
</div>
</div>
<!----------------------6div-------------------------->
<div class="formrow">
<div class="formcolm">
<!------------------------9password---------------------------------->
<label class="form_txt">Creat Password</label>
<span id="password-info" class="error"></span><br/>
<input id="password" name="password" type="password" placeholder="Creat Password.." class="forminpout">
</div>
<!------------------------------------------------->
<div class="formcolm">
<!------------------------10repassword---------------------------------->
<label class="form_txt" >Confirm Password</label>
<span id="repassword-info" class="error"></span><br/>
<input id="repassword" name="repassword" type="password" placeholder="Confirm Password.." class="forminpout">
</div>
</div>     
<!----------------------7div-------------------------->
<div class="formrow">
<div class="formcolm">
<!------------------------12role---------------------------------->
<label class="form_txt">User Title</label>
<span id="role-info" class="error"></span><br/><br/>
<select  name="role"  id="role"  class="forminpout">
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
</div>
<!-----------------------8div/uploads/Image----------------->
</div>  
<div class="formcon2">
<div class="image_div">
<img src="images/profileface/default.jpg" id="image1">   
<!------------------------13userimage---------------------------------->

</div>
</div>
<div class="formcon3" id="status_data" >
<!------------------------14submit---------------------------------->
<input type="hidden" name="userid" id="userid" value="<?php echo $userid ;?>">
<button name="submit"  onClick="senddata();" class="submit" >Submit Data</button>
</div>
</div>

<!--------------------------------add user------------------------------------------------>    
</div>
<!---------------------contant here------------------------------>
</div>
</div>
<!--------------------------------------------------page content here------------------------------------------------------->
<!--------------------------------add user--------------------------------userid---------------->
<script type="text/javascript">
    function senddata() {
    var valid;	
    valid = validatedata();
    if(valid) {
    jQuery.ajax({
    url: "include_ajaxs/adduser.php",
    data:   'fname='+$('#fname').val()+
            '&lname='+$('#lname').val()+
            '&address='+$('#address').val()+
            '&bday='+$('#bday').val()+
            '&email='+$('#email').val()+
            '&tell='+$('#tell').val()+
            '&role='+$('#role').val()+
            '&office='+$('#office').val()+
            '&userid='+$('#userid').val()+
            '&username='+$('#username').val()+
            '&password='+$('#password').val(),
            type: "POST",
            success:function(data){
            $("#status_data").html(data);
            },
            error:function (){}
    });
    }
    }
    //------------------------------------------------
    function validatedata() {
    var valid = true;	
	$(".error").html('');
    //--------------------------fname-----------------------------
    if(!$("#fname").val()) {
		$("#fname-info").html("<i class='fa fa-times'></i>First Name is Required");
        //  $("#fname").css('background-color','#FFFFDF');
		valid = false;
        }else if(!$("#fname").val().match(/^[a-zA-Z][a-zA-Z\s]*$/)){
        $("#fname-info").html("<i class='fa fa-times'></i>First Name Only Letters and White Space Allowed");
        valid = false;
        }else{
        $("#fname-info").html('<i class="fas fa-check"></i>');
    }   
    //--------------------------lname-----------------------------
    if(!$("#lname").val()) {
		$("#lname-info").html("<i class='fa fa-times'></i>Last Name is Required");
        //$("#lname").css('background-color','#FFFFDF');
		valid = false;
        }else if(!$("#lname").val().match(/^[a-zA-Z][a-zA-Z\s]*$/)){
        $("#lname-info").html("<i class='fa fa-times'></i>Last Name Only Letters and White Space Allowed");
        valid = false;
        }else{
        $("#lname-info").html('<i class="fas fa-check"></i>');
    }      
    //-------------------------address----------------------------
    if(!$("#address").val()) {
	    $("#address-info").html("<i class='fa fa-times'></i>Address is Required");
        //$("#lname").css('background-color','#FFFFDF');
		valid = false;
        }else if(!$("#address").val().match(/^[a-zA-Z0-9,.!? ]*$/)){
        $("#address-info").html("<i class='fa fa-times'></i>Address Only Letters,Numbers and White Space Allowed");
        valid = false;
        }else{
        $("#address-info").html('<i class="fas fa-check"></i>');
    }      
    //---------------------------bday-----------------------------
    if(!$("#bday").val()) {
		$("#bday-info").html("<i class='fa fa-times'></i>Birth day is Required");
        // $("#bday").css('background-color','#FFFFDF');
		valid = false;
        }else{
        $("#bday-info").html('<i class="fas fa-check"></i>');
    }        
    //---------------------------email----------------------------
    if(!$("#email").val()) {
		$("#email-info").html("<i class='fa fa-times'></i>Email is required");
        //		$("#email").css('background-color','#FFFFDF');
		valid = false;
        }else if(!$("#email").val().match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)){
         $("#email-info").html("<i class='fa fa-times'></i>Invalid Email Format");
        //		$("#email").css('background-color','#FFFFDF');
		valid = false; 
        }else{
        $("#email-info").html('<i class="fas fa-check"></i>');
    }   
    //---------------------------tell-----------------------------
    if(!$("#tell").val()) {
		$("#tell-info").html("<i class='fa fa-times'></i>Tell Number is Required");
        //$("#tell").css('background-color','#FFFFDF');
		valid = false;
        }else if(!/^[0-9]+$/.test($("#tell").val())){
        $("#tell-info").html("<i class='fa fa-times'></i>Invalid Tell Format There are Letters");
        valid = false;
        }else if($("#tell").val().length < 10){
         $("#tell-info").html("<i class='fa fa-times'></i>Invalid Tell Check the Numbers");
        valid = false;
        }else{
        $("#tell-info").html('<i class="fas fa-check"></i>');
    }  
    
    //---------------------------role-----------------------------
    if(!$("#role").val()) {
		$("#role-info").html("<i class='fa fa-times'></i>User Role is Required");
		valid = false;
        } else{
        $("#role-info").html('<i class="fas fa-check"></i>');
    }    
    //---------------------------type-----------------------------
    if(!$("#office").val()) {
		$("#office-info").html("<i class='fa fa-times'></i>User Office is Required");
		valid = false;
        }else{
        $("#office-info").html('<i class="fas fa-check"></i>');
    }      
    //--------------------------username--------------------------
    if(!$("#username").val()) {
		$("#username-info").html("<i class='fa fa-times'></i>User Name is Required");
        //		$("#username").css('background-color','#FFFFDF');
		valid = false;
        }else if(/[^a-zA-Z]/.test($("#username").val())){
        $("#username-info").html("  User Name Only Letters Allowed");
        valid = false;
        }else if($("#username").val().length > 10){
         $("#username-info").html("<i class='fa fa-times'></i>User Name Only Below 10 Characters");
        valid = false;
        } else{
        $("#username-info").html('<i class="fas fa-check"></i>');
    }      
    //--------------------------password--------------------------
    if(!$("#password").val()) {
		$("#password-info").html("<i class='fa fa-times'></i>User Password is Required");
        //		$("#password").css('background-color','#FFFFDF');
		valid = false;
        }else{
        $("#password-info").html('<i class="fas fa-check"></i>');
    }      
    //--------------------------repassword------------------------
    if(!$("#repassword").val()) {
		$("#repassword-info").html("<i class='fa fa-times'></i>User Confirm Password is Required");
        //		$("#repassword").css('background-color','#FFFFDF');
		valid = false;
        }else if($("#password").val()!= $("#repassword").val()){
         $("#repassword-info").html("<i class='fa fa-times'></i>Confirm Password Not Match");
        valid = false;
        }else{
        $("#repassword-info").html('<i class="fas fa-check"></i>');
    }    
    //------------------------------------------------------------
    return valid;
    }
</script>
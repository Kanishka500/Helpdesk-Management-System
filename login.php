<?php
ob_start();
include_once("includes/connect.php");
include_once("includes/setting.php");
include_once("classes/User.php");
if (isset($_POST['login'])) {
  $loginname = $_POST['login_name'];
  $loginpass = $_POST['login_password'];
  $sql = "SELECT * FROM user_login WHERE login_name=:loginname";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['loginname' => $loginname]);
  $result = $stmt->fetchall();
  if (!empty($result)) {
    // output data of each row
    foreach ($result as $row) {
      $username = $row['login_name'];
      $userid = $row['login_userid'];
      $pass_value = $row['login_password'];
        if (password_verify($loginpass,$pass_value)) {
            User::createRecord($pdo, $userid);
            $action = "Login to System";
            User::addRecord($pdo, $userid, $username, $action);
            $pdo = null;
            header("location:index.php");
        }else{
            $message1 = "Please Enter Your<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Correct Password";
        }
    }
  } else {
    $message1 = "Please Enter Your<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Correct User Name";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="fontawesome/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="css/login.css">
  <title>RPTA | Login</title>
</head>
<body>
  <div class="container_wapper">
    <div class="container">
      <!---------------------------------------------------------------------------->
      <div class="head">
        <table border="0" width="100%">
          <tr>
            <td align="right" width="40%">
              <img <?php echo 'src="' . $logo_path . '"'; ?> width="65" height="65">&nbsp;
            </td>
            <td align="left" width="60%">
              <h1 id="head_text1"><?php echo $login_hed1; ?></h1>
              <h2 id="head_text2"><?php echo $login_hed2; ?></h2>
              <h2 id="head_text3"><?php echo $login_hed3; ?></h2>
            </td>
        </table>
      </div>
      <!----------------------------------------------------------------------------->
      <div class="section1">
        <img src="images/login1.png" id="image1">
      </div>
      <!----------------------------------------------------------------------------->
      <div class="section2">
        <form action="#" name="login" method="post" onSubmit="return validate_form();">
          <div class="from_div">
            <label for="uname"><b id="login_txt1">Username</b></label>
            <input type="text" placeholder="Enter Username" name="login_name" required>
            <label for="psw"><b id="login_txt1">Password</b></label>
            <input type="password" placeholder="Enter Password" name="login_password" required>
            <button type="submit" name="login" class="button">Please Login</button>

          </div>
          <div class="message_div" align="center">
            <label id="message">
              <?php if (!empty($message1)) {
                echo '<i class="fa fa-key"></i>';
                echo  $message1;
              } ?>
            </label>
          </div>
          <div class="submit_div" align="right">
            <label id="message_txt1" style="font-size:12px;">
              <i class="fa fa-briefcase"></i>Forgot My&nbsp;
              <a href="forgot_password.html" class="read_more1" target="_balank">Password</a>
            </label>
          </div>
        </form>
      </div>
      <!----------------------------------------------------------------------------->
    </div>
    <!----------------------------------------------------------------------------->
  </div>
</body>
</html>
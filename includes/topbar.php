<!-------------------topbar----------------------->
<div id="top_bar">
  <div id="top_barwapper">
    <!-------------------1----------------------->
    <div>
      <span class="toptxt1">
        <?php
        // set the default timezone to use. Available since PHP 5.1
        date_default_timezone_set('Asia/Colombo');
        // Prints something like: Monday 8th of August 2005 03:12:46 PM
        echo date('l jS \of F Y  h:i:s A');
        ?>
      </span>
    </div>
    <!-------------------1----------------------->
    <div>
      <span class="toptxt1"><i class="fa fa-phone"></i>
        <?php
        echo $topbar_con2;
        ?>
      </span>
    </div>
    <!-------------------1----------------------->
    <div>
      <!------------------------notification------------------------------------------>
      <a href="index.php?page=user_profile&act=tab1" target="_parent" class="notification">
        <span>Message Inbox</span>
        <?php
        $opend = 0;
        include("connect.php");
        if (!isset($_SESSION)) {
          session_start();
        }
        if (isset($_SESSION['userid'])) {
          $userid = $_SESSION['userid'];
          $role=$_SESSION['role'];
        }
        $sql = "SELECT * FROM user_messages WHERE to_id=:userid AND opened=:opend ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userid' => $userid, 'opend' => $opend]);
        $result = $stmt->rowCount();
        $message = $result;
        $pdom = null;
        if ($message <> 0) {
          echo '<span class="badge">';
          echo $message;
          echo '</span>';
        }
        ?>
      </a>
      <!------------------------notification------------------------------------------>
    </div>
  </div>
</div>
<!-------------------topbar----------------------->
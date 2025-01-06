<?php
$max = 4;
$url = 'index.php?page=user_profile&act=tab1';
$statement = "user_messages WHERE to_id=:userid ORDER BY messages_id DESC";
$sql = "SELECT * FROM {$statement}";
$stmt = $pdo->prepare($sql);
$stmt->execute(['userid' => $userid]);
$rows = $stmt->rowCount();
$totalpages = ceil($rows / $max);
$lastpage = $totalpages;
if (isset($_GET['pnin']) && $_GET['pnin'] != '') {
  $pn = preg_replace('#[^0-9]#', '', $_GET['pnin']);
  if ($pn < 1) {
    $pn = 1;
  } else if ($pn > $lastpage) {
    $pn = $lastpage;
  }
} else {
  $pn = 1;
}
$limite = ($pn - 1) * $max;
if ($limite < 0) {
  $limite = 0;
}
// -----------------------------------------------------
$textline1 = "Numbers of Records : <b>$rows</b>";
$textline2 = "View Page <b>$pn</b> of <b>$lastpage</b> Pages";
$paginationCtrls = '';
if ($lastpage != 1) {

  if ($pn > 1) {
    $previous = $pn - 1;
    $paginationCtrls .= '<a href="' . $url . '&pnin=' . $previous . '">Previous</a>';
    for ($i = $pn - 4; $i < $pn; $i++) {
      if ($i > 0) {
        $paginationCtrls .= '<a href="' . $url . '&pnin=' . $i . '">' . $i . '</a>';
      }
    }
  }

  $paginationCtrls .= '<a href="' . $url . '&pnin=' . $pn . '"' . 'class=active' . '>' . $pn . '</a>';

  for ($i = $pn + 1; $i <= $lastpage; $i++) {
    $paginationCtrls .= '<a href="' . $url . '&pnin=' . $i . '">' . $i . '</a>';
    if ($i >= $pn + 4) {
      break;
    }
  }

  if ($pn != $lastpage) {
    $next = $pn + 1;
    $paginationCtrls .= '<a href="' . $url . '&pnin=' . $next . '">Next</a> ';
  }
}

$sql = "SELECT * FROM {$statement} LIMIT {$limite},{$max}";
$stmt = $pdo->prepare($sql);
$stmt->execute(['userid' => $userid]);
$result = $stmt->fetchAll();
if (!empty($result)) {
  foreach ($result as $row) {
    $messageid = $row['messages_id'];
    $sendeid = $row['from_id'];
    $sendername = $row['sender_name'];
    $timesent = $row['time_sent'];
    $subject = $row['subject'];
    $message = $row['message'];
    $opened = $row['opened'];
    $senderimage = $row['sender_image'];

?>
    <div class="container_message" <?php if ($opened == 0) {
                                      echo 'style="background-color:#faecec"';
                                    } ?>>
      <img src="images/profileface/<?php if (!empty($senderimage)) {
                                      echo $senderimage;
                                    } ?>" class="imgmeleft" style="width:100%;">
      <p><a href="#" class="more"><?php if (!empty($sendername)) {
                                    echo $sendername;
                                  } ?></a></p>
      <p class="chat_m2"><?php if (!empty($subject)) {
                            echo $subject;
                          } ?></p>
      <p class="chat_m3"><?php if (!empty($message)) {
                            echo $message;
                          } ?></p>
      <form method="post" <?php if (!empty($pn)) {
                            echo 'action="index.php?page=user_profile&act=tab1&pnin=';
                            echo $pn;
                            echo '"';
                          } ?> name="delinbox">
        <span class="time-right">
          <?php if ($opened != 1) { ?>
            <input type="hidden" <?php if (!empty($messageid)) {
                                    echo 'value="' . $messageid . '"';
                                  } ?> name=" readid">
            <input type="submit" name="read" value="Read" class="buttmess" />
            &nbsp;|&nbsp;
          <?php } ?>
          <input type="hidden" <?php if (!empty($messageid)) {
                                  echo 'value="' . $messageid . '"';
                                } ?> name="delid">
          <input type="submit" name="deloutbox" value="Delete" class="buttmess" />
          &nbsp;|&nbsp;
          <input type="hidden" <?php if (!empty($sendeid)) {
                                  echo 'value="' . $sendeid . '"';
                                } ?> name="replyid">
          <input type="submit" name="reply" value="Reply" class="buttmess" />
          &nbsp;|&nbsp;
          <?php if (!empty($timesent)) {
            echo $timesent;
          } ?>
        </span>
      </form>
    </div>
<?php }
} else {
  echo '<label class="message">There was Not Data</label>';
}
?>
<div id="view_section4">
  <p class="chat_m2"><?php echo $textline1; ?></p>
  <p class="chat_m2"><?php echo $textline2; ?></p>
</div>
<div id="view_section5" class="pagination" align="right">
  <?php if (!empty($result)) {
    echo $paginationCtrls;
  } ?>
</div>
<?php
$meta_dir = 'metas';
if (!empty($_GET['page'])) {
  $meta = $_GET['page'];
  $metas = scandir($meta_dir, 0);
  unset($metas[0], $metas[1]);
  /*print_r($metas);*/
  if (in_array($meta . ".php", $metas)) {
    include($meta_dir . '/' . $meta . ".php");
  }
} else {
  include($meta_dir . '/' . 'home' . ".php");
}
?>
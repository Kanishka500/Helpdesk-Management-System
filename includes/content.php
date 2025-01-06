<!-------------------content----------------------->
<div id="main_body">
    <div id="menu_war">
    <!-------------------sidebar----------------------->
    <?php
    include_once("includes/navbar.php"); //naverbar
    ?>
    <!-------------------sidebar----------------------->
    </div>
    <!-------------------container----------------------->
	<?php
	$pages_dir = 'pages';
	if (!empty($_GET['page'])) {
	$page = $_GET['page'];
	/*echo $page;*/
	$pages = scandir($pages_dir, 0);
	unset($pages[0], $pages[1]);
	/*print_r($pages);*/
	if (in_array($page . ".php", $pages)) {
		include($pages_dir . '/' . $page . '.php');
	}
	} else {
	include($pages_dir . '/' . 'home' . '.php');
	}
	?>
    <!-------------------container----------------------->
</div>
<!-------------------content----------------------->

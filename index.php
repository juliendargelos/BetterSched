<?php
	session_start();
	date_default_timezone_set('Europe/Paris');
	require_once('core/core-loader.php');
	$session=new Session;
	if(isset($_GET['page'])) {
		if(strpos($_GET['page'],'/')===false && file_exists('pages/'.$_GET['page'].'.php')) define('PAGE','pages/'.$_GET['page'].'.php');
		else {
			define('PAGE','pages/http-error.php');
			define('HTTP_ERROR',404);
		}
	}
	elseif($session->check()) define('PAGE','pages/sched.php');
	else define('PAGE','pages/signin.php');
	include(PAGE);
?>

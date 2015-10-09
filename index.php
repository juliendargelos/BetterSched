<?php
	session_start();
	define('INCLUDE',true);
	require_once('core/functions.php');
	if(isset($_GET['page'])) {
		if(strpos($_GET['page'],'/')===false && file_exists('pages/'.$_GET['page'].'.php')) define('PAGE','pages/'.$_GET['page'].'.php');
		else {
			define('PAGE','pages/http-error.php');
			define('HTTP_ERROR',404);
		}
	}
	elseif(session_check()) define('PAGE','pages/sched.php');
	else define('PAGE','pages/signin.php');
	include(PAGE);
?>

<?php
	if(!defined('INCLUDE')) {
		header('location: /');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="fr" class="">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>BetterSched'</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<?php if(substr(PAGE,-9)=='sched.php') echo '<link rel="stylesheet" type="text/css" href="css/sched.css">'; ?>
	</head>
	<body>
		<?php
			if(isset($_SESSION['result'])) {
				echo '<div id="result" onclick="this.className=\'hidden\';" ontouchstart="this.className=\'hidden\';">'.$_SESSION['result'].'</div>';
				unset($_SESSION['result']);
			}
		?>

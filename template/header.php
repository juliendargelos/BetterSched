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
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="apple-mobile-web-app-capable" content="yes">

		<meta name="msapplication-TileColor" content="#e74c3c">
		<meta name="msapplication-TileImage" content="mstile-144x144.png">

		<link rel="icon" type="image/png" href="favicon-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="favicon-160x160.png" sizes="160x160">
		<link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="favicon-48x48.png" sizes="48x48">
		<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="favicon-24x24.png" sizes="24x24">
		<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16">
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon-precomposed" sizes="180x180" href="apple-touch-icon-180x180.png">

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

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="format-detection" content="telephone=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="BetterSched'">

		<title>BetterSched'</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<?php if(substr(PAGE,-9)=='sched.php') echo '<link rel="stylesheet" type="text/css" href="css/sched.css">'; ?>
		
		<meta name="msapplication-TileColor" content="#e74c3c">
		<meta name="msapplication-TileImage" content="app/mstile-144x144.png">
		
		<link href="app/startup-1242x2148.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">
		<link href="app/startup-750x1294.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<link href="app/startup-640x1096.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<link href="app/startup-640x920.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<link href="app/startup-320x460.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">
		
		<link rel="icon" type="image/png" href="app/favicon-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="app/favicon-160x160.png" sizes="160x160">
		<link rel="icon" type="image/png" href="app/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="app/favicon-48x48.png" sizes="48x48">
		<link rel="icon" type="image/png" href="app/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="app/favicon-24x24.png" sizes="24x24">
		<link rel="icon" type="image/png" href="app/favicon-16x16.png" sizes="16x16">
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="app/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="app/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="app/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon-precomposed" sizes="180x180" href="app/apple-touch-icon-180x180.png">
		
		<link rel="manifest" href="app/manifest.json">
		
		<script type="text/javascript" src="js/piwik.js"></script>
	</head>
	<body>
		<?php
			if($session->result!=null) {
				echo '<div id="result" onclick="this.className=\'hidden\';" ontouchstart="this.className=\'hidden\';">'.$session->result.'</div>';
				$session->remove('result');
			}
		?>

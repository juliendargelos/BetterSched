<?php require_once('functions.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>BetterSched</title>
	</head>
	<body>
		<pre><?php if($sched=get_sched('MMI',1,40)) print_r($sched); ?></pre>
	</body>
</html>
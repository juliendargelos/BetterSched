<?php
	if(isset($_POST['id'],$_POST['mdp'])) {
		if($session->set($_POST['id'],$_POST['mdp'])) $_SESSION['result']='Bienvenue !';
		else $_SESSION['result']='Erreur de connexion';
	}
	header('location: /');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="refresh" content="0; URL=/">
		<title>Connexion</title>
	<head>
	<body style="background:#e74c3c;">
	</body>
</html>
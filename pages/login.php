<?php
	if(!defined('INCLUDE')) {
		header('location: /');
		exit;
	}

	if(isset($_POST['id'],$_POST['mdp'])) {
		if(session_set($_POST['id'],$_POST['mdp'])) $_SESSION['result']='Bienvenue !';
		else $_SESSION['result']='Erreur de connexion';
	}
	header('location: /');
?>

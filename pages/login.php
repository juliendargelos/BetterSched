<?php
	if(isset($_POST['id'],$_POST['mdp'])) {
		if($session->set($_POST['id'],$_POST['mdp'])) $_SESSION['result']='Bienvenue !';
		else $_SESSION['result']='Erreur de connexion';
	}
	header('location: /');
?>

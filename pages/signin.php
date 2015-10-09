<?php
	if(!defined('INCLUDE') || session_check()) {
		header('location: /');
		exit;
	}
?>
<?php include('template/header.php'); ?>
<div class="blockCo">
	<h1>BetterSched'</h1>
	<h2>Utilisez vos identifiants Sattelys pour vous connecter</h2>
	<form class="formCo" method="POST" action="/login">
		<div class="formElement formElement1">
			<label for="id">Identifiant :</label>
			<input type="text" name="id" class="inputText" placeholder=""/>
		</div>
		<div class="formElement formElement2">
			<label for="mdp">Mot de passe :</label>
			<input type="password" name="mdp" class="inputText" placeholder=""/>
		</div>
		<input type="submit"class="button" value="Valider"/>
	</form>
</div>
<script src="js/script.js"></script>
<?php include('template/footer.php'); ?>

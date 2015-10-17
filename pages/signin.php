<?php
	if(isset($session) && $session->check()) header('location: /');
?>
<?php include('template/header.php'); ?>
<div class="blockCo">
	<h1>BetterSched'</h1>
	<h2>Utilisez vos identifiants Sattelys pour vous connecter</h2>
	<form class="formCo" method="POST" action="/login">
		<div class="inputCo">
			<div class="formElement formElement1">
				<label for="id">Identifiant</label>
				<input type="text" name="id" class="inputText" placeholder=""/>
			</div>
			<div class="formElement formElement2">
				<label for="mdp">Mot de passe</label>
				<input type="password" name="mdp" class="inputText" placeholder=""/>
			</div>
		</div>
		<input type="submit"class="buttonCo" value="Valider" ontouchstart="this.className+=' touch';" ontouchend="this.className=this.className.replace(/\s+touch/,'');"/>
	</form>
</div>
<script src="js/script.js"></script>
<?php include('template/footer.php'); ?>

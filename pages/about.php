<?php
	if(!defined('INCLUDE')) {
		header('location: /');
		exit;
	}
?>
<?php include('template/header.php'); ?>
<header>
	<a href="/"><button type="button" name="button" class="button buttonApropos">Retour</button></a>
	<h3 class="titreapropos">BetterSched'</h3>
</header>
<section id="messageContainer">
	<div class="encartApropos">
		BetterSched' est une application PHP créée par Paul Bonneau et Julien Dargelos dans le but de proposer une
interface plus agréable que Sattelys, tant sur ordinateur que sur mobile.
	</div>
</section>
<section id="teamContainer">
	<div class="team">
		<h3 class="titreTeam">L'équipe</h3>
		<figure class="membreTeam">
			<img src="medias/juliendargelos.jpg" alt="Julien Dargelos" />
			<figcaption>
				<strong>Julien Dargelos</strong> Julien est un étudiant en 1ère année de MMI à l'IUT Michel de Montaigne.
			</figcaption>
			<div class="blocSocial">
				<a href="https://fr.linkedin.com/in/juliendargelos" target="_blank"><div class="linkdin iconSocial"></div></a>
				<a href="https://twitter.com/juliendargelos" target="_blank"><div class="twitter iconSocial"></div></a>
			</div>
		</figure>
		<figure class="membreTeam">
			<img src="medias/paulbonneau.jpg" alt="Paul Bonneau" />
			<figcaption>
				<strong>Paul Bonneau</strong> Paul est un étudiant en 1ère année de MMI à l'IUT Michel de Montaigne
			</figcaption>
			<div class="blocSocial">
				<a href="https://fr.linkedin.com/in/paulbonneau2" target="_blank"><div class="linkdin iconSocial"></div></a>
				<a href="https://twitter.com/bonneaupaul2" target="_blank"><div class="twitter iconSocial"></div></a>
			</div>
		</figure>
	</div>
</section>
<?php include('template/footer.php'); ?>

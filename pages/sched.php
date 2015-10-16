<?php
	// Vérfication de l'existence d'une session
	if(!$session->check()) header('location: /');
	
	// Instanciation des classes
	$param=new Param;
	$betterSched=new BetterSched;
	$table=new Table;
	
	// Définition des paramètres et chargement de l'emploi du temps
	$betterSched->user->username=$session->username;
	$betterSched->user->password=$session->password;
	$betterSched->week=$param->data['week'];
	$betterSched->group=$param->data['group'];
	$betterSched->year=$param->data['year'];
	$betterSched->get();
	
	// Chargement des données de l'emploi du temps dans le constructeur de tableau
	$table->data=$betterSched->data;
	
	$alert='';
	if($session->result!=null) {
		$alert.='<script type="text/javascript">';
		$alert.='	setTimeout(function(){';
		$alert.='		if(window.innerWidth<=600) {';
		$alert.='			document.getElementById(\'result\').parentNode.removeChild(document.getElementById(\'result\'));';
		$alert.='			document.body.insertAdjacentHTML(\'afterBegin\',\'<div id="result">Swippez pour passer d\\\'un jour à un autre.</div>\');';
		$alert.='		}';
		$alert.='	},3100);';
		$alert.='</script>';
	}
?>
<?php include('template/header.php'); ?>
		<div id="date">
			<?php echo $betterSched->data[$param->data['day']]['date']; ?>
		</div>
		<header>
			<a href="/about"><button type="button" name="button" class="button buttonApropos">À propos</button></a>
			<h3 class="titreapropos">BetterSched'</h3>
		</header>
		<table id="main">
			<tr>
				<td id="input" colspan="7">
					<form method="POST">
						<div>
							<label for="input-group">Groupe</label>
							<select id="input-group" name="group">
								<option value="MMI"<?php echo $param->data['group']=='MMI' ? ' selected' : ''; ?>>MMI</option>
								<option value="PUB"<?php echo $param->data['group']=='PUB' ? ' selected' : ''; ?>>PUB</option>
								<option value="LP"<?php echo $param->data['group']=='LP' ? ' selected' : ''; ?>>LP</option>
							</select>
						</div>
						<div>
							<label for="input-year">Année</label>
							<select id="input-year" name="year">
								<option value="1"<?php echo $param->data['year']==1 ? ' selected' : ''; ?>>1A</option>
								<option value="2"<?php echo $param->data['year']==2 ? ' selected' : ''; ?>>2A</option>
							</select>
						</div>
						<div>
							<label for="input-week">Semaine</label>
							<select id="input-week" name="week">
								<?php
									for($w=1; $w<=53; $w++) {
										$d=[
											'begin'=>date(datetime::ISO8601,strtotime(($w<38 ? '2016' : '2015').'W'.($w<10 ? '0'.$w : $w))),
											'end'=>date(datetime::ISO8601,strtotime(($w<38 ? '2016' : '2015').'W'.($w<10 ? '0'.$w : $w).'7'))
										];
										$d=[
											'begin'=>substr($d['begin'],8,2).'/'.substr($d['begin'],5,2).'/'.substr($d['begin'],2,2),
											'end'=>substr($d['end'],8,2).'/'.substr($d['end'],5,2).'/'.substr($d['end'],2,2)
										];
										echo '<option value="'.$w.'"'.($param->data['week']==$w ? ' selected' : '').'>'.$w.' ('.$d['begin'].' → '.$d['end'].')</option>';
									}
								?>
							</select>
						</div>
						<div>
							<input type="submit" value="Valider"/>
						</div>
					</form>
				</td>
			</tr>
			<tr id="sched">
				<td>
					<?php
						echo '<div id="full-sched">'.$table->week.'</div>';
						echo '<div id="day-sched" class="'.$param->data['day'].'">';
						echo '<div ontouchstart="swipe.start(event);" ontouchmove="swipe.move(event);" ontouchend="swipe.end(event);">';
						foreach($betterSched->data as $dayname=>$daydata) {
							echo '<div class="day d-'.$dayname.'" data-date="'.$daydata['date'].'">'.$table->day[$dayname].'</div>';
						}
						echo '</div></div>';
					?>
				</td>
			</tr>
		</table>
		<script type="text/javascript" src="js/sched.js"></script>
		<?php echo $alert; ?>
	</body>
</html>

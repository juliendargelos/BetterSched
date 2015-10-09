<?php
	if(!defined('INCLUDE') || !session_check()) {
		header('location: /');
		exit;
	}
	date_default_timezone_set('Europe/Paris');
	$days=fr_en_day('fr');
	$default=[
		'group'=>'MMI',
		'year'=>1,
		'week'=>date('W'),
		'day'=>strtolower(date('l'))
	];
	$param=[];
	foreach($default as $key=>$p) $param[$key]=isset($_POST[$key]) ? (is_numeric($p) ? intval($_POST[$key]) : $_POST[$key]) : $p;
	$sched=get_sched($param['group'],$param['year'],$param['week']);
	if($sched===false) {
		$param=$default;
		$sched=get_sched($param['group'],$param['year'],$param['week']);
	}
?>
<?php include('template/header.php'); ?>
		<div id="date">
			<?php echo $days[$param['day']]; ?>
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
								<option value="MMI"<?php echo $param['group']=='MMI' ? ' selected' : ''; ?>>MMI</option>
								<option value="PUB"<?php echo $param['group']=='PUB' ? ' selected' : ''; ?>>PUB</option>
								<option value="LP"<?php echo $param['group']=='LP' ? ' selected' : ''; ?>>LP</option>
							</select>
						</div>
						<div>
							<label for="input-year">Année</label>
							<select id="input-year" name="year">
								<option value="1"<?php echo $param['year']==1 ? ' selected' : ''; ?>>1A</option>
								<option value="2"<?php echo $param['year']==2 ? ' selected' : ''; ?>>2A</option>
							</select>
						</div>
						<div>
							<label for="input-week">Semaine</label>
							<select id="input-week" name="week">
								<?php
									for($w=1; $w<=53; $w++) echo '<option value="'.$w.'"'.($param['week']==$w ? ' selected' : '').'>'.$w.'</option>';
								?>
							</select>
						</div>
						<div>
							<label for="input-day">Jour</label>
							<select id="input-day" name="day">
								<?php
									foreach($days as $en_day=>$fr_day) {
										echo '<option value="'.$en_day.'"'.($param['day']==$en_day ? ' selected' : '').'>'.$fr_day.'</option>';
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
						echo '<div id="full-sched">'.sched_to_htmltable($sched).'</div>';
						echo '<div id="day-sched" class="'.$param['day'].'"><div ontouchstart="swipe.start(event);" ontouchmove="swipe.move(event);" ontouchend="swipe.end();">';
						foreach($sched as $dayname=>$daydata) {
							echo '<div class="day d-'.$dayname.'" data-date="'.$days[$dayname].'">'.daydata_to_htmltable($dayname,$daydata).'</div>';
						}
						echo '</div></div>';
					?>
				</td>
			</tr>
		</table>
		<script type="text/javascript" src="js/sched.js"></script>
	</body>
</html>

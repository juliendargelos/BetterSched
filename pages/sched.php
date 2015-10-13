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
<?php
	$alert=isset($_SESSION['result']) ? '<script type="text/javascript">setTimeout(function(){if(window.innerWidth<=600){document.getElementById(\'result\').parentNode.removeChild(document.getElementById(\'result\'));document.body.insertAdjacentHTML(\'afterBegin\',\'<div id="result">Swippez pour passer d\\\'un jour à un autre.</div>\');}},3100);</script>' : ''; 
?>
<?php include('template/header.php'); ?>
		<div id="date">
			<?php echo $sched[$param['day']]['date']; ?>
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
									for($w=1; $w<=53; $w++) {
										$d=[
											'begin'=>date(datetime::ISO8601,strtotime(($w<38 ? '2016' : '2015').'W'.($w<10 ? '0'.$w : $w))),
											'end'=>date(datetime::ISO8601,strtotime(($w<38 ? '2016' : '2015').'W'.($w<10 ? '0'.$w : $w).'7'))
										];
										$d=[
											'begin'=>substr($d['begin'],8,2).'/'.substr($d['begin'],5,2).'/'.substr($d['begin'],2,2),
											'end'=>substr($d['end'],8,2).'/'.substr($d['end'],5,2).'/'.substr($d['end'],2,2)
										];
										echo '<option value="'.$w.'"'.($param['week']==$w ? ' selected' : '').'>'.$w.' ('.$d['begin'].' → '.$d['end'].')</option>';
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
						echo '<div id="day-sched" class="'.$param['day'].'"><div ontouchstart="swipe.start(event);" ontouchmove="swipe.move(event);" ontouchend="swipe.end(event);">';
						foreach($sched as $dayname=>$daydata) {
							echo '<div class="day d-'.$dayname.'" data-date="'.$daydata['date'].'">'.daydata_to_htmltable($dayname,$daydata).'</div>';
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
